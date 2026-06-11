<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Audit Logs</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Track system activity, changes, and security events</p>
    </div>
    
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative group">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
            <input type="text" placeholder="Search logs..." class="input-modern pl-12 h-11 text-sm w-64 border-none shadow-sm hover:shadow">
        </div>
        <select class="input-modern h-11 text-sm w-40 px-4 border-none shadow-sm hover:shadow cursor-pointer">
            <option value="">All Actions</option>
            <option value="create">Create</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
            <option value="login">Login</option>
        </select>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-between bg-slate-50/50 dark:bg-zinc-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Activity History</h3>
        <span class="badge badge-primary"><?= number_format($total ?? 0) ?> Total Events</span>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Details</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-journal-text text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Audit Logs Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no activity logs recorded in the system.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold border border-slate-200 dark:border-zinc-700 shadow-sm">
                                <?= strtoupper(substr($log['first_name'] ?? 'S', 0, 1)) ?>
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 dark:text-white text-sm truncate"><?= htmlspecialchars(($log['first_name'] ?? 'System') . ' ' . ($log['last_name'] ?? '')) ?></div>
                                <div class="text-[10px] text-slate-500 dark:text-zinc-400 font-bold tracking-widest mt-0.5 truncate"><?= htmlspecialchars($log['email'] ?? 'system@internal') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?= getActionBadgeClass($log['action']) ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= getActionDotClass($log['action']) ?> mr-1.5"></span>
                            <?= htmlspecialchars($log['action']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="text-xs font-mono font-bold tracking-wider bg-slate-100 dark:bg-zinc-800 px-2.5 py-1 rounded-lg text-slate-600 dark:text-zinc-400 border border-slate-200/60 dark:border-zinc-700/50">
                            <?= htmlspecialchars($log['entity_type'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td>
                        <div class="text-xs font-medium text-slate-600 dark:text-zinc-400 max-w-[200px] truncate" title="<?= htmlspecialchars($log['details'] ?? '') ?>">
                            <?= htmlspecialchars($log['details'] ?? '—') ?>
                        </div>
                    </td>
                    <td>
                        <span class="text-xs font-mono font-semibold text-slate-500 dark:text-zinc-400"><?= htmlspecialchars($log['ip_address'] ?? '0.0.0.0') ?></span>
                    </td>
                    <td>
                        <div class="font-medium text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($log['created_at'])) ?></div>
                        <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold tracking-wider mt-0.5"><?= date('g:i A', strtotime($log['created_at'])) ?></div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (($pages ?? 1) > 1): ?>
    <div class="px-6 py-4 bg-slate-50/50 dark:bg-zinc-800/50 border-t border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-center gap-2">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="/admin/audit?page=<?= $i ?>" 
            class="w-10 h-10 flex items-center justify-center rounded-xl font-bold text-sm transition-all <?= $i === ($page ?? 1) ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'text-slate-500 hover:bg-white dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php
if (!function_exists('getActionBadgeClass')) {
    function getActionBadgeClass($action) {
        $classes = [
            'create' => 'badge-success',
            'update' => 'badge-warning',
            'delete' => 'badge-danger',
            'login' => 'badge-primary',
            'logout' => 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-200/60 dark:border-zinc-700/50'
        ];
        return $classes[$action] ?? 'badge-primary';
    }
}

if (!function_exists('getActionDotClass')) {
    function getActionDotClass($action) {
        $classes = [
            'create' => 'bg-success-500',
            'update' => 'bg-warning-500',
            'delete' => 'bg-danger-500',
            'login' => 'bg-primary-500',
            'logout' => 'bg-slate-400'
        ];
        return $classes[$action] ?? 'bg-primary-500';
    }
}
?>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Audit Logs';
require __DIR__ . '/../../layouts/app.php'; 
?>
