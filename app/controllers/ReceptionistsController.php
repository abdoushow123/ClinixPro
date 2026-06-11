<?php
/**
 * ClinixPro - Hospital Management System
 * Receptionists Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;

class ReceptionistsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['receptionist', 'administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Get pending receptionists
        User::initDb();
        $pendingReceptionists = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 4 
             AND u.registration_status = 'pending'
             ORDER BY u.created_at DESC"
        );
        
        // Get all receptionists
        $allReceptionists = Database::fetchAll(
            "SELECT u.*, r.name as role_name,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 4 
             ORDER BY u.registration_status, u.created_at DESC"
        );
        
        // Calculate stats
        $stats = [
            'total' => count($allReceptionists),
            'approved' => count(array_filter($allReceptionists, fn($r) => $r['registration_status'] === 'approved')),
            'pending' => count($pendingReceptionists)
        ];
        
        $this->view('receptionists/index', [
            'title' => 'Reception Team - ClinixPro',
            'stats' => $stats,
            'pending_receptionists' => $pendingReceptionists,
            'receptionists' => $allReceptionists,
            'csrf_token' => Security::generateCsrfToken(),
            'user' => $currentUser
        ]);
    }

    public function approveReceptionist(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('receptionist');
        
        if (!$this->isPost()) {
            $this->redirect('/receptionists');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/receptionists');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a receptionist
        User::initDb();
        $receptionist = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 4",
            [$id]
        );
        
        if (!$receptionist) {
            Session::flash('error', 'Receptionist not found');
            $this->redirect('/receptionists');
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
            
            Session::flash('success', 'Receptionist approved successfully');
            $this->redirect('/receptionists');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to approve receptionist: ' . $e->getMessage());
            $this->redirect('/receptionists');
        }
    }

    public function rejectReceptionist(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('receptionist');
        
        if (!$this->isPost()) {
            $this->redirect('/receptionists');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/receptionists');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a receptionist
        User::initDb();
        $receptionist = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 4",
            [$id]
        );
        
        if (!$receptionist) {
            Session::flash('error', 'Receptionist not found');
            $this->redirect('/receptionists');
        }
        
        try {
            $data = [
                'registration_status' => 'rejected',
                'is_active' => false
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Receptionist rejected successfully');
            $this->redirect('/receptionists');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to reject receptionist: ' . $e->getMessage());
            $this->redirect('/receptionists');
        }
    }
}
