<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Edit Appointment</h1>
        <p class="text-muted mb-0">Update appointment details</p>
    </div>
    <a href="/appointments/<?= $appointment['id'] ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Appointment</span>
    </a>
</div>

<!-- Appointment Form -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="/appointments/<?= $appointment['id'] ?>/update">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Select Patient</option>
                        <!-- Dynamic patient options -->
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Doctor *</label>
                    <select class="form-control" name="doctor_id" required>
                        <option value="">Select Doctor</option>
                        <!-- Dynamic doctor options -->
                    </select>
                </div>
            </div>
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Appointment Date *</label>
                    <input type="date" class="form-control" name="appointment_date" required value="<?= htmlspecialchars($appointment['appointment_date']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Appointment Time *</label>
                    <input type="time" class="form-control" name="appointment_time" required value="<?= htmlspecialchars($appointment['appointment_time']) ?>">
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Appointment Type *</label>
                <select class="form-control" name="appointment_type" required>
                    <option value="">Select Type</option>
                    <option value="consultation" <?= $appointment['appointment_type'] === 'consultation' ? 'selected' : '' ?>>Consultation</option>
                    <option value="follow-up" <?= $appointment['appointment_type'] === 'follow-up' ? 'selected' : '' ?>>Follow-up</option>
                    <option value="checkup" <?= $appointment['appointment_type'] === 'checkup' ? 'selected' : '' ?>>Check-up</option>
                    <option value="emergency" <?= $appointment['appointment_type'] === 'emergency' ? 'selected' : '' ?>>Emergency</option>
                    <option value="surgery" <?= $appointment['appointment_type'] === 'surgery' ? 'selected' : '' ?>>Surgery</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="scheduled" <?= $appointment['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="completed" <?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $appointment['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    <option value="no-show" <?= $appointment['status'] === 'no-show' ? 'selected' : '' ?>>No Show</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Reason for Visit</label>
                <textarea class="form-control" name="reason" rows="3"><?= htmlspecialchars($appointment['reason'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($appointment['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i>
                    <span>Update Appointment</span>
                </button>
                <a href="/appointments/<?= $appointment['id'] ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
