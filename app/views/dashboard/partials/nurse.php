<!-- Nurse Dashboard — Patient care & hospitalizations -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <!-- Assigned Patients -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-glow-accent text-white">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <span class="badge badge-accent">Active</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['assigned_patients'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Assigned Patients</p>
    </div>
    
    <!-- Active Admissions -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-glow-primary text-white">
                <i class="bi bi-hospital-fill text-xl"></i>
            </div>
            <span class="badge badge-primary">Rooms</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active_admissions'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Occupied Rooms</p>
    </div>
    
    <!-- Prescriptions to Administer -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-prescription2 text-xl"></i>
            </div>
            <span class="badge badge-warning"><?= ($stats['prescriptions_to_administer'] ?? 0) > 0 ? 'Action' : 'Clear' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['prescriptions_to_administer'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Med Rounds</p>
    </div>
    
    <!-- Available Beds -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-door-open text-xl"></i>
            </div>
            <span class="badge badge-success">Available</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['available_beds'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Beds Open</p>
    </div>
</div>

<!-- Quick Actions & Ward Occupancy -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.3s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4 flex-1">
            <a href="/patients" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="bi bi-file-medical text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Patient Records</span>
            </a>
            <a href="/prescriptions" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center group-hover:bg-accent-500 group-hover:text-white transition-colors">
                    <i class="bi bi-prescription2 text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Prescriptions</span>
            </a>
            <a href="/hospitalizations" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center group-hover:bg-warning-500 group-hover:text-white transition-colors">
                    <i class="bi bi-hospital text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Hospitalizations</span>
            </a>
            <a href="/rooms" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center group-hover:bg-success-500 group-hover:text-white transition-colors">
                    <i class="bi bi-door-open text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Room Status</span>
            </a>
        </div>
    </div>

    <!-- Ward Occupancy Chart -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.35s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Ward Occupancy</h3>
        <?php if (empty($room_occupancy)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-bar-chart text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No ward data available.</p>
        </div>
        <?php else: ?>
        <div class="space-y-4 flex-1">
            <?php foreach ($room_occupancy as $ward): 
                $total = (int)($ward['total_beds'] ?? 0);
                $occupied = (int)($ward['occupied_beds'] ?? 0);
                $pct = $total > 0 ? round(($occupied / $total) * 100) : 0;
                $barColor = $pct >= 90 ? 'bg-danger-500' : ($pct >= 70 ? 'bg-warning-500' : 'bg-success-500');
            ?>
            <div class="p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-sm text-slate-900 dark:text-white capitalize"><?= htmlspecialchars($ward['room_type'] ?? 'Ward') ?></span>
                    <span class="text-xs font-bold text-slate-500 dark:text-zinc-400"><?= $occupied ?>/<?= $total ?> beds</span>
                </div>
                <div class="w-full h-3 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                    <div class="h-full <?= $barColor ?> rounded-full transition-all duration-500" style="width: <?= $pct ?>%"></div>
                </div>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 mt-1"><?= $pct ?>% occupied · <?= $ward['total_rooms'] ?? 0 ?> rooms</p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Current Hospitalizations & Medication Rounds -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 items-stretch">
    <!-- Current Hospitalizations -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-hospital text-accent-500"></i> Current Hospitalizations
            </h3>
            <a href="/hospitalizations" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($active_hospitalizations)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-hospital text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No active hospitalizations.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach ($active_hospitalizations as $hosp): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-accent-100 dark:bg-accent-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-person-fill text-accent-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars(($hosp['patient_first_name'] ?? '') . ' ' . ($hosp['patient_last_name'] ?? '')) ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">Room <?= htmlspecialchars($hosp['room_number'] ?? 'N/A') ?> · <?= ucfirst($hosp['room_type'] ?? '') ?></p>
                </div>
                <span class="badge badge-accent">Admitted</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Medication Rounds -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-capsule text-warning-500"></i> Medication Rounds
            </h3>
            <a href="/prescriptions" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($pending_prescriptions)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-success-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">All medication rounds completed.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach ($pending_prescriptions as $rx): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-warning-100 dark:bg-warning-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-prescription2 text-warning-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars(($rx['patient_first_name'] ?? '') . ' ' . ($rx['patient_last_name'] ?? '')) ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">Dr. <?= htmlspecialchars(($rx['doctor_first_name'] ?? '') . ' ' . ($rx['doctor_last_name'] ?? '')) ?></p>
                </div>
                <span class="badge badge-warning">Administer</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
