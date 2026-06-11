<?php


use App\Core\Router;
use App\Core\Database;

// Initialize router
$router = new Router();

// Initialize database
$config = require __DIR__ . '/../config/config.php';
Database::init($config['database']);

// =====================================================
// PUBLIC ROUTES (No authentication required)
// =====================================================

$router->add('', ['controller' => 'home', 'action' => 'index']);
$router->add('login', ['controller' => 'auth', 'action' => 'login']);
$router->add('login/authenticate', ['controller' => 'auth', 'action' => 'authenticate', 'middleware' => ['Csrf']]);
$router->add('logout', ['controller' => 'auth', 'action' => 'logout']);
$router->add('register', ['controller' => 'registration', 'action' => 'index']);
$router->add('register/store', ['controller' => 'registration', 'action' => 'register', 'middleware' => ['Csrf']]);
$router->add('forgot-password', ['controller' => 'auth', 'action' => 'forgotPassword']);
$router->add('forgot-password/send', ['controller' => 'auth', 'action' => 'sendResetLink', 'middleware' => ['Csrf']]);
$router->add('reset-password', ['controller' => 'auth', 'action' => 'resetPassword']);
$router->add('reset-password/handle', ['controller' => 'auth', 'action' => 'handleResetPassword', 'middleware' => ['Csrf']]);

// =====================================================
// AUTHENTICATED ROUTES
// =====================================================

// Dashboard
$router->add('dashboard', ['controller' => 'dashboard', 'action' => 'index', 'middleware' => ['Auth']]);

// Doctor Team Management
$router->add('doctors', ['controller' => 'doctors', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('doctors/nurse-applications', ['controller' => 'doctors', 'action' => 'nurseApplications', 'middleware' => ['Auth']]);
$router->add('doctors/nurse-applications/{id}', ['controller' => 'doctors', 'action' => 'viewNurseApplication', 'middleware' => ['Auth']]);
$router->add('doctors/nurse-applications/{id}/accept', ['controller' => 'doctors', 'action' => 'acceptNurse', 'middleware' => ['Auth', 'Csrf']]);
$router->add('doctors/nurse-applications/{id}/reject', ['controller' => 'doctors', 'action' => 'rejectNurse', 'middleware' => ['Auth', 'Csrf']]);
$router->add('doctors/{id}/approve-nurse', ['controller' => 'doctors', 'action' => 'approveNurse', 'middleware' => ['Auth', 'Csrf']]);
$router->add('doctors/{id}/reject-nurse', ['controller' => 'doctors', 'action' => 'rejectNurse', 'middleware' => ['Auth', 'Csrf']]);

// Pharmacist Team Management
$router->add('pharmacists', ['controller' => 'pharmacists', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('pharmacists/{id}/approve', ['controller' => 'pharmacists', 'action' => 'approvePharmacist', 'middleware' => ['Auth', 'Csrf']]);
$router->add('pharmacists/{id}/reject', ['controller' => 'pharmacists', 'action' => 'rejectPharmacist', 'middleware' => ['Auth', 'Csrf']]);

// Nurse Team Management
$router->add('nurses', ['controller' => 'nurses', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('nurses/{id}/approve', ['controller' => 'nurses', 'action' => 'approveNurse', 'middleware' => ['Auth', 'Csrf']]);
$router->add('nurses/{id}/reject', ['controller' => 'nurses', 'action' => 'rejectNurse', 'middleware' => ['Auth', 'Csrf']]);

// Receptionist Team Management
$router->add('receptionists', ['controller' => 'receptionists', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('receptionists/{id}/approve', ['controller' => 'receptionists', 'action' => 'approveReceptionist', 'middleware' => ['Auth', 'Csrf']]);
$router->add('receptionists/{id}/reject', ['controller' => 'receptionists', 'action' => 'rejectReceptionist', 'middleware' => ['Auth', 'Csrf']]);

// Profile
$router->add('profile', ['controller' => 'profile', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('profile/update', ['controller' => 'profile', 'action' => 'update', 'middleware' => ['Auth']]);

// =====================================================
// PATIENT MANAGEMENT ROUTES
// =====================================================

$router->add('patients', ['controller' => 'patients', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('patients/create', ['controller' => 'patients', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('patients/store', ['controller' => 'patients', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('patients/{id}', ['controller' => 'patients', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('patients/{id}/edit', ['controller' => 'patients', 'action' => 'edit', 'middleware' => ['Auth']]);
$router->add('patients/{id}/update', ['controller' => 'patients', 'action' => 'update', 'middleware' => ['Auth', 'Csrf']]);
$router->add('patients/{id}/delete', ['controller' => 'patients', 'action' => 'delete', 'middleware' => ['Auth', 'Csrf']]);
$router->add('patients/search', ['controller' => 'patients', 'action' => 'search', 'middleware' => ['Auth']]);

// =====================================================
// MEDICAL RECORDS ROUTES
// =====================================================

$router->add('medical-records', ['controller' => 'medical-records', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('medical-records/search', ['controller' => 'medical-records', 'action' => 'search', 'middleware' => ['Auth']]);
$router->add('medical-records/create', ['controller' => 'medical-records', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('medical-records/store', ['controller' => 'medical-records', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('medical-records/{id}', ['controller' => 'medical-records', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('medical-records/{id}/edit', ['controller' => 'medical-records', 'action' => 'edit', 'middleware' => ['Auth']]);
$router->add('medical-records/{id}/update', ['controller' => 'medical-records', 'action' => 'update', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// APPOINTMENTS ROUTES
// =====================================================

$router->add('appointments', ['controller' => 'appointments', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('appointments/create', ['controller' => 'appointments', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('appointments/store', ['controller' => 'appointments', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('appointments/{id}', ['controller' => 'appointments', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('appointments/{id}/cancel', ['controller' => 'appointments', 'action' => 'cancel', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// HOSPITALIZATION ROUTES
// =====================================================

$router->add('hospitalizations', ['controller' => 'hospitalizations', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('hospitalizations/admit', ['controller' => 'hospitalizations', 'action' => 'admit', 'middleware' => ['Auth']]);
$router->add('hospitalizations/store', ['controller' => 'hospitalizations', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('hospitalizations/{id}', ['controller' => 'hospitalizations', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('hospitalizations/{id}/discharge', ['controller' => 'hospitalizations', 'action' => 'discharge', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// ROOMS ROUTES
// =====================================================

$router->add('rooms', ['controller' => 'rooms', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('rooms/create', ['controller' => 'rooms', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('rooms/store', ['controller' => 'rooms', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('rooms/{id}', ['controller' => 'rooms', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('rooms/{id}/edit', ['controller' => 'rooms', 'action' => 'edit', 'middleware' => ['Auth']]);
$router->add('rooms/{id}/update', ['controller' => 'rooms', 'action' => 'update', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// PRESCRIPTIONS ROUTES
// =====================================================

$router->add('prescriptions', ['controller' => 'prescriptions', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('prescriptions/create', ['controller' => 'prescriptions', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('prescriptions/store', ['controller' => 'prescriptions', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('prescriptions/{id}', ['controller' => 'prescriptions', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('prescriptions/{id}/dispense', ['controller' => 'prescriptions', 'action' => 'dispense', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// LABORATORY ROUTES
// =====================================================

$router->add('laboratory', ['controller' => 'laboratory', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('laboratory/team', ['controller' => 'laboratory', 'action' => 'team', 'middleware' => ['Auth']]);
$router->add('laboratory/request', ['controller' => 'laboratory', 'action' => 'request', 'middleware' => ['Auth']]);
$router->add('laboratory/store-request', ['controller' => 'laboratory', 'action' => 'storeRequest', 'middleware' => ['Auth', 'Csrf']]);
$router->add('laboratory/{id}', ['controller' => 'laboratory', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('laboratory/{id}/result', ['controller' => 'laboratory', 'action' => 'result', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// PHARMACY ROUTES
// =====================================================

$router->add('pharmacy', ['controller' => 'pharmacy', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('pharmacy/inventory', ['controller' => 'pharmacy', 'action' => 'inventory', 'middleware' => ['Auth']]);
$router->add('pharmacy/inventory/add', ['controller' => 'pharmacy', 'action' => 'addInventory', 'middleware' => ['Auth', 'Csrf']]);
$router->add('pharmacy/inventory/{id}', ['controller' => 'pharmacy', 'action' => 'showInventory', 'middleware' => ['Auth']]);

// =====================================================
// BILLING ROUTES
// =====================================================

$router->add('billing', ['controller' => 'billing', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('billing/create', ['controller' => 'billing', 'action' => 'create', 'middleware' => ['Auth']]);
$router->add('billing/store', ['controller' => 'billing', 'action' => 'store', 'middleware' => ['Auth', 'Csrf']]);
$router->add('billing/{id}', ['controller' => 'billing', 'action' => 'show', 'middleware' => ['Auth']]);
$router->add('billing/{id}/payment', ['controller' => 'billing', 'action' => 'payment', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// INSURANCE ROUTES
// =====================================================

$router->add('insurance', ['controller' => 'insurance', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('insurance/claim', ['controller' => 'insurance', 'action' => 'claim', 'middleware' => ['Auth']]);
$router->add('insurance/store-claim', ['controller' => 'insurance', 'action' => 'storeClaim', 'middleware' => ['Auth', 'Csrf']]);

// =====================================================
// ADMIN ROUTES (Administrator only)
// =====================================================

$router->add('admin/users', ['controller' => 'users', 'action' => 'index', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/users/create', ['controller' => 'users', 'action' => 'create', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/users/store', ['controller' => 'users', 'action' => 'store', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);
$router->add('admin/users/{id}', ['controller' => 'users', 'action' => 'show', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/users/{id}/edit', ['controller' => 'users', 'action' => 'edit', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/users/{id}/update', ['controller' => 'users', 'action' => 'update', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);
$router->add('admin/users/{id}/delete', ['controller' => 'users', 'action' => 'delete', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);
$router->add('admin/users/{id}/approve', ['controller' => 'users', 'action' => 'approve', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);
$router->add('admin/users/{id}/reject', ['controller' => 'users', 'action' => 'reject', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);

$router->add('admin/doctors', ['controller' => 'doctors', 'action' => 'index', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/doctors/create', ['controller' => 'doctors', 'action' => 'create', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/doctors/store', ['controller' => 'doctors', 'action' => 'store', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);

$router->add('admin/roles', ['controller' => 'roles', 'action' => 'index', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/roles/{id}', ['controller' => 'roles', 'action' => 'show', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/roles/{id}/edit', ['controller' => 'roles', 'action' => 'edit', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/roles/{id}/update', ['controller' => 'roles', 'action' => 'update', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin', 'Csrf']]);
$router->add('admin/audit-logs', ['controller' => 'audit', 'action' => 'index', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);
$router->add('admin/settings', ['controller' => 'settings', 'action' => 'index', 'namespace' => 'admin', 'middleware' => ['Auth', 'Admin']]);

// =====================================================
// API ROUTES
// =====================================================

$router->add('api/patients', ['controller' => 'patients', 'action' => 'index', 'namespace' => 'api', 'middleware' => ['Auth']]);
$router->add('api/patients/{id}', ['controller' => 'patients', 'action' => 'show', 'namespace' => 'api', 'middleware' => ['Auth']]);
$router->add('api/appointments', ['controller' => 'appointments', 'action' => 'index', 'namespace' => 'api', 'middleware' => ['Auth']]);
$router->add('api/laboratory/tests', ['controller' => 'laboratory', 'action' => 'tests', 'namespace' => 'api', 'middleware' => ['Auth']]);
$router->add('api/doctors', ['controller' => 'registration', 'action' => 'getDoctors']);

// =====================================================
// NOTIFICATIONS ROUTES
// =====================================================

$router->add('notifications', ['controller' => 'notifications', 'action' => 'index', 'middleware' => ['Auth']]);
$router->add('notifications/{id}/read', ['controller' => 'notifications', 'action' => 'markRead', 'middleware' => ['Auth']]);
$router->add('notifications/read-all', ['controller' => 'notifications', 'action' => 'markAllRead', 'middleware' => ['Auth']]);

// =====================================================
// ERROR ROUTES
// =====================================================

$router->add('404', ['controller' => 'error', 'action' => 'notFound']);
$router->add('403', ['controller' => 'error', 'action' => 'forbidden']);
$router->add('500', ['controller' => 'error', 'action' => 'serverError']);
