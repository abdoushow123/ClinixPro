<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Room Details</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Viewing status and specification for Room <?= htmlspecialchars($room['room_number']) ?></p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="/rooms/<?= $room['id'] ?>/edit" class="btn-primary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-pencil-square"></i>
            <span>Edit Room</span>
        </a>
        <a href="/rooms" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Rooms</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="bi bi-door-open-fill text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase"><?= htmlspecialchars($room['room_number']) ?></h2>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest"><?= htmlspecialchars($room['room_type']) ?> Unit</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-sm font-black uppercase tracking-wider <?= $room['is_available'] ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' ?>">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            <?= $room['is_available'] ? 'Available' : 'Occupied' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Bed Count</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($room['bed_count'] ?? 1) ?></p>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Floor Level</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($room['floor'] ?? 'G') ?></p>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Category</p>
                        <p class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase"><?= htmlspecialchars($room['room_type']) ?></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-500"></i>
                        Description & Amenities
                    </h3>
                    <div class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800">
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-medium italic">
                            <?= !empty($room['description']) ? nl2br(htmlspecialchars($room['description'])) : 'No detailed description or list of amenities provided for this room.' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Occupancy -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-6">
            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[10px] mb-6">Current Occupancy</h3>
            
            <?php if (!$room['is_available']): ?>
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-800">
                    <p class="text-[10px] font-black uppercase text-red-500 mb-2">Patient Admitted</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($occupant['name'] ?? 'Confidential') ?></p>
                    </div>
                </div>
                <button class="w-full btn-secondary py-3 flex items-center justify-center gap-2 text-red-600 border-red-200">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Transfer Patient</span>
                </button>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-check-lg text-2xl text-green-600"></i>
                </div>
                <p class="text-sm font-bold text-slate-900 dark:text-white">Ready for Admission</p>
                <p class="text-xs text-slate-500 mt-1">This room is currently empty and sanitized.</p>
                <a href="/hospitalizations/admit?room_id=<?= $room['id'] ?>" class="mt-6 w-full btn-primary py-3 flex items-center justify-center gap-2">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Admit Patient Here</span>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="glass-card p-6 border-blue-500/10">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-hospital text-blue-500"></i>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Facility Management</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed italic">
                Housekeeping status: <span class="text-green-500 font-bold">SANITIZED</span>. 
                Last maintenance check: <?= date('M d, Y') ?>.
            </p>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Room details';
require __DIR__ . '/../layouts/app.php'; 
?>
