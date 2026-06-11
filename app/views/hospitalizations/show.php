<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Admission Details</h1>
        <p class="text-muted mb-0">View hospitalization information</p>
    </div>
    <div class="d-flex gap-3">
        <form method="POST" action="/hospitalizations/<?= $admission['id'] ?>/discharge" data-confirm="Discharge this patient?">
            <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-box-arrow-right"></i>
                <span>Discharge Patient</span>
            </button>
        </form>
        <a href="/hospitalizations" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>
</div>

<!-- Admission Card -->
<div class="card mb-8">
    <div class="card-body">
        <div class="grid grid-2">
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Patient</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($admission['patient_name'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Room</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($admission['room_number'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Admission Date</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= date('F d, Y', strtotime($admission['admission_date'])) ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Attending Doctor</div>
                <div style="font-weight: 600; color: var(--text-primary); font-size: 1.125rem;"><?= htmlspecialchars($admission['doctor_name'] ?? 'N/A') ?></div>
            </div>
        </div>
        
        <?php if (!empty($admission['reason'])): ?>
        <div style="margin-top: 2rem;">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Reason for Admission</div>
            <div style="color: var(--text-primary);"><?= htmlspecialchars($admission['reason']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($admission['notes'])): ?>
        <div style="margin-top: 2rem;">
            <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.5rem;">Notes</div>
            <div style="color: var(--text-primary);"><?= htmlspecialchars($admission['notes']) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = true;
$sidebarUser = $user ?? [];
$displayName = $user['full_name'] ?? 'User';
$initials = substr($user['full_name'] ?? 'U', 0, 1);
$pageTitle = 'Admission Details';
$displayTitle = 'Admission Details';
$currentPath = '/hospitalizations';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; ?>
