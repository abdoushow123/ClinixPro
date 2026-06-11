<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Add Medication</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Add new pharmaceutical supplies to hospital inventory</p>
    </div>
    <a href="/pharmacy/inventory" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Inventory</span>
    </a>
</div>

<!-- Medication Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/pharmacy/inventory/add" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Basic Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-capsule-pill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Medication Details</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Medication Name *</label>
                    <input type="text" name="medication_name" required class="input-modern" placeholder="e.g., Amoxicillin">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Generic Name</label>
                    <input type="text" name="generic_name" class="input-modern" placeholder="e.g., Amoxicillin Trihydrate">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Manufacturer</label>
                    <input type="text" name="manufacturer" class="input-modern" placeholder="e.g., Pfizer, Novartis">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Category</label>
                    <input type="text" name="category" class="input-modern" placeholder="e.g., Antibiotics, Analgesics">
                </div>
            </div>
        </div>

        <!-- Stock & Pricing -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Stock & Pricing</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Initial Stock *</label>
                    <input type="number" name="current_stock" required min="0" value="0" class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Min. Stock Level</label>
                    <input type="number" name="minimum_stock_level" min="0" value="10" class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Unit Price ($)</label>
                    <input type="number" name="unit_price" step="0.01" placeholder="0.00" class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" class="input-modern">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Register Medication</span>
            </button>
            <a href="/pharmacy/inventory" class="btn-secondary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-x-circle"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Add Medication';
require __DIR__ . '/../../layouts/app.php'; 
?>
