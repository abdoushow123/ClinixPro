<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Insurance & Claims</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Process medical insurance claims and manage provider policies</p>
    </div>
    <a href="/insurance/claim" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-shield-plus text-lg"></i>
        <span>New Insurance Claim</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-shield-check text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total_claims'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Claims</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['approved'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Approved</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-shield-x text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['rejected'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Rejected</p>
    </div>
</div>

<!-- Claims Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Insurance Claims Registry</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search claims..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
            <select class="input-modern h-10 text-sm w-32 px-3 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                <option value="">All Status</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Insurance Provider</th>
                    <th>Amount</th>
                    <th class="text-center">Date</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($claims)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-shield-check text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Insurance Claims Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no insurance claims in the system.</p>
                        <a href="/insurance/claim" class="mt-4 inline-block font-semibold text-primary-600 hover:text-primary-500">Create your first claim</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach (($claims ?? []) as $claim): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($claim['patient_name'] ?? 'P', 0, 1)) ?>
                            </div>
                            <div class="font-bold text-slate-900 dark:text-white text-sm hover:text-primary-600 transition-colors"><?= htmlspecialchars($claim['patient_name'] ?? 'N/A') ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="font-bold text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($claim['insurance_provider'] ?? 'N/A') ?></div>
                        <div class="text-[10px] text-slate-400 dark:text-zinc-500 uppercase font-bold tracking-wider mt-0.5">Policy: <?= htmlspecialchars($claim['policy_number'] ?? '—') ?></div>
                    </td>
                    <td>
                        <div class="font-black text-lg text-slate-900 dark:text-white"><?= \App\Helpers\CurrencyHelper::format($claim['amount'] ?? 0) ?></div>
                    </td>
                    <td class="text-center">
                        <div class="font-medium text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($claim['claim_date'])) ?></div>
                    </td>
                    <td>
                        <span class="badge <?= getClaimBadgeClass($claim['status']) ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= getClaimDotClass($claim['status']) ?> mr-1.5"></span>
                            <?= htmlspecialchars($claim['status']) ?>
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/insurance/claims/<?= $claim['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Details">
                                <i class="bi bi-eye-fill"></i>
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

<?php
function getClaimBadgeClass($status) {
    $classes = [
        'approved' => 'badge-success',
        'pending' => 'badge-warning',
        'rejected' => 'badge-danger'
    ];
    return $classes[$status] ?? 'badge-primary';
}

function getClaimDotClass($status) {
    $classes = [
        'approved' => 'bg-success-500',
        'pending' => 'bg-warning-500',
        'rejected' => 'bg-danger-500'
    ];
    return $classes[$status] ?? 'bg-primary-500';
}
?>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Insurance';
require __DIR__ . '/../layouts/app.php'; 
?>
