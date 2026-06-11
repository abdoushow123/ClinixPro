<?php ob_start(); ?>
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Pharmacists Directory</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage pharmacy staff, applications, and approvals</p>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 items-stretch">
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-capsule text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Pharmacists</p>
    </div>
    
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Active</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['approved'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Approved</p>
    </div>
    
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
            <span class="badge badge-warning">Awaiting</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending</p>
    </div>
</div>

<!-- Pending Approvals -->
<?php if (!empty($pending_pharmacists)): ?>
<div class="glass-panel overflow-hidden mb-8 animate-fade-in border-t-4 border-t-warning-500" style="animation-delay: 0.4s;">
    <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 bg-warning-50/50 dark:bg-warning-900/10 flex items-center justify-between">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning-500"></i>
            Pending Approvals
        </h3>
        <span class="badge badge-warning"><?= count($pending_pharmacists) ?> Pending</span>
    </div>
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Pharmacist Profile</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_pharmacists as $pharmacist): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($pharmacist['full_name'] ?? 'Unknown', 0, 1)) ?>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($pharmacist['full_name'] ?? 'Unknown') ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5"><i class="bi bi-envelope text-slate-400"></i> <?= htmlspecialchars($pharmacist['email']) ?></div>
                    </td>
                    <td>
                        <div class="font-medium text-slate-500 dark:text-zinc-400 flex items-center gap-1.5"><i class="bi bi-telephone text-slate-400"></i> <?= htmlspecialchars($pharmacist['phone'] ?? 'N/A') ?></div>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="/pharmacists/<?= $pharmacist['id'] ?>/approve" class="inline" data-confirm="Approve this pharmacist?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-success-50 text-success-700 hover:bg-success-100 dark:bg-success-900/30 dark:text-success-400 dark:hover:bg-success-900/50 rounded-lg text-xs font-bold transition-colors">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="/pharmacists/<?= $pharmacist['id'] ?>/reject" class="inline" data-confirm="Reject this pharmacist?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-danger-50 text-danger-700 hover:bg-danger-100 dark:bg-danger-900/30 dark:text-danger-400 dark:hover:bg-danger-900/50 rounded-lg text-xs font-bold transition-colors">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Pharmacists List -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-between bg-white/40 dark:bg-zinc-800/40">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">Pharmacy Staff</h3>
        <span class="badge badge-primary"><?= count($pharmacists) ?> Staff Members</span>
    </div>
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pharmacists)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-capsule text-4xl text-slate-400 dark:text-zinc-500"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No pharmacists found</h4>
                            <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no pharmacy staff registered.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($pharmacists as $pharmacist): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-2xl flex items-center justify-center font-bold text-lg shadow-sm border border-primary-200 dark:border-primary-800/50">
                                <?= strtoupper(substr($pharmacist['full_name'] ?? 'Unknown', 0, 1)) ?>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($pharmacist['full_name'] ?? 'Unknown') ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5"><i class="bi bi-envelope text-slate-400"></i> <?= htmlspecialchars($pharmacist['email']) ?></span>
                    </td>
                    <td>
                        <span class="font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5"><i class="bi bi-telephone text-slate-400"></i> <?= htmlspecialchars($pharmacist['phone'] ?? 'N/A') ?></span>
                    </td>
                    <td>
                        <span class="badge badge-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 mr-1.5"></span>
                            Active
                        </span>
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
$hasSidebar = true;
$sidebarUser = $user ?? [];
$displayName = $user['full_name'] ?? 'User';
$initials = substr($user['full_name'] ?? 'U', 0, 1);
$pageTitle = 'Pharmacists';
$displayTitle = 'Pharmacy Staff';
$currentPath = '/pharmacists';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; ?>
