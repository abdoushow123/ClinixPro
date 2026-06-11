<?php
/**
 * ClinixPro - Hospital Management System
 * Room Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Room extends Model
{
    protected string $table = 'rooms';
    protected string $primaryKey = 'id';

    /**
     * Get available rooms
     */
    public static function getAvailable(): array
    {
        self::initDb();
        $query = "
            SELECT * FROM rooms 
            WHERE is_active = true 
            AND current_occupancy < bed_capacity
            ORDER BY room_number
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get rooms by type
     */
    public static function getByType(string $type): array
    {
        return self::all(['room_type' => $type], 'room_number');
    }

    /**
     * Get room with current occupancy
     */
    public static function withOccupancy(int $roomId): ?array
    {
        self::initDb();
        $query = "
            SELECT r.*, 
                   (SELECT COUNT(*) FROM hospitalizations WHERE room_id = r.id AND status = 'admitted') as current_admissions
            FROM rooms r
            WHERE r.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$roomId]);
    }

    /**
     * Update room occupancy
     */
    public static function updateOccupancy(int $roomId, int $change): bool
    {
        self::initDb();
        $query = "
            UPDATE rooms 
            SET current_occupancy = current_occupancy + ? 
            WHERE id = ? AND current_occupancy + ? >= 0
        ";
        $stmt = Database::query($query, [$change, $roomId, $change]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['total'] = self::count();
        $stats['available'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM rooms WHERE is_active = true AND current_occupancy < bed_capacity"
        );
        $stats['occupied'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM rooms WHERE current_occupancy >= bed_capacity"
        );
        $stats['total_beds'] = Database::fetchColumn("SELECT SUM(bed_capacity) FROM rooms");
        $stats['occupied_beds'] = Database::fetchColumn("SELECT SUM(current_occupancy) FROM rooms");
        
        return $stats;
    }

    /**
     * Get count of available rooms (rooms with at least one open bed)
     */
    public static function getAvailableCount(): int
    {
        self::initDb();
        return (int) Database::fetchColumn(
            "SELECT COUNT(*) FROM rooms WHERE is_active = true AND current_occupancy < bed_capacity"
        );
    }

    /**
     * Get occupancy breakdown by room type (for chart)
     */
    public static function getOccupancyByType(): array
    {
        self::initDb();
        $query = "
            SELECT room_type, 
                   COUNT(*) as total_rooms,
                   SUM(bed_capacity) as total_beds,
                   SUM(current_occupancy) as occupied_beds
            FROM rooms
            WHERE is_active = true
            GROUP BY room_type
            ORDER BY room_type
        ";
        return Database::fetchAll($query);
    }
}
