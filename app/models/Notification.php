<?php
/**
 * ClinixPro - Hospital Management System
 * Notification Model
 */

namespace App\Models;

use App\Core\Database;

class Notification
{
    /**
     * Create a new notification
     */
    public static function create(int $userId, string $title, string $message, string $type = 'info', ?string $referenceUrl = null): bool
    {
        try {
            Database::query(
                "INSERT INTO notifications (user_id, title, message, type, reference_url) VALUES (?, ?, ?, ?, ?)",
                [$userId, $title, $message, $type, $referenceUrl]
            );
            return true;
        } catch (\Exception $e) {
            error_log("Failed to create notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get notifications for a user
     */
    public static function getUserNotifications(int $userId, int $limit = 50): array
    {
        try {
            return Database::fetchAll(
                "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
                [$userId, $limit]
            );
        } catch (\Exception $e) {
            error_log("Failed to get notifications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unread notification count
     */
    public static function getUnreadCount(int $userId): int
    {
        try {
            return (int) Database::fetchColumn(
                "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = false",
                [$userId]
            );
        } catch (\Exception $e) {
            error_log("Failed to get unread count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mark a specific notification as read
     */
    public static function markAsRead(int $id, int $userId): bool
    {
        try {
            Database::query(
                "UPDATE notifications SET is_read = true WHERE id = ? AND user_id = ?",
                [$id, $userId]
            );
            return true;
        } catch (\Exception $e) {
            error_log("Failed to mark notification as read: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(int $userId): bool
    {
        try {
            Database::query(
                "UPDATE notifications SET is_read = true WHERE user_id = ?",
                [$userId]
            );
            return true;
        } catch (\Exception $e) {
            error_log("Failed to mark all notifications as read: " . $e->getMessage());
            return false;
        }
    }
}
