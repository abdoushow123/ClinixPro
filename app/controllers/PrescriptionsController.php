<?php
/**
 * ClinixPro - Hospital Management System
 * Prescriptions Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\User;

class PrescriptionsController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'pharmacist', 'nurse']);
        
        $prescriptions = Prescription::withDetails();
        $stats = Prescription::getStatistics();
        
        $this->view('prescriptions/index', [
            'title' => 'Prescriptions - ClinixPro',
            'prescriptions' => $prescriptions,
            'stats' => $stats
        ]);
    }

    public function create(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        $patientId = $this->get('patient_id');
        $patient = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        $this->view('prescriptions/create', [
            'title' => 'Create Prescription - ClinixPro',
            'patient' => $patient,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/prescriptions');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/prescriptions/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'doctor_id' => $currentUser['id'],
            'medication_name' => $this->sanitize($this->post('medication_name')),
            'dosage' => $this->sanitize($this->post('dosage')),
            'frequency' => $this->sanitize($this->post('frequency')),
            'duration' => $this->sanitize($this->post('duration')),
            'instructions' => $this->sanitize($this->post('instructions')),
            'status' => 'pending',
            'prescription_date' => date('Y-m-d'),
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['medication_name'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/prescriptions/create');
        }
        
        try {
            Prescription::create($data);
            
            User::logActivity($currentUser['id'], 'prescription_created', 'prescription', null, [
                'patient_id' => $data['patient_id']
            ]);
            
            Session::flash('success', 'Prescription created successfully');
            $this->redirect('/prescriptions');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create prescription: ' . $e->getMessage());
            $this->redirect('/prescriptions/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'pharmacist', 'nurse']);
        
        $prescriptions = Prescription::withDetails();
        $prescription = array_filter($prescriptions, fn($p) => $p['id'] == $id);
        $prescription = reset($prescription);
        
        if (!$prescription) {
            $this->view('errors/404', ['message' => 'Prescription not found']);
            return;
        }
        
        $this->view('prescriptions/show', [
            'title' => 'Prescription Details - ClinixPro',
            'prescription' => $prescription
        ]);
    }

    public function dispense(int $id): void
    {
        $this->requireAnyRole(['administrator', 'pharmacist']);
        
        if (!$this->isPost()) {
            $this->redirect('/prescriptions');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/prescriptions');
        }
        
        $currentUser = $this->getCurrentUser();
        
        try {
            Prescription::update($id, [
                'status' => 'dispensed',
                'dispensed_by' => $currentUser['id'],
                'dispensed_date' => date('Y-m-d')
            ]);
            
            User::logActivity($currentUser['id'], 'prescription_dispensed', 'prescription', $id);
            
            Session::flash('success', 'Prescription dispensed successfully');
            $this->redirect('/prescriptions');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to dispense prescription: ' . $e->getMessage());
            $this->redirect('/prescriptions');
        }
    }
}
