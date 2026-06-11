<?php
/**
 * ClinixPro - Hospital Management System
 * Pharmacy Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\MedicationInventory;
use App\Models\User;

class PharmacyController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'pharmacist']);
        
        $medications = MedicationInventory::getAll();
        $stats = MedicationInventory::getStatistics();
        
        $this->view('pharmacy/index', [
            'title' => 'Pharmacy - ClinixPro',
            'medications' => $medications,
            'stats' => $stats
        ]);
    }

    public function inventory(): void
    {
        $this->requireAnyRole(['administrator', 'pharmacist']);
        
        $medications = MedicationInventory::getAll();
        $lowStock = MedicationInventory::getLowStock();
        $expiring = MedicationInventory::getExpiring();
        
        $this->view('pharmacy/inventory', [
            'title' => 'Medication Inventory - ClinixPro',
            'medications' => $medications,
            'low_stock' => $lowStock,
            'expiring' => $expiring,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function addInventory(): void
    {
        $this->requireAnyRole(['administrator', 'pharmacist']);
        
        if (!$this->isPost()) {
            $this->redirect('/pharmacy/inventory');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/pharmacy/inventory');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $medicationId = $this->post('medication_id');
        $quantity = $this->post('quantity');
        $notes = $this->sanitize($this->post('notes'));
        
        if (empty($medicationId) || empty($quantity) || $quantity <= 0) {
            Session::flash('error', 'Please provide valid medication and quantity');
            $this->redirect('/pharmacy/inventory');
        }
        
        try {
            MedicationInventory::addStock($medicationId, $quantity, 'manual', null, $notes);
            
            User::logActivity($currentUser['id'], 'inventory_added', 'medication', $medicationId, [
                'quantity' => $quantity
            ]);
            
            Session::flash('success', 'Stock added successfully');
            $this->redirect('/pharmacy/inventory');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to add stock: ' . $e->getMessage());
            $this->redirect('/pharmacy/inventory');
        }
    }

    public function showInventory(int $id): void
    {
        $this->requireAnyRole(['administrator', 'pharmacist']);
        
        $medication = MedicationInventory::find($id);
        
        if (!$medication) {
            $this->view('errors/404', ['message' => 'Medication not found']);
            return;
        }
        
        $this->view('pharmacy/show-inventory', [
            'title' => 'Medication Details - ClinixPro',
            'medication' => $medication
        ]);
    }
}
