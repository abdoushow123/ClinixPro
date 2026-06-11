<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Schedule Appointment</h1>
        <p class="text-muted mb-0">Book a new appointment for a patient</p>
    </div>
    <a href="/appointments" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Appointments</span>
    </a>
</div>

<!-- Appointment Form -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="/appointments/store">
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
                    <input type="date" class="form-control" name="appointment_date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Appointment Time *</label>
                    <input type="time" class="form-control" name="appointment_time" required>
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Appointment Type *</label>
                <select class="form-control" name="appointment_type" required>
                    <option value="">Select Type</option>
                    <option value="consultation">Consultation</option>
                    <option value="follow-up">Follow-up</option>
                    <option value="checkup">Check-up</option>
                    <option value="emergency">Emergency</option>
                    <option value="surgery">Surgery</option>
                </select>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Reason for Visit</label>
                <textarea class="form-control" name="reason" rows="3" placeholder="Describe the reason for this appointment"></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Any additional notes"></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i>
                    <span>Schedule Appointment</span>
                </button>
                <a href="/appointments" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
