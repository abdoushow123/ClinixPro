<!-- Pharmacist Dashboard — Medication management -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <!-- Pending Prescriptions -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-prescription2 text-xl"></i>
            </div>
            <span class="badge badge-warning"><?= ($stats['pending_prescriptions'] ?? 0) > 0 ? 'Action' : 'Clear' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_prescriptions'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Pending Rx</p>
    </div>
    
    <!-- Dispensed Today -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Today</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['dispensed_today'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Dispensed</p>
    </div>
    
    <!-- Low Stock -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-danger-500 to-danger-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            </div>
            <span class="badge badge-danger"><?= ($stats['low_stock'] ?? 0) > 0 ? 'Alert' : 'OK' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['low_stock'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Low Stock</p>
    </div>
    
    <!-- Expiring Soon -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
            <span class="badge badge-warning">30 days</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['expiring_soon'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Expiring Soon</p>
    </div>
</div>

<!-- Quick Actions & Pending Prescriptions Queue -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.3s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-4 flex-1">
            <a href="/prescriptions" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center group-hover:bg-warning-500 group-hover:text-white transition-colors">
                    <i class="bi bi-prescription2 text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">Dispense Prescription</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Process pending prescriptions</p>
                </div>
            </a>
            <a href="/pharmacy" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center group-hover:bg-success-500 group-hover:text-white transition-colors">
                    <i class="bi bi-box-seam text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">Add Inventory Stock</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Update medication stock levels</p>
                </div>
            </a>
            <a href="/pharmacy" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="bi bi-list-check text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">View Inventory</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Full medication inventory</p>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Pending Prescriptions Queue -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-prescription2 text-warning-500"></i> Pending Prescriptions Queue
            </h3>
            <a href="/prescriptions" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group">
                View All <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <?php if (empty($pending_prescriptions)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-success-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">All prescriptions have been dispensed.</p>
        </div>
        <?php else: ?>
        <div class="flex-1 overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_prescriptions as $rx): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-warning-500 to-warning-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    <?= strtoupper(substr($rx['patient_first_name'] ?? 'N', 0, 1)) ?>
                                </div>
                                <span class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars(($rx['patient_first_name'] ?? '') . ' ' . ($rx['patient_last_name'] ?? '')) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm font-medium text-slate-600 dark:text-zinc-400">Dr. <?= htmlspecialchars(($rx['doctor_first_name'] ?? '') . ' ' . ($rx['doctor_last_name'] ?? '')) ?></span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600 dark:text-zinc-400"><?= date('M d, Y', strtotime($rx['prescription_date'])) ?></span>
                        </td>
                        <td>
                            <span class="badge badge-warning">Dispense</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Low Stock & Expiring Medications -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 items-stretch">
    <!-- Low Stock Alerts -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-danger-500"></i> Low Stock Alerts
            </h3>
            <a href="/pharmacy" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View Inventory</a>
        </div>
        
        <?php if (empty($low_stock_medications)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-success-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">All medications are well stocked.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach ($low_stock_medications as $med): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-danger-50/50 dark:bg-danger-900/10 border border-danger-200/50 dark:border-danger-800/30 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-danger-100 dark:bg-danger-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-capsule text-danger-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars($med['medication_name'] ?? '') ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">Stock: <?= number_format($med['current_stock'] ?? 0) ?> / Min: <?= number_format($med['minimum_stock_level'] ?? 0) ?></p>
                </div>
                <span class="badge badge-danger">Low</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Expiring Medications -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.6s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-clock-history text-amber-500"></i> Expiring Medications
            </h3>
            <a href="/pharmacy" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($expiring_medications)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-success-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No medications expiring within 30 days.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach ($expiring_medications as $med): 
                $daysLeft = max(0, (int) ((strtotime($med['expiry_date']) - time()) / 86400));
                $urgency = $daysLeft <= 7 ? 'danger' : ($daysLeft <= 14 ? 'warning' : 'amber');
            ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-<?= $urgency ?>-100 dark:bg-<?= $urgency ?>-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-clock text-<?= $urgency ?>-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars($med['medication_name'] ?? '') ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">Expires: <?= date('M d, Y', strtotime($med['expiry_date'])) ?></p>
                </div>
                <span class="badge badge-<?= $urgency ?>"><?= $daysLeft ?> days</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
