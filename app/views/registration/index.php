<?php ob_start(); ?>

<div class="min-h-screen flex items-center justify-center p-6 py-12 relative z-10">
    <div class="w-full max-w-3xl">
        <!-- Logo & Branding -->
        <div class="text-center mb-8 animate-slide-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-600 to-accent-500 rounded-2xl shadow-glow-primary mb-4 border border-white/20">
                <i class="bi bi-person-plus-fill text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Create Account</h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">Join the <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent font-bold">ClinixPro</span> medical network</p>
        </div>

        <!-- Registration Card -->
        <div class="glass-panel overflow-hidden animate-scale-in">
            <form method="POST" action="/register/store" enctype="multipart/form-data" class="p-8 md:p-10 space-y-8">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-person-badge text-primary-500"></i> Select Your Role
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php 
                        $roles = [
                            ['id' => 2, 'slug' => 'doctor', 'name' => 'Doctor', 'icon' => 'bi-person-badge'],
                            ['id' => 3, 'slug' => 'nurse', 'name' => 'Nurse', 'icon' => 'bi-heart-pulse'],
                            ['id' => 5, 'slug' => 'pharmacist', 'name' => 'Pharmacist', 'icon' => 'bi-capsule'],
                            ['id' => 6, 'slug' => 'laboratory', 'name' => 'Laboratory', 'icon' => 'bi-droplet'],
                            ['id' => 4, 'slug' => 'receptionist', 'name' => 'Receptionist', 'icon' => 'bi-headset'],
                            ['id' => 1, 'slug' => 'administrator', 'name' => 'Admin', 'icon' => 'bi-gear'],
                        ];
                        foreach ($roles as $role): 
                        ?>
                        <label class="relative flex flex-col items-center p-4 rounded-xl border-2 border-slate-200/50 dark:border-zinc-700/50 bg-white/50 dark:bg-zinc-800/50 cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 dark:hover:bg-primary-900/20 transition-all group overflow-hidden">
                            <input type="radio" name="role" value="<?= $role['id'] ?>" class="peer sr-only" required onchange="handleRoleChange('<?= $role['slug'] ?>')">
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-primary-500 rounded-xl transition-all shadow-[inset_0_0_0_1px_rgba(59,130,246,0)] peer-checked:shadow-[inset_0_0_0_1px_rgba(59,130,246,0.5)]"></div>
                            <div class="absolute top-0 right-0 w-16 h-16 bg-primary-500/10 rounded-full blur-xl transform translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            <i class="<?= $role['icon'] ?> text-2xl text-slate-400 dark:text-zinc-500 group-hover:text-primary-500 peer-checked:text-primary-600 dark:peer-checked:text-primary-400 mb-2 transition-colors relative z-10"></i>
                            <span class="text-sm font-bold text-slate-600 dark:text-zinc-400 group-hover:text-primary-600 peer-checked:text-primary-600 dark:peer-checked:text-primary-400 transition-colors relative z-10"><?= $role['name'] ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Information -->
                    <div class="space-y-5">
                        <h3 class="text-sm font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest flex items-center gap-2 pb-2 border-b border-slate-200/50 dark:border-zinc-700/50">
                            <i class="bi bi-person"></i> Personal Info
                        </h3>
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" required class="input-modern" placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" required class="input-modern" placeholder="john@example.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" required class="input-modern" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>

                    <!-- Account Security -->
                    <div class="space-y-5">
                        <h3 class="text-sm font-bold text-accent-600 dark:text-accent-400 uppercase tracking-widest flex items-center gap-2 pb-2 border-b border-slate-200/50 dark:border-zinc-700/50">
                            <i class="bi bi-shield-lock"></i> Account Details
                        </h3>
                        
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" required class="input-modern" placeholder="johndoe77">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" required class="input-modern" placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" required class="input-modern" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Role-specific fields -->
                <div id="nurseFields" class="hidden animate-fade-in space-y-6 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
                    <h3 class="text-sm font-bold text-warning-600 dark:text-warning-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="bi bi-clipboard-pulse"></i> Nurse Application Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Assigned Doctor</label>
                            <select name="assigned_doctor_id" id="doctorSelect" class="input-modern">
                                <option value="">Select a Doctor</option>
                                <?php if (!empty($doctors)): ?>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?= $doctor['id'] ?>">
                                            Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Curriculum Vitae (PDF)</label>
                            <input type="file" name="cv_file" id="cvFile" accept=".pdf" class="input-modern file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                            <p class="mt-1 text-xs text-slate-500 dark:text-zinc-500 font-medium">PDF only, max 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-200/50 dark:border-zinc-700/50">
                    <button type="submit" class="btn-primary w-full py-4 text-lg flex items-center justify-center gap-3 shadow-glow-primary hover:scale-[1.02] transition-transform">
                        <i class="bi bi-person-check-fill text-xl"></i>
                        <span>Complete Registration</span>
                    </button>
                    <p class="mt-6 text-center text-sm font-medium text-slate-500 dark:text-zinc-400">
                        Already have an account? 
                        <a href="/login" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-500 transition-colors">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleRoleChange(role) {
        const nurseFields = document.getElementById('nurseFields');
        const cvFile = document.getElementById('cvFile');
        const doctorSelect = document.getElementById('doctorSelect');
        
        if (role === 'nurse') {
            nurseFields.classList.remove('hidden');
            cvFile.required = true;
            doctorSelect.required = true;
        } else {
            nurseFields.classList.add('hidden');
            cvFile.required = false;
            doctorSelect.required = false;
        }
    }
</script>

<?php 
$content = ob_get_clean();
$hasSidebar = false;
$pageTitle = 'Register - ClinixPro';
require __DIR__ . '/../layouts/app.php'; 
?>
