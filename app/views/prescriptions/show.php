<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Prescription Order</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Viewing medication dosage and patient instructions</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <button onclick="window.print()" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-printer"></i>
            <span>Print Rx</span>
        </button>
        <a href="/prescriptions" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Orders</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Prescription Content -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="bi bi-prescription2 text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($prescription['medication'] ?? 'Prescription') ?></h2>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest <?= $prescription['status'] === 'dispensed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' ?>">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <?= htmlspecialchars($prescription['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Patient Details</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($prescription['patient_name'] ?? 'N/A') ?></p>
                        <p class="text-sm text-slate-500 italic">Patient ID: <?= htmlspecialchars($prescription['patient_id'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Issue Date</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white"><?= date('F d, Y', strtotime($prescription['prescription_date'])) ?></p>
                        <p class="text-xs text-slate-500 italic"><?= \App\Helpers\DateHelper::formatRelativeTime($prescription['prescription_date']) ?></p>
                    </div>
                </div>

                <!-- Dosage Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                        <p class="text-[10px] font-black uppercase text-blue-500 mb-1">Dosage</p>
                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($prescription['dosage']) ?></p>
                    </div>
                    <div class="p-4 rounded-2xl bg-green-50/50 dark:bg-green-900/10 border border-green-100 dark:border-green-800">
                        <p class="text-[10px] font-black uppercase text-green-500 mb-1">Frequency</p>
                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($prescription['frequency']) ?></p>
                    </div>
                    <div class="p-4 rounded-2xl bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800">
                        <p class="text-[10px] font-black uppercase text-purple-500 mb-1">Duration</p>
                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($prescription['duration']) ?></p>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mb-12">
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-500"></i>
                        Patient Usage Instructions
                    </h3>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                            <?= !empty($prescription['instructions']) ? nl2br(htmlspecialchars($prescription['instructions'])) : 'No specific patient instructions provided.' ?>
                        </p>
                    </div>
                </div>

                <!-- Pharmacist Notes -->
                <?php if (!empty($prescription['notes'])): ?>
                <div>
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-square-dots text-amber-500"></i>
                        Pharmacist Notes
                    </h3>
                    <div class="p-6 rounded-3xl bg-amber-50/20 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800">
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed italic text-sm">
                            <?= nl2br(htmlspecialchars($prescription['notes'])) ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Meta & Actions -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-6">
            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[10px] mb-6">Order Metadata</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Prescribed By</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">Dr. <?= htmlspecialchars($prescription['doctor_name'] ?? 'N/A') ?></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">License #</span>
                    <span class="font-mono text-xs text-slate-500"><?= htmlspecialchars($prescription['doctor_license'] ?? 'N/A') ?></span>
                </div>
                <?php if ($prescription['status'] === 'dispensed'): ?>
                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-bold">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Dispensed by Pharmacy</span>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1"><?= date('M d, Y H:i', strtotime($prescription['dispensed_at'] ?? 'now')) ?></p>
                </div>
                <?php else: ?>
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <form method="POST" action="/prescriptions/<?= $prescription['id'] ?>/dispense" class="w-full" data-confirm="Confirm that medication has been dispensed to the patient?">
                        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="w-full btn-primary py-4 flex items-center justify-center gap-2 shadow-blue-500/20 shadow-lg">
                            <i class="bi bi-check2-all"></i>
                            <span>Mark as Dispensed</span>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Security Info -->
        <div class="glass-card p-6 border-red-500/10">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-exclamation-triangle text-red-500"></i>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Controlled Substance</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed italic">
                Verify identity and log dispensing in the narcotic registry if applicable. This Rx is valid for 30 days from date of issue.
            </p>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Prescription details';
require __DIR__ . '/../layouts/app.php'; 
?>
