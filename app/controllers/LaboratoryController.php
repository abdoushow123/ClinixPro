<?php
/**
 * ClinixPro - Hospital Management System
 * Laboratory Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\LabRequest;
use App\Models\LaboratoryTest;
use App\Models\Patient;
use App\Models\User;

class LaboratoryController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'laboratory']);
        
        $requests = LabRequest::getPending();
        $stats = LabRequest::getStatistics();
        
        $this->view('laboratory/index', [
            'title' => 'Laboratory - ClinixPro',
            'requests' => $requests,
            'stats' => $stats
        ]);
    }

    public function team(): void
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['laboratory', 'administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }

        $currentUser = $this->getCurrentUser();

        // Get pending laboratory staff
        User::initDb();
        $pendingLabStaff = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 6 
             AND u.registration_status = 'pending'
             ORDER BY u.created_at DESC"
        );

        // Get all laboratory staff
        $allLabStaff = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 6 
             ORDER BY u.registration_status, u.created_at DESC"
        );

        // Calculate stats
        $stats = [
            'total' => count($allLabStaff),
            'approved' => count(array_filter($allLabStaff, fn($s) => $s['registration_status'] === 'approved')),
            'pending' => count($pendingLabStaff)
        ];

        $this->view('laboratory/team', [
            'title' => 'Laboratory Team - ClinixPro',
            'stats' => $stats,
            'pending_lab_staff' => $pendingLabStaff,
            'lab_staff' => $allLabStaff,
            'csrf_token' => Security::generateCsrfToken(),
            'user' => $currentUser
        ]);
    }

    public function request(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        $patientId = $this->get('patient_id');
        $patient = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        $tests = LaboratoryTest::getActive();
        $categories = LaboratoryTest::getCategories();
        
        $this->view('laboratory/request', [
            'title' => 'Request Lab Test - ClinixPro',
            'patient' => $patient,
            'tests' => $tests,
            'categories' => $categories,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function storeRequest(): void
    {
        $this->requireAnyRole(['administrator', 'doctor']);
        
        if (!$this->isPost()) {
            $this->redirect('/laboratory');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/laboratory/request');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'doctor_id' => $currentUser['id'],
            'test_id' => $this->post('test_id'),
            'request_date' => date('Y-m-d'),
            'request_time' => date('H:i:s'),
            'priority' => $this->post('priority'),
            'clinical_notes' => $this->sanitize($this->post('clinical_notes')),
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['test_id'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/laboratory/request');
        }
        
        try {
            $requestId = LabRequest::create($data);
            
            User::logActivity($currentUser['id'], 'lab_request_created', 'lab_request', $requestId);
            
            Session::flash('success', 'Lab test requested successfully');
            $this->redirect('/laboratory/' . $requestId);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to request lab test: ' . $e->getMessage());
            $this->redirect('/laboratory/request');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'doctor', 'laboratory']);
        
        $request = LabRequest::withResults($id);
        
        if (!$request) {
            $this->view('errors/404', ['message' => 'Lab request not found']);
            return;
        }
        
        $this->view('laboratory/show', [
            'title' => 'Lab Request Details - ClinixPro',
            'request' => $request,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function result(int $id): void
    {
        $this->requireAnyRole(['administrator', 'laboratory']);
        
        if (!$this->isPost()) {
            $this->redirect('/laboratory/' . $id);
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/laboratory/' . $id);
        }
        
        $currentUser = $this->getCurrentUser();
        
        $results = $this->sanitize($this->post('results'));
        $interpretation = $this->sanitize($this->post('interpretation'));
        $isAbnormal = $this->post('is_abnormal') === '1';
        
        if (empty($results)) {
            Session::flash('error', 'Please provide test results');
            $this->redirect('/laboratory/' . $id);
        }
        
        try {
            self::initDb();
            $query = "
                INSERT INTO lab_results (lab_request_id, result_date, result_time, results, interpretation, is_abnormal, technician_id, created_at)
                VALUES (?, CURRENT_DATE, CURRENT_TIME, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ";
            Database::query($query, [$id, $results, $interpretation, $isAbnormal, $currentUser['id']]);
            
            // Update request status
            LabRequest::update($id, ['status' => 'completed']);
            
            User::logActivity($currentUser['id'], 'lab_result_added', 'lab_request', $id);
            
            Session::flash('success', 'Lab results recorded successfully');
            $this->redirect('/laboratory/' . $id);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to record results: ' . $e->getMessage());
            $this->redirect('/laboratory/' . $id);
        }
    }
}
