<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Pharmacy Inventory</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage pharmaceutical stock and medications</p>
    </div>
    <div class="flex gap-3">
        <a href="/pharmacy/inventory" class="btn-secondary">
            <i class="bi bi-box-seam text-lg"></i>
            <span>Full Inventory</span>
        </a>
        <a href="/pharmacy/inventory/add" class="btn-primary">
            <i class="bi bi-plus-circle text-lg"></i>
            <span>Add Medication</span>
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-capsule text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total_medications'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Medications</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['in_stock'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">In Stock</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['low_stock'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Low Stock</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-x-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['expiring'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Expiring Soon</p>
    </div>
</div>

<!-- Medications Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Medication Stock List</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search medications..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Medication</th>
                    <th class="text-center">Current Stock</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($medications)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-capsule text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Medications Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no medications in the inventory.</p>
                        <a href="/pharmacy/inventory/add" class="mt-4 inline-block font-semibold text-primary-600 hover:text-primary-500">Add first medication</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach (($medications ?? []) as $medication): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold shadow-sm border border-slate-200 dark:border-zinc-700">
                                <i class="bi bi-capsule"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm hover:text-primary-600 transition-colors"><?= htmlspecialchars($medication['name']) ?></div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mt-0.5"><?= htmlspecialchars($medication['generic_name'] ?? 'Generic N/A') ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center min-w-[80px] px-3 py-1 rounded-lg text-xs font-black <?= $medication['stock'] <= 10 ? 'bg-danger-50 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400' : 'bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-400' ?>">
                            <?= number_format($medication['stock']) ?> units
                        </span>
                    </td>
                    <td>
                        <div class="font-bold text-lg text-slate-700 dark:text-zinc-300"><?= \App\Helpers\CurrencyHelper::format($medication['price'] ?? 0) ?></div>
                    </td>
                    <td>
                        <span class="badge <?= $medication['stock'] > 0 ? 'badge-success' : 'badge-danger' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $medication['stock'] > 0 ? 'bg-success-500' : 'bg-danger-500' ?> mr-1.5"></span>
                            <?= $medication['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/pharmacy/inventory/<?= $medication['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/pharmacy/inventory/<?= $medication['id'] ?>/edit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-xl transition-all" title="Edit Medication">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Pharmacy';
require __DIR__ . '/../layouts/app.php'; 
?>
