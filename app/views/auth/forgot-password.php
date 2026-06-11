<?php ob_start(); ?>

<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Ambient glowing blobs -->
    <div class="absolute -top-[20%] left-[20%] w-[50%] h-[50%] rounded-full bg-accent-500/20 dark:bg-accent-900/30 blur-[120px] animate-pulse-slow pointer-events-none"></div>

    <div class="w-full max-w-[500px] glass-panel p-8 md:p-12 animate-scale-in border-t-4 border-t-primary-500">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-600 to-accent-500 rounded-2xl shadow-glow-primary mb-6 border border-white/20">
                <i class="bi bi-hospital-fill text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Forgot Password</h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium px-4">Enter your email address and we'll send you a link to reset your password.</p>
        </div>

        <form method="POST" action="/forgot-password/send" class="space-y-6">
            <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-envelope text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>
                    <input type="email" name="email" required autofocus 
                        class="input-modern pl-11" 
                        placeholder="name@hospital.com">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-lg shadow-glow-primary">
                <i class="bi bi-send mr-2"></i>
                <span>Send Reset Link</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800 text-center">
            <a href="/login" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-zinc-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Sign In</span>
            </a>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
$hasSidebar = false;
$pageTitle = 'Forgot Password - ClinixPro';
require __DIR__ . '/../layouts/app.php'; 
?>
