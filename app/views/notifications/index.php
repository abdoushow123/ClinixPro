<?php ob_start(); ?>
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Notifications Center</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium">Stay updated with your activity and alerts</p>
    </div>
    <div class="flex gap-3">
        <button onclick="markAllNotificationsRead()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-zinc-300 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-300 hover:scale-105 active:scale-95">
            <i class="bi bi-check2-all text-lg"></i>
            Mark All Read
        </button>
    </div>
</div>

<!-- Notifications List -->
<div class="glass-panel overflow-hidden animate-slide-up">
    <?php if (empty($notifications)): ?>
        <div class="p-16 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-zinc-800 dark:to-zinc-900 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="bi bi-bell-slash text-4xl text-slate-400 dark:text-zinc-500"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Notifications</h3>
            <p class="text-slate-500 dark:text-zinc-400 font-medium">You're all caught up! No new notifications.</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-slate-100 dark:divide-zinc-800/50">
            <?php foreach ($notifications as $notif): ?>
                <div class="p-6 transition-all duration-300 <?= !$notif['is_read'] ? 'bg-primary-50/50 dark:bg-primary-900/10 border-l-4 border-l-primary-500' : 'hover:bg-slate-50 dark:hover:bg-zinc-800/30 border-l-4 border-l-transparent' ?>" id="notification-row-<?= $notif['id'] ?>">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <?php
                                $iconMap = [
                                    'info' => '<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/20"><i class="bi bi-info-lg text-white text-xl"></i></div>',
                                    'success' => '<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center shadow-lg shadow-success-500/20"><i class="bi bi-check-lg text-white text-xl"></i></div>',
                                    'warning' => '<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center shadow-lg shadow-warning-500/20"><i class="bi bi-exclamation-triangle text-white text-xl"></i></div>',
                                    'danger' => '<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-danger-500 to-danger-600 flex items-center justify-center shadow-lg shadow-danger-500/20"><i class="bi bi-x-lg text-white text-xl"></i></div>'
                                ];
                                echo $iconMap[$notif['type']] ?? $iconMap['info'];
                            ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                        <?= htmlspecialchars($notif['title']) ?>
                                        <?php if (!$notif['is_read']): ?>
                                            <span class="w-2.5 h-2.5 bg-primary-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.6)]" id="notification-dot-<?= $notif['id'] ?>"></span>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="text-sm text-slate-600 dark:text-zinc-400 mt-1.5 leading-relaxed"><?= htmlspecialchars($notif['message']) ?></p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider bg-slate-100 dark:bg-zinc-800 px-3 py-1.5 rounded-lg">
                                        <?= date('M j, g:i a', strtotime($notif['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <?php if ($notif['reference_url']): ?>
                                    <a href="<?= $notif['reference_url'] ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-bold text-primary-600 dark:text-primary-400 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 transition-colors" onclick="markNotificationReadFull(<?= $notif['id'] ?>)">
                                        <i class="bi bi-arrow-right"></i>
                                        View Details
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!$notif['is_read']): ?>
                                    <button onclick="markNotificationReadFull(<?= $notif['id'] ?>)" id="notification-mark-<?= $notif['id'] ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-bold text-slate-600 dark:text-zinc-400 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-colors">
                                        <i class="bi bi-check2"></i>
                                        Mark as Read
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>

<?php 
$hasSidebar = true;
$sidebarUser = $user ?? [];
$displayName = $user['full_name'] ?? 'User';
$initials = substr($user['full_name'] ?? 'U', 0, 1);
$pageTitle = 'My Notifications';
$displayTitle = 'My Notifications';
$currentPath = '/notifications';
$isActive = function($path) use ($currentPath) { return $currentPath === $path; };
require __DIR__ . '/../layouts/app.php'; ?>

<script>
function markNotificationReadFull(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(res => res.json()).then(data => {
        if (data.success) {
            const row = document.getElementById(`notification-row-${id}`);
            const dot = document.getElementById(`notification-dot-${id}`);
            const markBtn = document.getElementById(`notification-mark-${id}`);
            
            if (row) {
                row.classList.remove('bg-primary-50/50', 'dark:bg-primary-900/10', 'border-l-primary-500');
                row.classList.add('border-l-transparent', 'hover:bg-slate-50', 'dark:hover:bg-zinc-800/30');
            }
            if (dot) dot.remove();
            if (markBtn) markBtn.remove();
            
            updateNotificationBadge(-1);
        }
    });
}
</script>
