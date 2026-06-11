<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Medical Staff</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage hospital doctors, specialists and on-call schedules</p>
    </div>
    <a href="/admin/doctors/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-person-badge-fill text-lg"></i>
        <span>Register New Doctor</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-badge text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Doctors</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Active</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Active Providers</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-check-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['appointments'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Today's Visits</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
            <span class="badge badge-danger">Emergency</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['on_call'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">On Call Now</p>
    </div>
</div>

<!-- Doctors Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-zinc-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-zinc-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Medical Staff Directory</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search staff..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
            <select class="input-modern h-10 text-sm w-40 px-3 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                <option value="">All Specialties</option>
                <option value="general">General Medicine</option>
                <option value="cardiology">Cardiology</option>
                <option value="neurology">Neurology</option>
                <option value="pediatrics">Pediatrics</option>
            </select>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Doctor Profile</th>
                    <th>Specialization</th>
                    <th>Contact Info</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($doctors)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-person-badge text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Medical Staff Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no doctors registered in the system.</p>
                        <a href="/admin/doctors/create" class="mt-4 inline-block font-semibold text-primary-600 hover:text-primary-500">Register a new doctor</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                <?= strtoupper(substr($doctor['name'] ?? 'D', 0, 1)) ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm">Dr. <?= htmlspecialchars($doctor['name'] ?? 'N/A') ?></div>
                                <div class="text-[10px] text-slate-500 dark:text-zinc-400 font-bold tracking-widest mt-0.5"><?= htmlspecialchars($doctor['email'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-100 dark:border-primary-800/50">
                            <?= htmlspecialchars($doctor['specialty'] ?? 'General') ?>
                        </span>
                    </td>
                    <td>
                        <div class="font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5">
                            <i class="bi bi-telephone-fill text-slate-400"></i>
                            <?= htmlspecialchars($doctor['phone'] ?? 'N/A') ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 mr-1.5"></span>
                            Available
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/doctors/<?= $doctor['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Profile">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/admin/doctors/<?= $doctor['id'] ?>/edit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-warning-600 hover:bg-warning-50 dark:hover:bg-warning-900/30 rounded-xl transition-all" title="Edit Doctor">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Medical Staff';
require __DIR__ . '/../../layouts/app.php'; 
?>
