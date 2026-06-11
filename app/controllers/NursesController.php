<?php
/**
 * ClinixPro - Hospital Management System
 * Nurses Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;

class NursesController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['nurse', 'administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Get pending nurses
        User::initDb();
        $pendingNurses = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 3 
             AND u.registration_status = 'pending'
             ORDER BY u.created_at DESC"
        );
        
        // Get all nurses
        $allNurses = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 3 
             ORDER BY u.registration_status, u.created_at DESC"
        );
        
        // Calculate stats
        $stats = [
            'total' => count($allNurses),
            'approved' => count(array_filter($allNurses, fn($n) => $n['registration_status'] === 'approved')),
            'pending' => count($pendingNurses)
        ];
        
        $this->view('nurses/index', [
            'title' => 'Nursing Team - ClinixPro',
            'stats' => $stats,
            'pending_nurses' => $pendingNurses,
            'nurses' => $allNurses,
            'csrf_token' => Security::generateCsrfToken(),
            'user' => $currentUser
        ]);
    }

    public function approveNurse(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('nurse');
        
        if (!$this->isPost()) {
            $this->redirect('/nurses');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/nurses');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a nurse
        User::initDb();
        $nurse = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 3",
            [$id]
        );
        
        if (!$nurse) {
            Session::flash('error', 'Nurse not found');
            $this->redirect('/nurses');
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
            $this->redirect('/nurses');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to approve nurse: ' . $e->getMessage());
            $this->redirect('/nurses');
        }
    }

    public function rejectNurse(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('nurse');
        
        if (!$this->isPost()) {
            $this->redirect('/nurses');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/nurses');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a nurse
        User::initDb();
        $nurse = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 3",
            [$id]
        );
        
        if (!$nurse) {
            Session::flash('error', 'Nurse not found');
            $this->redirect('/nurses');
        }
        
        try {
            $data = [
                'registration_status' => 'rejected',
                'is_active' => false
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Nurse rejected successfully');
            $this->redirect('/nurses');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to reject nurse: ' . $e->getMessage());
            $this->redirect('/nurses');
        }
    }
}
