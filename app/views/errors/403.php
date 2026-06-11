<?php ob_start(); ?>

<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="relative inline-block mb-12">
            <div class="w-32 h-32 bg-red-100 dark:bg-red-900/20 rounded-[2.5rem] flex items-center justify-center transform -rotate-12">
                <i class="bi bi-shield-lock-fill text-red-600 text-6xl"></i>
            </div>
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-xl flex items-center justify-center transform rotate-12">
                <span class="text-2xl font-black text-slate-900 dark:text-white">403</span>
            </div>
        </div>

        <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Access Restricted</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mb-12 leading-relaxed">
            Your current security clearance does not permit access to this sector. Please contact system administration if you believe this is an error.
        </p>

        <div class="flex flex-col gap-4">
            <a href="/dashboard" class="btn-primary py-4 shadow-lg shadow-blue-500/20">
                <i class="bi bi-house-door-fill"></i>
                <span>Return to Dashboard</span>
            </a>
            <button onclick="history.back()" class="btn-secondary py-4">
                <i class="bi bi-arrow-left"></i>
                <span>Previous Page</span>
            </button>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = false;
$pageTitle = '403 - Restricted Access';
require __DIR__ . '/../layouts/app.php'; 
?>
