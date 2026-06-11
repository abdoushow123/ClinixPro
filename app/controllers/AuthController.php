<?php
/**
 * ClinixPro - Hospital Management System
 * Authentication Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function login(): void
    {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', [
            'title' => 'Login - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    /**
     * Handle login submission
     */
    public function authenticate(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/login');
        }

        $email = $this->sanitize($this->post('email'));
        $password = $this->post('password');

        // Validate input
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please enter email and password');
            $this->redirect('/login');
        }

        // Check rate limiting
        if (!Security::checkRateLimit('login_' . Security::getClientIp(), 5, 300)) {
            Session::flash('error', 'Too many login attempts. Please try again later.');
            $this->redirect('/login');
        }

        // Authenticate user
        $user = User::authenticate($email, $password);

        if ($user) {
            // Log successful login
            User::logLoginAttempt($user['id'], 'success');

            // Set session
            Session::set('user_id', $user['id']);
            Session::set('user', $user);
            Session::regenerate();

            // Log activity
            User::logActivity($user['id'], 'login');

            // Redirect to intended page or dashboard
            $redirect = Session::get('redirect_after_login', '/dashboard');
            Session::remove('redirect_after_login');
            $this->redirect($redirect);
        } else {
            // Log failed attempt
            $tempUser = User::findByEmail($email);
            $userId = $tempUser ? $tempUser['id'] : null;
            User::logLoginAttempt($userId, 'failed', 'Invalid credentials');

            Session::flash('error', 'Invalid email or password');
            $this->redirect('/login');
        }
    }

    /**
     * Handle logout
     */
    public function logout(): void
    {
        if ($this->isAuthenticated()) {
            $user = $this->getCurrentUser();
            
            // Log activity
            User::logActivity($user['id'], 'logout');
            
            // Destroy session
            Session::destroy();
        }

        $this->redirect('/login');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', [
            'title' => 'Forgot Password - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    /**
     * Handle forgot password submission
     */
    public function sendResetLink(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/forgot-password');
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/forgot-password');
        }

        $email = $this->sanitize($this->post('email'));

        if (empty($email)) {
            Session::flash('error', 'Please enter your email');
            $this->redirect('/forgot-password');
        }

        $user = User::findByEmail($email);

        if ($user) {
            // Generate reset token
            $token = User::createPasswordResetToken($user['id']);
            
            // In production, send email with reset link
            // For demo, just show success message
            Session::flash('success', 'Password reset link has been sent to your email');
            
            // Log activity
            User::logActivity($user['id'], 'password_reset_requested');
        } else {
            // Don't reveal if email exists
            Session::flash('success', 'If the email exists, a reset link has been sent');
        }

        $this->redirect('/forgot-password');
    }

    /**
     * Show reset password form
     */
    public function resetPassword(): void
    {
        $token = $this->get('token');

        if (empty($token)) {
            Session::flash('error', 'Invalid reset token');
            $this->redirect('/login');
        }

        // Validate token
        $tokenData = User::validatePasswordResetToken($token);

        if (!$tokenData) {
            Session::flash('error', 'Invalid or expired reset token');
            $this->redirect('/login');
        }

        $this->view('auth/reset-password', [
            'title' => 'Reset Password - ClinixPro',
            'token' => $token,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    /**
     * Handle password reset
     */
    public function handleResetPassword(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/login');
        }

        $token = $this->post('token');
        $password = $this->post('password');
        $confirmPassword = $this->post('confirm_password');

        if (empty($token) || empty($password)) {
            Session::flash('error', 'Please fill all fields');
            $this->redirect('/reset-password?token=' . $token);
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'Passwords do not match');
            $this->redirect('/reset-password?token=' . $token);
        }

        if (!Security::validatePasswordStrength($password)) {
            Session::flash('error', 'Password must be at least 8 characters with uppercase, lowercase, number, and special character');
            $this->redirect('/reset-password?token=' . $token);
        }

        // Reset password
        if (User::resetPassword($token, $password)) {
            Session::flash('success', 'Password has been reset successfully');
            $this->redirect('/login');
        } else {
            Session::flash('error', 'Invalid or expired reset token');
            $this->redirect('/login');
        }
    }
}
