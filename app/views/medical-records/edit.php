<?php ob_start(); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Edit Medical Record</h1>
        <p class="text-muted mb-0">Update medical record details</p>
    </div>
    <a href="/medical-records/<?= $record['id'] ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Record</span>
    </a>
</div>

<!-- Medical Record Form -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="/medical-records/<?= $record['id'] ?>/update">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="grid grid-2 mb-8">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Select Patient</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Record Type *</label>
                    <select class="form-control" name="record_type" required>
                        <option value="">Select Type</option>
                        <option value="consultation" <?= $record['record_type'] === 'consultation' ? 'selected' : '' ?>>Consultation</option>
                        <option value="lab_result" <?= $record['record_type'] === 'lab_result' ? 'selected' : '' ?>>Lab Result</option>
                        <option value="prescription" <?= $record['record_type'] === 'prescription' ? 'selected' : '' ?>>Prescription</option>
                        <option value="imaging" <?= $record['record_type'] === 'imaging' ? 'selected' : '' ?>>Imaging</option>
                        <option value="surgery" <?= $record['record_type'] === 'surgery' ? 'selected' : '' ?>>Surgery</option>
                        <option value="vaccination" <?= $record['record_type'] === 'vaccination' ? 'selected' : '' ?>>Vaccination</option>
                        <option value="checkup" <?= $record['record_type'] === 'checkup' ? 'selected' : '' ?>>Check-up</option>
                        <option value="discharge" <?= $record['record_type'] === 'discharge' ? 'selected' : '' ?>>Discharge</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Title *</label>
                <input type="text" class="form-control" name="title" required value="<?= htmlspecialchars($record['title'] ?? '') ?>">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Record Date *</label>
                <input type="date" class="form-control" name="record_date" required value="<?= htmlspecialchars($record['record_date']) ?>">
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Description *</label>
                <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($record['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Diagnosis</label>
                <textarea class="form-control" name="diagnosis" rows="2"><?= htmlspecialchars($record['diagnosis'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Treatment</label>
                <textarea class="form-control" name="treatment" rows="2"><?= htmlspecialchars($record['treatment'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group mb-8">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($record['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-medical"></i>
                    <span>Update Record</span>
                </button>
                <a href="/medical-records/<?= $record['id'] ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
