<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Edit Room</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Update specification for Room <?= htmlspecialchars($room['room_number']) ?></p>
    </div>
    <a href="/rooms/<?= $room['id'] ?>" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Room</span>
    </a>
</div>

<!-- Room Form -->
<div class="glass-card overflow-hidden">
    <form method="POST" action="/rooms/<?= $room['id'] ?>/update" class="p-8 space-y-10">
        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
        
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <i class="bi bi-pencil-fill text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Modify Specification</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Room Number *</label>
                    <input type="text" name="room_number" required class="input-modern" value="<?= htmlspecialchars($room['room_number']) ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Room Category *</label>
                    <select name="room_type" required class="input-modern">
                        <option value="">Select Category</option>
                        <option value="standard" <?= $room['room_type'] === 'standard' ? 'selected' : '' ?>>Standard</option>
                        <option value="private" <?= $room['room_type'] === 'private' ? 'selected' : '' ?>>Private</option>
                        <option value="icu" <?= $room['room_type'] === 'icu' ? 'selected' : '' ?>>ICU</option>
                        <option value="surgery" <?= $room['room_type'] === 'surgery' ? 'selected' : '' ?>>Surgery</option>
                        <option value="maternity" <?= $room['room_type'] === 'maternity' ? 'selected' : '' ?>>Maternity</option>
                        <option value="pediatric" <?= $room['room_type'] === 'pediatric' ? 'selected' : '' ?>>Pediatric</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bed Capacity *</label>
                    <input type="number" name="bed_count" required min="1" class="input-modern" value="<?= htmlspecialchars($room['bed_count'] ?? 1) ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Floor / Level</label>
                    <input type="number" name="floor" class="input-modern" value="<?= htmlspecialchars($room['floor'] ?? '') ?>">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description & Amenities</label>
                <textarea name="description" rows="4" class="input-modern"><?= htmlspecialchars($room['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-check-circle-fill"></i>
                <span>Update Room Details</span>
            </button>
            <a href="/rooms/<?= $room['id'] ?>" class="btn-secondary flex-1 flex items-center justify-center gap-2 py-4">
                <i class="bi bi-x-circle"></i>
                <span>Discard Changes</span>
            </a>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Edit Room';
require __DIR__ . '/../layouts/app.php'; 
?>
