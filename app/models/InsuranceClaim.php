<?php
/**
 * ClinixPro - Hospital Management System
 * Insurance Claim Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class InsuranceClaim extends Model
{
    protected string $table = 'insurance_claims';
    protected string $primaryKey = 'id';

    /**
     * Get claims with patient and invoice information
     */
    public static function withDetails(): array
    {
        self::initDb();
        $query = "
            SELECT ic.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   i.invoice_number, i.total_amount as invoice_amount
            FROM insurance_claims ic
            LEFT JOIN patients p ON ic.patient_id = p.id
            LEFT JOIN invoices i ON ic.invoice_id = i.id
            ORDER BY ic.claim_date DESC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get claims for a specific patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT ic.*, i.invoice_number, i.total_amount as invoice_amount
            FROM insurance_claims ic
            LEFT JOIN invoices i ON ic.invoice_id = i.id
            WHERE ic.patient_id = ?
            ORDER BY ic.claim_date DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Get pending claims
     */
    public static function pending(): array
    {
        self::initDb();
        $query = "
            SELECT ic.*, p.first_name as patient_first_name, p.last_name as patient_last_name,
                   i.invoice_number, i.total_amount as invoice_amount
            FROM insurance_claims ic
            LEFT JOIN patients p ON ic.patient_id = p.id
            LEFT JOIN invoices i ON ic.invoice_id = i.id
            WHERE ic.status = 'pending'
            ORDER BY ic.claim_date DESC
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
        $stats['pending'] = self::count(['status' => 'submitted']);
        $stats['approved'] = self::count(['status' => 'approved']);
        $stats['rejected'] = self::count(['status' => 'denied']);
        $stats['total_claimed'] = Database::fetchColumn(
            "SELECT SUM(claimed_amount) FROM insurance_claims"
        ) ?: 0;
        $stats['total_approved'] = Database::fetchColumn(
            "SELECT SUM(approved_amount) FROM insurance_claims WHERE status = 'approved'"
        ) ?: 0;
        
        return $stats;
    }
}
