<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Medication Inventory</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Detailed tracking of hospital pharmaceutical supplies</p>
    </div>
    <div class="flex gap-3">
        <a href="/pharmacy" class="btn-secondary inline-flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Pharmacy Overview</span>
        </a>
        <a href="/pharmacy/inventory/add" class="btn-primary inline-flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Add Medication</span>
        </a>
    </div>
</div>

<!-- Inventory Directory -->
<div class="glass-card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Inventory Items</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" placeholder="Search inventory..." class="input-modern h-9 pl-9 text-xs w-64">
            </div>
            <select class="input-modern h-9 text-xs w-32 px-3">
                <option value="">All Categories</option>
                <option value="antibiotics">Antibiotics</option>
                <option value="analgesics">Analgesics</option>
                <option value="antivirals">Antivirals</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Medication Details</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Category</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 text-center">Stock</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Unit Price</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Expiry</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                <?php if (empty($inventory)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-box-seam text-3xl text-slate-400"></i>
                            </div>
                            <p class="font-medium">No inventory items found</p>
                            <a href="/pharmacy/inventory/add" class="mt-4 text-blue-600 font-bold hover:underline">Add your first medication</a>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach (($inventory ?? []) as $item): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold"><?= htmlspecialchars($item['generic_name'] ?? 'N/A') ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                            <?= htmlspecialchars($item['category'] ?? 'General') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-black text-slate-900 dark:text-white"><?= number_format($item['stock']) ?></div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">Units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-700 dark:text-slate-300"><?= \App\Helpers\CurrencyHelper::format($item['price'] ?? 0) ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <?php 
                        $expiry = $item['expiry_date'] ? strtotime($item['expiry_date']) : null;
                        $isExpiring = $expiry && $expiry < strtotime('+3 months');
                        $isExpired = $expiry && $expiry < time();
                        ?>
                        <div class="text-sm <?= $isExpired ? 'text-red-600 font-black' : ($isExpiring ? 'text-amber-600 font-bold' : 'text-slate-600 dark:text-slate-400') ?>">
                            <?= $expiry ? date('M d, Y', $expiry) : 'N/A' ?>
                        </div>
                        <?php if ($isExpired): ?>
                            <span class="text-[9px] font-black text-red-500 uppercase">EXPIRED</span>
                        <?php elseif ($isExpiring): ?>
                            <span class="text-[9px] font-black text-amber-500 uppercase">EXPIRING SOON</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="/pharmacy/inventory/<?= $item['id'] ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/pharmacy/inventory/<?= $item['id'] ?>/edit" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Edit">
                                <i class="bi bi-pencil"></i>
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
$displayTitle = 'Pharmacy Inventory';
require __DIR__ . '/../layouts/app.php'; 
?>
