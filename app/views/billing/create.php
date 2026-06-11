<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Create Invoice</h1>
        <p class="text-muted mb-0">Generate a new invoice for a patient</p>
    </div>
    <a href="/billing" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Billing</span>
    </a>
</div>

<!-- Invoice Form -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="/billing/store">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Select Patient</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Invoice Date *</label>
                    <input type="date" class="form-control" name="invoice_date" required>
                </div>
            </div>
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Due Date *</label>
                    <input type="date" class="form-control" name="due_date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Describe the services or items being billed"></textarea>
            </div>
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Amount *</label>
                    <input type="number" class="form-control" name="amount" step="0.01" required placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Tax</label>
                    <input type="number" class="form-control" name="tax" step="0.01" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Discount</label>
                <input type="number" class="form-control" name="discount" step="0.01" placeholder="0.00">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Any additional notes"></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-receipt"></i>
                    <span>Create Invoice</span>
                </button>
                <a href="/billing" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
