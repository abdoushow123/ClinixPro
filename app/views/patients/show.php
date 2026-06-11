<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Patient Details</h1>
        <p class="text-muted mb-0">View patient information and history</p>
    </div>
    <div class="d-flex gap-3">
        <a href="/patients/<?= $patient['id'] ?>/edit" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            <span>Edit</span>
        </a>
        <form method="POST" action="/patients/<?= $patient['id'] ?>/delete" style="display: inline;" data-confirm="Are you sure you want to delete this patient?">
            <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i>
                <span>Delete</span>
            </button>
        </form>
    </div>
</div>

<!-- Patient Profile Card -->
<div class="card mb-8">
    <div class="card-body">
        <div class="d-flex align-items-start gap-4">
            <div style="width: 120px; height: 120px; background: var(--accent); border-radius: var(--radius-2xl); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 3rem;">
                <?= strtoupper(substr($patient['first_name'], 0, 1)) ?>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></h2>
                <div class="d-flex gap-3 mb-4">
                    <span class="badge badge-info"><?= htmlspecialchars($patient['patient_id']) ?></span>
                    <span class="badge <?= $patient['gender'] === 'male' ? 'badge-info' : 'badge-danger' ?>">
                        <i class="bi bi-<?= $patient['gender'] === 'male' ? 'gender-male' : 'gender-female' ?>" style="margin-right: 0.25rem;"></i>
                        <?= ucfirst($patient['gender']) ?>
                    </span>
                    <span class="badge badge-success">Active</span>
                </div>
                <div class="d-flex gap-6" style="color: var(--text-secondary); font-size: 0.875rem;">
                    <div><i class="bi bi-calendar3" style="margin-right: 0.5rem;"></i><?= date('M d, Y', strtotime($patient['date_of_birth'])) ?> (<?= calculateAge($patient['date_of_birth']) ?> years)</div>
                    <div><i class="bi bi-telephone" style="margin-right: 0.5rem;"></i><?= htmlspecialchars($patient['phone']) ?></div>
                    <div><i class="bi bi-envelope" style="margin-right: 0.5rem;"></i><?= htmlspecialchars($patient['email'] ?? 'N/A') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Grid -->
<div class="grid grid-3 mb-8">
    <!-- Contact Information -->
    <div class="card">
        <div class="card-header">
            <h3>Contact Information</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap-4;">
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Address</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['address'] ?? 'N/A') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">City</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['city'] ?? 'N/A') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">State</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['state'] ?? 'N/A') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Postal Code</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['postal_code'] ?? 'N/A') ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Medical Information -->
    <div class="card">
        <div class="card-header">
            <h3>Medical Information</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap-4;">
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Blood Type</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['blood_type'] ?? 'Not specified') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Allergies</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['allergies'] ?? 'None reported') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Chronic Conditions</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['chronic_conditions'] ?? 'None reported') ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Insurance Information -->
    <div class="card">
        <div class="card-header">
            <h3>Insurance</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap-4;">
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Provider</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['insurance_provider'] ?? 'Not specified') ?></div>
                </div>
                <div>
                    <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Policy Number</div>
                    <div style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($patient['insurance_policy_number'] ?? 'Not specified') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Contact -->
<div class="card mb-8">
    <div class="card-header">
        <h3>Emergency Contact</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-3">
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Contact Name</div>
                <div style="color: var(--text-primary); font-weight: 600;"><?= htmlspecialchars($patient['emergency_contact_name'] ?? 'Not specified') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Phone</div>
                <div style="color: var(--text-primary); font-weight: 600;"><?= htmlspecialchars($patient['emergency_contact_phone'] ?? 'Not specified') ?></div>
            </div>
            <div>
                <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-bottom: 0.25rem;">Relationship</div>
                <div style="color: var(--text-primary); font-weight: 600;"><?= htmlspecialchars($patient['emergency_contact_relationship'] ?? 'Not specified') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3>Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-4">
            <a href="/appointments/create?patient_id=<?= $patient['id'] ?>" class="btn btn-primary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-calendar-plus"></i>
                <span>Schedule Appointment</span>
            </a>
            <a href="/medical-records/create?patient_id=<?= $patient['id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-file-medical"></i>
                <span>Add Record</span>
            </a>
            <a href="/prescriptions/create?patient_id=<?= $patient['id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-prescription2"></i>
                <span>New Prescription</span>
            </a>
            <a href="/billing/create?patient_id=<?= $patient['id'] ?>" class="btn btn-secondary" style="justify-content: center; text-decoration: none;">
                <i class="bi bi-receipt"></i>
                <span>Create Invoice</span>
            </a>
        </div>
    </div>
</div>

<?php
function calculateAge($dateOfBirth) {
    $birthDate = new DateTime($dateOfBirth);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}
?>

<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
