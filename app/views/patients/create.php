<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Register Patient</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Add a new patient to the ClinixPro system</p>
    </div>
    <a href="/patients" class="btn-secondary">
        <i class="bi bi-arrow-left-short text-xl"></i>
        <span>Back to Directory</span>
    </a>
</div>

<!-- Patient Form -->
<div class="glass-panel overflow-hidden animate-slide-up" style="animation-delay: 0.1s;">
    <form method="POST" action="/patients/store" class="p-8 md:p-10 space-y-12">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Personal Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-person-badge text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Personal Details</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Basic identification information</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">First Name <span class="text-danger-500">*</span></label>
                    <input type="text" name="first_name" required class="input-modern" placeholder="John">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Last Name <span class="text-danger-500">*</span></label>
                    <input type="text" name="last_name" required class="input-modern" placeholder="Doe">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Date of Birth <span class="text-danger-500">*</span></label>
                    <input type="date" name="date_of_birth" required class="input-modern">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Gender <span class="text-danger-500">*</span></label>
                    <div class="relative">
                        <select name="gender" required class="input-modern appearance-none pr-10">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                <div class="form-group mb-0 lg:col-span-2">
                    <label class="form-label">Blood Type</label>
                    <div class="relative">
                        <select name="blood_type" class="input-modern appearance-none pr-10">
                            <option value="">Unknown / Not Tested</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-geo-alt-fill text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Contact & Address</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">How we can reach the patient</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="form-group mb-0 lg:col-span-1">
                    <label class="form-label">Email Address</label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" class="input-modern pl-11" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-group mb-0 lg:col-span-1">
                    <label class="form-label">Phone Number <span class="text-danger-500">*</span></label>
                    <div class="relative">
                        <i class="bi bi-telephone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="tel" name="phone" required class="input-modern pl-11" placeholder="(555) 000-0000">
                    </div>
                </div>
                <div class="form-group mb-0 md:col-span-2 lg:col-span-1">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="input-modern" placeholder="ZIP or Postal Code">
                </div>
                <div class="form-group mb-0 md:col-span-2 lg:col-span-3">
                    <label class="form-label">Full Address</label>
                    <textarea name="address" rows="2" class="input-modern" placeholder="Street address, apartment, suite, etc."></textarea>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="input-modern" placeholder="City">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">State / Province</label>
                    <input type="text" name="state" class="input-modern" placeholder="State">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="input-modern" placeholder="Country" value="USA">
                </div>
            </div>
        </div>

        <!-- Emergency Contact & Medical Info side-by-side on large screens -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8">
            <!-- Emergency Contact -->
            <div class="space-y-6">
                <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                    <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="bi bi-heart-pulse-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Emergency Contact</h3>
                        <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">In case of emergency</p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="form-group mb-0">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="input-modern" placeholder="Full name">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="emergency_contact_phone" class="input-modern" placeholder="Phone number">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Relationship</label>
                            <input type="text" name="emergency_contact_relationship" class="input-modern" placeholder="e.g. Spouse, Parent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="space-y-6">
                <div class="flex items-center gap-4 border-b border-slate-200/60 dark:border-zinc-700/50 pb-4">
                    <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="bi bi-file-medical-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Medical Notes</h3>
                        <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Allergies and conditions</p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Insurance Provider</label>
                            <input type="text" name="insurance_provider" class="input-modern" placeholder="Company name">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Policy Number</label>
                            <input type="text" name="insurance_policy_number" class="input-modern" placeholder="Policy ID">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Allergies & Conditions</label>
                        <textarea name="allergies" rows="4" class="input-modern" placeholder="List any known allergies, chronic conditions, or important medical notes..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-8 border-t border-slate-200/60 dark:border-zinc-700/50">
            <a href="/patients" class="btn-secondary w-full sm:w-auto px-8">
                Cancel
            </a>
            <button type="submit" class="btn-primary w-full sm:w-auto px-10 shadow-glow-primary">
                <i class="bi bi-check-circle-fill mr-2"></i>
                <span>Complete Registration</span>
            </button>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Add Patient';
require __DIR__ . '/../layouts/app.php'; 
?>
