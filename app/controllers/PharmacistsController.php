<?php
/**
 * ClinixPro - Hospital Management System
 * Pharmacists Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;

class PharmacistsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['pharmacist', 'administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Get pending pharmacists
        User::initDb();
        $pendingPharmacists = Database::fetchAll(
            "SELECT u.*, (u.first_name || ' ' || u.last_name) as full_name, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 5 
             AND u.registration_status = 'pending'
             ORDER BY u.created_at DESC"
        );
        
        // Get all pharmacists
        $allPharmacists = Database::fetchAll(
            "SELECT u.*, (u.first_name || ' ' || u.last_name) as full_name, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.role_id = 5 
             ORDER BY u.registration_status, u.created_at DESC"
        );
        
        $stats = [
            'total' => count($allPharmacists) + count($pendingPharmacists),
            'approved' => count($allPharmacists),
            'pending' => count($pendingPharmacists),
        ];

        $this->view('pharmacists/index', [
            'title' => 'Pharmacy Team - ClinixPro',
            'pending_pharmacists' => $pendingPharmacists,
            'pharmacists' => $allPharmacists,
            'stats' => $stats,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function approvePharmacist(int $id): void
    {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to approve pharmacists'
            ]);
            exit;
        }
        
        if (!$this->isPost()) {
            $this->redirect('/pharmacists');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/pharmacists');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a pharmacist
        User::initDb();
        $pharmacist = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 5",
            [$id]
        );
        
        if (!$pharmacist) {
            Session::flash('error', 'Pharmacist not found');
            $this->redirect('/pharmacists');
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
            
            Session::flash('success', 'Pharmacist approved successfully');
            $this->redirect('/pharmacists');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to approve pharmacist: ' . $e->getMessage());
            $this->redirect('/pharmacists');
        }
    }

    public function rejectPharmacist(int $id): void
    {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        $role = strtolower($user['role_name'] ?? '');
        if (!in_array($role, ['administrator', 'admin'], true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to reject pharmacists'
            ]);
            exit;
        }
        
        if (!$this->isPost()) {
            $this->redirect('/pharmacists');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/pharmacists');
        }
        
        $currentUser = $this->getCurrentUser();
        
        // Verify the user is a pharmacist
        User::initDb();
        $pharmacist = Database::fetchOne(
            "SELECT * FROM users WHERE id = ? AND role_id = 5",
            [$id]
        );
        
        if (!$pharmacist) {
            Session::flash('error', 'Pharmacist not found');
            $this->redirect('/pharmacists');
        }
        
        try {
            $data = [
                'registration_status' => 'rejected',
                'is_active' => false
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'Pharmacist rejected successfully');
            $this->redirect('/pharmacists');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to reject pharmacist: ' . $e->getMessage());
            $this->redirect('/pharmacists');
        }
    }
}
