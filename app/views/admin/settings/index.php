<?php 
$settings = $settings ?? [];
?>
<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">System Settings</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Configure system-wide settings and preferences</p>
    </div>
</div>

<!-- Settings Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    <!-- General Settings -->
    <div class="glass-panel animate-slide-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 bg-slate-50/50 dark:bg-zinc-800/50 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-building-fill"></i>
            </div>
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">General Settings</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="/admin/settings/update">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Hospital Name</label>
                    <input type="text" class="input-modern" name="hospital_name" value="<?= htmlspecialchars($settings['hospital_name'] ?? 'ClinixPro Hospital') ?>">
                </div>
                
                <div class="mb-5">
                    <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Hospital Address</label>
                    <textarea class="input-modern min-h-[100px] resize-y" name="hospital_address" rows="2"><?= htmlspecialchars($settings['hospital_address'] ?? '') ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Phone Number</label>
                        <div class="relative">
                            <i class="bi bi-telephone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="tel" class="input-modern pl-10" name="hospital_phone" value="<?= htmlspecialchars($settings['hospital_phone'] ?? '') ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Email</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="email" class="input-modern pl-10" name="hospital_email" value="<?= htmlspecialchars($settings['hospital_email'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end border-t border-slate-200/60 dark:border-zinc-700/50 pt-5">
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="flex flex-col gap-8">
        <!-- Notification Settings -->
        <div class="glass-panel animate-slide-up" style="animation-delay: 0.2s;">
            <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 bg-slate-50/50 dark:bg-zinc-800/50 flex items-center gap-3">
                <div class="w-10 h-10 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white">Notification Settings</h3>
            </div>
            <div class="p-6">
                <form method="POST" action="/admin/settings/update">
                    <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-xl border border-slate-200/60 dark:border-zinc-700/50">
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Email Notifications</h4>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Receive system alerts via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" <?= ($settings['email_notifications'] ?? 1) ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-primary-500"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-xl border border-slate-200/60 dark:border-zinc-700/50">
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">SMS Notifications</h4>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Send text messages for critical alerts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" <?= ($settings['sms_notifications'] ?? 0) ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-primary-500"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-xl border border-slate-200/60 dark:border-zinc-700/50">
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Appointment Reminders</h4>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Auto-send reminders to patients</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="appointment_reminders" value="1" class="sr-only peer" <?= ($settings['appointment_reminders'] ?? 1) ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-primary-500"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end border-t border-slate-200/60 dark:border-zinc-700/50 pt-5">
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Security Settings -->
        <div class="glass-panel animate-slide-up" style="animation-delay: 0.3s;">
            <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 bg-slate-50/50 dark:bg-zinc-800/50 flex items-center gap-3">
                <div class="w-10 h-10 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white">Security & Backup</h3>
            </div>
            <div class="p-6">
                <form method="POST" action="/admin/settings/update">
                    <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Session Timeout (min)</label>
                            <input type="number" class="input-modern" name="session_timeout" value="<?= htmlspecialchars($settings['session_timeout'] ?? 30) ?>">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Password Policy</label>
                            <select class="input-modern" name="password_policy">
                                <option value="standard" <?= ($settings['password_policy'] ?? 'standard') === 'standard' ? 'selected' : '' ?>>Standard</option>
                                <option value="strong" <?= ($settings['password_policy'] ?? 'standard') === 'strong' ? 'selected' : '' ?>>Strong</option>
                                <option value="strict" <?= ($settings['password_policy'] ?? 'standard') === 'strict' ? 'selected' : '' ?>>Strict</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-xl border border-slate-200/60 dark:border-zinc-700/50 mb-6">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Two-Factor Auth</h4>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Require 2FA for all admin users</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="two_factor_auth" value="1" class="sr-only peer" <?= ($settings['two_factor_auth'] ?? 0) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-primary-500"></div>
                        </label>
                    </div>

                    <div class="flex justify-between items-center border-t border-slate-200/60 dark:border-zinc-700/50 pt-5">
                        <div class="flex gap-2">
                            <button type="button" class="btn-secondary py-2 px-3 text-sm">
                                <i class="bi bi-cloud-download"></i>
                                Backup
                            </button>
                            <button type="button" class="btn-secondary py-2 px-3 text-sm">
                                <i class="bi bi-cloud-upload"></i>
                                Restore
                            </button>
                        </div>
                        <button type="submit" class="btn-primary py-2 px-4">
                            <i class="bi bi-check-circle-fill"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Settings';
require __DIR__ . '/../../layouts/app.php'; 
?>
