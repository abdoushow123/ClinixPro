<?php
/**
 * ClinixPro - Hospital Management System
 * API Laboratory Controller
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\LaboratoryTest;

class LaboratoryController extends Controller
{
    public function tests(): void
    {
        $this->requireAuth();
        
        $tests = LaboratoryTest::getAll();
        
        $this->json([
            'success' => true,
            'data' => $tests
        ]);
    }
}
