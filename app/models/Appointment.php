<?php
/**
 * ClinixPro - Hospital Management System
 * Appointment Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Appointment extends Model
{
    protected string $table = 'appointments';
    protected string $primaryKey = 'id';

    /**
     * Get appointments with patient and doctor information
     */
    public static function withDetails(): array
    {
        self::initDb();
        $query = "
            SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN users d ON a.doctor_id = d.id
            ORDER BY a.appointment_date, a.appointment_time
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get appointments for a specific patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT a.*, d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM appointments a
            LEFT JOIN users d ON a.doctor_id = d.id
            WHERE a.patient_id = ?
            ORDER BY a.appointment_date, a.appointment_time
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Get upcoming appointments
     */
    public static function upcoming(): array
    {
        self::initDb();
        $query = "
            SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN users d ON a.doctor_id = d.id
            WHERE a.appointment_date >= CURRENT_DATE
            AND a.status != 'cancelled'
            ORDER BY a.appointment_date, a.appointment_time
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['total'] = self::count();
        $stats['pending'] = self::count(['status' => 'pending']);
        $stats['confirmed'] = self::count(['status' => 'confirmed']);
        $stats['completed'] = self::count(['status' => 'completed']);
        $stats['cancelled'] = self::count(['status' => 'cancelled']);
        $stats['today'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM appointments WHERE appointment_date = CURRENT_DATE"
        );
        $stats['upcoming'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM appointments WHERE appointment_date > CURRENT_DATE AND status != 'cancelled'"
        );
        
        return $stats;
    }

    /**
     * Get appointments for a specific doctor with patient details
     */
    public static function forDoctor(int $doctorId): array
    {
        self::initDb();
        $query = "
            SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            WHERE a.doctor_id = ?
            AND a.status != 'cancelled'
            ORDER BY a.appointment_date, a.appointment_time
        ";
        return Database::fetchAll($query, [$doctorId]);
    }

    /**
     * Get today's appointments for a specific doctor
     */
    public static function todayForDoctor(int $doctorId): array
    {
        self::initDb();
        $query = "
            SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            WHERE a.doctor_id = ?
            AND a.appointment_date = CURRENT_DATE
            AND a.status != 'cancelled'
            ORDER BY a.appointment_time
        ";
        return Database::fetchAll($query, [$doctorId]);
    }

    /**
     * Get all of today's appointments with patient and doctor details (receptionist view)
     */
    public static function todayAll(): array
    {
        self::initDb();
        $query = "
            SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN users d ON a.doctor_id = d.id
            WHERE a.appointment_date = CURRENT_DATE
            ORDER BY a.appointment_time
        ";
        return Database::fetchAll($query);
    }
}
