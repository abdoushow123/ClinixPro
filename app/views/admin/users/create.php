<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Add New User</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Create a new system user account and assign permissions</p>
    </div>
    <a href="/admin/users" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Users</span>
    </a>
</div>

<!-- User Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/admin/users/store" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Personal Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-plus-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Personal Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" required class="input-modern" placeholder="Enter first name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required class="input-modern" placeholder="Enter last name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username *</label>
                    <input type="text" name="username" required class="input-modern" placeholder="Choose a unique username">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address *</label>
                    <input type="email" name="email" required class="input-modern" placeholder="name@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Phone Number</label>
                    <input type="tel" name="phone" class="input-modern" placeholder="+1 (555) 000-0000">
                </div>
            </div>
        </div>

        <!-- Role & Password -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-shield-lock-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Security & Permissions</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">System Role *</label>
                    <select name="role_id" required class="input-modern">
                        <option value="">Select Role</option>
                        <option value="1">Administrator</option>
                        <option value="2">Doctor</option>
                        <option value="3">Nurse</option>
                        <option value="4">Receptionist</option>
                        <option value="5">Pharmacist</option>
                        <option value="6">Laboratory</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Account Status</label>
                    <select name="is_active" class="input-modern">
                        <option value="1">Active (Can Login)</option>
                        <option value="0">Disabled (No Access)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Password *</label>
                    <input type="password" name="password" required class="input-modern" placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirm Password *</label>
                    <input type="password" name="confirm_password" required class="input-modern" placeholder="••••••••">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-person-plus-fill"></i>
                <span>Create User Account</span>
            </button>
            <a href="/admin/users" class="btn-secondary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-x-circle"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Create User';
require __DIR__ . '/../../layouts/app.php'; 
?>
