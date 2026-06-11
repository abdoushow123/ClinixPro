<?php
/**
 * ClinixPro - Hospital Management System
 * Doctors Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;

class DoctorsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (in_array($role, ['administrator', 'admin'], true)) {
            $this->redirect('/admin/doctors');
        }

        $this->requireRole('doctor');
        
        $currentUser = $this->getCurrentUser();
        
        // Get stats for the doctor
        $stats = [
            'total_doctors' => 1, // Self
            'approved_nurses' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ? AND registration_status = 'approved'",
                [$currentUser['id']]
            ) ?: 0,
            'pending_nurses' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ? AND registration_status = 'pending'",
                [$currentUser['id']]
            ) ?: 0,
            'today_appointments' => 0 // To be implemented with appointments system
        ];

        // Get pending nurses assigned to this doctor
        $pending_nurses = Database::fetchAll(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 3 
             AND u.assigned_doctor_id = ? 
             AND u.registration_status = 'pending'
             ORDER BY u.created_at DESC",
            [$currentUser['id']]
        );
        
        // Get all active nurses assigned to this doctor
        $team_members = Database::fetchAll(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 3 
             AND u.assigned_doctor_id = ?
             AND u.registration_status = 'approved'
             ORDER BY u.created_at DESC",
            [$currentUser['id']]
        );
        
        $this->view('doctors/index', [
            'title' => 'My Team - ClinixPro',
            'stats' => $stats,
            'pending_nurses' => $pending_nurses,
            'team_members' => $team_members,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function nurseApplications(): void
    {
        $this->requireAuth();
        $this->requireRole('doctor');
        
        $currentUser = $this->getCurrentUser();
        
        User::initDb();
        
        // Get statistics
        $stats = [
            'total' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ?",
                [$currentUser['id']]
            ) ?: 0,
            'pending' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ? AND registration_status = 'pending'",
                [$currentUser['id']]
            ) ?: 0,
            'accepted' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ? AND registration_status = 'approved'",
                [$currentUser['id']]
            ) ?: 0,
            'rejected' => Database::fetchColumn(
                "SELECT COUNT(*) FROM users WHERE role_id = 3 AND assigned_doctor_id = ? AND registration_status = 'rejected'",
                [$currentUser['id']]
            ) ?: 0
        ];
        
        // Get all nurse applications assigned to this doctor
        $applications = Database::fetchAll(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 3 
             AND u.assigned_doctor_id = ?
             ORDER BY u.registration_status, u.created_at DESC",
            [$currentUser['id']]
        );
        
        $this->view('doctors/nurse-applications', [
            'title' => 'Nurse Applications - ClinixPro',
            'stats' => $stats,
            'applications' => $applications,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function viewNurseApplication(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('doctor');
        
        $currentUser = $this->getCurrentUser();
        
        User::initDb();
        $application = Database::fetchOne(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ? AND u.role_id = 3 AND u.assigned_doctor_id = ?",
            [$id, $currentUser['id']]
        );
        
        if (!$application) {
            Session::flash('error', 'Nurse application not found');
            $this->redirect('/doctors/nurse-applications');
        }
        
        $this->view('doctors/nurse-application-detail', [
            'title' => 'Nurse Application - ClinixPro',
            'application' => $application,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function acceptNurse(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('doctor');
        
        if (!$this->isPost()) {
            $this->redirect('/doctors/nurse-applications');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/doctors/nurse-applications');
        }
        
        $currentUser = $this->getCurrentUser();
        
        User::initDb();
        $nurse = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 3 AND assigned_doctor_id = ?",
            [$id, $currentUser['id']]
        );
        
        if (!$nurse) {
            Session::flash('error', 'Nurse not found or not assigned to you');
            $this->redirect('/doctors/nurse-applications');
        }
        
        try {
            $data = [
                'registration_status' => 'approved',
                'is_active' => true,
                'approved_by' => $currentUser['id'],
                'approved_by_role_id' => $currentUser['role_id'],
                'approved_at' => date('Y-m-d H:i:s')
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Nurse accepted successfully');
            $this->redirect('/doctors/nurse-applications');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to accept nurse: ' . $e->getMessage());
            $this->redirect('/doctors/nurse-applications');
        }
    }

    public function approveNurse(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('doctor');
        
        if (!$this->isPost()) {
            $this->redirect('/doctors');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/doctors');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the nurse is assigned to this doctor
        User::initDb();
        $nurse = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 3 AND assigned_doctor_id = ?",
            [$id, $currentUser['id']]
        );
        
        if (!$nurse) {
            Session::flash('error', 'Nurse not found or not assigned to you');
            $this->redirect('/doctors');
        }
        
        try {
            $data = [
                'registration_status' => 'approved',
                'is_active' => true,
                'approved_by' => $currentUser['id'],
                'approved_by_role_id' => $currentUser['role_id'],
                'approved_at' => date('Y-m-d H:i:s')
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Nurse approved successfully');
            $this->redirect('/doctors');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to approve nurse: ' . $e->getMessage());
            $this->redirect('/doctors');
        }
    }

    public function rejectNurse(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('doctor');
        
        if (!$this->isPost()) {
            $this->redirect('/doctors/nurse-applications');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/doctors/nurse-applications');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the nurse is assigned to this doctor
        User::initDb();
        $nurse = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 3 AND assigned_doctor_id = ?",
            [$id, $currentUser['id']]
        );
        
        if (!$nurse) {
            Session::flash('error', 'Nurse not found or not assigned to you');
            $this->redirect('/doctors/nurse-applications');
        }
        
        try {
            $data = [
                'registration_status' => 'rejected',
                'is_active' => false
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Nurse rejected successfully');
            $this->redirect('/doctors/nurse-applications');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to reject nurse: ' . $e->getMessage());
            $this->redirect('/doctors/nurse-applications');
        }
    }
}
