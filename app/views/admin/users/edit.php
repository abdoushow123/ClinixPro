<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Edit User</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Update account details for <?= htmlspecialchars($user['username']) ?></p>
    </div>
    <a href="/admin/users/<?= $user['id'] ?>" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Details</span>
    </a>
</div>

<!-- User Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/admin/users/<?= $user['id'] ?>/update" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Personal Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-badge text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Profile Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" required class="input-modern" 
                        value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" placeholder="Enter first name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required class="input-modern" 
                        value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" placeholder="Enter last name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username *</label>
                    <input type="text" name="username" required class="input-modern" 
                        value="<?= htmlspecialchars($user['username'] ?? '') ?>" placeholder="Choose a username">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address *</label>
                    <input type="email" name="email" required class="input-modern" 
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="name@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Phone Number</label>
                    <input type="tel" name="phone" class="input-modern" 
                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-shield-lock text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Account Status & Role</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">System Role *</label>
                    <select name="role_id" required class="input-modern">
                        <option value="">Select Role</option>
                        <option value="1" <?= ($user['role_id'] == 1) ? 'selected' : '' ?>>Administrator</option>
                        <option value="2" <?= ($user['role_id'] == 2) ? 'selected' : '' ?>>Doctor</option>
                        <option value="3" <?= ($user['role_id'] == 3) ? 'selected' : '' ?>>Nurse</option>
                        <option value="4" <?= ($user['role_id'] == 4) ? 'selected' : '' ?>>Receptionist</option>
                        <option value="5" <?= ($user['role_id'] == 5) ? 'selected' : '' ?>>Pharmacist</option>
                        <option value="6" <?= ($user['role_id'] == 6) ? 'selected' : '' ?>>Laboratory</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Account Status</label>
                    <select name="is_active" class="input-modern">
                        <option value="1" <?= ($user['is_active']) ? 'selected' : '' ?>>Active (Can Login)</option>
                        <option value="0" <?= (!$user['is_active']) ? 'selected' : '' ?>>Disabled (No Access)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <span>Save Changes</span>
            </button>
            <a href="/admin/users/<?= $user['id'] ?>" class="btn-secondary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-x-circle"></i>
                <span>Cancel Editing</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Edit User';
require __DIR__ . '/../../layouts/app.php'; 
?>
