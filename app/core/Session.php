<?php
/**
 * ClinixPro - Hospital Management System
 * Session Manager
 * 
 * Handles secure session management.
 */

namespace App\Core;

class Session
{
    /**
     * Initialize session
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../../config/config.php';
            $sessionConfig = $config['session'];
            
            // Only set secure cookie if in production or HTTPS
            $isSecure = $sessionConfig['secure'] || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
            
            // Set session cookie parameters first
            session_name($sessionConfig['name']);
            session_set_cookie_params([
                'lifetime' => $sessionConfig['lifetime'],
                'path' => $sessionConfig['path'],
                'domain' => $sessionConfig['domain'],
                'secure' => $isSecure,
                'httponly' => $sessionConfig['httponly'],
                'samesite' => $sessionConfig['samesite'],
            ]);
            
            // Set ini settings after cookie params
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', $isSecure ? '1' : '0');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_samesite', $sessionConfig['samesite']);
            
            session_start();
            
            // Regenerate session ID periodically
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } else if (time() - $_SESSION['created'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    /**
     * Set session value
     * 
     * @param string $key Session key
     * @param mixed $value Session value
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     * 
     * @param string $key Session key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     * 
     * @param string $key Session key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session value
     * 
     * @param string $key Session key
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Set flash message (available on next request)
     * 
     * @param string $key Flash key
     * @param mixed $value Flash value
     */
    public static function flash(string $key, $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * Get flash message
     * 
     * @param string $key Flash key
     * @param mixed $default Default value
     * @return mixed
     */
    public static function getFlash(string $key, $default = null)
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return $default;
    }

    /**
     * Check if flash message exists
     * 
     * @param string $key Flash key
     * @return bool
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Destroy session
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Get session ID
     * 
     * @return string
     */
    public static function getId(): string
    {
        return session_id();
    }
}
