<?php
/**
 * ClinixPro - Hospital Management System
 * Authentication Middleware
 * 
 * Ensures user is authenticated before accessing protected routes.
 */

namespace App\Middlewares;

use App\Core\Session;
use App\Core\Controller;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Session::has('user_id')) {
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit;
        }
    }
}
