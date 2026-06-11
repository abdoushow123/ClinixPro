<?php ob_start(); ?>

<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="relative inline-block mb-12">
            <div class="w-32 h-32 bg-amber-100 dark:bg-amber-900/20 rounded-[2.5rem] flex items-center justify-center transform rotate-6">
                <i class="bi bi-exclamation-triangle-fill text-amber-600 text-6xl"></i>
            </div>
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-xl flex items-center justify-center transform -rotate-12 border-2 border-amber-500">
                <span class="text-2xl font-black text-slate-900 dark:text-white">500</span>
            </div>
        </div>

        <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">System Malfunction</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mb-12 leading-relaxed">
            The core server is experiencing critical failure. Our maintenance team has been alerted and a diagnostic sweep is underway.
        </p>

        <div class="flex flex-col gap-4">
            <a href="/dashboard" class="btn-primary py-4 shadow-lg shadow-blue-500/20 bg-amber-600 border-amber-600 hover:bg-amber-700">
                <i class="bi bi-house-door-fill"></i>
                <span>Emergency Override (Go Home)</span>
            </a>
            <button onclick="location.reload()" class="btn-secondary py-4">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Re-Initialize System</span>
            </button>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = false;
$pageTitle = '500 - Server Error';
require __DIR__ . '/../layouts/app.php'; 
?>
