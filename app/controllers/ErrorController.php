<?php
/**
 * ClinixPro - Hospital Management System
 * Error Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['message' => 'Page not found']);
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->view('errors/403', ['message' => 'Access denied']);
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->view('errors/500', ['message' => 'Server error']);
    }
}
