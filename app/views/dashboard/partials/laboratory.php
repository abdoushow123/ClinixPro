<!-- Laboratory Dashboard — Lab workflow & test processing -->

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-stretch">
    <!-- Pending Tests -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
            <span class="badge badge-warning"><?= ($stats['pending_tests'] ?? 0) > 0 ? 'Action' : 'Clear' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending_tests'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Pending Tests</p>
    </div>
    
    <!-- In Progress -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-glow-primary text-white">
                <i class="bi bi-gear-wide-connected text-xl"></i>
            </div>
            <span class="badge badge-primary">Active</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['in_progress'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">In Progress</p>
    </div>
    
    <!-- Completed Today -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Today</span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['completed_today'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Completed</p>
    </div>
    
    <!-- Urgent Priority -->
    <div class="glass-panel p-6 animate-slide-up flex flex-col hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-danger-500 to-danger-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                <i class="bi bi-lightning-fill text-xl"></i>
            </div>
            <span class="badge badge-danger"><?= ($stats['urgent_priority'] ?? 0) > 0 ? 'Urgent' : 'None' ?></span>
        </div>
        <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['urgent_priority'] ?? 0) ?></h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Urgent</p>
    </div>
</div>

<!-- Quick Actions & Urgent Tests -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Quick Actions -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col" style="animation-delay: 0.3s;">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-4 flex-1">
            <a href="/laboratory" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="bi bi-pencil-square text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">Enter Lab Result</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Record test results</p>
                </div>
            </a>
            <a href="/laboratory" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center group-hover:bg-warning-500 group-hover:text-white transition-colors">
                    <i class="bi bi-list-task text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">View All Tests</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Browse all lab requests</p>
                </div>
            </a>
            <a href="/laboratory/team" class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/50 hover:bg-white dark:hover:bg-zinc-700 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center group-hover:bg-accent-500 group-hover:text-white transition-colors">
                    <i class="bi bi-people text-xl"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">Lab Team</span>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">View team members</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Urgent Priority Tests -->
    <div class="glass-panel p-6 animate-fade-in flex flex-col lg:col-span-2" style="animation-delay: 0.35s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-lightning-fill text-danger-500"></i> Urgent Priority Tests
            </h3>
        </div>
        
        <?php if (empty($urgent_tests)): ?>
        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-success-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">No urgent tests at this time.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 flex-1 overflow-y-auto pr-2 scrollbar-custom" style="max-height: 350px;">
            <?php foreach ($urgent_tests as $test): ?>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-danger-50/50 dark:bg-danger-900/10 border border-danger-200/50 dark:border-danger-800/30 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-danger-100 dark:bg-danger-900/30 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                    <i class="bi bi-lightning-fill text-danger-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white font-bold text-sm truncate"><?= htmlspecialchars($test['test_name'] ?? 'Lab Test') ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">
                        <?= htmlspecialchars(($test['patient_first_name'] ?? '') . ' ' . ($test['patient_last_name'] ?? '')) ?>
                        · Dr. <?= htmlspecialchars(($test['doctor_first_name'] ?? '') . ' ' . ($test['doctor_last_name'] ?? '')) ?>
                    </p>
                </div>
                <?php
                $testStatusClass = match($test['status'] ?? '') {
                    'in_progress' => 'badge-primary',
                    default => 'badge-danger'
                };
                ?>
                <span class="badge <?= $testStatusClass ?>"><?= ucfirst(str_replace('_', ' ', $test['status'] ?? 'Pending')) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Test Requests Queue -->
<div class="glass-panel p-6 animate-fade-in mb-8" style="animation-delay: 0.4s;">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-flask text-warning-500"></i> Pending Test Requests
        </h3>
        <a href="/laboratory" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 flex items-center gap-1 group">
            View All <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
    
    <?php if (empty($pending_tests)): ?>
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4 mx-auto">
            <i class="bi bi-check-circle text-success-400 text-2xl"></i>
        </div>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">All test requests have been processed.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_tests as $test): ?>
                <tr class="<?= ($test['priority'] ?? '') === 'urgent' ? 'bg-danger-50/30 dark:bg-danger-900/10' : '' ?>">
                    <td>
                        <span class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($test['test_name'] ?? 'N/A') ?></span>
                        <p class="text-xs text-slate-500 dark:text-zinc-400"><?= htmlspecialchars($test['test_code'] ?? '') ?></p>
                    </td>
                    <td>
                        <span class="text-sm font-medium text-slate-700 dark:text-zinc-300"><?= htmlspecialchars(($test['patient_first_name'] ?? '') . ' ' . ($test['patient_last_name'] ?? '')) ?></span>
                    </td>
                    <td>
                        <span class="text-sm text-slate-600 dark:text-zinc-400">Dr. <?= htmlspecialchars(($test['doctor_first_name'] ?? '') . ' ' . ($test['doctor_last_name'] ?? '')) ?></span>
                    </td>
                    <td>
                        <span class="text-sm text-slate-600 dark:text-zinc-400"><?= htmlspecialchars(ucfirst($test['category'] ?? 'General')) ?></span>
                    </td>
                    <td>
                        <?php if (($test['priority'] ?? '') === 'urgent'): ?>
                            <span class="badge badge-danger"><i class="bi bi-lightning-fill mr-1"></i>Urgent</span>
                        <?php else: ?>
                            <span class="badge badge-primary">Normal</span>
                        <?php endif; ?>
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
