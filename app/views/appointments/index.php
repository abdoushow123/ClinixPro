<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Appointments</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Schedule and manage patient appointments</p>
    </div>
    <a href="/appointments/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-calendar-plus text-lg"></i>
        <span>New Appointment</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-check text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo number_format($stats['total'] ?? 0); ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar3 text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo number_format($stats['today'] ?? 0); ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Today</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo number_format($stats['pending'] ?? 0); ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-x-circle text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo number_format($stats['cancelled'] ?? 0); ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Cancelled</p>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Upcoming Appointments</h3>
        <span class="badge badge-primary"><?php echo number_format(count($appointments ?? [])); ?> Scheduled</span>
    </div>
    <?php if (empty($appointments)): ?>
        <div class="p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                <i class="bi bi-calendar-x text-4xl text-slate-400 dark:text-zinc-500"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium mb-2">No upcoming appointments found.</p>
            <a href="/appointments/create" class="btn-primary inline-flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Schedule One</span>
            </a>
        </div>
    <?php else: ?>
        <div class="p-6 overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                    <?= strtoupper(substr($appointment['patient_name'][0] ?? 'P', 0, 1)) ?>
                                </div>
                                <span class="font-medium text-slate-900 dark:text-white"><?= htmlspecialchars($appointment['patient_name'] ?? 'N/A') ?></span>
                            </div>
                        </td>
                        <td><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></td>
                        <td><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></td>
                        <td class="font-medium text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($appointment['appointment_type'] ?? 'General') ?></td>
                        <td class="font-medium text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($appointment['doctor_name'] ?? 'Unassigned') ?></td>
                        <td>
                            <span class="badge <?= $appointment['status'] === 'scheduled' ? 'badge-success' : ($appointment['status'] === 'cancelled' ? 'badge-danger' : 'badge-warning') ?>">
                                <?= htmlspecialchars($appointment['status']) ?>
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/appointments/<?= $appointment['id'] ?>" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-all" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/appointments/<?= $appointment['id'] ?>/edit" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-all" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Appointments';
require __DIR__ . '/../layouts/app.php'; 
?>
