<?php
/**
 * ClinixPro - Hospital Management System
 * Admin Doctors Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;

class DoctorsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $doctors = User::all(['role_id' => 2]); // Assuming role_id 2 is doctor
        
        $this->view('admin/doctors/index', [
            'title' => 'Manage Doctors - ClinixPro',
            'doctors' => $doctors
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $this->view('admin/doctors/create', [
            'title' => 'Add Doctor - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/doctors');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/doctors/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $email = $this->sanitize($this->post('email'));
        $username = $this->sanitize($this->post('username'));
        $password = $this->post('password');
        $firstName = $this->sanitize($this->post('first_name'));
        $lastName = $this->sanitize($this->post('last_name'));
        $specialization = $this->sanitize($this->post('specialization'));
        
        if (empty($email) || empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/admin/doctors/create');
        }
        
        if (!Security::validatePasswordStrength($password)) {
            Session::flash('error', 'Password must be at least 8 characters with uppercase, lowercase, number, and special character');
            $this->redirect('/admin/doctors/create');
        }
        
        try {
            $passwordHash = Security::hashPassword($password);
            
            $data = [
                'role_id' => 2, // Doctor role
                'email' => $email,
                'username' => $username,
                'password_hash' => $passwordHash,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'is_active' => true,
                'email_verified' => true,
                'created_by' => $currentUser['id']
            ];
            
            User::create($data);
            
            Session::flash('success', 'Doctor added successfully');
            $this->redirect('/admin/doctors');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to add doctor: ' . $e->getMessage());
            $this->redirect('/admin/doctors/create');
        }
    }
}
