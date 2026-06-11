<?php
/**
 * ClinixPro - Hospital Management System
 * API Appointments Controller
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Appointment;

class AppointmentsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $appointments = Appointment::withDetails();
        
        $this->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
}
