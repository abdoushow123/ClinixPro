<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Add New Room</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Register a new hospital room or specialty ward</p>
    </div>
    <a href="/rooms" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Rooms</span>
    </a>
</div>

<!-- Room Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/rooms/store" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-door-open-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Room Specification</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Room Number *</label>
                    <input type="text" name="room_number" required class="input-modern" placeholder="e.g., 204-B">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Room Category *</label>
                    <select name="room_type" required class="input-modern">
                        <option value="">Select Category</option>
                        <option value="standard">Standard</option>
                        <option value="private">Private</option>
                        <option value="icu">ICU</option>
                        <option value="surgery">Surgery</option>
                        <option value="maternity">Maternity</option>
                        <option value="pediatric">Pediatric</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bed Capacity *</label>
                    <input type="number" name="bed_count" required min="1" value="1" class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Floor / Level</label>
                    <input type="number" name="floor" class="input-modern" placeholder="e.g., 2">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description & Amenities</label>
                <textarea name="description" rows="4" class="input-modern" placeholder="List room features, proximity to nurse station, or specialized equipment..."></textarea>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Register Room</span>
            </button>
            <a href="/rooms" class="btn-secondary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-x-circle"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Add Room';
require __DIR__ . '/../layouts/app.php'; 
?>
