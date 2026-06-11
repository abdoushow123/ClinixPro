<?php
/**
 * ClinixPro - Hospital Management System
 * Configuration File
 */

return [
    // Application
    'app' => [
        'name' => 'ClinixPro',
        'env' => getenv('APP_ENV') ?: 'development',
        'debug' => true,
        'url' => getenv('APP_URL') ?: 'http://localhost',
        'timezone' => 'UTC',
    ],

    // Database
    'database' => [
        'driver' => 'pgsql',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '5432',
        'database' => getenv('DB_NAME') ?: 'clinixpro',
        'username' => getenv('DB_USER') ?: 'postgres',
        'password' => getenv('DB_PASSWORD') ?: 'ServBay.dev',
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],

    // Session
    'session' => [
        'name' => 'clinixpro_session',
        'lifetime' => 3600, // 1 hour
        'path' => '/',
        'domain' => getenv('SESSION_DOMAIN') ?: '',
        'secure' => getenv('APP_ENV') === 'production', // Only secure in production
        'httponly' => true,
        'samesite' => 'Lax', // Changed from Strict to Lax for better compatibility
    ],

    // Security
    'security' => [
        'csrf_token_name' => 'csrf_token',
        'csrf_token_lifetime' => 3600,
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
        'password_reset_ttl' => 3600, // 1 hour
    ],

    // Logging
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => getenv('LOG_LEVEL') ?: 'info',
        'max_files' => 30,
    ],

    // Paths
    'paths' => [
        'base' => dirname(__DIR__),
        'app' => dirname(__DIR__) . '/app',
        'public' => dirname(__DIR__) . '/public',
        'storage' => dirname(__DIR__) . '/storage',
        'logs' => dirname(__DIR__) . '/logs',
    ],

    // Views
    'views' => [
        'path' => dirname(__DIR__) . '/app/views',
        'cache' => dirname(__DIR__) . '/storage/cache/views',
        'compiled' => dirname(__DIR__) . '/storage/cache/compiled',
    ],
];
