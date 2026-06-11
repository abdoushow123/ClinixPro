<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Add New Doctor</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Register a new medical specialist in the system</p>
    </div>
    <a href="/admin/doctors" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Staff Directory</span>
    </a>
</div>

<!-- Doctor Form -->
<div class="glass-panel animate-slide-up" style="animation-delay: 0.1s;">
    <div class="p-8">
        <form method="POST" action="/admin/doctors/store">
            <input type="hidden" name="_token" value="<?= $csrf_token ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="bi bi-person-vcard text-primary-500"></i>
                            Personal Information
                        </h4>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Full Name <span class="text-danger-500">*</span></label>
                            <input type="text" class="input-modern" name="name" required placeholder="Enter doctor's full name">
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Email Address <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="email" class="input-modern pl-10" name="email" required placeholder="Enter email address">
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Phone Number <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <i class="bi bi-telephone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="tel" class="input-modern pl-10" name="phone" required placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="bi bi-briefcase text-primary-500"></i>
                            Professional Details
                        </h4>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Specialty <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <select class="input-modern appearance-none" name="specialty" required>
                                    <option value="">Select Specialty</option>
                                    <option value="general">General Practice</option>
                                    <option value="cardiology">Cardiology</option>
                                    <option value="neurology">Neurology</option>
                                    <option value="pediatrics">Pediatrics</option>
                                    <option value="surgery">Surgery</option>
                                    <option value="orthopedics">Orthopedics</option>
                                    <option value="dermatology">Dermatology</option>
                                    <option value="radiology">Radiology</option>
                                    <option value="oncology">Oncology</option>
                                    <option value="gynecology">Gynecology</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Qualifications</label>
                            <textarea class="input-modern min-h-[100px] resize-y" name="qualifications" rows="2" placeholder="Enter qualifications and certifications"></textarea>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 dark:text-zinc-300 mb-2">Notes</label>
                            <textarea class="input-modern min-h-[100px] resize-y" name="notes" rows="2" placeholder="Any additional notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200/60 dark:border-zinc-700/50 mt-4">
                <a href="/admin/doctors" class="btn-secondary">
                    <span>Cancel</span>
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Register Doctor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Add New Doctor';
require __DIR__ . '/../../layouts/app.php'; 
?>
