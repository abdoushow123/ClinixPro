<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">New Prescription</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Issue a pharmaceutical order for a patient</p>
    </div>
    <a href="/prescriptions" class="btn-secondary">
        <i class="bi bi-arrow-left-short text-xl"></i>
        <span>Back to Prescriptions</span>
    </a>
</div>

<!-- Prescription Form -->
<div class="glass-panel overflow-hidden animate-slide-up" style="animation-delay: 0.1s;">
    <form method="POST" action="/prescriptions/store" class="p-8 md:p-10 space-y-12">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Patient & Medication -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-person-badge-fill text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Order Information</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Select patient and medication</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">Select Patient <span class="text-danger-500">*</span></label>
                    <div class="relative">
                        <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        <select name="patient_id" required class="input-modern pl-11 appearance-none pr-10">
                            <option value="">Choose a patient...</option>
                            <?php if (!empty($patients)): ?>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?> (<?= htmlspecialchars($p['patient_id'] ?? 'ID') ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">Medication Name <span class="text-danger-500">*</span></label>
                    <div class="relative">
                        <i class="bi bi-capsule absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="medication" required class="input-modern pl-11" placeholder="e.g., Amoxicillin 500mg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Dosage & Timing -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-clock-history text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Dosage & Frequency</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">When and how much to take</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">Dosage Amount <span class="text-danger-500">*</span></label>
                    <input type="text" name="dosage" required class="input-modern" placeholder="e.g., 1 tablet">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Frequency <span class="text-danger-500">*</span></label>
                    <input type="text" name="frequency" required class="input-modern" placeholder="e.g., 3 times daily">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Duration <span class="text-danger-500">*</span></label>
                    <input type="text" name="duration" required class="input-modern" placeholder="e.g., 7 days">
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-chat-left-dots-fill text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Usage Instructions</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Notes for patient and pharmacy</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">Patient Instructions</label>
                    <textarea name="instructions" rows="4" class="input-modern" placeholder="e.g., Take after meals, avoid alcohol..."></textarea>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Internal Pharmacist Notes</label>
                    <textarea name="notes" rows="4" class="input-modern" placeholder="Any specific compounding or storage requirements..."></textarea>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-8 border-t border-slate-200/60 dark:border-zinc-700/50">
            <a href="/prescriptions" class="btn-secondary w-full sm:w-auto px-8">
                Cancel
            </a>
            <button type="submit" class="btn-primary w-full sm:w-auto px-10 shadow-glow-primary">
                <i class="bi bi-prescription2 mr-2"></i>
                <span>Issue Prescription</span>
            </button>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'New Prescription';
require __DIR__ . '/../layouts/app.php'; 
?>
