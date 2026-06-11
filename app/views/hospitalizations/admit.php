<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Admit Patient</h1>
        <p class="text-muted mb-0">Admit a patient to the hospital</p>
    </div>
    <a href="/hospitalizations" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Hospitalizations</span>
    </a>
</div>

<!-- Admission Form -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="/hospitalizations/store">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="form-group mb-8">
                <label class="form-label">Patient *</label>
                <select class="form-control" name="patient_id" required>
                    <option value="">Select Patient</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Room *</label>
                <select class="form-control" name="room_id" required>
                    <option value="">Select Room</option>
                </select>
            </div>
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Admission Date *</label>
                    <input type="date" class="form-control" name="admission_date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Admission Time *</label>
                    <input type="time" class="form-control" name="admission_time" required>
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Attending Doctor</label>
                <select class="form-control" name="attending_doctor_id">
                    <option value="">Select Doctor</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Reason for Admission *</label>
                <textarea class="form-control" name="admission_reason" rows="3" required placeholder="Describe the reason for admission"></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Any additional notes"></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-hospital"></i>
                    <span>Admit Patient</span>
                </button>
                <a href="/hospitalizations" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = true;
$sidebarUser = $user ?? [];
$displayName = $user['full_name'] ?? 'User';
$initials = substr($user['full_name'] ?? 'U', 0, 1);
$pageTitle = 'Admit Patient';
$displayTitle = 'Admit Patient';
$currentPath = '/hospitalizations';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; ?>
