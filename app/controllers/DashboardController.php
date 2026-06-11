<?php
/**
 * ClinixPro - Hospital Management System
 * Dashboard Controller
 * 
 * Serves role-specific dashboard data and views for each of the 6 roles.
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospitalization;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabRequest;
use App\Models\Invoice;
use App\Models\MedicationInventory;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $currentUser = $this->getCurrentUser();
        $role = strtolower($currentUser['role_name'] ?? '');
        
        // Branch by role to serve role-specific data
        switch ($role) {
            case 'doctor':
                $data = $this->getDoctorData($currentUser);
                break;
            case 'nurse':
                $data = $this->getNurseData($currentUser);
                break;
            case 'receptionist':
                $data = $this->getReceptionistData($currentUser);
                break;
            case 'pharmacist':
                $data = $this->getPharmacistData($currentUser);
                break;
            case 'laboratory':
                $data = $this->getLaboratoryData($currentUser);
                break;
            default: // administrator / admin
                $data = $this->getAdminData($currentUser);
                break;
        }
        
        $this->view('dashboard/index', array_merge($data, [
            'title' => 'Dashboard - ClinixPro',
            'user' => $currentUser,
            'dashboard_role' => $role,
        ]));
    }

    /**
     * Administrator — full system oversight
     */
    private function getAdminData(array $user): array
    {
        $invoiceStats = Invoice::getRevenueStats();
        $pendingRegistrations = User::getPendingRegistrations();
        
        return [
            'stats' => [
                'patients' => Patient::count(),
                'patients_today' => Patient::getStatistics()['today'],
                'active_admissions' => Hospitalization::getStatistics()['active'],
                'pending_appointments' => Appointment::getStatistics()['pending'],
                'total_revenue' => $invoiceStats['total_revenue'],
                'pending_registrations' => count($pendingRegistrations),
            ],
            'recent_activity' => User::getRecentActivity(10),
            'staff_stats' => User::getStaffStatistics(),
            'upcoming_appointments' => Appointment::upcoming(),
            'invoice_stats' => $invoiceStats,
            'pending_registrations' => $pendingRegistrations,
        ];
    }

    /**
     * Doctor — clinical workflow: own patients, appointments, prescriptions
     */
    private function getDoctorData(array $user): array
    {
        $userId = $user['id'];
        $todayAppointments = Appointment::todayForDoctor($userId);
        $doctorLabRequests = LabRequest::forDoctor($userId);
        $pendingLabResults = array_filter($doctorLabRequests, fn($r) => $r['status'] !== 'completed');
        
        return [
            'stats' => [
                'my_patients_today' => count($todayAppointments),
                'pending_appointments' => count(array_filter(Appointment::forDoctor($userId), fn($a) => $a['status'] === 'pending')),
                'active_prescriptions' => count(array_filter(Prescription::forDoctor($userId), fn($p) => $p['status'] === 'pending')),
                'pending_lab_results' => count($pendingLabResults),
            ],
            'today_appointments' => $todayAppointments,
            'recent_prescriptions' => array_slice(Prescription::forDoctor($userId), 0, 10),
            'pending_lab_requests' => array_slice(array_values($pendingLabResults), 0, 10),
        ];
    }

    /**
     * Nurse — patient care: assigned patients, vitals, hospitalizations
     */
    private function getNurseData(array $user): array
    {
        $activeHospitalizations = Hospitalization::getActive();
        $roomStats = Room::getStatistics();
        $pendingPrescriptions = Prescription::getPendingForNurse();
        
        return [
            'stats' => [
                'assigned_patients' => count($activeHospitalizations),
                'active_admissions' => $roomStats['occupied'] ?? 0,
                'prescriptions_to_administer' => count($pendingPrescriptions),
                'available_beds' => ($roomStats['total_beds'] ?? 0) - ($roomStats['occupied_beds'] ?? 0),
            ],
            'active_hospitalizations' => array_slice($activeHospitalizations, 0, 10),
            'pending_prescriptions' => array_slice($pendingPrescriptions, 0, 10),
            'room_occupancy' => Room::getOccupancyByType(),
        ];
    }

    /**
     * Receptionist — front desk: appointments, registration, billing
     */
    private function getReceptionistData(array $user): array
    {
        $todayAppointments = Appointment::todayAll();
        $appointmentStats = Appointment::getStatistics();
        $pendingInvoices = Invoice::getPendingInvoices();
        
        return [
            'stats' => [
                'today_appointments' => $appointmentStats['today'],
                'patients_registered_today' => Patient::todayRegistrations(),
                'pending_invoices' => count($pendingInvoices),
                'rooms_available' => Room::getAvailableCount(),
            ],
            'today_appointments_list' => array_slice($todayAppointments, 0, 10),
            'pending_invoices' => array_slice($pendingInvoices, 0, 10),
            'appointment_stats' => $appointmentStats,
        ];
    }

    /**
     * Pharmacist — medication management: dispense, inventory
     */
    private function getPharmacistData(array $user): array
    {
        $pendingPrescriptions = Prescription::pending();
        $lowStock = MedicationInventory::getLowStock();
        $expiringSoon = MedicationInventory::getExpiring(30);
        $medStats = MedicationInventory::getStatistics();
        
        return [
            'stats' => [
                'pending_prescriptions' => count($pendingPrescriptions),
                'dispensed_today' => Prescription::getDispensedToday(),
                'low_stock' => count($lowStock),
                'expiring_soon' => count($expiringSoon),
            ],
            'pending_prescriptions' => array_slice($pendingPrescriptions, 0, 10),
            'low_stock_medications' => array_slice($lowStock, 0, 10),
            'expiring_medications' => array_slice($expiringSoon, 0, 10),
            'inventory_stats' => $medStats,
        ];
    }

    /**
     * Laboratory — lab workflow: process tests, enter results
     */
    private function getLaboratoryData(array $user): array
    {
        $labStats = LabRequest::getStatistics();
        $pendingTests = LabRequest::getPending();
        $urgentTests = LabRequest::getUrgent();
        
        return [
            'stats' => [
                'pending_tests' => $labStats['pending'],
                'in_progress' => $labStats['in_progress'],
                'completed_today' => LabRequest::getCompletedToday(),
                'urgent_priority' => count($urgentTests),
            ],
            'pending_tests' => array_slice($pendingTests, 0, 10),
            'urgent_tests' => array_slice($urgentTests, 0, 10),
            'lab_stats' => $labStats,
        ];
    }
}
