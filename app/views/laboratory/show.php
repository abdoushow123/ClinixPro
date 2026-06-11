<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Lab Test Details</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Viewing clinical results and diagnostic report</p>
    </div>
    <a href="/laboratory" class="btn-secondary inline-flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Laboratory</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <!-- Header Section -->
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="bi bi-flask-fill text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($test['test_type'] ?? 'Clinical Diagnostic') ?></h2>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest <?= $test['status'] === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' ?>">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <?= htmlspecialchars($test['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Patient Information</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($test['patient_name'] ?? 'N/A') ?></p>
                        <p class="text-xs text-slate-500 italic">Patient ID: <?= htmlspecialchars($test['patient_id'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Requested On</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= date('F d, Y', strtotime($test['requested_date'])) ?></p>
                        <p class="text-xs text-slate-500 italic"><?= \App\Helpers\DateHelper::formatRelativeTime($test['requested_date']) ?></p>
                    </div>
                </div>

                <!-- Clinical Notes -->
                <div class="mb-12">
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-blue-500"></i>
                        Clinical Indication / Notes
                    </h3>
                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed italic">
                            <?= !empty($test['notes']) ? nl2br(htmlspecialchars($test['notes'])) : 'No clinical notes provided with this request.' ?>
                        </p>
                    </div>
                </div>

                <!-- Test Results -->
                <?php if ($test['status'] === 'completed'): ?>
                <div class="animate-slide-in">
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-file-earmark-check text-green-500"></i>
                        Diagnostic Results & Observations
                    </h3>
                    <div class="p-8 rounded-2xl bg-green-50/30 dark:bg-green-900/10 border-2 border-green-100/50 dark:border-green-800/30">
                        <div class="text-slate-800 dark:text-slate-200 font-medium leading-relaxed prose dark:prose-invert max-w-none">
                            <?= nl2br(htmlspecialchars($test['results'] ?? 'Result findings are pending upload.')) ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-hourglass-split text-2xl text-slate-400 animate-spin-slow"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Processing Test</h4>
                    <p class="text-sm text-slate-500">Diagnostic findings will appear here once the lab technician completes the analysis.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Meta & Actions -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-6">
            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[10px] mb-6">Test Metadata</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Priority</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        <?= htmlspecialchars($test['priority'] ?? 'Normal') ?>
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Requested By</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">Dr. <?= htmlspecialchars($test['doctor_name'] ?? 'N/A') ?></span>
                </div>
                <?php if ($test['status'] === 'completed'): ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Completed At</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300"><?= date('M d, H:i', strtotime($test['completed_at'] ?? 'now')) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 space-y-3">
                <?php if ($test['status'] !== 'completed'): ?>
                <a href="/laboratory/<?= $test['id'] ?>/result" class="w-full btn-primary py-3 flex items-center justify-center gap-2">
                    <i class="bi bi-file-earmark-plus"></i>
                    <span>Input Results</span>
                </a>
                <?php endif; ?>
                <button onclick="window.print()" class="w-full btn-secondary py-3 flex items-center justify-center gap-2">
                    <i class="bi bi-printer"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>

        <!-- System Message -->
        <div class="glass-card p-6 border-blue-500/10">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-shield-check text-blue-500"></i>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Secure Report</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed italic">
                This laboratory report is electronically signed and encrypted. Access is restricted to authorized medical personnel only.
            </p>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Test details';
require __DIR__ . '/../layouts/app.php'; 
?>
