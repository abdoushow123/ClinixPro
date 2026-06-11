<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">New Insurance Claim</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Submit a medical claim for provider reimbursement</p>
    </div>
    <a href="/insurance" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Insurance</span>
    </a>
</div>

<!-- Claim Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/insurance/store-claim" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Patient & Provider -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-badge-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Patient & Policy Info</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Select Patient *</label>
                    <div class="relative group">
                        <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <select name="patient_id" required class="input-modern pl-12">
                            <option value="">Choose a patient...</option>
                            <?php if (!empty($patients)): ?>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?> (<?= htmlspecialchars($p['patient_id'] ?? 'ID') ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Insurance Provider *</label>
                    <div class="relative group">
                        <i class="bi bi-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="insurance_provider" required class="input-modern pl-12" placeholder="e.g., BlueCross BlueShield">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Policy Number *</label>
                    <div class="relative group">
                        <i class="bi bi-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="policy_number" required class="input-modern pl-12" placeholder="Enter policy number">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Service Type *</label>
                    <select name="service_type" required class="input-modern">
                        <option value="">Select Service Category</option>
                        <option value="consultation">Consultation</option>
                        <option value="procedure">Procedure</option>
                        <option value="medication">Medication</option>
                        <option value="hospitalization">Hospitalization</option>
                        <option value="laboratory">Laboratory</option>
                        <option value="imaging">Imaging</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Financial Info -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Claim Financials</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Requested Amount ($) *</label>
                    <input type="number" name="amount" step="0.01" required class="input-modern" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Claim Date</label>
                    <input type="date" name="claim_date" class="input-modern" value="<?= date('Y-m-d') ?>">
                </div>
            </div>
        </div>

        <!-- Detailed Notes -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-journal-text text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Description & Notes</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Service Description *</label>
                    <textarea name="description" rows="3" required class="input-modern" placeholder="Provide a detailed description of the medical services provided..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Internal Notes</label>
                    <textarea name="notes" rows="2" class="input-modern" placeholder="Any internal billing notes or policy specific information..."></textarea>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-shield-check"></i>
                <span>Submit Insurance Claim</span>
            </button>
            <a href="/insurance" class="btn-secondary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-x-circle"></i>
                <span>Cancel Submission</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'New Claim';
require __DIR__ . '/../layouts/app.php'; 
?>
