<?php
/**
 * ClinixPro - Hospital Management System
 * Patient Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Patient extends Model
{
    protected string $table = 'patients';
    protected string $primaryKey = 'id';

    /**
     * Find patient by patient ID
     */
    public static function findByPatientId(string $patientId)
    {
        return self::findBy('patient_id', $patientId);
    }

    /**
     * Search patients
     */
    public static function search(string $query, int $limit = 50): array
    {
        self::initDb();
        $searchTerm = "%$query%";
        $sql = "
            SELECT * FROM patients 
            WHERE patient_id ILIKE ? 
            OR first_name ILIKE ? 
            OR last_name ILIKE ? 
            OR email ILIKE ? 
            OR phone ILIKE ?
            ORDER BY last_name, first_name
            LIMIT ?
        ";
        return Database::fetchAll($sql, [
            $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit
        ]);
    }

    /**
     * Get patient with recent medical records
     */
    public static function withMedicalRecords(int $patientId): ?array
    {
        self::initDb();
        $sql = "
            SELECT p.*, 
                   (SELECT COUNT(*) FROM medical_records WHERE patient_id = p.id) as records_count,
                   (SELECT COUNT(*) FROM hospitalizations WHERE patient_id = p.id AND status = 'admitted') as active_admissions
            FROM patients p
            WHERE p.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($sql, [$patientId]);
    }

    /**
     * Generate unique patient ID
     */
    public static function generatePatientId(): string
    {
        $prefix = 'PTN';
        $timestamp = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return $prefix . $timestamp . $random;
    }

    /**
     * Get patient statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['total'] = self::count();
        $stats['male'] = self::count(['gender' => 'male']);
        $stats['female'] = self::count(['gender' => 'female']);
        
        // Patients registered today
        $stats['today'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM patients WHERE created_at >= CURRENT_DATE"
        );
        
        // Active patients (those with hospitalizations or recent activity)
        $stats['active'] = Database::fetchColumn(
            "SELECT COUNT(DISTINCT patient_id) FROM hospitalizations WHERE status = 'admitted'"
        );
        
        // Patients with pending appointments
        $stats['pending'] = Database::fetchColumn(
            "SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE status = 'pending' AND appointment_date >= CURRENT_DATE"
        );
        
        $stats['this_month'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM patients WHERE EXTRACT(MONTH FROM created_at) = EXTRACT(MONTH FROM NOW())"
        );
        
        return $stats;
    }

    /**
     * Get patients associated with a specific doctor (via medical records or appointments)
     */
    public static function forDoctor(int $doctorId): array
    {
        self::initDb();
        $query = "
            SELECT DISTINCT p.*
            FROM patients p
            WHERE p.id IN (
                SELECT DISTINCT patient_id FROM medical_records WHERE doctor_id = ?
                UNION
                SELECT DISTINCT patient_id FROM appointments WHERE doctor_id = ?
            )
            ORDER BY p.last_name, p.first_name
        ";
        return Database::fetchAll($query, [$doctorId, $doctorId]);
    }

    /**
     * Get count of patients registered today
     */
    public static function todayRegistrations(): int
    {
        self::initDb();
        return (int) Database::fetchColumn(
            "SELECT COUNT(*) FROM patients WHERE created_at >= CURRENT_DATE"
        );
    }
}
