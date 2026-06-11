<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <a href="/admin/roles" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mb-2 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Back to Roles
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1"><?= htmlspecialchars($role['name']) ?></h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Role details and permissions</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/roles/<?= $role['id'] ?>/edit" class="btn-secondary inline-flex items-center gap-2">
            <i class="bi bi-pencil"></i>
            <span>Edit Role</span>
        </a>
    </div>
</div>

<!-- Role Details -->
<div class="glass-card p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Role Name</h3>
            <p class="text-lg font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($role['name']) ?></p>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Role ID</h3>
            <p class="text-lg font-semibold text-slate-900 dark:text-white">#<?= $role['id'] ?></p>
        </div>
        <div class="md:col-span-2">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Description</h3>
            <p class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($role['description'] ?? 'No description provided') ?></p>
        </div>
    </div>
</div>

<!-- Permissions -->
<div class="glass-card p-6 mb-6">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Permissions</h3>
    <?php 
    $permissions = json_decode($role['permissions'] ?? '{}', true);
    if (!empty($permissions) && is_array($permissions)): 
    ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($permissions as $module => $actions): ?>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
            <h4 class="font-semibold text-slate-900 dark:text-white mb-2 capitalize"><?= htmlspecialchars($module) ?></h4>
            <div class="flex flex-wrap gap-2">
                <?php 
                if (is_array($actions)):
                    foreach ($actions as $action): 
                ?>
                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs font-medium">
                    <?= htmlspecialchars($action) ?>
                </span>
                <?php 
                    endforeach;
                else:
                ?>
                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-xs font-medium">
                    Full Access
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-slate-500 dark:text-slate-400">No specific permissions defined.</p>
    <?php endif; ?>
</div>

<!-- Approval Settings -->
<div class="glass-card p-6 mb-6">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Approval Settings</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Requires Approval</h4>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium <?= $role['requires_approval'] ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' ?>">
                <i class="bi bi-<?= $role['requires_approval'] ? 'clock' : 'check-circle' ?>"></i>
                <?= $role['requires_approval'] ? 'Yes' : 'No' ?>
            </span>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Auto Approve</h4>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium <?= $role['auto_approve'] ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400' ?>">
                <i class="bi bi-<?= $role['auto_approve'] ? 'lightning' : 'dash' ?>"></i>
                <?= $role['auto_approve'] ? 'Yes' : 'No' ?>
            </span>
        </div>
    </div>
</div>

<!-- Can Approve Roles -->
<?php 
$canApproveRoles = json_decode($role['can_approve_roles'] ?? '[]', true);
if (!empty($canApproveRoles)): 
?>
<div class="glass-card p-6">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Can Approve These Roles</h3>
    <div class="flex flex-wrap gap-2">
        <?php foreach ($canApproveRoles as $roleId): ?>
        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-medium">
            Role ID #<?= $roleId ?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Role Details';
require __DIR__ . '/../../layouts/app.php'; 
?>
