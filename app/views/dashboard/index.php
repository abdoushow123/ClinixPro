<?php ob_start(); ?>
<!-- Welcome Banner -->
<div class="glass-panel p-8 mb-8 animate-fade-in relative overflow-hidden group">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-400/20 to-accent-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-700"></div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between relative z-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Welcome back, <?= htmlspecialchars($user['first_name'] ?? 'User') ?>! <span class="inline-block animate-bounce-in">👋</span></h1>
            <p class="text-slate-600 dark:text-zinc-400 text-lg">You are logged in as <strong class="text-primary-600 dark:text-primary-400 px-2 py-1 bg-primary-50 dark:bg-primary-900/30 rounded-lg ml-1"><?= htmlspecialchars($user['role_name'] ?? 'Staff') ?></strong></p>
        </div>
        <div class="flex items-center gap-4 bg-white/50 dark:bg-zinc-800/50 p-4 rounded-2xl backdrop-blur-md border border-white/40 dark:border-zinc-700/50 shadow-sm">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
                <i class="bi bi-clock-history text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest"><?= date('l, F j') ?></p>
                <p class="text-xl font-black text-slate-900 dark:text-white"><?= date('g:i A') ?></p>
            </div>
        </div>
    </div>
</div>

<?php
// Load role-specific dashboard partial
$role = strtolower($dashboard_role ?? 'administrator');
// Normalize admin to administrator for partial file naming
if ($role === 'admin') $role = 'administrator';

$partialFile = __DIR__ . "/partials/{$role}.php";
if (file_exists($partialFile)) {
    require $partialFile;
} else {
    // Fallback to administrator view
    require __DIR__ . '/partials/administrator.php';
}
?>

<?php $content = ob_get_clean(); ?>

<?php 
$currentPath = '/dashboard';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; 
?>
