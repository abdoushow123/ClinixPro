<?php ob_start(); ?>

<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Ambient glowing blobs -->
    <div class="absolute -top-[20%] left-[20%] w-[50%] h-[50%] rounded-full bg-success-500/20 dark:bg-success-900/30 blur-[120px] animate-pulse-slow pointer-events-none"></div>

    <div class="w-full max-w-[500px] glass-panel p-8 md:p-12 animate-scale-in border-t-4 border-t-primary-500">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-600 to-accent-500 rounded-2xl shadow-glow-primary mb-6 border border-white/20">
                <i class="bi bi-shield-lock-fill text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Set New Password</h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium px-4">Create a strong new password for your account.</p>
        </div>

        <form method="POST" action="/reset-password/handle" class="space-y-6">
            <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-lock text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>
                    <input type="password" name="password" required 
                        class="input-modern pl-11" 
                        placeholder="••••••••">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-lock-fill text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>
                    <input type="password" name="confirm_password" required 
                        class="input-modern pl-11" 
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-lg shadow-glow-primary">
                <i class="bi bi-check2-circle mr-2"></i>
                <span>Save Password</span>
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
$pageTitle = 'Reset Password - ClinixPro';
require __DIR__ . '/../layouts/app.php'; 
?>
