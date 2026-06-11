<?php ob_start(); ?>

<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Ambient glowing blobs -->
    <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-primary-500/20 dark:bg-primary-900/30 blur-[120px] animate-pulse-slow pointer-events-none"></div>
    <div class="absolute top-[60%] -right-[10%] w-[40%] h-[60%] rounded-full bg-accent-500/20 dark:bg-accent-900/30 blur-[120px] animate-float pointer-events-none"></div>

    <div class="w-full max-w-[1000px] flex flex-col md:flex-row glass-panel overflow-hidden p-0 animate-scale-in">
        
        <!-- Branding Section (Left Side) -->
        <div class="w-full md:w-5/12 bg-gradient-to-br from-primary-600 to-accent-600 p-12 flex flex-col items-center justify-center text-center relative overflow-hidden text-white hidden md:flex">
            <!-- Decorative inner circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/10 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl shadow-[0_8px_32px_rgba(0,0,0,0.2)] mb-8 transform hover:scale-110 hover:rotate-6 transition-all duration-500 border border-white/30">
                    <i class="bi bi-hospital-fill text-white text-5xl"></i>
                </div>
                <h1 class="text-4xl font-black tracking-tight mb-2">ClinixPro</h1>
                <p class="text-primary-100 font-medium text-lg mb-8">Modern Hospital Management</p>
                
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-semibold text-white/90 shadow-lg">
                    <i class="bi bi-shield-check text-success-300 text-lg"></i>
                    <span>Secure Access Portal</span>
                </div>
            </div>
        </div>

        <!-- Login Form Section (Right Side) -->
        <div class="w-full md:w-7/12 p-8 md:p-12 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-xl relative">
            <div class="md:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-600 to-accent-500 rounded-2xl shadow-glow-primary mb-4 border border-white/20">
                    <i class="bi bi-hospital-fill text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-black text-gradient">ClinixPro</h1>
            </div>

            <div class="mb-8 text-center md:text-left">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Welcome Back</h2>
                <p class="text-slate-500 dark:text-zinc-400 font-medium">Please enter your credentials to sign in</p>
            </div>

            <form method="POST" action="/login/authenticate" class="space-y-6">
                <input type="hidden" name="_token" value="<?= $csrf_token ?? '' ?>">
                
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

                <div class="form-group">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="form-label mb-0">Password</label>
                        <a href="/forgot-password" class="text-sm font-bold text-primary-600 hover:text-primary-500 dark:text-primary-400 transition-colors">Forgot password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                        <input type="password" id="password" name="password" required 
                            class="input-modern pl-11 pr-11" 
                            placeholder="••••••••">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary-500 transition-colors">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" 
                            class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500 dark:bg-zinc-800 dark:border-zinc-700 transition-colors">
                        <label for="remember" class="ml-2 block text-sm font-medium text-slate-600 dark:text-zinc-400 cursor-pointer">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 text-lg shadow-glow-primary">
                    <span>Sign In to Dashboard</span>
                    <i class="bi bi-arrow-right-short text-2xl"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800 text-center">
                <p class="text-sm font-medium text-slate-600 dark:text-zinc-400">
                    Don't have an account? 
                    <a href="/register" class="font-bold text-primary-600 dark:text-primary-400 hover:underline transition-colors">Register here</a>
                </p>
                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 text-xs font-semibold shadow-inner border border-slate-200/50 dark:border-zinc-700/50">
                    <i class="bi bi-info-circle text-primary-500"></i>
                    <span>Default Admin: admin@clinixpro.com / Admin@123</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (togglePassword && password && eyeIcon) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>

<?php 
$content = ob_get_clean();
$hasSidebar = false;
$pageTitle = 'Login - ClinixPro';
require __DIR__ . '/../layouts/app.php'; 
?>
