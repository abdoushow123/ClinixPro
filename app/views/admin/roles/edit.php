<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <a href="/admin/roles/<?= $role['id'] ?>" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mb-2 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Back to Role
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Edit Role</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Modify role permissions and settings</p>
    </div>
</div>

<!-- Edit Form -->
<form method="POST" action="/admin/roles/<?= $role['id'] ?>/update" class="space-y-6">
    <input type="hidden" name="_token" value="<?= $csrf_token ?>">

    <!-- Basic Information -->
    <div class="glass-card p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Role Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($role['name']) ?>" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Role ID</label>
                <input type="text" value="#<?= $role['id'] ?>" disabled
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 cursor-not-allowed">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($role['description'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="glass-card p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Permissions (JSON)</h3>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Permissions JSON</label>
            <textarea name="permissions" rows="8"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($role['permissions'] ?? '{}') ?></textarea>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Enter permissions as JSON object. Example: {"patients": ["read", "write"], "medical_records": ["read"]}</p>
        </div>
    </div>

    <!-- Approval Settings -->
    <div class="glass-card p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Approval Settings</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="requires_approval" value="1" <?= $role['requires_approval'] ? 'checked' : '' ?>
                    class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Requires Approval</label>
                <span class="text-xs text-slate-500 dark:text-slate-400">- New users with this role need approval before activation</span>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="auto_approve" value="1" <?= $role['auto_approve'] ? 'checked' : '' ?>
                    class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Auto Approve</label>
                <span class="text-xs text-slate-500 dark:text-slate-400">- This role is automatically approved (system roles)</span>
            </div>
        </div>
    </div>

    <!-- Can Approve Roles -->
    <div class="glass-card p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Can Approve These Roles</h3>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Select Roles This Role Can Approve</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <?php 
                $allRoles = Database::fetchAll("SELECT * FROM roles ORDER BY id");
                $canApproveRoles = json_decode($role['can_approve_roles'] ?? '[]', true) ?: [];
                foreach ($allRoles as $r): 
                ?>
                <label class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">
                    <input type="checkbox" name="can_approve_roles[]" value="<?= $r['id'] ?>" 
                        <?= in_array($r['id'], $canApproveRoles) ? 'checked' : '' ?>
                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300"><?= htmlspecialchars($r['name']) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3">
        <a href="/admin/roles/<?= $role['id'] ?>" class="btn-secondary px-6 py-2.5">Cancel</a>
        <button type="submit" class="btn-primary px-6 py-2.5">
            <i class="bi bi-check-lg mr-2"></i>Save Changes
        </button>
    </div>
</form>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Edit Role';
require __DIR__ . '/../../layouts/app.php'; 
?>
