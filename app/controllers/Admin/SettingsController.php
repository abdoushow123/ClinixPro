<?php
/**
 * ClinixPro - Hospital Management System
 * Admin Settings Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $this->view('admin/settings/index', [
            'title' => 'System Settings - ClinixPro'
        ]);
    }
}
