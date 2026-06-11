<?php
/**
 * ClinixPro - Hospital Management System
 * Audit Controller (Admin)
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class AuditController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $page = (int)($this->get('page', 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        // Get activity logs
        $query = "
            SELECT al.*, u.first_name, u.last_name, u.email
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        $logs = Database::fetchAll($query);
        
        // Get total count
        $total = Database::fetchColumn("SELECT COUNT(*) FROM activity_logs");
        
        // Get login logs
        $loginQuery = "
            SELECT ll.*, u.first_name, u.last_name, u.email
            FROM login_logs ll
            LEFT JOIN users u ON ll.user_id = u.id
            ORDER BY ll.created_at DESC
            LIMIT 20
        ";
        $loginLogs = Database::fetchAll($loginQuery);
        
        $this->view('admin/audit/index', [
            'title' => 'Audit Logs - ClinixPro',
            'logs' => $logs,
            'login_logs' => $loginLogs,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);
    }
}
