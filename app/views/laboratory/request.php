<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Request Lab Test</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Order clinical diagnostics for a patient</p>
    </div>
    <a href="/laboratory" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Laboratory</span>
    </a>
</div>

<!-- Lab Request Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/laboratory/store-request" class="p-8 space-y-8">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Select Patient *</label>
                <div class="relative group">
                    <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <select name="patient_id" required class="input-modern pl-12">
                        <option value="">Choose a patient...</option>
                        <?php if (!empty($patients)): ?>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (isset($patient) && $patient['id'] == $p['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?> (<?= htmlspecialchars($p['patient_id']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Test Category *</label>
                <div class="relative group">
                    <i class="bi bi-flask absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <select name="test_id" required class="input-modern pl-12">
                        <option value="">Select diagnostic test...</option>
                        <option value="1">Complete Blood Count (CBC)</option>
                        <option value="2">Blood Chemistry Panel</option>
                        <option value="3">Lipid Profile</option>
                        <option value="4">Liver Function Test</option>
                        <option value="5">Kidney Function Test</option>
                        <option value="6">Thyroid Function Test</option>
                        <option value="7">Urinalysis</option>
                        <option value="8">Stool Analysis</option>
                        <option value="9">Blood Glucose Test</option>
                        <option value="10">Hemoglobin A1C</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Priority Level</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="normal" checked class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 dark:peer-checked:bg-blue-900/20 transition-all font-bold text-slate-600 dark:text-slate-400 peer-checked:text-blue-600">
                            Normal
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="high" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 dark:peer-checked:bg-amber-900/20 transition-all font-bold text-slate-600 dark:text-slate-400 peer-checked:text-amber-600">
                            High
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="urgent" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 peer-checked:border-red-500 peer-checked:bg-red-50/50 dark:peer-checked:bg-red-900/20 transition-all font-bold text-slate-600 dark:text-slate-400 peer-checked:text-red-600">
                            Urgent
                        </div>
                    </label>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Requested For</label>
                <div class="relative group">
                    <i class="bi bi-calendar-event absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="date" name="requested_date" class="input-modern pl-12" value="<?= date('Y-m-d') ?>">
                </div>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Clinical Notes & Reason for Test</label>
            <textarea name="clinical_notes" rows="4" class="input-modern" placeholder="Provide any relevant symptoms, clinical history, or specific instructions for the lab technician..."></textarea>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-flask"></i>
                <span>Submit Lab Request</span>
            </button>
            <a href="/laboratory" class="btn-secondary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-x-circle"></i>
                <span>Cancel Request</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Request Lab Test';
require __DIR__ . '/../layouts/app.php'; 
?>
