<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Appointment Details</h1>
        <p class="text-muted mb-0">View appointment information</p>
    </div>
    <div class="d-flex gap-3">
        <a href="/appointments/<?= $appointment['id'] ?>/edit" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            <span>Edit</span>
        </a>
        <a href="/appointments" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>
</div>

<!-- Appointment Details Card -->
<div class="card mb-8">
    <div class="card-body">
        <div class="grid grid-2">
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Patient</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($appointment['patient_name'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Doctor</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($appointment['doctor_name'] ?? 'Not assigned') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Date</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= date('F d, Y', strtotime($appointment['appointment_date'])) ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Time</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Type</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars(ucfirst($appointment['appointment_type'] ?? 'General')) ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Status</div>
                <span class="badge <?= $appointment['status'] === 'scheduled' ? 'badge-success' : 'badge-warning' ?>">
                    <?= ucfirst($appointment['status']) ?>
                </span>
            </div>
        </div>
        
        <?php if (!empty($appointment['reason'])): ?>
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Reason for Visit</div>
            <div style="color: var(--text-primary);"><?= htmlspecialchars($appointment['reason']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($appointment['notes'])): ?>
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Notes</div>
            <div style="color: var(--text-primary);"><?= htmlspecialchars($appointment['notes']) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3>Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-4">
            <a href="/medical-records/create?patient_id=<?= $appointment['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-file-medical"></i>
                <span>Add Record</span>
            </a>
            <a href="/prescriptions/create?patient_id=<?= $appointment['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-prescription2"></i>
                <span>Prescription</span>
            </a>
            <a href="/billing/create?patient_id=<?= $appointment['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-receipt"></i>
                <span>Create Invoice</span>
            </a>
            <a href="/appointments/<?= $appointment['id'] ?>/edit" class="btn btn-primary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-pencil"></i>
                <span>Reschedule</span>
            </a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
