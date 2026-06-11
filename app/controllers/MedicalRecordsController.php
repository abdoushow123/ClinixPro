<?php
/**
 * ClinixPro - Hospital Management System
 * Medical Records Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;

class MedicalRecordsController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'nurse']);
        
        $records = MedicalRecord::recent(50);
        $stats = MedicalRecord::getStatistics();
        
        $this->view('medical-records/index', [
            'title' => 'Medical Records - ClinixPro',
            'records' => $records,
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
        
        $this->view('medical-records/create', [
            'title' => 'Add Medical Record - ClinixPro',
            'patient' => $patient,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/medical-records');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/medical-records/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'doctor_id' => $currentUser['id'], // Assuming current user is a doctor
            'visit_date' => $this->post('visit_date'),
            'visit_type' => $this->sanitize($this->post('visit_type')),
            'chief_complaint' => $this->sanitize($this->post('chief_complaint')),
            'diagnosis' => $this->sanitize($this->post('diagnosis')),
            'symptoms' => $this->sanitize($this->post('symptoms')),
            'treatment_plan' => $this->sanitize($this->post('treatment_plan')),
            'notes' => $this->sanitize($this->post('notes')),
            'is_confidential' => $this->post('is_confidential') === '1',
            'created_by' => $currentUser['id']
        ];
        
        // Validation
        if (empty($data['patient_id']) || empty($data['visit_date']) || empty($data['diagnosis'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/medical-records/create');
        }
        
        try {
            $recordId = MedicalRecord::create($data);
            
            // Log activity
            User::logActivity($currentUser['id'], 'medical_record_created', 'medical_record', $recordId);
            
            Session::flash('success', 'Medical record created successfully');
            $this->redirect('/medical-records/' . $recordId);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create medical record: ' . $e->getMessage());
            $this->redirect('/medical-records/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'nurse']);
        
        $record = MedicalRecord::withDoctor($id);
        
        if (!$record) {
            $this->view('errors/404', ['message' => 'Medical record not found']);
            return;
        }
        
        // Check confidentiality
        if ($record['is_confidential'] && !$this->hasPermission('medical_records.read')) {
            $this->view('errors/403', ['message' => 'This record is confidential']);
            return;
        }
        
        $this->view('medical-records/show', [
            'title' => 'Medical Record Details - ClinixPro',
            'record' => $record
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        $record = MedicalRecord::find($id);
        
        if (!$record) {
            $this->view('errors/404', ['message' => 'Medical record not found']);
            return;
        }
        
        $this->view('medical-records/edit', [
            'title' => 'Edit Medical Record - ClinixPro',
            'record' => $record,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/medical-records');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/medical-records/' . $id . '/edit');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'visit_date' => $this->post('visit_date'),
            'visit_type' => $this->sanitize($this->post('visit_type')),
            'chief_complaint' => $this->sanitize($this->post('chief_complaint')),
            'diagnosis' => $this->sanitize($this->post('diagnosis')),
            'symptoms' => $this->sanitize($this->post('symptoms')),
            'treatment_plan' => $this->sanitize($this->post('treatment_plan')),
            'notes' => $this->sanitize($this->post('notes')),
            'is_confidential' => $this->post('is_confidential') === '1'
        ];
        
        try {
            MedicalRecord::update($id, $data);
            
            // Log activity
            User::logActivity($currentUser['id'], 'medical_record_updated', 'medical_record', $id);
            
            Session::flash('success', 'Medical record updated successfully');
            $this->redirect('/medical-records/' . $id);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update medical record: ' . $e->getMessage());
            $this->redirect('/medical-records/' . $id . '/edit');
        }
    }

    public function search(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'nurse']);
        
        $query = $this->sanitize($this->get('q', ''));
        
        if (empty($query)) {
            $this->redirect('/medical-records');
        }
        
        $records = MedicalRecord::search($query);
        
        $this->view('medical-records/search', [
            'title' => 'Search Results - ClinixPro',
            'records' => $records,
            'query' => $query
        ]);
    }
}
