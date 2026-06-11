<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Medical Record Details</h1>
        <p class="text-muted mb-0">View medical record information</p>
    </div>
    <div class="d-flex gap-3">
        <a href="/medical-records/<?= $record['id'] ?>/edit" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            <span>Edit</span>
        </a>
        <a href="/medical-records" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>
</div>

<!-- Medical Record Card -->
<div class="card mb-8">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-6">
            <div class="d-flex align-items-center gap-4">
                <div style="width: 64px; height: 64px; background: #dbeafe; border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-<?= getRecordIcon($record['visit_type'] ?? '') ?>" style="color: #3b82f6; font-size: 2rem;"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem 0;"><?= htmlspecialchars($record['diagnosis'] ?? 'Medical Record') ?></h2>
                    <span class="badge badge-info"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $record['visit_type'] ?? 'visit'))) ?></span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-muted" style="font-size: 0.875rem;">Visit Date</div>
                <div style="font-weight: 600; color: var(--text-primary);"><?= !empty($record['visit_date']) ? date('F d, Y', strtotime($record['visit_date'])) : 'N/A' ?></div>
            </div>
        </div>
        
        <div class="grid grid-2 mb-8">
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Patient</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($record['patient_name'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Doctor</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($record['doctor_name'] ?? 'N/A') ?></div>
            </div>
        </div>
        
        <?php if (!empty($record['chief_complaint'])): ?>
        <div style="margin-bottom: 2rem;">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Chief Complaint</div>
            <div style="color: var(--text-primary); line-height: 1.6;"><?= htmlspecialchars($record['chief_complaint']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($record['diagnosis'])): ?>
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: var(--radius-lg);">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Diagnosis</div>
            <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($record['diagnosis']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($record['treatment_plan'])): ?>
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #d1fae5; border-radius: var(--radius-lg);">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Treatment Plan</div>
            <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($record['treatment_plan']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($record['notes'])): ?>
        <div style="margin-bottom: 2rem;">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Notes</div>
            <div style="color: var(--text-primary);"><?= htmlspecialchars($record['notes']) ?></div>
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
            <a href="/prescriptions/create?patient_id=<?= $record['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-prescription2"></i>
                <span>Prescription</span>
            </a>
            <a href="/appointments/create?patient_id=<?= $record['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-calendar-plus"></i>
                <span>Appointment</span>
            </a>
            <a href="/billing/create?patient_id=<?= $record['patient_id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-receipt"></i>
                <span>Invoice</span>
            </a>
            <a href="/medical-records/<?= $record['id'] ?>/edit" class="btn btn-primary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-pencil"></i>
                <span>Edit Record</span>
            </a>
        </div>
    </div>
</div>

<?php
function getRecordIcon($type) {
    $icons = [
        'consultation' => 'chat-dots',
        'lab_result' => 'flask',
        'prescription' => 'prescription2',
        'imaging' => 'image',
        'surgery' => 'scissors',
        'vaccination' => 'shield-check',
        'checkup' => 'heart-pulse',
        'discharge' => 'box-arrow-right'
    ];
    return $icons[$type] ?? 'file-earmark-medical';
}
?>

<?php $content = ob_get_clean(); ?>

<?php
$hasSidebar = true;
$sidebarUser = $user ?? $authUser ?? [];
$displayName = trim(($sidebarUser['first_name'] ?? '') . ' ' . ($sidebarUser['last_name'] ?? '')) ?: ($sidebarUser['full_name'] ?? 'User');
$initials = strtoupper(substr($displayName, 0, 1));
$pageTitle = 'Medical Record - ClinixPro';
$displayTitle = 'Medical Record';
$currentPath = '/medical-records';
$isActive = function($path) use ($currentPath) {
    return $path === $currentPath || ($path !== '/' && str_starts_with($currentPath, $path));
};
require __DIR__ . '/../layouts/app.php';
