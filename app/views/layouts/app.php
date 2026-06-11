<?php
use App\Models\Notification;

$hasSidebar = $hasSidebar ?? true;
$sidebarUser = $sidebarUser ?? [];
$displayName = $displayName ?? 'User';
$initials = strtoupper(substr($displayName, 0, 1));
$pageTitle = $pageTitle ?? 'ClinixPro';
$displayTitle = $displayTitle ?? 'Dashboard';

// Robust navigation highlighting
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isActive = function($path) use ($currentUri) {
    if ($path === '/dashboard') {
        return $currentUri === $path;
    }
    return strpos($currentUri, $path) === 0;
};

$sidebarRole = strtolower($sidebarUser['role_name'] ?? '');
$doctorsNavHref = in_array($sidebarRole, ['administrator', 'admin'], true) ? '/admin/doctors' : '/doctors';

$unreadCount = 0;
$latestNotifications = [];
if (!empty($sidebarUser['id'])) {
    $unreadCount = Notification::getUnreadCount($sidebarUser['id']);
    $latestNotifications = Notification::getUserNotifications($sidebarUser['id'], 5);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $csrf_token ?? '' ?>">
    <meta name="description" content="ClinixPro - Modern Hospital Management System">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/assets/css/app.css?v=<?= time() ?>" rel="stylesheet">
    
    <!-- Theme Script -->
    <script src="/assets/js/theme.js"></script>
    
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body class="<?= $hasSidebar ? 'flex' : '' ?> min-h-screen relative">
    <!-- Ambient Background Blobs for WOW factor -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-primary-400/20 dark:bg-primary-900/20 blur-[120px] animate-pulse-slow"></div>
        <div class="absolute top-[60%] -right-[10%] w-[40%] h-[60%] rounded-full bg-accent-400/20 dark:bg-accent-900/20 blur-[120px] animate-float"></div>
    </div>

    <?php if ($hasSidebar): ?>
    <!-- Floating Premium Sidebar -->
    <aside class="fixed lg:sticky left-4 lg:left-0 top-4 bottom-4 lg:bottom-auto lg:h-[calc(100vh-2rem)] w-64 lg:ml-4 glass-panel border border-white/40 dark:border-white/10 z-50 flex flex-col transition-all duration-500 ease-spring -translate-x-full lg:translate-x-0 overflow-hidden shadow-2xl flex-shrink-0" id="appSidebar">
        <!-- Brand -->
        <div class="p-6 pb-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center shadow-glow-primary">
                    <i class="bi bi-hospital-fill text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent dark:from-primary-400 dark:to-accent-400">ClinixPro</h1>
                    <p class="text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">Hospital System</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto scrollbar-custom mt-2">
            <?php
            $role = strtolower($sidebarUser['role_name'] ?? '');
            
            // Define visibility rules for different sections based on role matrix
            // Main section
            $showPatients = in_array($role, ['administrator', 'admin', 'doctor', 'nurse', 'receptionist']);
            $showAppointments = in_array($role, ['administrator', 'admin', 'doctor', 'receptionist']);
            $showMedicalRecords = in_array($role, ['administrator', 'admin', 'doctor', 'nurse']);
            
            // Team section — no self-referential links
            $showDoctorsMenu = in_array($role, ['administrator', 'admin', 'doctor']);
            $showNursesMenu = in_array($role, ['administrator', 'admin', 'doctor']); // doctors can see assigned nurses
            $showPharmacistsMenu = in_array($role, ['administrator', 'admin', 'pharmacist']);
            $showLabTeamMenu = in_array($role, ['administrator', 'admin', 'laboratory']);
            $showReceptionistsMenu = in_array($role, ['administrator', 'admin']);
            
            // Hospital section
            $showHospitalizations = in_array($role, ['administrator', 'admin', 'doctor', 'nurse']);
            $showRooms = in_array($role, ['administrator', 'admin', 'nurse', 'receptionist']);
            $showLaboratory = in_array($role, ['administrator', 'admin', 'doctor', 'laboratory']);
            $showPharmacy = in_array($role, ['administrator', 'admin', 'pharmacist']);
            
            // Services section
            $showPrescriptions = in_array($role, ['administrator', 'admin', 'doctor', 'pharmacist', 'nurse']);
            $showBilling = in_array($role, ['administrator', 'admin', 'receptionist']);
            $showInsurance = in_array($role, ['administrator', 'admin', 'receptionist']);
            ?>

            <div>
                <h3 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 px-3">Main</h3>
                <div class="space-y-1">
                    <a href="/dashboard" class="nav-link <?= $isActive('/dashboard') ? 'active' : '' ?> group">
                        <i class="bi bi-speedometer2 text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <?php if ($showPatients): ?>
                    <a href="/patients" class="nav-link <?= $isActive('/patients') ? 'active' : '' ?> group">
                        <i class="bi bi-people text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Patients</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showAppointments): ?>
                    <a href="/appointments" class="nav-link <?= $isActive('/appointments') ? 'active' : '' ?> group">
                        <i class="bi bi-calendar-check text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Appointments</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showMedicalRecords): ?>
                    <a href="/medical-records" class="nav-link <?= $isActive('/medical-records') ? 'active' : '' ?> group">
                        <i class="bi bi-file-medical text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Medical Records</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($showDoctorsMenu || $showNursesMenu || $showPharmacistsMenu || $showLabTeamMenu || $showReceptionistsMenu): ?>
            <div>
                <h3 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 px-3">Team</h3>
                <div class="space-y-1">
                    <?php if ($showDoctorsMenu): ?>
                    <a href="<?= in_array($role, ['administrator', 'admin']) ? '/admin/doctors' : '/doctors' ?>" class="nav-link <?= $isActive(in_array($role, ['administrator', 'admin']) ? '/admin/doctors' : '/doctors') ? 'active' : '' ?> group">
                        <i class="bi bi-person-badge text-lg group-hover:scale-110 transition-transform"></i>
                        <span><?= in_array($role, ['administrator', 'admin']) ? 'Doctors' : 'My Team' ?></span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showNursesMenu): ?>
                    <a href="/nurses" class="nav-link <?= $isActive('/nurses') ? 'active' : '' ?> group">
                        <i class="bi bi-heart-pulse text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Nurses</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showPharmacistsMenu): ?>
                    <a href="/pharmacists" class="nav-link <?= $isActive('/pharmacists') ? 'active' : '' ?> group">
                        <i class="bi bi-capsule text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Pharmacists</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showLabTeamMenu): ?>
                    <a href="/laboratory/team" class="nav-link <?= $isActive('/laboratory/team') ? 'active' : '' ?> group">
                        <i class="bi bi-flask text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Laboratory Team</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showReceptionistsMenu): ?>
                    <a href="/receptionists" class="nav-link <?= $isActive('/receptionists') ? 'active' : '' ?> group">
                        <i class="bi bi-headset text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Receptionists</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($showHospitalizations || $showRooms || $showLaboratory || $showPharmacy): ?>
            <div>
                <h3 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 px-3">Hospital</h3>
                <div class="space-y-1">
                    <?php if ($showHospitalizations): ?>
                    <a href="/hospitalizations" class="nav-link <?= $isActive('/hospitalizations') ? 'active' : '' ?> group">
                        <i class="bi bi-hospital text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Hospitalizations</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showRooms): ?>
                    <a href="/rooms" class="nav-link <?= $isActive('/rooms') ? 'active' : '' ?> group">
                        <i class="bi bi-door-open text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Rooms</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showLaboratory): ?>
                    <a href="/laboratory" class="nav-link <?= $isActive('/laboratory') ? 'active' : '' ?> group">
                        <i class="bi bi-flask-fill text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Laboratory</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showPharmacy): ?>
                    <a href="/pharmacy" class="nav-link <?= $isActive('/pharmacy') ? 'active' : '' ?> group">
                        <i class="bi bi-prescription2 text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Pharmacy</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($showPrescriptions || $showBilling || $showInsurance): ?>
            <div>
                <h3 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 px-3">Services</h3>
                <div class="space-y-1">
                    <?php if ($showPrescriptions): ?>
                    <a href="/prescriptions" class="nav-link <?= $isActive('/prescriptions') ? 'active' : '' ?> group">
                        <i class="bi bi-file-earmark-text text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Prescriptions</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showBilling): ?>
                    <a href="/billing" class="nav-link <?= $isActive('/billing') ? 'active' : '' ?> group">
                        <i class="bi bi-receipt text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Billing</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($showInsurance): ?>
                    <a href="/insurance" class="nav-link <?= $isActive('/insurance') ? 'active' : '' ?> group">
                        <i class="bi bi-shield-check text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Insurance</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (in_array($role, ['administrator', 'admin'])): ?>
            <div>
                <h3 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 px-3">Admin</h3>
                <div class="space-y-1">
                    <a href="/admin/users" class="nav-link <?= $isActive('/admin/users') ? 'active' : '' ?> group">
                        <i class="bi bi-person-gear text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Users</span>
                    </a>
                    <a href="/admin/roles" class="nav-link <?= $isActive('/admin/roles') ? 'active' : '' ?> group">
                        <i class="bi bi-shield-lock text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Roles</span>
                    </a>
                    <a href="/admin/audit-logs" class="nav-link <?= $isActive('/admin/audit-logs') ? 'active' : '' ?> group">
                        <i class="bi bi-journal-text text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Audit Logs</span>
                    </a>
                    <a href="/admin/settings" class="nav-link <?= $isActive('/admin/settings') ? 'active' : '' ?> group">
                        <i class="bi bi-gear text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-4 mt-auto border-t border-slate-100 dark:border-zinc-800 bg-white/40 dark:bg-zinc-900/40 backdrop-blur-md">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white dark:ring-zinc-800">
                    <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-slate-900 dark:text-white truncate"><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wider"><?= htmlspecialchars($sidebarUser['role_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="/profile" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-zinc-300 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-colors">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </a>
                <a href="/logout" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-danger-600 bg-danger-50 hover:bg-danger-100 dark:bg-danger-900/20 dark:hover:bg-danger-900/40 transition-colors">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 p-4 lg:px-8 transition-all duration-500 w-full relative z-10" id="mainWrapper">
        <?php if ($hasSidebar): ?>
        <!-- Modern Floating Header -->
        <header class="sticky top-4 z-40 glass-panel mb-6 px-6 py-3 rounded-2xl animate-fade-in flex items-center justify-between shadow-soft">
            <div class="flex items-center gap-4">
                <button class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors" id="sidebarToggle">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div>
                    <!-- Breadcrumbs could go here, for now display title -->
                    <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight"><?= htmlspecialchars($displayTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Date Display -->
                <div class="hidden md:flex flex-col items-end mr-4">
                    <p class="text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-widest"><?= date('l') ?></p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white"><?= date('M j, Y') ?></p>
                </div>
                
                <!-- Dark Mode Toggle -->
                <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-600 dark:text-zinc-300 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-300 hover:scale-105 active:scale-95">
                    <i class="bi bi-moon-stars text-lg dark:hidden"></i>
                    <i class="bi bi-sun text-lg hidden dark:block text-warning-400"></i>
                </button>
                
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-600 dark:text-zinc-300 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-300 hover:scale-105 active:scale-95 relative" id="notificationBtn">
                        <i class="bi bi-bell text-lg"></i>
                        <?php if ($unreadCount > 0): ?>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-danger-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.8)] border-2 border-white dark:border-zinc-900" id="notificationBadge"></span>
                        <?php endif; ?>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-80 glass-panel shadow-2xl opacity-0 invisible transition-all duration-300 transform origin-top-right scale-95 z-50" id="notificationDropdown">
                        <div class="p-4 border-b border-slate-200 dark:border-zinc-800 flex justify-between items-center">
                            <h3 class="font-bold text-slate-900 dark:text-white">Notifications <span class="text-xs bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400 px-2 py-0.5 rounded-full ml-1" id="notificationCountText"><?= $unreadCount ?> new</span></h3>
                            <?php if ($unreadCount > 0): ?>
                            <button onclick="markAllNotificationsRead()" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Mark all read</button>
                            <?php endif; ?>
                        </div>
                        <div class="max-h-80 overflow-y-auto scrollbar-custom">
                            <?php if (empty($latestNotifications)): ?>
                                <div class="p-6 text-center text-slate-500 dark:text-zinc-400 text-sm">
                                    <i class="bi bi-bell-slash text-2xl mb-2 block opacity-50"></i>
                                    You're all caught up!
                                </div>
                            <?php else: ?>
                                <?php foreach ($latestNotifications as $notif): ?>
                                    <a href="<?= $notif['reference_url'] ?: '#' ?>" class="block p-4 border-b border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors <?= !$notif['is_read'] ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' ?>" onclick="markNotificationRead(<?= $notif['id'] ?>, this)">
                                        <div class="flex gap-3">
                                            <div class="mt-1 flex-shrink-0">
                                                <?php
                                                    $iconMap = [
                                                        'info' => '<i class="bi bi-info-circle-fill text-primary-500"></i>',
                                                        'success' => '<i class="bi bi-check-circle-fill text-success-500"></i>',
                                                        'warning' => '<i class="bi bi-exclamation-triangle-fill text-warning-500"></i>',
                                                        'danger' => '<i class="bi bi-x-circle-fill text-danger-500"></i>'
                                                    ];
                                                    echo $iconMap[$notif['type']] ?? $iconMap['info'];
                                                ?>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($notif['title']) ?></p>
                                                <p class="text-xs text-slate-600 dark:text-zinc-400 mt-0.5 line-clamp-2"><?= htmlspecialchars($notif['message']) ?></p>
                                                <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1"><?= date('M j, g:i a', strtotime($notif['created_at'])) ?></p>
                                            </div>
                                            <?php if (!$notif['is_read']): ?>
                                                <div class="ml-auto flex-shrink-0 flex items-center">
                                                    <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 text-center border-t border-slate-200 dark:border-zinc-800">
                            <a href="/notifications" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 hover:underline">View All Notifications</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <?php endif; ?>

        <!-- Content Area -->
        <main class="flex-1 <?= $centeredContent ?? false ? 'flex items-center justify-center' : '' ?> animate-slide-up pb-8">
            <!-- Flash Messages -->
            <div class="<?= $hasSidebar ? '' : 'max-w-md mx-auto mt-8' ?>">
                <?php if (\App\Core\Session::hasFlash('success')): ?>
                <div class="mb-6 p-4 bg-success-50 dark:bg-success-900/20 border-l-4 border-success-500 rounded-r-xl flex items-center gap-3 shadow-sm animate-slide-in">
                    <div class="w-8 h-8 rounded-full bg-success-100 dark:bg-success-800/50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check-lg text-success-600 dark:text-success-400 text-lg"></i>
                    </div>
                    <p class="text-success-800 dark:text-success-200 font-medium"><?= htmlspecialchars(\App\Core\Session::getFlash('success')) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (\App\Core\Session::hasFlash('error')): ?>
                <div class="mb-6 p-4 bg-danger-50 dark:bg-danger-900/20 border-l-4 border-danger-500 rounded-r-xl flex items-center gap-3 shadow-sm animate-slide-in">
                    <div class="w-8 h-8 rounded-full bg-danger-100 dark:bg-danger-800/50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-exclamation-lg text-danger-600 dark:text-danger-400 text-lg"></i>
                    </div>
                    <p class="text-danger-800 dark:text-danger-200 font-medium"><?= htmlspecialchars(\App\Core\Session::getFlash('error')) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (\App\Core\Session::hasFlash('warning')): ?>
                <div class="mb-6 p-4 bg-warning-50 dark:bg-warning-900/20 border-l-4 border-warning-500 rounded-r-xl flex items-center gap-3 shadow-sm animate-slide-in">
                    <div class="w-8 h-8 rounded-full bg-warning-100 dark:bg-warning-800/50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning-600 dark:text-warning-400"></i>
                    </div>
                    <p class="text-warning-800 dark:text-warning-200 font-medium"><?= htmlspecialchars(\App\Core\Session::getFlash('warning')) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (\App\Core\Session::hasFlash('info')): ?>
                <div class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-500 rounded-r-xl flex items-center gap-3 shadow-sm animate-slide-in">
                    <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-800/50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-info-lg text-primary-600 dark:text-primary-400 text-lg"></i>
                    </div>
                    <p class="text-primary-800 dark:text-primary-200 font-medium"><?= htmlspecialchars(\App\Core\Session::getFlash('info')) ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Dynamic Page Content -->
            <?= $content ?? '' ?>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="/assets/js/charts.js?v=<?= time() ?>"></script>
    <script src="/assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
