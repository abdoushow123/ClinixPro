<?php ob_start(); ?>
<!-- Page Header -->
<div class="glass-card p-6 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Laboratory Team</h1>
        <p class="text-slate-600 dark:text-slate-400">Manage laboratory staff and approvals</p>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 items-stretch">
    <div class="glass-card p-6 animate-scale-in flex items-center gap-4" style="animation-delay: 0.1s;">
        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
            <i class="bi bi-flask text-white text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-3xl font-bold text-slate-900 dark:text-white"><?= number_format($stats['total'] ?? 0) ?></h3>
            <p class="text-slate-600 dark:text-slate-400 font-medium">Total Lab Staff</p>
        </div>
    </div>
    
    <div class="glass-card p-6 animate-scale-in flex items-center gap-4" style="animation-delay: 0.2s;">
        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
            <i class="bi bi-person-check text-white text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-3xl font-bold text-slate-900 dark:text-white"><?= number_format($stats['approved'] ?? 0) ?></h3>
            <p class="text-slate-600 dark:text-slate-400 font-medium">Approved</p>
        </div>
    </div>
    
    <div class="glass-card p-6 animate-scale-in flex items-center gap-4" style="animation-delay: 0.3s;">
        <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
            <i class="bi bi-clock text-white text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-3xl font-bold text-slate-900 dark:text-white"><?= number_format($stats['pending'] ?? 0) ?></h3>
            <p class="text-slate-600 dark:text-slate-400 font-medium">Pending</p>
        </div>
    </div>
</div>

<!-- Pending Approvals -->
<?php if (!empty($pending_lab_staff)): ?>
<div class="glass-card p-6 mb-8 animate-fade-in" style="animation-delay: 0.4s;">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Pending Approvals</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Staff Member</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Email</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Phone</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_lab_staff as $staff): ?>
                <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                <?= strtoupper(substr($staff['full_name'], 0, 1)) ?>
                            </div>
                            <span class="font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($staff['full_name']) ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($staff['email']) ?></td>
                    <td class="py-3 px-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($staff['phone'] ?? 'N/A') ?></td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <form method="POST" action="/laboratory/team/<?= $staff['id'] ?>/approve" data-confirm="Approve this staff member?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="btn-primary text-sm px-4 py-2">
                                    <i class="bi bi-check"></i>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="/laboratory/team/<?= $staff['id'] ?>/reject" data-confirm="Reject this staff member?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                                    <i class="bi bi-x"></i>
                                    Reject
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

<!-- Lab Staff List -->
<div class="glass-card p-6 animate-fade-in" style="animation-delay: 0.5s;">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Laboratory Staff</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Name</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Email</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Phone</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-400">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lab_staff)): ?>
                <tr>
                    <td colspan="4">
                        <div class="text-center py-12">
                            <i class="bi bi-flask text-slate-400 dark:text-slate-600 text-5xl mb-4"></i>
                            <p class="text-slate-600 dark:text-slate-400">No laboratory staff found</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($lab_staff as $staff): ?>
                <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                <?= strtoupper(substr($staff['full_name'], 0, 1)) ?>
                            </div>
                            <span class="font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($staff['full_name']) ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($staff['email']) ?></td>
                    <td class="py-3 px-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($staff['phone'] ?? 'N/A') ?></td>
                    <td class="py-3 px-4">
                        <?php if ($staff['registration_status'] === 'approved'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Active</span>
                        <?php elseif ($staff['registration_status'] === 'pending'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">Pending</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rejected</span>
                        <?php endif; ?>
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
$pageTitle = 'Laboratory Team';
$displayTitle = 'Laboratory Staff';
$currentPath = '/laboratory/team';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; ?>
