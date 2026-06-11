<?php
/**
 * ClinixPro - Hospital Management System
 * Profile Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $currentUser = $this->getCurrentUser();
        $user = User::find($currentUser['id']);
        
        $this->view('profile/index', [
            'title' => 'My Profile - ClinixPro',
            'user' => $user
        ]);
    }

    public function update(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/profile');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/profile');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'first_name' => $this->sanitize($this->post('first_name')),
            'last_name' => $this->sanitize($this->post('last_name')),
            'phone' => $this->sanitize($this->post('phone')),
        ];
        
        if (empty($data['first_name']) || empty($data['last_name'])) {
            Session::flash('error', 'First name and last name are required');
            $this->redirect('/profile');
        }
        
        try {
            User::update($currentUser['id'], $data);
            Session::flash('success', 'Profile updated successfully');
            $this->redirect('/profile');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update profile: ' . $e->getMessage());
            $this->redirect('/profile');
        }
    }
}
