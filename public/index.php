<?php
/**
 * ClinixPro - Hospital Management System
 * Front Controller
 * 
 * All requests are routed through this file.
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
$config = require BASE_PATH . '/config/config.php';

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Error reporting based on environment
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $config['logging']['path'] . '/php_error.log');
}

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = BASE_PATH . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize session
use App\Core\Session;
Session::init();

// Initialize database
use App\Core\Database;
Database::init($config['database']);

// Load routes
require BASE_PATH . '/routes/web.php';

// Get URL path
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch route
try {
    $router->dispatch($url);
} catch (\Exception $e) {
    // Log error
    error_log($e->getMessage());
    
    // Show error page
    if ($config['app']['debug']) {
        http_response_code(500);
        echo '<h1>Internal Server Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        require BASE_PATH . '/app/views/errors/500.php';
    }
}
