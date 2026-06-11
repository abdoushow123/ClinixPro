<?php ob_start(); 
$userFirstName = $user['first_name'] ?? 'User';
$userLastName = $user['last_name'] ?? '';
$userFullName = trim($userFirstName . ' ' . $userLastName);
$initials = strtoupper(substr($userFirstName, 0, 1));
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">User Details</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Viewing administrative profile for <?= htmlspecialchars($user['username']) ?></p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn-primary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-pencil-square"></i>
            <span>Edit User</span>
        </a>
        <a href="/admin/users" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Directory</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: User Summary -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-8 text-center">
            <div class="relative inline-block mb-6">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center text-white font-black text-4xl shadow-xl mx-auto transform hover:rotate-6 transition-transform duration-300">
                    <?= $initials ?>
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 <?= $user['is_active'] ? 'bg-green-500' : 'bg-red-500' ?> border-4 border-white dark:border-slate-900 rounded-full flex items-center justify-center text-white" title="<?= $user['is_active'] ? 'Active Account' : 'Disabled Account' ?>">
                    <i class="bi bi-<?= $user['is_active'] ? 'check-lg' : 'x-lg' ?>"></i>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1"><?= htmlspecialchars($userFullName) ?></h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium mb-4">@<?= htmlspecialchars($user['username']) ?></p>
            
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    <?= htmlspecialchars($user['role_name'] ?? 'User') ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest <?= $user['is_active'] ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400' ?>">
                    <?= $user['is_active'] ? 'Active' : 'Disabled' ?>
                </span>
            </div>

            <div class="space-y-3 text-left border-t border-slate-100 dark:border-slate-800 pt-6">
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-envelope text-blue-500"></i>
                    <span class="truncate"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <?php if (!empty($user['phone'])): ?>
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-telephone text-blue-500"></i>
                    <span><?= htmlspecialchars($user['phone']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-calendar-check text-blue-500"></i>
                    <span>Joined <?= date('M d, Y', strtotime($user['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <!-- Role Permissions -->
        <div class="glass-card p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-shield-check text-blue-600"></i>
                Role Privileges
            </h3>
            <div class="space-y-3">
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Primary Role</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300"><?= htmlspecialchars($user['role_name'] ?? 'None') ?></p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Role Description</p>
                    <p class="text-xs text-slate-600 dark:text-slate-400 italic"><?= htmlspecialchars($user['role_description'] ?? 'Standard system user with basic access.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Full Info -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <div class="px-8 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-500"></i>
                    Full Account Information
                </h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">First Name</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($user['first_name'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Last Name</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($user['last_name'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">System Username</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white font-mono"><?= htmlspecialchars($user['username']) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Verified Email</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400 underline decoration-blue-500/30"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Phone Number</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($user['phone'] ?? 'Not Provided') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Last Updated</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= date('M d, Y', strtotime($user['updated_at'] ?? 'now')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Actions -->
        <div class="glass-card p-8 bg-red-50/10 border-red-500/10">
            <h3 class="text-sm font-black uppercase text-red-600 dark:text-red-400 mb-6 flex items-center gap-2">
                <i class="bi bi-shield-exclamation"></i>
                Danger Zone
            </h3>
            <div class="flex flex-wrap gap-4">
                <form method="POST" action="/admin/users/<?= $user['id'] ?>/<?= $user['is_active'] ? 'reject' : 'approve' ?>" class="inline">
                    <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn-secondary border-amber-200 text-amber-700 hover:bg-amber-50">
                        <i class="bi bi-slash-circle"></i>
                        <span><?= $user['is_active'] ? 'Deactivate Account' : 'Activate Account' ?></span>
                    </button>
                </form>
                
                <form method="POST" action="/admin/users/<?= $user['id'] ?>/delete" class="inline" data-confirm="CRITICAL: This will permanently remove this user from the system. Continue?">
                    <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn-secondary border-red-200 text-red-600 hover:bg-red-50">
                        <i class="bi bi-trash3"></i>
                        <span>Permanent Delete</span>
                    </button>
                </form>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 italic">Deleting a user will archive their historical activity logs but prevent any further access.</p>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'User Profile';
require __DIR__ . '/../../layouts/app.php'; 
?>
