<?php
/**
 * ClinixPro - Hospital Management System
 * Admin Middleware
 * 
 * Ensures user has administrator role.
 */

namespace App\Middlewares;

use App\Core\Session;

class AdminMiddleware
{
    public function handle(): void
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $user = Session::get('user');
        
        // Check if user has administrator role (case-insensitive)
        $isAdmin = isset($user['role_name']) && in_array(strtolower($user['role_name']), ['administrator', 'admin']);
        
        if (!$isAdmin) {
            http_response_code(403);
            echo 'Access denied. Administrator privileges required.';
            exit;
        }
    }
}
