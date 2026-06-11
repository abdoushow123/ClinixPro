<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Patients Directory</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage and view all registered patient records</p>
    </div>
    <a href="/patients/create" class="btn-primary">
        <i class="bi bi-person-plus-fill text-lg"></i>
        <span>Register New Patient</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($total) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Patients</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <span class="badge badge-success">Active</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['active'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Active Patients</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-calendar-check-fill text-xl"></i>
            </div>
            <span class="badge badge-warning">Today</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['today'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Today's Visits</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-clock-history text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending Reviews</p>
    </div>
</div>

<!-- Search & Filters -->
<div class="glass-panel p-2 mb-8 animate-fade-in flex flex-col md:flex-row gap-2" style="animation-delay: 0.5s;">
    <form method="GET" action="/patients/search" class="flex-1 flex flex-col md:flex-row gap-2">
        <div class="flex-1 relative group">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
            <input type="text" name="q" placeholder="Search by name, ID, email, or phone..." required 
                class="input-modern pl-11 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
        </div>
        <button type="submit" class="btn-primary md:w-auto w-full px-8 rounded-xl shadow-none">
            <span>Search</span>
        </button>
    </form>
</div>

<!-- Patients Directory -->
<div class="glass-panel overflow-hidden animate-fade-in flex flex-col" style="animation-delay: 0.6s;">
    <div class="px-6 py-5 border-b border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-between bg-white/40 dark:bg-zinc-800/40">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">All Patients</h3>
        <span class="badge badge-primary"><?= number_format($total) ?> Records</span>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Patient Profile</th>
                    <th>Patient ID</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-person-x text-4xl text-slate-400 dark:text-zinc-500"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No patients found</h4>
                            <p class="text-slate-500 dark:text-zinc-400 font-medium mb-6">There are currently no patients in the system.</p>
                            <a href="/patients/create" class="btn-primary">Register First Patient</a>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($patients as $patient): ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-sm">
                                <?= strtoupper(substr($patient['first_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white hover:text-primary-600 transition-colors"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></div>
                                <div class="text-xs font-medium text-slate-500 dark:text-zinc-400 flex items-center gap-1">
                                    <i class="bi bi-envelope"></i> <?= htmlspecialchars($patient['email'] ?? 'No email') ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-mono text-xs font-bold bg-slate-100 dark:bg-zinc-800 px-2.5 py-1 rounded-md text-slate-600 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                            <?= htmlspecialchars($patient['patient_id']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="font-semibold text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($patient['date_of_birth'])) ?></span>
                    </td>
                    <td>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?= $patient['gender'] === 'male' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800' : 'bg-accent-50 text-accent-700 dark:bg-accent-900/30 dark:text-accent-400 border border-accent-200 dark:border-accent-800' ?>">
                            <i class="bi bi-<?= $patient['gender'] === 'male' ? 'gender-male' : 'gender-female' ?>"></i>
                            <?= ucfirst($patient['gender']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5">
                            <i class="bi bi-telephone text-slate-400"></i>
                            <?= htmlspecialchars($patient['phone']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 mr-1.5"></span>
                            Active
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/patients/<?= $patient['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/patients/<?= $patient['id'] ?>/edit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-accent-600 hover:bg-accent-50 dark:hover:bg-accent-900/30 rounded-xl transition-all" title="Edit Patient">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="/patients/<?= $patient['id'] ?>/delete" class="inline-block" data-confirm="Are you sure you want to delete this patient?">
                                <input type="hidden" name="_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                                <button type="submit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/30 rounded-xl transition-all" title="Delete Patient">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="px-6 py-4 bg-white/40 dark:bg-zinc-800/40 border-t border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-center gap-2">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="/patients?page=<?= $i ?>" 
            class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all <?= $i === $page ? 'bg-primary-600 text-white shadow-md' : 'text-slate-500 hover:bg-white dark:text-zinc-400 dark:hover:bg-zinc-700' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Patients';
require __DIR__ . '/../layouts/app.php'; 
?>
