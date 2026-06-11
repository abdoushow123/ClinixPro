<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Record Payment</h1>
        <p class="text-muted mb-0">Record a payment for invoice #<?= htmlspecialchars($invoice['invoice_number']) ?></p>
    </div>
    <a href="/billing/<?= $invoice['id'] ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Invoice</span>
    </a>
</div>

<!-- Payment Form -->
<div class="card">
    <div class="card-body">
        <div class="mb-8" style="padding: 1.5rem; background: var(--gray-50); border-radius: var(--radius-lg);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Invoice Amount</div>
                    <div style="font-size: 1.5rem; font-weight: 800;"><?= formatCurrency($invoice['amount']) ?></div>
                </div>
                <div class="text-right">
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Patient</div>
                    <div style="font-weight: 600;"><?= htmlspecialchars($invoice['patient_name'] ?? 'N/A') ?></div>
                </div>
            </div>
        </div>
        
        <form method="POST" action="/billing/<?= $invoice['id'] ?>/payment">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="form-group mb-8">
                <label class="form-label">Payment Amount *</label>
                <input type="number" class="form-control" name="amount" step="0.01" required value="<?= htmlspecialchars($invoice['amount']) ?>">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Payment Method *</label>
                <select class="form-control" name="payment_method" required>
                    <option value="">Select Method</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="check">Check</option>
                    <option value="insurance">Insurance</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Payment Date *</label>
                <input type="date" class="form-control" name="payment_date" required value="<?= date('Y-m-d') ?>">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Reference Number</label>
                <input type="text" class="form-control" name="reference_number" placeholder="Transaction or check number">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Any additional notes"></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-credit-card"></i>
                    <span>Record Payment</span>
                </button>
                <a href="/billing/<?= $invoice['id'] ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>

<?php
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}
?>

<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
