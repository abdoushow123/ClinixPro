<?php
/**
 * ClinixPro - Hospital Management System
 * Hospitalization Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Hospitalization extends Model
{
    protected string $table = 'hospitalizations';
    protected string $primaryKey = 'id';

    /**
     * Get active hospitalizations
     */
    public static function getActive(): array
    {
        self::initDb();
        $query = "
            SELECT h.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   r.room_number, r.room_type
            FROM hospitalizations h
            LEFT JOIN patients p ON h.patient_id = p.id
            LEFT JOIN rooms r ON h.room_id = r.id
            WHERE h.status = 'admitted'
            ORDER BY h.admission_date DESC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get hospitalization with details
     */
    public static function withDetails(int $hospitalizationId): ?array
    {
        self::initDb();
        $query = "
            SELECT h.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   p.phone, p.blood_type, p.allergies,
                   r.room_number, r.room_type, r.floor,
                   d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM hospitalizations h
            LEFT JOIN patients p ON h.patient_id = p.id
            LEFT JOIN rooms r ON h.room_id = r.id
            LEFT JOIN doctors d ON h.attending_doctor_id = d.id
            WHERE h.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$hospitalizationId]);
    }

    /**
     * Get hospitalizations for a patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT h.*, r.room_number
            FROM hospitalizations h
            LEFT JOIN rooms r ON h.room_id = r.id
            WHERE h.patient_id = ?
            ORDER BY h.admission_date DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Admit patient
     */
    public static function admitPatient(array $data): int
    {
        self::initDb();
        
        Database::beginTransaction();
        
        try {
            // Create hospitalization record
            $hospitalizationId = self::create($data);
            
            // Update room occupancy
            if (isset($data['room_id'])) {
                Room::updateOccupancy($data['room_id'], 1);
            }
            
            Database::commit();
            
            return $hospitalizationId;
        } catch (\Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Discharge patient
     */
    public static function dischargePatient(int $hospitalizationId, string $dischargeSummary): bool
    {
        self::initDb();
        
        $hospitalization = self::find($hospitalizationId);
        if (!$hospitalization || $hospitalization['status'] !== 'admitted') {
            return false;
        }
        
        Database::beginTransaction();
        
        try {
            // Update hospitalization
            self::update($hospitalizationId, [
                'discharge_date' => date('Y-m-d'),
                'discharge_time' => date('H:i:s'),
                'status' => 'discharged',
                'discharge_summary' => $dischargeSummary
            ]);
            
            // Update room occupancy
            if ($hospitalization['room_id']) {
                Room::updateOccupancy($hospitalization['room_id'], -1);
            }
            
            Database::commit();
            
            return true;
        } catch (\Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['active'] = self::count(['status' => 'admitted']);
        $stats['today_admissions'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM hospitalizations WHERE DATE(admission_date) = CURRENT_DATE"
        );
        $stats['today_discharges'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM hospitalizations WHERE DATE(discharge_date) = CURRENT_DATE"
        );
        $stats['this_month'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM hospitalizations WHERE EXTRACT(MONTH FROM admission_date) = EXTRACT(MONTH FROM CURRENT_DATE)"
        );
        
        return $stats;
    }
}
