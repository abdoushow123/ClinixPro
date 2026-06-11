<!-- Doctor Dashboard — Clinical workflow -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <!-- My Patients Today -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-glow-primary text-white">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <span class="badge badge-primary">Today</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['my_patients_today'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">My Patients</p>
    </div>
    
    <!-- Pending Appointments -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-calendar-event-fill text-xl"></i>
            </div>
            <span class="badge badge-warning">Pending</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_appointments'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Appointments</p>
    </div>
    
    <!-- Active Prescriptions -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-glow-accent text-white">
                <i class="bi bi-prescription2 text-xl"></i>
            </div>
            <span class="badge badge-accent">Active</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active_prescriptions'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Prescriptions</p>
    </div>
    
    <!-- Pending Lab Results -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-flask text-xl"></i>
            </div>
            <span class="badge badge-success">Lab</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_lab_results'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Pending Results</p>
    </div>
</div>

<!-- Quick Actions & Today's Appointments -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-1" style="animation-delay: 0.3s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4 flex-1">
            <a href="/medical-records/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="bi bi-file-medical text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">New Record</span>
            </a>
            <a href="/prescriptions/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center group-hover:bg-accent-500 group-hover:text-white transition-colors">
                    <i class="bi bi-prescription2 text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Prescription</span>
            </a>
            <a href="/laboratory/request" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center group-hover:bg-success-500 group-hover:text-white transition-colors">
                    <i class="bi bi-flask text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Lab Test</span>
            </a>
            <a href="/hospitalizations/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center group-hover:bg-warning-500 group-hover:text-white transition-colors">
                    <i class="bi bi-hospital text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Admit Patient</span>
            </a>
        </div>
    </div>
    
    <!-- Today's Appointments -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-calendar-check text-primary-500"></i> Today's Appointments
            </h3>
            <a href="/appointments" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group">
                View All <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <?php if (empty($today_appointments)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-calendar-x text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No appointments scheduled for today.</p>
        </div>
        <?php else: ?>
        <div class="flex-1 overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($today_appointments, 0, 8) as $appointment): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    <?= strtoupper(substr($appointment['patient_first_name'] ?? 'N', 0, 1)) ?>
                                </div>
                                <span class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars(($appointment['patient_first_name'] ?? '') . ' ' . ($appointment['patient_last_name'] ?? '')) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="font-bold text-slate-800 dark:text-zinc-200 text-sm"><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></span>
                        </td>
                        <td>
                            <span class="text-sm font-medium text-slate-600 dark:text-zinc-400"><?= htmlspecialchars($appointment['appointment_type'] ?? 'General') ?></span>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($appointment['status'] ?? '') {
                                'confirmed' => 'badge-success',
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default => 'badge-warning'
                            };
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($appointment['status'] ?? 'Pending')) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Prescriptions & Pending Lab Results -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 items-stretch">
    <!-- Recent Prescriptions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-prescription2 text-accent-500"></i> Recent Prescriptions
            </h3>
            <a href="/prescriptions" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($recent_prescriptions)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-prescription2 text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No recent prescriptions.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach (array_slice($recent_prescriptions, 0, 6) as $rx): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-accent-100 dark:bg-accent-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-capsule text-accent-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars(($rx['patient_first_name'] ?? '') . ' ' . ($rx['patient_last_name'] ?? '')) ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400"><?= date('M d, Y', strtotime($rx['prescription_date'])) ?></p>
                </div>
                <?php
                $rxClass = match($rx['status'] ?? '') {
                    'dispensed' => 'badge-success',
                    'cancelled' => 'badge-danger',
                    default => 'badge-warning'
                };
                ?>
                <span class="badge <?= $rxClass ?>"><?= ucfirst($rx['status'] ?? 'Pending') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Pending Lab Results -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.6s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-flask text-success-500"></i> Pending Lab Results
            </h3>
            <a href="/laboratory" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($pending_lab_requests)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-flask text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No pending lab results.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach (array_slice($pending_lab_requests, 0, 6) as $lab): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 <?= ($lab['priority'] ?? '') === 'urgent' ? 'bg-danger-100 dark:bg-danger-900/30' : 'bg-success-100 dark:bg-success-900/30' ?> rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-droplet-half <?= ($lab['priority'] ?? '') === 'urgent' ? 'text-danger-500' : 'text-success-500' ?>"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars($lab['test_name'] ?? 'Lab Test') ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400"><?= htmlspecialchars(($lab['patient_first_name'] ?? '') . ' ' . ($lab['patient_last_name'] ?? '')) ?></p>
                </div>
                <?php
                $labClass = match($lab['status'] ?? '') {
                    'in_progress' => 'badge-primary',
                    'completed' => 'badge-success',
                    default => 'badge-warning'
                };
                ?>
                <span class="badge <?= $labClass ?>"><?= ucfirst(str_replace('_', ' ', $lab['status'] ?? 'Pending')) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
