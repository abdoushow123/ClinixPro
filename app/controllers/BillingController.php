<?php
/**
 * ClinixPro - Hospital Management System
 * Billing Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;

class BillingController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        $invoices = Invoice::withPatient();
        $stats = Invoice::getStatistics();
        
        $this->view('billing/index', [
            'title' => 'Billing - ClinixPro',
            'invoices' => $invoices,
            'stats' => $stats
        ]);
    }

    public function create(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        $patientId = $this->get('patient_id');
        $patient = null;
        
        if ($patientId) {
            $patient = Patient::find($patientId);
        }
        
        $this->view('billing/create', [
            'title' => 'Create Invoice - ClinixPro',
            'patient' => $patient,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        if (!$this->isPost()) {
            $this->redirect('/billing');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/billing/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $subtotal = (float)$this->post('subtotal');
        $taxAmount = (float)$this->post('tax_amount', 0);
        $discountAmount = (float)$this->post('discount_amount', 0);
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        
        $data = [
            'patient_id' => $this->post('patient_id'),
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => $this->post('invoice_date'),
            'due_date' => $this->post('due_date'),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $this->sanitize($this->post('notes')),
            'created_by' => $currentUser['id']
        ];
        
        if (empty($data['patient_id']) || empty($data['invoice_date']) || empty($data['due_date'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/billing/create');
        }
        
        try {
            Database::beginTransaction();
            
            $invoiceId = Invoice::create($data);
            
            // Add invoice items if provided
            $items = $this->post('items', []);
            if (is_array($items) && !empty($items)) {
                foreach ($items as $item) {
                    if (!empty($item['description']) && !empty($item['quantity']) && !empty($item['unit_price'])) {
                        $itemData = [
                            'invoice_id' => $invoiceId,
                            'item_type' => $item['type'] ?? 'service',
                            'description' => $item['description'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $item['quantity'] * $item['unit_price']
                        ];
                        Database::query(
                            "INSERT INTO invoice_items (invoice_id, item_type, description, quantity, unit_price, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)",
                            [$itemData['invoice_id'], $itemData['item_type'], $itemData['description'], $itemData['quantity'], $itemData['unit_price'], $itemData['total_price']]
                        );
                    }
                }
            }
            
            Database::commit();
            
            User::logActivity($currentUser['id'], 'invoice_created', 'invoice', $invoiceId);
            
            Session::flash('success', 'Invoice created successfully');
            $this->redirect('/billing/' . $invoiceId);
        } catch (\Exception $e) {
            Database::rollback();
            Session::flash('error', 'Failed to create invoice: ' . $e->getMessage());
            $this->redirect('/billing/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        $invoice = Invoice::withDetails($id);
        
        if (!$invoice) {
            $this->view('errors/404', ['message' => 'Invoice not found']);
            return;
        }
        
        $items = Invoice::getItems($id);
        
        $this->view('billing/show', [
            'title' => 'Invoice Details - ClinixPro',
            'invoice' => $invoice,
            'items' => $items,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function payment(int $id): void
    {
        $this->requireAnyRole(['administrator', 'receptionist']);
        
        if (!$this->isPost()) {
            $this->redirect('/billing/' . $id);
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/billing/' . $id);
        }
        
        $currentUser = $this->getCurrentUser();
        $amount = (float)$this->post('amount');
        $paymentMethod = $this->post('payment_method');
        
        if (empty($amount) || $amount <= 0) {
            Session::flash('error', 'Please provide a valid payment amount');
            $this->redirect('/billing/' . $id);
        }
        
        try {
            Database::beginTransaction();
            
            $invoice = Invoice::find($id);
            
            // Add payment
            Database::query(
                "INSERT INTO payments (invoice_id, payment_date, payment_method, amount, reference_number, received_by, created_at) VALUES (?, CURRENT_DATE, ?, ?, ?, ?, CURRENT_TIMESTAMP)",
                [$id, $paymentMethod, $amount, $this->post('reference_number'), $currentUser['id']]
            );
            
            // Update invoice
            $newPaidAmount = $invoice['paid_amount'] + $amount;
            $newBalance = $invoice['total_amount'] - $newPaidAmount;
            $newStatus = $newBalance <= 0 ? 'paid' : ($newBalance < $invoice['total_amount'] ? 'partial' : 'pending');
            
            Invoice::update($id, [
                'paid_amount' => $newPaidAmount,
                'balance_amount' => $newBalance,
                'status' => $newStatus
            ]);
            
            Database::commit();
            
            User::logActivity($currentUser['id'], 'payment_received', 'invoice', $id, [
                'amount' => $amount,
                'method' => $paymentMethod
            ]);
            
            Session::flash('success', 'Payment recorded successfully');
            $this->redirect('/billing/' . $id);
        } catch (\Exception $e) {
            Database::rollback();
            Session::flash('error', 'Failed to record payment: ' . $e->getMessage());
            $this->redirect('/billing/' . $id);
        }
    }
}
