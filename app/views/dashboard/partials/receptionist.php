<!-- Receptionist Dashboard — Front desk operations -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <!-- Today's Appointments -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-glow-primary text-white">
                <i class="bi bi-calendar-check-fill text-xl"></i>
            </div>
            <span class="badge badge-primary">Today</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['today_appointments'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Appointments</p>
    </div>
    
    <!-- Patients Registered Today -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-person-plus-fill text-xl"></i>
            </div>
            <span class="badge badge-success">New</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['patients_registered_today'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Registered Today</p>
    </div>
    
    <!-- Pending Invoices -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-receipt text-xl"></i>
            </div>
            <span class="badge badge-warning"><?= ($stats['pending_invoices'] ?? 0) > 0 ? 'Action' : 'Clear' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_invoices'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Pending Invoices</p>
    </div>
    
    <!-- Rooms Available -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-glow-accent text-white">
                <i class="bi bi-door-open text-xl"></i>
            </div>
            <span class="badge badge-accent">Rooms</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['rooms_available'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Available</p>
    </div>
</div>

<!-- Quick Actions & Today's Appointment Queue -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.3s;">
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
            <a href="/insurance" class="flex flex-col items-center justify-center gap-3 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center group-hover:bg-accent-500 group-hover:text-white transition-colors">
                    <i class="bi bi-shield-check text-xl"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 text-center">Insurance</span>
            </a>
        </div>
    </div>
    
    <!-- Today's Appointment Queue -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-calendar-check text-primary-500"></i> Today's Appointment Queue
            </h3>
            <a href="/appointments" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group">
                View All <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <?php if (empty($today_appointments_list)): ?>
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
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($today_appointments_list as $appointment): ?>
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
                            <span class="text-sm font-medium text-slate-600 dark:text-zinc-400">Dr. <?= htmlspecialchars(($appointment['doctor_first_name'] ?? '') . ' ' . ($appointment['doctor_last_name'] ?? '')) ?></span>
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

<!-- Pending Invoices -->
<div class="glass-panel p-6 animate-fade-in mb-8" style="animation-delay: 0.5s;">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-receipt text-warning-500"></i> Pending Invoices
        </h3>
        <a href="/billing" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700">View All</a>
    </div>
    
    <?php if (empty($pending_invoices)): ?>
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4 mx-auto">
            <i class="bi bi-check-circle text-success-400 text-2xl"></i>
        </div>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">All invoices have been processed.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Patient</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_invoices as $invoice): ?>
                <tr>
                    <td>
                        <span class="font-bold text-primary-600 dark:text-primary-400 text-sm"><?= htmlspecialchars($invoice['invoice_number'] ?? 'N/A') ?></span>
                    </td>
                    <td>
                        <span class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars(($invoice['patient_first_name'] ?? '') . ' ' . ($invoice['patient_last_name'] ?? '')) ?></span>
                    </td>
                    <td>
                        <span class="font-black text-slate-900 dark:text-white text-sm">$<?= number_format($invoice['total_amount'] ?? 0, 2) ?></span>
                    </td>
                    <td>
                        <span class="text-sm text-slate-600 dark:text-zinc-400"><?= date('M d, Y', strtotime($invoice['invoice_date'])) ?></span>
                    </td>
                    <td>
                        <span class="badge badge-warning">Pending</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
