<!-- Administrator Dashboard — Full system oversight -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8 items-stretch">
    <!-- Total Patients -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-glow-primary text-white">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <span class="badge badge-primary">Total</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['patients'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Patients</p>
    </div>
    
    <!-- New Patients Today -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-person-plus-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Today</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['patients_today'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">New Patients</p>
    </div>
    
    <!-- Active Admissions -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-glow-accent text-white">
                <i class="bi bi-hospital-fill text-xl"></i>
            </div>
            <span class="badge badge-warning">Active</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active_admissions'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Admissions</p>
    </div>
    
    <!-- Pending Appointments -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-calendar-event-fill text-xl"></i>
            </div>
            <span class="badge badge-primary">Pending</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_appointments'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Appointments</p>
    </div>

    <!-- Total Revenue -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-currency-dollar text-xl"></i>
            </div>
            <span class="badge badge-success">Revenue</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1">$<?= number_format($stats['total_revenue'] ?? 0, 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Total Revenue</p>
    </div>

    <!-- Pending Registrations -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.35s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-person-exclamation text-xl"></i>
            </div>
            <span class="badge badge-danger"><?= ($stats['pending_registrations'] ?? 0) > 0 ? 'Action' : 'Clear' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_registrations'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Pending Users</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Patient Trends Chart -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Patient Trends</h3>
            <div class="inline-flex bg-slate-100 dark:bg-zinc-800 rounded-lg p-1">
                <button class="px-3 py-1 rounded-md bg-white dark:bg-zinc-700 shadow-sm text-xs font-bold text-slate-800 dark:text-white">Week</button>
                <button class="px-3 py-1 rounded-md text-xs font-bold text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-white">Month</button>
            </div>
        </div>
        <div class="flex-1 min-h-[300px] w-full relative">
            <canvas id="patientTrendsChart"></canvas>
        </div>
    </div>
    
    <!-- Department Distribution -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.6s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Department Split</h3>
        <div class="flex-1 min-h-[250px] relative flex items-center justify-center">
            <canvas id="departmentChart"></canvas>
        </div>
    </div>
</div>

<!-- Quick Actions & Team Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-1" style="animation-delay: 0.7s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4 flex-1">
            <a href="/patients/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="bi bi-person-plus text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">New Patient</span>
            </a>
            <a href="/appointments/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center group-hover:bg-success-500 group-hover:text-white transition-colors">
                    <i class="bi bi-calendar-plus text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Schedule</span>
            </a>
            <a href="/billing/create" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center group-hover:bg-warning-500 group-hover:text-white transition-colors">
                    <i class="bi bi-receipt text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Invoice</span>
            </a>
            <a href="/admin/users" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center group-hover:bg-accent-500 group-hover:text-white transition-colors">
                    <i class="bi bi-person-gear text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Manage Users</span>
            </a>
        </div>
    </div>
    
    <!-- Team Overview -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.8s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Hospital Team</h3>
            <a href="/admin/users" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group">
                Manage Team <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 flex-1">
            <?php 
            $roleIcons = [
                'Administrator' => ['icon' => 'bi-shield-lock', 'color' => 'danger'],
                'Doctor' => ['icon' => 'bi-person-badge', 'color' => 'primary'],
                'Nurse' => ['icon' => 'bi-heart-pulse', 'color' => 'accent'],
                'Pharmacist' => ['icon' => 'bi-capsule', 'color' => 'success'],
                'Laboratory' => ['icon' => 'bi-flask', 'color' => 'warning'],
                'Receptionist' => ['icon' => 'bi-headset', 'color' => 'slate']
            ];
            foreach ($staff_stats ?? [] as $sStat): 
                $roleName = $sStat['role_name'];
                $conf = $roleIcons[$roleName] ?? ['icon' => 'bi-person', 'color' => 'primary'];
                $icon = $conf['icon'];
                $color = $conf['color'];
            ?>
            <div class="p-4 rounded-2xl bg-white/40 dark:bg-zinc-800/40 border border-slate-200/50 dark:border-zinc-700/50 flex flex-col items-center gap-3 hover:bg-white dark:hover:bg-zinc-800 transition-colors group shadow-sm">
                <div class="w-12 h-12 bg-<?= $color ?>-100 dark:bg-<?= $color ?>-900/30 rounded-xl flex items-center justify-center text-<?= $color ?>-600 dark:text-<?= $color ?>-400 group-hover:scale-110 transition-transform">
                    <i class="bi <?= $icon ?> text-xl"></i>
                </div>
                <div class="text-center">
                    <p class="text-xl font-black text-slate-900 dark:text-white leading-none mb-1"><?= number_format($sStat['count']) ?></p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-zinc-500"><?= htmlspecialchars($roleName) ?>s</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Recent Activity and Upcoming Appointments -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 items-stretch">
    <!-- Recent Activity -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.9s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-activity text-primary-500"></i> Recent Activity
            </h3>
            <a href="/admin/audit-logs" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
        </div>
        
        <?php if (empty($recent_activity)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-inbox text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No recent activity to show.</p>
        </div>
        <?php else: ?>
        <div class="space-y-4 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 400px;">
            <?php foreach ($recent_activity as $activity): ?>
            <div class="flex items-start gap-4 p-4 rounded-xl bg-white/40 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="bi bi-record-circle text-primary-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm mb-0.5"><?= htmlspecialchars($activity['action']) ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 line-clamp-1"><?= htmlspecialchars($activity['details'] ?? '') ?></p>
                </div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase whitespace-nowrap bg-slate-100 dark:bg-zinc-800 px-2 py-1 rounded-md">
                    <?= \App\Helpers\DateHelper::formatRelativeTime($activity['created_at']) ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Appointments -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 1.0s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-calendar-check text-success-500"></i> Upcoming Appointments
            </h3>
            <a href="/appointments" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View Calendar</a>
        </div>
        
        <?php if (empty($upcoming_appointments)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-calendar-x text-slate-400 dark:text-zinc-500 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No upcoming appointments scheduled.</p>
        </div>
        <?php else: ?>
        <div class="flex-1 overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($upcoming_appointments, 0, 5) as $appointment): ?>
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
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 dark:text-zinc-200 text-sm"><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></span>
                                <span class="text-xs font-medium text-slate-500 dark:text-zinc-400"><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></span>
                            </div>
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
