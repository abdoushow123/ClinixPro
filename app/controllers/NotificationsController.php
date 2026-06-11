<?php
/**
 * ClinixPro - Hospital Management System
 * Notifications Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Notification;

class NotificationsController extends Controller
{
    /**
     * Display all notifications
     */
    public function index()
    {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        
        $notifications = Notification::getUserNotifications($user['id'], 100);
        
        $this->view('notifications/index', [
            'title' => 'My Notifications',
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read (AJAX)
     */
    public function markRead(int $id)
    {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        
        if ($this->isPost()) {
            $success = Notification::markAsRead($id, $user['id']);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllRead()
    {
        $this->requireAuth();
        $user = $this->getCurrentUser();
        
        if ($this->isPost()) {
            $success = Notification::markAllAsRead($user['id']);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }
}
