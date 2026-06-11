<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">User Management</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Control system access and manage user credentials</p>
    </div>
    <a href="/admin/users/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-person-plus text-lg"></i>
        <span>Register New User</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Users</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Active</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Active Accounts</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
            <span class="badge badge-warning">Awaiting</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending Requests</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-x-fill text-xl"></i>
            </div>
            <span class="badge badge-danger">Disabled</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['inactive'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Inactive Accounts</p>
    </div>
</div>

<!-- Pending Approvals -->
<?php if (!empty($pendingUsers)): ?>
<div class="glass-panel overflow-hidden mb-8 animate-fade-in border-t-4 border-t-warning-500" style="animation-delay: 0.4s;">
    <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 bg-warning-50/50 dark:bg-warning-900/10 flex items-center justify-between">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning-500"></i>
            Pending Registration Requests
        </h3>
        <span class="badge badge-warning"><?= count($pendingUsers) ?> Pending</span>
    </div>
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role Requested</th>
                    <th>Details</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingUsers as $pUser): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold shadow-sm border border-slate-200 dark:border-zinc-700">
                                <?= strtoupper(substr($pUser['first_name'] ?? $pUser['username'] ?? 'N', 0, 1)) ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars(trim(($pUser['first_name'] ?? '') . ' ' . ($pUser['last_name'] ?? '')) ?: $pUser['username']) ?></div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold tracking-widest mt-0.5"><?= htmlspecialchars($pUser['email'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                            <?= htmlspecialchars($pUser['role_name'] ?? 'Unknown') ?>
                        </span>
                    </td>
                    <td>
                        <div class="text-xs font-medium text-slate-500 dark:text-zinc-400">
                            Requested <?= \App\Helpers\DateHelper::formatRelativeTime($pUser['created_at']) ?>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="/admin/users/<?= $pUser['id'] ?>/approve" class="inline">
                                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-success-50 text-success-700 hover:bg-success-100 dark:bg-success-900/30 dark:text-success-400 dark:hover:bg-success-900/50 rounded-lg text-xs font-bold transition-colors">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="/admin/users/<?= $pUser['id'] ?>/reject" class="inline">
                                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
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

<!-- System Users Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Active System Users</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search users..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
            <select class="input-modern h-10 text-sm w-32 px-3 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                <option value="">All Roles</option>
                <option value="administrator">Administrator</option>
                <option value="doctor">Doctor</option>
                <option value="nurse">Nurse</option>
                <option value="receptionist">Receptionist</option>
            </select>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($user['first_name'] ?? $user['username'] ?? 'N', 0, 1)) ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['username']) ?></div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold tracking-widest mt-0.5">@<?= htmlspecialchars($user['username'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-primary">
                            <?= htmlspecialchars($user['role_name'] ?? 'User') ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $user['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $user['is_active'] ? 'bg-success-500' : 'bg-danger-500' ?> mr-1.5"></span>
                            <?= $user['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td>
                        <div class="font-medium text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($user['created_at'])) ?></div>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/users/<?= $user['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/admin/users/<?= $user['id'] ?>/edit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-xl transition-all" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'User Management';
require __DIR__ . '/../../layouts/app.php'; 
?>
