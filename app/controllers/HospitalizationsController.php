<?php
/**
 * ClinixPro - Hospital Management System
 * Hospitalizations Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Hospitalization;
use App\Models\Patient;
use App\Models\Room;
use App\Models\User;

class HospitalizationsController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'nurse']);
        
        $hospitalizations = Hospitalization::getActive();
        $stats = Hospitalization::getStatistics();
        
        $this->view('hospitalizations/index', [
            'title' => 'Hospitalizations - ClinixPro',
            'hospitalizations' => $hospitalizations,
            'stats' => $stats,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function admit(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        $patientId = $this->get('patient_id');
        $patient = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        $availableRooms = Room::getAvailable();
        
        $this->view('hospitalizations/admit', [
            'title' => 'Admit Patient - ClinixPro',
            'patient' => $patient,
            'rooms' => $availableRooms,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/hospitalizations');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/hospitalizations/admit');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'room_id' => $this->post('room_id'),
            'admission_date' => $this->post('admission_date'),
            'admission_time' => $this->post('admission_time'),
            'admission_reason' => $this->sanitize($this->post('admission_reason')),
            'attending_doctor_id' => $currentUser['id'],
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['admission_date']) || empty($data['admission_reason'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/hospitalizations/admit');
        }
        
        try {
            $hospitalizationId = Hospitalization::admitPatient($data);
            
            User::logActivity($currentUser['id'], 'patient_admitted', 'hospitalization', $hospitalizationId);
            
            Session::flash('success', 'Patient admitted successfully');
            $this->redirect('/hospitalizations/' . $hospitalizationId);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to admit patient: ' . $e->getMessage());
            $this->redirect('/hospitalizations/admit');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'nurse']);
        
        $hospitalization = Hospitalization::withDetails($id);
        
        if (!$hospitalization) {
            $this->view('errors/404', ['message' => 'Hospitalization record not found']);
            return;
        }
        
        $this->view('hospitalizations/show', [
            'title' => 'Hospitalization Details - ClinixPro',
            'hospitalization' => $hospitalization,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function discharge(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/hospitalizations/' . $id);
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/hospitalizations/' . $id);
        }
        
        $currentUser = $this->getCurrentUser();
        $dischargeSummary = $this->sanitize($this->post('discharge_summary'));
        
        if (empty($dischargeSummary)) {
            Session::flash('error', 'Please provide discharge summary');
            $this->redirect('/hospitalizations/' . $id);
        }
        
        try {
            Hospitalization::dischargePatient($id, $dischargeSummary);
            
            User::logActivity($currentUser['id'], 'patient_discharged', 'hospitalization', $id);
            
            Session::flash('success', 'Patient discharged successfully');
            $this->redirect('/hospitalizations');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to discharge patient: ' . $e->getMessage());
            $this->redirect('/hospitalizations/' . $id);
        }
    }
}
