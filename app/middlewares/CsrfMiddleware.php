<?php
/**
 * ClinixPro - Hospital Management System
 * CSRF Middleware
 * 
 * Validates CSRF tokens for POST requests.
 */

namespace App\Middlewares;

use App\Core\Security;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!Security::validateCsrfToken($token)) {
                http_response_code(403);
                echo 'CSRF token validation failed';
                exit;
            }
        }
    }
}
