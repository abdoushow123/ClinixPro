<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Room Management</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage hospital accommodation, beds, and availability</p>
    </div>
    <a href="/rooms/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-plus-circle text-lg"></i>
        <span>Add New Room</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-door-open text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Rooms</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Available</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['available'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Ready</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-fill-lock text-xl"></i>
            </div>
            <span class="badge badge-danger">Occupied</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['occupied'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">In Use</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-tools text-xl"></i>
            </div>
            <span class="badge badge-warning">Maintenance</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['maintenance'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Service Required</p>
    </div>
</div>

<!-- Rooms Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 animate-fade-in" style="animation-delay: 0.5s;">
    <?php if (empty($rooms)): ?>
    <div class="col-span-full py-20 glass-panel text-center">
        <div class="w-20 h-20 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-door-closed text-4xl text-slate-400 dark:text-zinc-500"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Rooms Defined</h3>
        <p class="text-slate-500 dark:text-zinc-400 max-w-md mx-auto mb-8">Add hospital rooms and wings to manage patient admissions effectively.</p>
        <a href="/rooms/create" class="btn-primary">Add First Room</a>
    </div>
    <?php else: ?>
    <?php foreach (($rooms ?? []) as $room): ?>
    <div class="glass-panel flex flex-col group hover:border-primary-500/50 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 <?= $room['is_available'] ? 'bg-success-50 text-success-600 dark:bg-success-900/20 dark:text-success-400 border border-success-200 dark:border-success-800' : 'bg-danger-50 text-danger-600 dark:bg-danger-900/20 dark:text-danger-400 border border-danger-200 dark:border-danger-800' ?> rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <i class="bi bi-door-open-fill text-2xl"></i>
                </div>
                <span class="badge <?= $room['is_available'] ? 'badge-success' : 'badge-danger' ?>">
                    <?= $room['is_available'] ? 'Available' : 'Occupied' ?>
                </span>
            </div>

            <h4 class="text-2xl font-black text-slate-900 dark:text-white mb-1 uppercase tracking-tight">
                <?= htmlspecialchars($room['room_number']) ?>
            </h4>
            <p class="text-xs font-bold text-primary-500 uppercase tracking-widest mb-6 italic"><?= htmlspecialchars($room['room_type'] ?? 'Standard Room') ?></p>

            <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 mb-8">
                <div class="flex-1 text-center border-r border-slate-200 dark:border-zinc-700">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Beds</p>
                    <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($room['bed_count'] ?? 1) ?></p>
                </div>
                <div class="flex-1 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Floor</p>
                    <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($room['floor'] ?? '1') ?></p>
                </div>
            </div>

            <div class="flex gap-2 mt-auto">
                <a href="/rooms/<?= $room['id'] ?>" class="flex-1 btn-primary py-2.5 text-xs flex items-center justify-center gap-2">
                    <i class="bi bi-eye"></i>
                    <span>Details</span>
                </a>
                <a href="/rooms/<?= $room['id'] ?>/edit" class="btn-secondary w-10 py-2.5 flex items-center justify-center text-xs" title="Edit Room">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Room Management';
require __DIR__ . '/../layouts/app.php'; 
?>
