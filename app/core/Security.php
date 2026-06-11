<?php
/**
 * ClinixPro - Hospital Management System
 * Security Utilities
 * 
 * Handles security-related operations like CSRF, XSS protection, password hashing.
 */

namespace App\Core;

class Security
{
    /**
     * Generate CSRF token
     * 
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        $config = require __DIR__ . '/../../config/config.php';
        
        // Check if a valid token already exists in session
        $existingToken = Session::get('csrf_token');
        $tokenTime = Session::get('csrf_token_time');
        
        // If token exists and hasn't expired, reuse it
        if ($existingToken && $tokenTime && (time() - $tokenTime < $config['security']['csrf_token_lifetime'])) {
            return $existingToken;
        }
        
        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        Session::set('csrf_token', $token);
        Session::set('csrf_token_time', time());
        
        return $token;
    }

    /**
     * Validate CSRF token
     * 
     * @param string|null $token Token to validate
     * @return bool
     */
    public static function validateCsrfToken(?string $token): bool
    {
        $config = require __DIR__ . '/../../config/config.php';
        $sessionToken = Session::get('csrf_token');
        $tokenTime = Session::get('csrf_token_time');
        
        if (!$token || !$sessionToken) {
            return false;
        }
        
        // Check token expiration
        if (time() - $tokenTime > $config['security']['csrf_token_lifetime']) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }

    /**
     * Hash password
     * 
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);
    }

    /**
     * Verify password
     * 
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Sanitize input data
     * 
     * @param string $data Input data
     * @return string Sanitized data
     */
    public static function sanitizeInput(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    /**
     * Sanitize array of inputs
     * 
     * @param array $data Input data array
     * @return array Sanitized data array
     */
    public static function sanitizeArray(array $data): array
    {
        return array_map(function($item) {
            if (is_string($item)) {
                return self::sanitizeInput($item);
            }
            return $item;
        }, $data);
    }

    /**
     * Escape output for HTML
     * 
     * @param string $data Data to escape
     * @return string Escaped data
     */
    public static function escape(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     * 
     * @param string $email Email address
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate password strength
     * 
     * @param string $password Password to validate
     * @return bool
     */
    public static function validatePasswordStrength(string $password): bool
    {
        $config = require __DIR__ . '/../../config/config.php';
        $minLength = $config['security']['password_min_length'];
        
        if (strlen($password) < $minLength) {
            return false;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }

    /**
     * Generate random token
     * 
     * @param int $length Token length
     * @return string
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Get client IP address
     * 
     * @return string
     */
    public static function getClientIp(): string
    {
        $ip = '';
        
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    /**
     * Get user agent
     * 
     * @return string
     */
    public static function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    /**
     * Check if request is from same origin
     * 
     * @return bool
     */
    public static function isSameOrigin(): bool
    {
        $config = require __DIR__ . '/../../config/config.php';
        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        $appUrl = parse_url($config['app']['url']);
        
        return strpos($origin, $appUrl['host']) !== false;
    }

    /**
     * Generate UUID v4
     * 
     * @return string
     */
    public static function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Encrypt sensitive data
     * 
     * @param string $data Data to encrypt
     * @param string $key Encryption key
     * @return string
     */
    public static function encrypt(string $data, string $key): string
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt sensitive data
     * 
     * @param string $data Data to decrypt
     * @param string $key Decryption key
     * @return string|false
     */
    public static function decrypt(string $data, string $key)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     * Rate limit check
     * 
     * @param string $identifier Unique identifier (e.g., IP)
     * @param int $maxAttempts Maximum attempts
     * @param int $window Time window in seconds
     * @return bool
     */
    public static function checkRateLimit(string $identifier, int $maxAttempts, int $window = 60): bool
    {
        $key = 'rate_limit_' . md5($identifier);
        $attempts = Session::get($key, ['count' => 0, 'time' => time()]);
        
        // Reset if window expired
        if (time() - $attempts['time'] > $window) {
            $attempts = ['count' => 0, 'time' => time()];
        }
        
        if ($attempts['count'] >= $maxAttempts) {
            return false;
        }
        
        $attempts['count']++;
        Session::set($key, $attempts);
        
        return true;
    }
}
