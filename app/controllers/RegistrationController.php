<?php
/**
 * ClinixPro - Hospital Management System
 * Registration Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;

class RegistrationController extends Controller
{
    public function index(): void
    {
        // Fetch active doctors for nurse selection
        $doctors = Database::fetchAll(
            "SELECT id, first_name, last_name FROM users WHERE role_id = 2 AND is_active = true AND registration_status = 'approved' ORDER BY first_name, last_name"
        );
        
        $this->view('registration/index', [
            'title' => 'Register - ClinixPro',
            'csrf_token' => Security::generateCsrfToken(),
            'doctors' => $doctors
        ]);
    }

    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/register');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/register');
        }
        
        $role = $this->post('role');
        $email = $this->sanitize($this->post('email'));
        $username = $this->sanitize($this->post('username'));
        $password = $this->post('password');
        $fullName = $this->sanitize($this->post('full_name'));
        $phone = $this->sanitize($this->post('phone'));
        
        // Split full name into first and last name
        $nameParts = explode(' ', trim($fullName), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
        
        if (empty($email) || empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/register');
        }
        
        if (!Security::validatePasswordStrength($password)) {
            Session::flash('error', 'Password must be at least 8 characters with uppercase, lowercase, number, and special character');
            $this->redirect('/register');
        }
        
        // Determine registration status based on role configuration
        $roleConfig = Database::fetchOne(
            "SELECT requires_approval, auto_approve FROM roles WHERE id = ?",
            [(int)$role]
        );
        
        $registrationStatus = 'approved';
        $isActive = true;
        
        if ($roleConfig && $roleConfig['auto_approve']) {
            // Auto-approved roles (like admin)
            $registrationStatus = 'approved';
            $isActive = true;
        } elseif ($roleConfig && $roleConfig['requires_approval']) {
            // Roles requiring approval
            $registrationStatus = 'pending';
            $isActive = false;
        }
        
        // Nurses need to be assigned to a doctor and upload CV
        $assignedDoctorId = null;
        $cvPath = null;
        
        if ($role == 3) { // Nurse role
            $assignedDoctorId = $this->post('assigned_doctor_id') ? (int)$this->post('assigned_doctor_id') : null;
            
            // Handle CV file upload
            if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
                $cvFile = $_FILES['cv_file'];
                
                // Validate file type (PDF only)
                $fileExtension = strtolower(pathinfo($cvFile['name'], PATHINFO_EXTENSION));
                if ($fileExtension !== 'pdf') {
                    Session::flash('error', 'CV must be a PDF file');
                    $this->redirect('/register');
                }
                
                // Validate file size (5MB max)
                if ($cvFile['size'] > 5 * 1024 * 1024) {
                    Session::flash('error', 'CV file must be less than 5MB');
                    $this->redirect('/register');
                }
                
                // Create upload directory if it doesn't exist
                $uploadDir = __DIR__ . '/../../storage/cvs/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $fileName = 'cv_' . uniqid() . '_' . time() . '.pdf';
                $filePath = $uploadDir . $fileName;
                
                // Move uploaded file
                if (move_uploaded_file($cvFile['tmp_name'], $filePath)) {
                    $cvPath = '/storage/cvs/' . $fileName;
                } else {
                    Session::flash('error', 'Failed to upload CV file');
                    $this->redirect('/register');
                }
            }
        }
        
        try {
            $passwordHash = Security::hashPassword($password);
            
            $data = [
                'role_id' => (int)$role,
                'email' => $email,
                'username' => $username,
                'password_hash' => $passwordHash,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => !empty($phone) ? $phone : null,
                'is_active' => (bool)($isActive ? true : false),
                'email_verified' => (bool)true,
                'registration_status' => $registrationStatus,
                'assigned_doctor_id' => !empty($assignedDoctorId) ? (int)$assignedDoctorId : null,
                'cv_path' => $cvPath
            ];
            
            User::create($data);
            
            if ($registrationStatus === 'pending') {
                Session::flash('success', 'Registration submitted successfully. Your account is pending approval.');
                $this->redirect('/login');
            } else {
                Session::flash('success', 'Registration successful. You can now log in.');
                $this->redirect('/login');
            }
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            Session::flash('error', 'Registration failed: ' . $e->getMessage());
            $this->redirect('/register');
        }
    }

    public function getDoctors(): void
    {
        $doctors = Database::fetchAll(
            "SELECT id, first_name, last_name FROM users WHERE role_id = 2 AND is_active = true AND registration_status = 'approved' ORDER BY first_name, last_name"
        );
        
        $this->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
}
