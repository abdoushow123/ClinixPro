<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Medication Details</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Detailed inventory information and stock levels</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="/pharmacy/inventory/<?= $medication['id'] ?>/edit" class="btn-primary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-pencil-square"></i>
            <span>Edit Medication</span>
        </a>
        <a href="/pharmacy/inventory" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Inventory</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="bi bi-capsule-pill text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($medication['medication_name'] ?? $medication['name'] ?? 'Medication') ?></h2>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                <?= htmlspecialchars($medication['category'] ?? 'General') ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Current Availability</p>
                        <span class="px-4 py-1.5 rounded-xl text-sm font-black uppercase tracking-wider <?= ($medication['current_stock'] ?? $medication['stock'] ?? 0) > 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' ?>">
                            <?= ($medication['current_stock'] ?? $medication['stock'] ?? 0) > 0 ? 'In Stock' : 'Out of Stock' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 mb-12">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Generic Name</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($medication['generic_name'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Manufacturer</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($medication['manufacturer'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Dosage Form</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($medication['dosage_form'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Strength</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white font-mono"><?= htmlspecialchars($medication['strength'] ?? '—') ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Unit Price</p>
                        <p class="text-2xl font-black text-blue-600 dark:text-blue-400"><?= \App\Helpers\CurrencyHelper::format($medication['unit_price'] ?? $medication['price'] ?? 0) ?></p>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Current Stock</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white"><?= number_format($medication['current_stock'] ?? $medication['stock'] ?? 0) ?></p>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Min. Stock Level</p>
                        <p class="text-2xl font-black text-amber-600"><?= number_format($medication['minimum_stock_level'] ?? 10) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Meta Data -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-6">
            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[10px] mb-6">Supply Chain & Expiry</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Expiry Date</span>
                    <?php 
                    $expiry = $medication['expiry_date'] ? strtotime($medication['expiry_date']) : null;
                    $isExpiring = $expiry && $expiry < strtotime('+3 months');
                    ?>
                    <span class="font-bold <?= $isExpiring ? 'text-red-500' : 'text-slate-700 dark:text-slate-300' ?>">
                        <?= $expiry ? date('M d, Y', $expiry) : 'N/A' ?>
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Batch Number</span>
                    <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300"><?= htmlspecialchars($medication['batch_number'] ?? 'N/A') ?></span>
                </div>
                <div class="flex flex-col gap-1 text-sm pt-2">
                    <span class="text-slate-500">Supplier</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300 italic"><?= htmlspecialchars($medication['supplier'] ?? 'Direct Manufacturer') ?></span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                <button class="w-full btn-primary py-3 flex items-center justify-center gap-2 bg-blue-600">
                    <i class="bi bi-cart-plus"></i>
                    <span>Order Restock</span>
                </button>
            </div>
        </div>

        <div class="glass-card p-6 border-amber-500/10 bg-amber-50/5 dark:bg-amber-900/5">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-exclamation-octagon text-amber-500"></i>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Safety Warning</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed italic">
                Verify medication dosage and patient allergies before dispensing. Ensure batch numbers match for clinical audits.
            </p>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Medication details';
require __DIR__ . '/../../layouts/app.php'; 
?>
