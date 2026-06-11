<?php
/**
 * ClinixPro - Hospital Management System
 * Prescription Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Prescription extends Model
{
    protected string $table = 'prescriptions';
    protected string $primaryKey = 'id';

    /**
     * Get prescriptions with patient and doctor information
     */
    public static function withDetails(): array
    {
        self::initDb();
        $query = "
            SELECT p.*, pat.first_name as patient_first_name, pat.last_name as patient_last_name, pat.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM prescriptions p
            LEFT JOIN patients pat ON p.patient_id = pat.id
            LEFT JOIN users d ON p.doctor_id = d.id
            ORDER BY p.prescription_date DESC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get prescriptions for a specific patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT p.*, d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM prescriptions p
            LEFT JOIN users d ON p.doctor_id = d.id
            WHERE p.patient_id = ?
            ORDER BY p.prescription_date DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Get pending prescriptions (not dispensed)
     */
    public static function pending(): array
    {
        self::initDb();
        $query = "
            SELECT p.*, pat.first_name as patient_first_name, pat.last_name as patient_last_name,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM prescriptions p
            LEFT JOIN patients pat ON p.patient_id = pat.id
            LEFT JOIN users d ON p.doctor_id = d.id
            WHERE p.status = 'pending'
            ORDER BY p.prescription_date DESC
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
        $stats['dispensed'] = self::count(['status' => 'dispensed']);
        $stats['today'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM prescriptions WHERE prescription_date = CURRENT_DATE"
        );
        
        return $stats;
    }

    /**
     * Get prescriptions created by a specific doctor
     */
    public static function forDoctor(int $doctorId): array
    {
        self::initDb();
        $query = "
            SELECT p.*, pat.first_name as patient_first_name, pat.last_name as patient_last_name, pat.patient_id
            FROM prescriptions p
            LEFT JOIN patients pat ON p.patient_id = pat.id
            WHERE p.doctor_id = ?
            ORDER BY p.prescription_date DESC
        ";
        return Database::fetchAll($query, [$doctorId]);
    }

    /**
     * Get count of prescriptions dispensed today
     */
    public static function getDispensedToday(): int
    {
        self::initDb();
        return (int) Database::fetchColumn(
            "SELECT COUNT(*) FROM prescriptions WHERE status = 'dispensed' AND prescription_date = CURRENT_DATE"
        );
    }

    /**
     * Get pending prescriptions that need to be administered (nurse view)
     */
    public static function getPendingForNurse(): array
    {
        self::initDb();
        $query = "
            SELECT p.*, pat.first_name as patient_first_name, pat.last_name as patient_last_name, pat.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM prescriptions p
            LEFT JOIN patients pat ON p.patient_id = pat.id
            LEFT JOIN users d ON p.doctor_id = d.id
            WHERE p.status = 'dispensed'
            ORDER BY p.prescription_date DESC
        ";
        return Database::fetchAll($query);
    }
}
