<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Hospitalizations</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage patient admissions, room assignments, and discharges</p>
    </div>
    <a href="/hospitalizations/admit" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-plus-circle text-lg"></i>
        <span>Admit Patient</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-hospital text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Admissions</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Active In-Patients</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-box-arrow-right text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['discharged'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Discharged Today</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-door-open-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['available_rooms'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Available Rooms</p>
    </div>
</div>

<!-- Active Admissions Table -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Active Admissions</h3>
        <span class="badge badge-success"><?= number_format(count($hospitalizations ?? [])) ?> Currently In-Patient</span>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Room</th>
                    <th>Admitted Date</th>
                    <th>Attending Doctor</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hospitalizations)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-hospital text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Active Admissions</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are no patients currently hospitalized.</p>
                        <a href="/hospitalizations/admit" class="mt-4 inline-block font-semibold text-primary-600 hover:text-primary-500">Admit a patient</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($hospitalizations as $admission): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($admission['patient_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm hover:text-primary-600 transition-colors"><?= htmlspecialchars($admission['patient_name']) ?></div>
                                <div class="text-[10px] font-mono text-slate-400 dark:text-zinc-500 tracking-widest mt-0.5">ID: <?= htmlspecialchars($admission['patient_id'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 text-xs font-bold">
                            <i class="bi bi-door-open text-primary-500"></i>
                            <?= htmlspecialchars($admission['room_number'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td>
                        <div class="font-medium text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($admission['admission_date'])) ?></div>
                        <div class="text-[10px] uppercase font-bold tracking-wider mt-0.5 text-slate-400 dark:text-zinc-500"><?= \App\Helpers\DateHelper::formatRelativeTime($admission['admission_date']) ?></div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 font-medium text-slate-700 dark:text-zinc-300">
                            <i class="bi bi-person-badge text-primary-500"></i>
                            Dr. <?= htmlspecialchars($admission['doctor_name'] ?? 'N/A') ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 mr-1.5"></span>
                            Admitted
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/hospitalizations/<?= $admission['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <form method="POST" action="/hospitalizations/<?= $admission['id'] ?>/discharge" class="inline-block" data-confirm="Are you sure you want to discharge this patient?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-warning-600 hover:bg-warning-50 dark:hover:bg-warning-900/30 rounded-xl transition-all" title="Discharge Patient">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
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
$displayTitle = 'Patient Admissions';
require __DIR__ . '/../layouts/app.php'; 
?>
