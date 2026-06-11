<?php
/**
 * ClinixPro - Hospital Management System
 * User Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\Security;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    /**
     * Find user by email
     */
    public static function findByEmail(string $email)
    {
        return self::findBy('email', $email);
    }

    /**
     * Find user by username
     */
    public static function findByUsername(string $username)
    {
        return self::findBy('username', $username);
    }

    /**
     * Find user by UUID
     */
    public static function findByUuid(string $uuid)
    {
        return self::findBy('uuid', $uuid);
    }

    /**
     * Get user with role information
     */
    public static function withRole(int $userId)
    {
        self::initDb();
        $query = "
            SELECT u.*, (u.first_name || ' ' || u.last_name) as full_name, r.name as role_name, r.description as role_description, r.permissions
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?
            LIMIT 1
        ";
        return Database::fetchOne($query, [$userId]);
    }

    /**
     * Authenticate user
     */
    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        
        if (!$user) {
            return null;
        }

        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return null;
        }

        // Check if account is active
        if (!$user['is_active']) {
            return null;
        }

        // Verify password
        if (!Security::verifyPassword($password, $user['password_hash'])) {
            // Increment failed login attempts
            self::incrementFailedAttempts($user['id']);
            return null;
        }

        // Reset failed attempts on successful login
        self::resetFailedAttempts($user['id']);

        // Get user with role
        return self::withRole($user['id']);
    }

    /**
     * Increment failed login attempts
     */
    public static function incrementFailedAttempts(int $userId): void
    {
        self::initDb();
        $config = require __DIR__ . '/../../config/config.php';
        $maxAttempts = $config['security']['max_login_attempts'];
        $lockoutDuration = $config['security']['lockout_duration'];

        $query = "
            UPDATE users 
            SET failed_login_attempts = failed_login_attempts + 1,
                locked_until = CASE 
                    WHEN failed_login_attempts + 1 >= ? THEN NOW() + INTERVAL '1 second' * ?
                    ELSE locked_until 
                END
            WHERE id = ?
        ";
        Database::query($query, [$maxAttempts, $lockoutDuration, $userId]);
    }

    /**
     * Reset failed login attempts
     */
    public static function resetFailedAttempts(int $userId): void
    {
        self::initDb();
        $query = "
            UPDATE users 
            SET failed_login_attempts = 0,
                locked_until = NULL,
                last_login_at = NOW(),
                last_login_ip = ?
            WHERE id = ?
        ";
        Database::query($query, [Security::getClientIp(), $userId]);
    }

    /**
     * Check if account is locked
     */
    public static function isLocked(int $userId): bool
    {
        $user = self::find($userId);
        if (!$user) {
            return false;
        }

        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return true;
        }

        return false;
    }

    /**
     * Log login attempt
     */
    public static function logLoginAttempt(int $userId, string $status, ?string $failureReason = null): void
    {
        self::initDb();
        $query = "
            INSERT INTO login_logs (user_id, ip_address, user_agent, status, failure_reason)
            VALUES (?, ?, ?, ?, ?)
        ";
        Database::query($query, [
            $userId ?: null,
            Security::getClientIp(),
            Security::getUserAgent(),
            $status,
            $failureReason
        ]);
    }

    /**
     * Log activity
     */
    public static function logActivity(int $userId, string $action, ?string $entityType = null, ?int $entityId = null, array $details = []): void
    {
        self::initDb();
        $query = "
            INSERT INTO activity_logs (user_id, action, entity_type, entity_id, details, ip_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        Database::query($query, [
            $userId ?: null,
            $action,
            $entityType,
            $entityId,
            json_encode($details),
            Security::getClientIp()
        ]);
    }

    /**
     * Create password reset token
     */
    public static function createPasswordResetToken(int $userId): string
    {
        self::initDb();
        $token = Security::generateToken();
        $config = require __DIR__ . '/../../config/config.php';
        $expiresAt = date('Y-m-d H:i:s', time() + $config['security']['password_reset_ttl']);

        // Delete any existing tokens for this user
        Database::delete('password_reset_tokens', 'user_id = ?', [$userId]);

        // Insert new token
        $query = "
            INSERT INTO password_reset_tokens (user_id, token, expires_at)
            VALUES (?, ?, ?)
        ";
        Database::query($query, [$userId, $token, $expiresAt]);

        return $token;
    }

    /**
     * Validate password reset token
     */
    public static function validatePasswordResetToken(string $token): ?array
    {
        self::initDb();
        $query = "
            SELECT * FROM password_reset_tokens
            WHERE token = ? AND used = false AND expires_at > NOW()
            LIMIT 1
        ";
        return Database::fetchOne($query, [$token]);
    }

    /**
     * Reset password using token
     */
    public static function resetPassword(string $token, string $newPassword): bool
    {
        $tokenData = self::validatePasswordResetToken($token);
        
        if (!$tokenData) {
            return false;
        }

        self::initDb();
        $passwordHash = Security::hashPassword($newPassword);

        // Update user password
        Database::update('users', ['password_hash' => $passwordHash], 'id = ?', [$tokenData['user_id']]);

        // Mark token as used
        Database::update('password_reset_tokens', ['used' => true], 'id = ?', [$tokenData['id']]);

        return true;
    }

    /**
     * Get all users with roles
     */
    public static function allWithRoles(): array
    {
        self::initDb();
        $query = "
            SELECT u.*, r.name as role_name, r.description as role_description
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            ORDER BY u.created_at DESC
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get staff statistics by role
     */
    public static function getStaffStatistics(): array
    {
        self::initDb();
        $query = "
            SELECT r.name as role_name, COUNT(u.id) as count
            FROM roles r
            LEFT JOIN users u ON r.id = u.role_id
            GROUP BY r.id, r.name
        ";
        return Database::fetchAll($query);
    }

    /**
     * Get recent activity logs
     * 
     * @param int $limit Number of logs to fetch
     * @return array
     */
    public static function getRecentActivity(int $limit = 10): array
    {
        self::initDb();
        $query = "
            SELECT al.*, u.first_name, u.last_name, u.username
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT ?
        ";
        return Database::fetchAll($query, [$limit]);
    }

    /**
     * Check if user has permission
     */
    public static function hasPermission(array $user, string $permission): bool
    {
        // Admin has all permissions
        if (isset($user['role_name']) && strtolower($user['role_name']) === 'administrator') {
            return true;
        }

        // Check role permissions
        if (isset($user['permissions'])) {
            $permissions = json_decode($user['permissions'], true);
            if (is_array($permissions)) {
                if (isset($permissions['all']) && $permissions['all']) {
                    return true;
                }
                // Check specific permission
                foreach ($permissions as $key => $value) {
                    if (strpos($permission, $key) === 0) {
                        if (is_array($value)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get users with pending/inactive registrations (admin dashboard)
     */
    public static function getPendingRegistrations(): array
    {
        self::initDb();
        $query = "
            SELECT u.*, r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.is_active = false
            ORDER BY u.created_at DESC
        ";
        return Database::fetchAll($query);
    }
}
