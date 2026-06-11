<?php
/**
 * ClinixPro - Hospital Management System
 * Invoice Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Invoice extends Model
{
    protected string $table = 'invoices';
    protected string $primaryKey = 'id';

    /**
     * Get invoices with patient information
     */
    public static function withPatient(): array
    {
        self::initDb();
        $query = "
            SELECT i.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id
            FROM invoices i
            LEFT JOIN patients p ON i.patient_id = p.id
            ORDER BY i.invoice_date DESC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get invoice with details
     */
    public static function withDetails(int $invoiceId): ?array
    {
        self::initDb();
        $query = "
            SELECT i.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id,
                   p.email, p.phone
            FROM invoices i
            LEFT JOIN patients p ON i.patient_id = p.id
            WHERE i.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$invoiceId]);
    }

    /**
     * Get invoice items
     */
    public static function getItems(int $invoiceId): array
    {
        self::initDb();
        $query = "SELECT * FROM invoice_items WHERE invoice_id = ?";
        return Database::fetchAll($query, [$invoiceId]);
    }

    /**
     * Get invoices for a patient
     */
    public static function forPatient(int $patientId): array
    {
        self::initDb();
        $query = "
            SELECT * FROM invoices 
            WHERE patient_id = ?
            ORDER BY invoice_date DESC
        ";
        return Database::fetchAll($query, [$patientId]);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $timestamp = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return $prefix . $timestamp . $random;
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
        $stats['paid'] = self::count(['status' => 'paid']);
        $stats['overdue'] = self::count(['status' => 'overdue']);
        $stats['total_revenue'] = Database::fetchColumn(
            "SELECT SUM(paid_amount) FROM invoices"
        ) ?: 0;
        $stats['outstanding'] = Database::fetchColumn(
            "SELECT SUM(balance_amount) FROM invoices WHERE status != 'paid'"
        ) ?: 0;
        
        return $stats;
    }

    /**
     * Get revenue statistics for admin dashboard
     */
    public static function getRevenueStats(): array
    {
        self::initDb();
        $stats = [];
        
        $stats['total_revenue'] = Database::fetchColumn(
            "SELECT COALESCE(SUM(paid_amount), 0) FROM invoices"
        );
        $stats['this_month'] = Database::fetchColumn(
            "SELECT COALESCE(SUM(paid_amount), 0) FROM invoices WHERE EXTRACT(MONTH FROM invoice_date) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM invoice_date) = EXTRACT(YEAR FROM CURRENT_DATE)"
        );
        $stats['pending_amount'] = Database::fetchColumn(
            "SELECT COALESCE(SUM(balance_amount), 0) FROM invoices WHERE status != 'paid'"
        );
        $stats['pending_count'] = Database::fetchColumn(
            "SELECT COUNT(*) FROM invoices WHERE status = 'pending'"
        );
        
        return $stats;
    }

    /**
     * Get pending invoices with patient info (receptionist view)
     */
    public static function getPendingInvoices(): array
    {
        self::initDb();
        $query = "
            SELECT i.*, p.first_name as patient_first_name, p.last_name as patient_last_name, p.patient_id
            FROM invoices i
            LEFT JOIN patients p ON i.patient_id = p.id
            WHERE i.status = 'pending'
            ORDER BY i.invoice_date DESC
        ";
        return Database::fetchAll($query);
    }
}
