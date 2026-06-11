<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Edit Patient</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Update patient information</p>
    </div>
    <a href="/patients/<?= $patient['id'] ?>" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Patient</span>
    </a>
</div>

<!-- Patient Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/patients/<?= $patient['id'] ?>/update" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <!-- Personal Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-circle text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Personal Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" required class="input-modern" value="<?= htmlspecialchars($patient['first_name']) ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required class="input-modern" value="<?= htmlspecialchars($patient['last_name']) ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Date of Birth *</label>
                    <input type="date" name="date_of_birth" required class="input-modern" value="<?= htmlspecialchars($patient['date_of_birth']) ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gender *</label>
                    <select name="gender" required class="input-modern">
                        <option value="">Select Gender</option>
                        <option value="male" <?= $patient['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $patient['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $patient['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Blood Type</label>
                    <select name="blood_type" class="input-modern">
                        <option value="">Select Blood Type</option>
                        <?php 
                        $types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        foreach ($types as $type): 
                        ?>
                        <option value="<?= $type ?>" <?= $patient['blood_type'] === $type ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-telephone-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Contact Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
                    <input type="email" name="email" class="input-modern" value="<?= htmlspecialchars($patient['email'] ?? '') ?>">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Phone Number *</label>
                    <input type="tel" name="phone" required class="input-modern" value="<?= htmlspecialchars($patient['phone']) ?>">
                </div>
                <div class="md:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" class="input-modern" value="<?= htmlspecialchars($patient['postal_code'] ?? '') ?>">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="input-modern"><?= htmlspecialchars($patient['address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">City</label>
                    <input type="text" name="city" class="input-modern" value="<?= htmlspecialchars($patient['city'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">State</label>
                    <input type="text" name="state" class="input-modern" value="<?= htmlspecialchars($patient['state'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Country</label>
                    <input type="text" name="country" class="input-modern" value="<?= htmlspecialchars($patient['country'] ?? 'USA') ?>">
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-exclamation-circle-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Emergency Contact</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Contact Name</label>
                    <input type="text" name="emergency_contact_name" class="input-modern" value="<?= htmlspecialchars($patient['emergency_contact_name'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Contact Phone</label>
                    <input type="tel" name="emergency_contact_phone" class="input-modern" value="<?= htmlspecialchars($patient['emergency_contact_phone'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Relationship</label>
                    <input type="text" name="emergency_contact_relationship" class="input-modern" value="<?= htmlspecialchars($patient['emergency_contact_relationship'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-file-medical-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Medical Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Insurance Provider</label>
                    <input type="text" name="insurance_provider" class="input-modern" value="<?= htmlspecialchars($patient['insurance_provider'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Policy Number</label>
                    <input type="text" name="insurance_policy_number" class="input-modern" value="<?= htmlspecialchars($patient['insurance_policy_number'] ?? '') ?>">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Allergies</label>
                    <textarea name="allergies" rows="2" class="input-modern"><?= htmlspecialchars($patient['allergies'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Chronic Conditions</label>
                    <textarea name="chronic_conditions" rows="2" class="input-modern"><?= htmlspecialchars($patient['chronic_conditions'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Additional Notes</label>
                    <textarea name="notes" rows="3" class="input-modern"><?= htmlspecialchars($patient['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <span>Update Patient Information</span>
            </button>
            <a href="/patients/<?= $patient['id'] ?>" class="btn-secondary flex-1 flex items-center justify-center gap-2">
                <i class="bi bi-x-circle"></i>
                <span>Cancel Changes</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Edit Patient';
require __DIR__ . '/../layouts/app.php'; 
?>
