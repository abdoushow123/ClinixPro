<?php ob_start(); ?>

<div class="relative w-full z-10">
    <!-- Navigation -->
    <nav class="glass-panel mx-6 mt-6 mb-12 px-6 py-4 flex items-center justify-between shadow-soft animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center shadow-glow-primary">
                <i class="bi bi-hospital-fill text-white text-xl"></i>
            </div>
            <span class="text-xl font-black bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent dark:from-primary-400 dark:to-accent-400">ClinixPro</span>
        </div>
        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle -->
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-600 dark:text-zinc-300 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-300 hover:scale-105 active:scale-95 mr-2">
                <i class="bi bi-moon-stars text-lg dark:hidden"></i>
                <i class="bi bi-sun text-lg hidden dark:block text-warning-400"></i>
            </button>
            <a href="/login" class="text-slate-600 dark:text-zinc-300 hover:text-primary-600 dark:hover:text-primary-400 font-bold transition-colors">Login</a>
            <a href="/register" class="btn-primary">Register</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-16 pb-24 px-6 text-center animate-slide-up">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 dark:text-white mb-6 leading-tight tracking-tight">
                Modern Hospital <br> <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent dark:from-primary-400 dark:to-accent-400">Management System</span>
            </h1>
            <p class="text-xl text-slate-500 dark:text-zinc-400 mb-10 max-w-2xl mx-auto font-medium">
                Comprehensive, intuitive, and secure healthcare administration platform for modern medical institutions.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/login" class="btn-primary shadow-glow-primary text-lg px-8 py-3">
                    Get Started <i class="bi bi-arrow-right"></i>
                </a>
                <a href="/register" class="btn-secondary text-lg px-8 py-3">
                    Create Account
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-20 px-6 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4">Everything You Need</h2>
                <p class="text-slate-500 dark:text-zinc-400 font-medium max-w-2xl mx-auto">Manage your entire medical institution from one unified platform.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-2xl flex items-center justify-center mb-6 border border-primary-200 dark:border-primary-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Patient Management</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Complete patient records, history, and real-time tracking in one secure place.</p>
                </div>

                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-success-50 dark:bg-success-900/20 text-success-600 dark:text-success-400 rounded-2xl flex items-center justify-center mb-6 border border-success-200 dark:border-success-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-file-medical-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Medical Records</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Secure, accessible, and organized digital medical records for all your patients.</p>
                </div>

                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-accent-50 dark:bg-accent-900/20 text-accent-600 dark:text-accent-400 rounded-2xl flex items-center justify-center mb-6 border border-accent-200 dark:border-accent-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-calendar-check-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Smart Scheduling</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Advanced appointment booking system with automated reminders.</p>
                </div>

                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-warning-50 dark:bg-warning-900/20 text-warning-600 dark:text-warning-400 rounded-2xl flex items-center justify-center mb-6 border border-warning-200 dark:border-warning-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-flask-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Laboratory System</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Efficient lab test management, tracking, and result publishing.</p>
                </div>

                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-danger-50 dark:bg-danger-900/20 text-danger-600 dark:text-danger-400 rounded-2xl flex items-center justify-center mb-6 border border-danger-200 dark:border-danger-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-capsule-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pharmacy & Inventory</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Integrated prescription handling and real-time medicine inventory tracking.</p>
                </div>

                <div class="glass-panel p-8 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-2xl flex items-center justify-center mb-6 border border-primary-200 dark:border-primary-800/50 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi bi-receipt-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Automated Billing</h3>
                    <p class="text-slate-500 dark:text-zinc-400 font-medium">Seamless invoicing, payment tracking, and insurance claim processing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 px-6 mt-10">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-600 to-accent-500 rounded-lg flex items-center justify-center shadow-glow-primary">
                    <i class="bi bi-hospital-fill text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900 dark:text-white">ClinixPro</span>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium text-sm">
                &copy; <?= date('Y') ?> ClinixPro. All rights reserved.
            </p>
        </div>
    </footer>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = false;
$pageTitle = 'ClinixPro';
require __DIR__ . '/../layouts/app.php'; 
?>
