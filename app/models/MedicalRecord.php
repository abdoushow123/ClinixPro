<?php
/**
 * ClinixPro - Hospital Management System
 * Medical Record Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class MedicalRecord extends Model
{
    protected string $table = 'medical_records';
    protected string $primaryKey = 'id';

    /**
     * Get medical records for a patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT mr.*, d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM medical_records mr
            LEFT JOIN users d ON mr.doctor_id = d.id
            WHERE mr.patient_id = ?
            ORDER BY mr.visit_date DESC, mr.created_at DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Get medical record with doctor information
     */
    public static function withDoctor(int $recordId): ?array
    {
        self::initDb();
        $query = "
            SELECT mr.*, p.patient_id,
                   TRIM(CONCAT(p.first_name, ' ', p.last_name)) AS patient_name,
                   p.first_name AS patient_first_name,
                   p.last_name AS patient_last_name,
                   TRIM(CONCAT(d.first_name, ' ', d.last_name)) AS doctor_name,
                   d.first_name AS doctor_first_name,
                   d.last_name AS doctor_last_name
            FROM medical_records mr
            LEFT JOIN patients p ON mr.patient_id = p.id
            LEFT JOIN users d ON mr.doctor_id = d.id
            WHERE mr.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$recordId]);
    }

    /**
     * Get recent medical records
     */
    public static function recent(int $limit = 20): array
    {
        self::initDb();
        $query = "
            SELECT mr.*,
                   TRIM(CONCAT(p.first_name, ' ', p.last_name)) AS patient_name,
                   p.first_name AS patient_first_name,
                   p.last_name AS patient_last_name,
                   TRIM(CONCAT(d.first_name, ' ', d.last_name)) AS doctor_name,
                   d.first_name AS doctor_first_name,
                   d.last_name AS doctor_last_name
            FROM medical_records mr
            LEFT JOIN patients p ON mr.patient_id = p.id
            LEFT JOIN users d ON mr.doctor_id = d.id
            ORDER BY mr.visit_date DESC, mr.created_at DESC
            LIMIT ?
        ";
        return Database::fetchAll($query, [$limit]);
    }

    /**
     * Search medical records
     */
    public static function search(string $query, int $limit = 50): array
    {
        self::initDb();
        $searchTerm = "%$query%";
        $sql = "
            SELECT mr.*, p.first_name as patient_first_name, p.last_name as patient_last_name,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM medical_records mr
            LEFT JOIN patients p ON mr.patient_id = p.id
            LEFT JOIN users d ON mr.doctor_id = d.id
            WHERE mr.diagnosis ILIKE ? 
            OR mr.chief_complaint ILIKE ?
            OR p.first_name ILIKE ?
            OR p.last_name ILIKE ?
            ORDER BY mr.visit_date DESC
            LIMIT ?
        ";
        return Database::fetchAll($sql, [
            $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit
        ]);
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['total'] = self::count();
        $stats['today'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM medical_records WHERE DATE(visit_date) = CURRENT_DATE"
        );
        $stats['week'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM medical_records WHERE visit_date >= DATE_TRUNC('week', CURRENT_DATE)"
        );
        $stats['this_month'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM medical_records WHERE EXTRACT(MONTH FROM visit_date) = EXTRACT(MONTH FROM CURRENT_DATE)"
        );
        $stats['patients'] = Database::fetchColumn(
            "SELECT COUNT(DISTINCT patient_id) FROM medical_records"
        );
        
        return $stats;
    }
}
