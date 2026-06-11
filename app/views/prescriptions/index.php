<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Prescriptions</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage patient drug prescriptions and dispensing history</p>
    </div>
    <a href="/prescriptions/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-prescription2 text-lg"></i>
        <span>New Prescription</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-prescription text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Orders</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['dispensed'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Dispensed</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Awaiting</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-event text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['today'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Today</p>
    </div>
</div>

<!-- Prescriptions Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Active Prescription Orders</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search orders..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
            <select class="input-modern h-10 text-sm w-32 px-3 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="dispensed">Dispensed</option>
            </select>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Medication</th>
                    <th>Prescribing Physician</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prescriptions)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-prescription2 text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Prescriptions Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no prescriptions in the system.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach (($prescriptions ?? []) as $prescription): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($prescription['patient_name'] ?? 'P', 0, 1)) ?>
                            </div>
                            <div class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($prescription['patient_name'] ?? 'N/A') ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="font-bold text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($prescription['medication'] ?? 'N/A') ?></div>
                        <div class="text-[10px] text-slate-400 uppercase font-bold tracking-wider"><?= htmlspecialchars($prescription['dosage'] ?? 'Standard Dosage') ?></div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 font-medium text-slate-700 dark:text-zinc-300">
                            <i class="bi bi-person-badge text-primary-500"></i>
                            Dr. <?= htmlspecialchars($prescription['doctor_name'] ?? 'N/A') ?>
                        </div>
                    </td>
                    <td class="font-medium text-slate-700 dark:text-zinc-300">
                        <?= date('M d, Y', strtotime($prescription['prescription_date'])) ?>
                    </td>
                    <td>
                        <span class="badge <?= $prescription['status'] === 'dispensed' ? 'badge-success' : 'badge-warning' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $prescription['status'] === 'dispensed' ? 'bg-success-500' : 'bg-warning-500' ?> mr-1.5"></span>
                            <?= htmlspecialchars($prescription['status']) ?>
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/prescriptions/<?= $prescription['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <?php if ($prescription['status'] !== 'dispensed'): ?>
                            <form method="POST" action="/prescriptions/<?= $prescription['id'] ?>/dispense" class="inline" data-confirm="Mark this medication as dispensed to the patient?">
                                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-success-600 hover:bg-success-50 dark:hover:bg-success-900/30 rounded-xl transition-all" title="Dispense">
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>
                            </form>
                            <?php endif; ?>
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
$displayTitle = 'Prescriptions';
require __DIR__ . '/../layouts/app.php'; 
?>
