<?php ob_start(); ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">Invoice Details</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">View and manage invoice #<?= htmlspecialchars($invoice['invoice_number']) ?></p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="/billing/<?= $invoice['id'] ?>/print" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-printer"></i>
            <span>Print</span>
        </a>
        <a href="/billing/<?= $invoice['id'] ?>/edit" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-pencil"></i>
            <span>Edit</span>
        </a>
        <a href="/billing" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Billing</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Invoice Content -->
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="bi bi-receipt-cutoff text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Invoice #<?= htmlspecialchars($invoice['invoice_number']) ?></h2>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest <?= getInvoiceBadgeClass($invoice['status']) ?>">
                                <?= htmlspecialchars($invoice['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Total Amount Due</p>
                        <p class="text-4xl font-black text-blue-600 dark:text-blue-400"><?= \App\Helpers\CurrencyHelper::format($invoice['total_amount'] ?? $invoice['amount'] ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Patient Details</p>
                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($invoice['patient_name'] ?? 'N/A') ?></p>
                        <p class="text-sm text-slate-500 italic">ID: <?= htmlspecialchars($invoice['patient_id'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Invoice Date</p>
                        <p class="font-bold text-slate-900 dark:text-white"><?= date('F d, Y', strtotime($invoice['invoice_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Due Date</p>
                        <p class="font-bold text-red-500"><?= date('F d, Y', strtotime($invoice['due_date'])) ?></p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-12">
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white mb-4">Billing Items</h3>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Description</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 text-center">Qty</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 text-right">Unit Price</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                                <?php if (empty($items)): ?>
                                <tr>
                                    <td class="px-6 py-4 text-slate-500 italic"><?= htmlspecialchars($invoice['description'] ?? 'General Consultation / Services') ?></td>
                                    <td class="px-6 py-4 text-center">1</td>
                                    <td class="px-6 py-4 text-right"><?= \App\Helpers\CurrencyHelper::format($invoice['subtotal'] ?? $invoice['amount'] ?? 0) ?></td>
                                    <td class="px-6 py-4 text-right font-bold"><?= \App\Helpers\CurrencyHelper::format($invoice['subtotal'] ?? $invoice['amount'] ?? 0) ?></td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($item['description']) ?></td>
                                    <td class="px-6 py-4 text-center"><?= $item['quantity'] ?></td>
                                    <td class="px-6 py-4 text-right"><?= \App\Helpers\CurrencyHelper::format($item['unit_price']) ?></td>
                                    <td class="px-6 py-4 text-right font-bold"><?= \App\Helpers\CurrencyHelper::format($item['total_price']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="flex justify-end">
                    <div class="w-full md:w-80 space-y-3 bg-slate-50 dark:bg-slate-800/30 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= \App\Helpers\CurrencyHelper::format($invoice['subtotal'] ?? $invoice['amount'] ?? 0) ?></span>
                        </div>
                        <?php if (!empty($invoice['tax_amount'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Tax</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= \App\Helpers\CurrencyHelper::format($invoice['tax_amount']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($invoice['discount_amount'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Discount</span>
                            <span class="font-bold text-green-600">-<?= \App\Helpers\CurrencyHelper::format($invoice['discount_amount']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between pt-3 border-t border-slate-200 dark:border-slate-700">
                            <span class="font-black uppercase text-xs text-slate-900 dark:text-white">Total</span>
                            <span class="font-black text-xl text-blue-600 dark:text-blue-400"><?= \App\Helpers\CurrencyHelper::format($invoice['total_amount'] ?? $invoice['amount'] ?? 0) ?></span>
                        </div>
                        <div class="flex justify-between pt-2">
                            <span class="text-slate-500 text-xs italic">Paid Amount</span>
                            <span class="font-bold text-sm text-slate-700 dark:text-slate-300"><?= \App\Helpers\CurrencyHelper::format($invoice['paid_amount'] ?? 0) ?></span>
                        </div>
                        <div class="flex justify-between pt-2">
                            <span class="text-slate-500 text-xs italic">Balance Due</span>
                            <span class="font-black text-sm text-red-500"><?= \App\Helpers\CurrencyHelper::format($invoice['balance_amount'] ?? ($invoice['total_amount'] ?? $invoice['amount'] ?? 0)) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($invoice['notes'])): ?>
            <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 text-center">Invoice Notes</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 text-center italic"><?= htmlspecialchars($invoice['notes']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Quick Actions -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-card p-6">
            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-xs mb-6">Payment Status</h3>
            
            <div class="space-y-4">
                <?php if (($invoice['balance_amount'] ?? 1) > 0): ?>
                <a href="/billing/<?= $invoice['id'] ?>/payment" class="w-full btn-primary py-4 flex items-center justify-center gap-3 shadow-blue-500/20 shadow-lg">
                    <i class="bi bi-credit-card-2-back"></i>
                    <span>Record Payment</span>
                </a>
                <?php else: ?>
                <div class="w-full bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl py-4 flex flex-col items-center gap-1">
                    <i class="bi bi-check-circle-fill text-green-500 text-2xl"></i>
                    <span class="font-black text-green-700 dark:text-green-300 uppercase tracking-widest text-[10px]">Fully Paid</span>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-3">
                    <a href="/billing/<?= $invoice['id'] ?>/print" class="btn-secondary py-3 flex items-center justify-center gap-2 text-xs">
                        <i class="bi bi-printer"></i>
                        <span>Print PDF</span>
                    </a>
                    <a href="/billing/<?= $invoice['id'] ?>/edit" class="btn-secondary py-3 flex items-center justify-center gap-2 text-xs">
                        <i class="bi bi-pencil"></i>
                        <span>Edit Info</span>
                    </a>
                </div>

                <a href="/patients/<?= $invoice['patient_id'] ?>" class="w-full btn-secondary py-3 flex items-center justify-center gap-2 text-xs">
                    <i class="bi bi-person-bounding-box"></i>
                    <span>View Patient Record</span>
                </a>
            </div>
        </div>

        <!-- Billing Support info -->
        <div class="glass-card p-6 border-blue-500/10">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-info-circle text-blue-500"></i>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Billing Information</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed italic">
                This invoice was generated by <?= htmlspecialchars($sidebarUser['displayName'] ?? 'System') ?>. 
                For disputes or adjustments, please contact the billing department.
            </p>
        </div>
    </div>
</div>

<?php
function getInvoiceBadgeClass($status) {
    $classes = [
        'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400'
    ];
    return $classes[$status] ?? 'bg-blue-100 text-blue-700';
}
?>

<?php $content = ob_get_clean(); ?>

<?php 
$displayTitle = 'Invoice details';
require __DIR__ . '/../layouts/app.php'; 
?>
