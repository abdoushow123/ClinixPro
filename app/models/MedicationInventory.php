<?php
/**
 * ClinixPro - Hospital Management System
 * Medication Inventory Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class MedicationInventory extends Model
{
    protected string $table = 'medication_inventory';
    protected string $primaryKey = 'id';

    /**
     * Get all medications
     */
    public static function getAll(): array
    {
        return self::all(['is_active' => true], 'medication_name');
    }

    /**
     * Get low stock medications
     */
    public static function getLowStock(): array
    {
        self::initDb();
        $query = "
            SELECT * FROM medication_inventory 
            WHERE is_active = true 
            AND current_stock <= minimum_stock_level
            ORDER BY current_stock ASC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get expiring medications
     */
    public static function getExpiring(int $days = 30): array
    {
        self::initDb();
        $query = "
            SELECT * FROM medication_inventory 
            WHERE is_active = true 
            AND expiry_date <= CURRENT_DATE + INTERVAL '1 day' * ?
            ORDER BY expiry_date ASC
        ";
        return Database::fetchAll($query, [$days]);
    }

    /**
     * Add stock
     */
    public static function addStock(int $medicationId, int $quantity, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): bool
    {
        self::initDb();
        
        Database::beginTransaction();
        
        try {
            // Update inventory
            Database::query(
                "UPDATE medication_inventory SET current_stock = current_stock + ? WHERE id = ?",
                [$quantity, $medicationId]
            );
            
            // Log transaction
            $query = "
                INSERT INTO inventory_transactions (medication_id, transaction_type, quantity, reference_type, reference_id, notes, created_at)
                VALUES (?, 'stock_in', ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ";
            Database::query($query, [$medicationId, $quantity, $referenceType, $referenceId, $notes]);
            
            Database::commit();
            
            return true;
        } catch (\Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Remove stock
     */
    public static function removeStock(int $medicationId, int $quantity, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): bool
    {
        self::initDb();
        
        Database::beginTransaction();
        
        try {
            // Check sufficient stock
            $currentStock = Database::fetchColumn(
                "SELECT current_stock FROM medication_inventory WHERE id = ?",
                [$medicationId]
            );
            
            if ($currentStock < $quantity) {
                throw new \Exception("Insufficient stock");
            }
            
            // Update inventory
            Database::query(
                "UPDATE medication_inventory SET current_stock = current_stock - ? WHERE id = ?",
                [$quantity, $medicationId]
            );
            
            // Log transaction
            $query = "
                INSERT INTO inventory_transactions (medication_id, transaction_type, quantity, reference_type, reference_id, notes, created_at)
                VALUES (?, 'stock_out', ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ";
            Database::query($query, [$medicationId, $quantity, $referenceType, $referenceId, $notes]);
            
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
        
        $stats['total_medications'] = self::count(['is_active' => true]);
        $stats['low_stock'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM medication_inventory WHERE is_active = true AND current_stock <= minimum_stock_level"
        );
        $stats['expiring_soon'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM medication_inventory WHERE is_active = true AND expiry_date <= CURRENT_DATE + INTERVAL '30 days'"
        );
        $stats['total_stock'] = Database::fetchColumn(
            "SELECT SUM(current_stock) FROM medication_inventory WHERE is_active = true"
        );
        
        return $stats;
    }
}
