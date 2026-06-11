<?php
/**
 * ClinixPro - Hospital Management System
 * Appointments Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentsController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'receptionist']);
        
        $appointments = Appointment::withDetails();
        $stats = Appointment::getStatistics();
        
        $this->view('appointments/index', [
            'title' => 'Appointments - ClinixPro',
            'appointments' => $appointments,
            'stats' => $stats,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function create(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'receptionist']);
        
        $patientId = $this->get('patient_id');
        $patient = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        $this->view('appointments/create', [
            'title' => 'Schedule Appointment - ClinixPro',
            'patient' => $patient,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'receptionist']);
        
        if (!$this->isPost()) {
            $this->redirect('/appointments');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/appointments/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'doctor_id' => $this->post('doctor_id') ?: $currentUser['id'],
            'appointment_date' => $this->post('appointment_date'),
            'appointment_time' => $this->post('appointment_time'),
            'reason' => $this->sanitize($this->post('reason')),
            'status' => 'pending',
            'notes' => $this->sanitize($this->post('notes')),
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['appointment_date']) || empty($data['appointment_time'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/appointments/create');
        }
        
        try {
            Appointment::create($data);
            
            User::logActivity($currentUser['id'], 'appointment_created', 'appointment', null, [
                'patient_id' => $data['patient_id']
            ]);
            
            Session::flash('success', 'Appointment scheduled successfully');
            $this->redirect('/appointments');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to schedule appointment: ' . $e->getMessage());
            $this->redirect('/appointments/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'receptionist']);
        
        $appointment = Appointment::withDetails();
        $appointment = array_filter($appointment, fn($a) => $a['id'] == $id);
        $appointment = reset($appointment);
        
        if (!$appointment) {
            $this->view('errors/404', ['message' => 'Appointment not found']);
            return;
        }
        
        $this->view('appointments/show', [
            'title' => 'Appointment Details - ClinixPro',
            'appointment' => $appointment
        ]);
    }

    public function cancel(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'receptionist']);
        
        if (!$this->isPost()) {
            $this->redirect('/appointments');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/appointments');
        }
        
        $currentUser = $this->getCurrentUser();
        
        try {
            Appointment::update($id, ['status' => 'cancelled']);
            
            User::logActivity($currentUser['id'], 'appointment_cancelled', 'appointment', $id);
            
            Session::flash('success', 'Appointment cancelled successfully');
            $this->redirect('/appointments');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to cancel appointment: ' . $e->getMessage());
            $this->redirect('/appointments');
        }
    }
}
