<?php
/**
 * ClinixPro - Hospital Management System
 * Insurance Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\InsuranceClaim;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\User;

class InsuranceController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        $claims = InsuranceClaim::withDetails();
        $stats = InsuranceClaim::getStatistics();
        
        $this->view('insurance/index', [
            'title' => 'Insurance Claims - ClinixPro',
            'claims' => $claims,
            'stats' => $stats
        ]);
    }

    public function claim(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        $patientId = $this->get('patient_id');
        $invoiceId = $this->get('invoice_id');
        $patient = null;
        $invoice = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        if ($invoiceId) {
            $invoice = Invoice::withDetails($invoiceId);
        }
        
        $this->view('insurance/claim', [
            'title' => 'File Insurance Claim - ClinixPro',
            'patient' => $patient,
            'invoice' => $invoice,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function storeClaim(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        if (!$this->isPost()) {
            $this->redirect('/insurance');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/insurance/claim');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'invoice_id' => $this->post('invoice_id'),
            'insurance_provider' => $this->sanitize($this->post('insurance_provider')),
            'policy_number' => $this->sanitize($this->post('policy_number')),
            'claim_number' => $this->sanitize($this->post('claim_number')),
            'amount' => (float)$this->post('amount'),
            'status' => 'pending',
            'claim_date' => date('Y-m-d'),
            'notes' => $this->sanitize($this->post('notes')),
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['insurance_provider']) || empty($data['amount'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/insurance/claim');
        }
        
        try {
            InsuranceClaim::create($data);
            
            User::logActivity($currentUser['id'], 'insurance_claim_created', 'insurance_claim', null, [
                'patient_id' => $data['patient_id'],
                'amount' => $data['amount']
            ]);
            
            Session::flash('success', 'Insurance claim filed successfully');
            $this->redirect('/insurance');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to file claim: ' . $e->getMessage());
            $this->redirect('/insurance/claim');
        }
    }
}
