<?php
/**
 * ClinixPro - Hospital Management System
 * Admin Users Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $users = User::all();
        $pendingUsers = User::all(['registration_status' => 'pending']);
        
        $this->view('admin/users/index', [
            'title' => 'Manage Users - ClinixPro',
            'users' => $users,
            'pendingUsers' => $pendingUsers,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $this->view('admin/users/create', [
            'title' => 'Create User - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/users/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $email = $this->sanitize($this->post('email'));
        $username = $this->sanitize($this->post('username'));
        $password = $this->post('password');
        $firstName = $this->sanitize($this->post('first_name'));
        $lastName = $this->sanitize($this->post('last_name'));
        $roleId = (int)$this->post('role_id');
        
        if (empty($email) || empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/admin/users/create');
        }
        
        if (!Security::validatePasswordStrength($password)) {
            Session::flash('error', 'Password must be at least 8 characters with uppercase, lowercase, number, and special character');
            $this->redirect('/admin/users/create');
        }
        
        try {
            $passwordHash = Security::hashPassword($password);
            
            $data = [
                'role_id' => $roleId,
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
            
            Session::flash('success', 'User created successfully');
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create user: ' . $e->getMessage());
            $this->redirect('/admin/users/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $user = User::find($id);
        
        if (!$user) {
            $this->view('errors/404', ['message' => 'User not found']);
            return;
        }
        
        $this->view('admin/users/show', [
            'title' => 'User Details - ClinixPro',
            'user' => $user
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $user = User::find($id);
        
        if (!$user) {
            $this->view('errors/404', ['message' => 'User not found']);
            return;
        }
        
        $this->view('admin/users/edit', [
            'title' => 'Edit User - ClinixPro',
            'user' => $user,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/users/' . $id . '/edit');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'first_name' => $this->sanitize($this->post('first_name')),
            'last_name' => $this->sanitize($this->post('last_name')),
            'phone' => $this->sanitize($this->post('phone')),
            'role_id' => (int)$this->post('role_id'),
            'is_active' => $this->post('is_active') === '1'
        ];
        
        if (empty($data['first_name']) || empty($data['last_name'])) {
            Session::flash('error', 'First name and last name are required');
            $this->redirect('/admin/users/' . $id . '/edit');
        }
        
        try {
            User::update($id, $data);
            
            Session::flash('success', 'User updated successfully');
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update user: ' . $e->getMessage());
            $this->redirect('/admin/users/' . $id . '/edit');
        }
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/users');
        }
        
        try {
            User::delete($id);
            
            Session::flash('success', 'User deleted successfully');
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete user: ' . $e->getMessage());
            $this->redirect('/admin/users');
        }
    }

    public function approve(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/users');
        }
        
        $currentUser = $this->getCurrentUser();
        
        try {
            $data = [
                'registration_status' => 'approved',
                'is_active' => true,
                'approved_by' => $currentUser['id'],
                'approved_by_role_id' => $currentUser['role_id'],
                'approved_at' => date('Y-m-d H:i:s')
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'User approved successfully');
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to approve user: ' . $e->getMessage());
            $this->redirect('/admin/users');
        }
    }

    public function reject(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/users');
        }
        
        try {
            $data = [
                'registration_status' => 'rejected',
                'is_active' => false
            ];
            
            User::update($id, $data);
            
            Session::flash('success', 'User rejected successfully');
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to reject user: ' . $e->getMessage());
            $this->redirect('/admin/users');
        }
    }
}
