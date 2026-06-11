<?php
/**
 * ClinixPro - Hospital Management System
 * Patients Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Patient;
use App\Models\User;

class PatientsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $page = (int)($this->get('page', 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $patients = Patient::all([], 'created_at DESC', $limit);
        $total = Patient::count();
        
        $this->view('patients/index', [
            'title' => 'Patients - ClinixPro',
            'patients' => $patients,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit),
            'stats' => Patient::getStatistics()
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        
        $this->view('patients/create', [
            'title' => 'Add Patient - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/patients');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/patients/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Sanitize and validate input
        $data = [
            'patient_id' => Patient::generatePatientId(),
            'first_name' => $this->sanitize($this->post('first_name')),
            'last_name' => $this->sanitize($this->post('last_name')),
            'date_of_birth' => $this->post('date_of_birth'),
            'gender' => $this->post('gender'),
            'blood_type' => $this->post('blood_type'),
            'email' => $this->sanitize($this->post('email')),
            'phone' => $this->sanitize($this->post('phone')),
            'address' => $this->sanitize($this->post('address')),
            'city' => $this->sanitize($this->post('city')),
            'state' => $this->sanitize($this->post('state')),
            'postal_code' => $this->sanitize($this->post('postal_code')),
            'emergency_contact_name' => $this->sanitize($this->post('emergency_contact_name')),
            'emergency_contact_phone' => $this->sanitize($this->post('emergency_contact_phone')),
            'emergency_contact_relationship' => $this->sanitize($this->post('emergency_contact_relationship')),
            'insurance_provider' => $this->sanitize($this->post('insurance_provider')),
            'insurance_policy_number' => $this->sanitize($this->post('insurance_policy_number')),
            'allergies' => $this->sanitize($this->post('allergies')),
            'chronic_conditions' => $this->sanitize($this->post('chronic_conditions')),
            'notes' => $this->sanitize($this->post('notes')),
            'created_by' => $currentUser['id']
        ];
        
        // Basic validation
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['date_of_birth']) || empty($data['phone'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/patients/create');
        }
        
        try {
            $patientId = Patient::create($data);
            
            // Log activity
            User::logActivity($currentUser['id'], 'patient_created', 'patient', $patientId, [
                'patient_id' => $data['patient_id'],
                'name' => $data['first_name'] . ' ' . $data['last_name']
            ]);
            
            Session::flash('success', 'Patient created successfully');
            $this->redirect('/patients/' . $patientId);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create patient: ' . $e->getMessage());
            $this->redirect('/patients/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        
        $patient = Patient::withMedicalRecords($id);
        
        if (!$patient) {
            $this->view('errors/404', ['message' => 'Patient not found']);
            return;
        }
        
        $this->view('patients/show', [
            'title' => 'Patient Details - ClinixPro',
            'patient' => $patient
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        
        $patient = Patient::find($id);
        
        if (!$patient) {
            $this->view('errors/404', ['message' => 'Patient not found']);
            return;
        }
        
        $this->view('patients/edit', [
            'title' => 'Edit Patient - ClinixPro',
            'patient' => $patient,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/patients');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/patients/' . $id . '/edit');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'first_name' => $this->sanitize($this->post('first_name')),
            'last_name' => $this->sanitize($this->post('last_name')),
            'date_of_birth' => $this->post('date_of_birth'),
            'gender' => $this->post('gender'),
            'blood_type' => $this->post('blood_type'),
            'email' => $this->sanitize($this->post('email')),
            'phone' => $this->sanitize($this->post('phone')),
            'address' => $this->sanitize($this->post('address')),
            'city' => $this->sanitize($this->post('city')),
            'state' => $this->sanitize($this->post('state')),
            'postal_code' => $this->sanitize($this->post('postal_code')),
            'emergency_contact_name' => $this->sanitize($this->post('emergency_contact_name')),
            'emergency_contact_phone' => $this->sanitize($this->post('emergency_contact_phone')),
            'emergency_contact_relationship' => $this->sanitize($this->post('emergency_contact_relationship')),
            'insurance_provider' => $this->sanitize($this->post('insurance_provider')),
            'insurance_policy_number' => $this->sanitize($this->post('insurance_policy_number')),
            'allergies' => $this->sanitize($this->post('allergies')),
            'chronic_conditions' => $this->sanitize($this->post('chronic_conditions')),
            'notes' => $this->sanitize($this->post('notes'))
        ];
        
        try {
            Patient::update($id, $data);
            
            // Log activity
            User::logActivity($currentUser['id'], 'patient_updated', 'patient', $id);
            
            Session::flash('success', 'Patient updated successfully');
            $this->redirect('/patients/' . $id);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update patient: ' . $e->getMessage());
            $this->redirect('/patients/' . $id . '/edit');
        }
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/patients');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/patients');
        }
        
        $currentUser = $this->getCurrentUser();
        
        try {
            Patient::delete($id);
            
            // Log activity
            User::logActivity($currentUser['id'], 'patient_deleted', 'patient', $id);
            
            Session::flash('success', 'Patient deleted successfully');
            $this->redirect('/patients');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete patient: ' . $e->getMessage());
            $this->redirect('/patients');
        }
    }

    public function search(): void
    {
        $this->requireAuth();
        
        $query = $this->sanitize($this->get('q', ''));
        
        if (empty($query)) {
            $this->redirect('/patients');
        }
        
        $patients = Patient::search($query);
        
        $this->view('patients/search', [
            'title' => 'Search Results - ClinixPro',
            'patients' => $patients,
            'query' => $query
        ]);
    }
}
