<?php
/**
 * ClinixPro - Hospital Management System
 * Lab Request Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class LabRequest extends Model
{
    protected string $table = 'lab_requests';
    protected string $primaryKey = 'id';

    /**
     * Get pending requests
     */
    public static function getPending(): array
    {
        self::initDb();
        $query = "
            SELECT lr.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name,
                   lt.test_name, lt.test_code, lt.category
            FROM lab_requests lr
            LEFT JOIN patients p ON lr.patient_id = p.id
            LEFT JOIN users d ON lr.doctor_id = d.id
            LEFT JOIN laboratory_tests lt ON lr.test_id = lt.id
            WHERE lr.status = 'pending'
            ORDER BY lr.priority DESC, lr.request_date ASC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get requests with results
     */
    public static function withResults(int $requestId): ?array
    {
        self::initDb();
        $query = "
            SELECT lr.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name,
                   lt.test_name, lt.test_code, lt.category, lt.normal_range, lt.sample_type,
                   lres.results, lres.interpretation, lres.is_abnormal, lres.result_date, lres.result_time
            FROM lab_requests lr
            LEFT JOIN patients p ON lr.patient_id = p.id
            LEFT JOIN users d ON lr.doctor_id = d.id
            LEFT JOIN laboratory_tests lt ON lr.test_id = lt.id
            LEFT JOIN lab_results lres ON lres.lab_request_id = lr.id
            WHERE lr.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$requestId]);
    }

    /**
     * Get requests for a patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT lr.*, lt.test_name, lt.test_code
            FROM lab_requests lr
            LEFT JOIN laboratory_tests lt ON lr.test_id = lt.id
            WHERE lr.patient_id = ?
            ORDER BY lr.request_date DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['pending'] = self::count(['status' => 'pending']);
        $stats['in_progress'] = self::count(['status' => 'in_progress']);
        $stats['completed'] = self::count(['status' => 'completed']);
        $stats['today'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM lab_requests WHERE DATE(request_date) = CURRENT_DATE"
        );
        
        return $stats;
    }

    /**
     * Get lab requests ordered by a specific doctor
     */
    public static function forDoctor(int $doctorId): array
    {
        self::initDb();
        $query = "
            SELECT lr.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   lt.test_name, lt.test_code, lt.category
            FROM lab_requests lr
            LEFT JOIN patients p ON lr.patient_id = p.id
            LEFT JOIN laboratory_tests lt ON lr.test_id = lt.id
            WHERE lr.doctor_id = ?
            ORDER BY lr.request_date DESC
        ";
        return Database::fetchAll($query, [$doctorId]);
    }

    /**
     * Get count of lab requests completed today
     */
    public static function getCompletedToday(): int
    {
        self::initDb();
        return (int) Database::fetchColumn(
            "SELECT COUNT(*) FROM lab_requests WHERE status = 'completed' AND DATE(request_date) = CURRENT_DATE"
        );
    }

    /**
     * Get urgent priority lab requests
     */
    public static function getUrgent(): array
    {
        self::initDb();
        $query = "
            SELECT lr.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name,
                   lt.test_name, lt.test_code, lt.category
            FROM lab_requests lr
            LEFT JOIN patients p ON lr.patient_id = p.id
            LEFT JOIN users d ON lr.doctor_id = d.id
            LEFT JOIN laboratory_tests lt ON lr.test_id = lt.id
            WHERE lr.priority = 'urgent'
            AND lr.status != 'completed'
            ORDER BY lr.request_date ASC
        ";
        return Database::fetchAll($query);
    }
}
