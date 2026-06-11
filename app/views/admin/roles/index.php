<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Roles & Permissions</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage system access levels and user permissions</p>
    </div>
    <a href="/admin/roles/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-shield-plus text-lg"></i>
        <span>Create New Role</span>
    </a>
</div>

<!-- Roles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($roles)): ?>
    <div class="col-span-full py-20 glass-panel text-center animate-fade-in">
        <div class="w-20 h-20 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-shield-lock text-4xl text-slate-400 dark:text-zinc-500"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Roles Defined</h3>
        <p class="text-slate-500 dark:text-zinc-400 max-w-md mx-auto mb-8">Start by defining system roles to control access to different modules.</p>
        <a href="/admin/roles/create" class="btn-primary">Add First Role</a>
    </div>
    <?php else: ?>
    <?php foreach ($roles as $index => $role): ?>
    <div class="glass-panel flex flex-col group hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: <?= 0.1 * ($index + 1) ?>s;">
        <div class="p-6 flex flex-col h-full">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300 border border-primary-100 dark:border-primary-800/50">
                    <i class="bi bi-shield-lock-fill text-2xl"></i>
                </div>
                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200/60 dark:border-zinc-700/50">
                    <?= number_format($role['user_count'] ?? 0) ?> Users
                </span>
            </div>

            <h4 class="text-xl font-black text-slate-900 dark:text-white mb-2 uppercase tracking-tight group-hover:text-primary-600 transition-colors">
                <?= htmlspecialchars($role['name']) ?>
            </h4>

            <p class="text-sm text-slate-500 dark:text-zinc-400 line-clamp-2 mb-8 flex-grow">
                <?= htmlspecialchars($role['description'] ?? 'No description provided for this security role.') ?>
            </p>

            <div class="flex gap-2 pt-6 border-t border-slate-200/60 dark:border-zinc-700/50 mt-auto">
                <a href="/admin/roles/<?= $role['id'] ?>" class="flex-1 btn-primary py-2.5 text-xs flex items-center justify-center gap-2">
                    <i class="bi bi-eye-fill"></i>
                    <span>Manage</span>
                </a>
                <a href="/admin/roles/<?= $role['id'] ?>/edit" class="btn-secondary py-2.5 px-4 text-xs group/edit hover:bg-warning-50 hover:text-warning-600 hover:border-warning-200 dark:hover:bg-warning-900/30 dark:hover:border-warning-800 dark:hover:text-warning-400" title="Edit Role">
                    <i class="bi bi-pencil-fill group-hover/edit:rotate-12 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Roles & Permissions';
require __DIR__ . '/../../layouts/app.php'; 
?>
