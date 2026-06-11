<?php
/**
 * ClinixPro - Hospital Management System
 * API Patients Controller
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Patient;

class PatientsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $patients = Patient::all();
        
        $this->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        
        $patient = Patient::find($id);
        
        if (!$patient) {
            $this->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
            return;
        }
        
        $this->json([
            'success' => true,
            'data' => $patient
        ]);
    }
}
