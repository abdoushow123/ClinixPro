<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Billing & Invoices</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Manage patient billing, invoices, and payment tracking</p>
    </div>
    <a href="/billing/create" class="btn-primary inline-flex items-center gap-2">
        <i class="bi bi-receipt-cutoff text-lg"></i>
        <span>New Invoice</span>
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-receipt text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['total'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Invoices</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-currency-dollar text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= \App\Helpers\CurrencyHelper::format($stats['revenue'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Total Revenue</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Pending</p>
    </div>
    <div class="glass-panel p-6 flex flex-col hover:-translate-y-1 transition-transform duration-300 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl flex items-center justify-center shadow-sm">
                <i class="bi bi-exclamation-octagon text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?= number_format($stats['overdue'] ?? 0) ?></h3>
        <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Overdue</p>
    </div>
</div>

<!-- Invoices Directory -->
<div class="glass-panel overflow-hidden animate-fade-in" style="animation-delay: 0.5s;">
    <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-900 dark:text-white">Recent Invoices</h3>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary-500"></i>
                <input type="text" placeholder="Search invoices..." class="input-modern h-10 pl-9 text-sm w-48 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
            </div>
            <select class="input-modern h-10 text-sm w-32 px-3 border-none shadow-none bg-transparent hover:bg-slate-50 dark:hover:bg-zinc-800/50">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>
    </div>
    
    <div class="table-wrapper border-0 shadow-none rounded-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <i class="bi bi-receipt text-4xl text-slate-400 dark:text-zinc-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Invoices Found</h4>
                        <p class="text-slate-500 dark:text-zinc-400 font-medium">There are currently no invoices in the system.</p>
                        <a href="/billing/create" class="mt-4 inline-block font-semibold text-primary-600 hover:text-primary-500">Create your first invoice</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach (($invoices ?? []) as $invoice): ?>
                <tr>
                    <td>
                        <span class="font-mono text-sm font-bold text-primary-600 dark:text-primary-400">#<?= htmlspecialchars($invoice['invoice_number']) ?></span>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold shadow-sm border border-slate-200 dark:border-zinc-700">
                                <?= strtoupper(substr($invoice['patient_name'] ?? 'N', 0, 1)) ?>
                            </div>
                            <div class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($invoice['patient_name'] ?? 'N/A') ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium text-slate-700 dark:text-zinc-300"><?= date('M d, Y', strtotime($invoice['invoice_date'])) ?></div>
                        <div class="text-[10px] uppercase font-bold tracking-wider mt-0.5 <?= strtotime($invoice['due_date']) < time() && $invoice['status'] !== 'paid' ? 'text-danger-500' : 'text-slate-500 dark:text-zinc-500' ?>">Due: <?= date('M d, Y', strtotime($invoice['due_date'])) ?></div>
                    </td>
                    <td>
                        <div class="font-black text-lg text-slate-900 dark:text-white"><?= \App\Helpers\CurrencyHelper::format($invoice['amount']) ?></div>
                    </td>
                    <td>
                        <span class="badge <?= getInvoiceBadgeClass($invoice['status']) ?>">
                            <?= htmlspecialchars($invoice['status']) ?>
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/billing/<?= $invoice['id'] ?>" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all" title="View">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/billing/<?= $invoice['id'] ?>/edit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-xl transition-all" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="/billing/<?= $invoice['id'] ?>/print" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-success-600 hover:bg-success-50 dark:hover:bg-success-900/30 rounded-xl transition-all" title="Print">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($pages) && $pages > 1): ?>
    <div class="px-6 py-4 bg-white/40 dark:bg-zinc-800/40 border-t border-slate-200/60 dark:border-zinc-700/50 flex items-center justify-center gap-2">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="/billing?page=<?= $i ?>" 
            class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all <?= (isset($page) && $i === $page) ? 'bg-primary-600 text-white shadow-md' : 'text-slate-500 hover:bg-white dark:text-zinc-400 dark:hover:bg-zinc-700' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php
if (!function_exists('getInvoiceBadgeClass')) {
    function getInvoiceBadgeClass($status) {
        $classes = [
            'paid' => 'badge-success',
            'pending' => 'badge-warning',
            'overdue' => 'badge-danger',
            'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-slate-400 border border-slate-200 dark:border-zinc-700'
        ];
        return $classes[$status] ?? 'badge-primary';
    }
}
?>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Billing & Invoices';
require __DIR__ . '/../layouts/app.php'; 
?>
