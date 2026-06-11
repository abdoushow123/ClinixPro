<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">My Profile</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Manage your account settings, security and personal preferences</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Profile Summary -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-8 text-center">
            <div class="relative inline-block mb-6">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center text-white font-black text-4xl shadow-xl mx-auto transform hover:rotate-6 transition-transform duration-300">
                    <?= strtoupper(substr($user['first_name'] ?? $user['username'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 border-4 border-white dark:border-slate-900 rounded-full flex items-center justify-center text-white" title="Active Account">
                    <i class="bi bi-check-lg"></i>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1"><?= htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['username']) ?></h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium mb-4">@<?= htmlspecialchars($user['username'] ?? '') ?></p>
            
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    <?= htmlspecialchars($user['role_name'] ?? 'User') ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    Active
                </span>
            </div>

            <div class="space-y-3 text-left border-t border-slate-100 dark:border-slate-800 pt-6">
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-envelope text-blue-500"></i>
                    <span class="truncate"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                </div>
                <?php if (!empty($user['phone'])): ?>
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-telephone text-blue-500"></i>
                    <span><?= htmlspecialchars($user['phone']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <i class="bi bi-calendar-event text-blue-500"></i>
                    <span>Joined <?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">System Access</h3>
            <div class="space-y-4">
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Last Login</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300"><?= $user['last_login_at'] ? date('M d, Y H:i', strtotime($user['last_login_at'])) : 'Never' ?></p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Login IP</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 font-mono"><?= htmlspecialchars($user['last_login_ip'] ?? 'Unknown') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Forms -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Edit Profile Form -->
        <div class="glass-card overflow-hidden">
            <div class="px-8 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-person-gear text-blue-500"></i>
                    Personal Information
                </h3>
            </div>
            <form method="POST" action="/profile/update" class="p-8 space-y-6">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">First Name</label>
                        <input type="text" name="first_name" class="input-modern" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Last Name</label>
                        <input type="text" name="last_name" class="input-modern" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username</label>
                        <input type="text" name="username" class="input-modern" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
                        <input type="email" name="email" class="input-modern" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="input-modern" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="btn-primary px-8">
                        <i class="bi bi-check2-circle"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="glass-card overflow-hidden">
            <div class="px-8 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-shield-lock text-red-500"></i>
                    Security & Password
                </h3>
            </div>
            <form method="POST" action="/profile/password" class="p-8 space-y-6">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Current Password</label>
                    <div class="relative group">
                        <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="password" name="current_password" class="input-modern pl-12" required placeholder="Enter current password">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">New Password</label>
                        <div class="relative group">
                            <i class="bi bi-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="password" name="new_password" class="input-modern pl-12" required placeholder="New password">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirm New Password</label>
                        <div class="relative group">
                            <i class="bi bi-key-fill absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="password" name="confirm_password" class="input-modern pl-12" required placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="btn-primary px-8 border-red-600 bg-red-600 hover:bg-red-700">
                        <i class="bi bi-shield-check"></i>
                        <span>Update Password</span>
                    </button>
                    <p class="mt-4 text-[10px] text-slate-400 italic">For your security, password must be at least 8 characters long with a mix of symbols.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'My Profile';
require __DIR__ . '/../layouts/app.php'; 
?>
