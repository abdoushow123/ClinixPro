<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Medical Records</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage patient clinical histories and notes</p>
    </div>
    <a href="/medical-records/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-file-earmark-plus text-lg"></i>
        <span>New Record</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-folder2-open text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Records</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-check text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['today'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Added Today</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-graph-up-arrow text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['week'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">This Week</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-people text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['patients'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Unique Patients</p>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-panel p-4 mb-8 flex flex-col md:flex-row gap-4" style="animation-delay: 0.5s;">
    <form method="GET" action="/medical-records/search" class="flex-1 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1 group">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
            <input type="text" name="q" placeholder="Search by patient, diagnosis, or clinician..." required class="input-modern pl-12 h-12">
        </div>
        <select name="visit_type" class="input-modern h-12">
            <option value="">All Visit Types</option>
            <option value="consultation">Consultation</option>
            <option value="follow_up">Follow Up</option>
            <option value="emergency">Emergency</option>
            <option value="routine">Routine</option>
        </select>
        <button type="submit" class="btn-primary h-12 px-8 flex items-center gap-2">
            <i class="bi bi-search"></i>
            <span>Search</span>
        </button>
    </form>
</div>

<!-- Records Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (empty($records)): ?>
        <div class="col-span-full py-20 glass-panel text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                <i class="bi bi-file-earmark-medical text-4xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Medical Records Found</h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">Start by creating a clinical record for a patient to track their medical history.</p>
            <a href="/medical-records/create" class="btn-primary">Create First Record</a>
        </div>
    <?php else: ?>
        <?php foreach ($records as $record): ?>
            <?php $visitType = $record['visit_type'] ?? 'consultation'; ?>
            <div class="glass-panel flex flex-col group hover:border-primary-500/50 transition-all duration-300">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="bi bi-<?= getRecordIcon($visitType) ?> text-2xl"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                            <?= ucfirst(str_replace('_', ' ', $visitType)) ?>
                        </span>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2 line-clamp-1">
                        <?= htmlspecialchars($record['diagnosis'] ?? 'Clinical Record') ?>
                    </h4>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                            <i class="bi bi-person-circle text-blue-500"></i>
                            <span class="font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($record['patient_name'] ?? 'Unknown Patient') ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <i class="bi bi-calendar3"></i>
                            <span><?= date('M d, Y', strtotime($record['visit_date'])) ?></span>
                            <span class="mx-1">•</span>
                            <span><?= \App\Helpers\DateHelper::formatRelativeTime($record['visit_date']) ?></span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3 mb-4 min-h-[4.5rem]">
                        <?= htmlspecialchars($record['treatment_plan'] ?? $record['notes'] ?? 'No treatment plan documented.') ?>
                    </p>
                    <div class="flex gap-2 pt-4 border-t border-slate-100 dark:border-slate-800 mt-auto">
                        <a href="/medical-records/<?= $record['id'] ?>" class="flex-1 btn-primary py-2 text-xs flex items-center justify-center gap-2">
                            <i class="bi bi-eye"></i>
                            <span>Full Details</span>
                        </a>
                        <a href="/medical-records/<?= $record['id'] ?>/edit" class="btn-secondary py-2 text-xs" title="Edit Record">
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
$displayTitle = 'Medical Records';
require __DIR__ . '/../layouts/app.php';
?>
