<?php
define('BASE_PATH', dirname(__DIR__));

// Load configuration
$config = require BASE_PATH . '/config/config.php';

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

use App\Core\Database;
use App\Models\Notification;

try {
    Database::init($config['database']);
    
    // Insert a test notification for user ID 1 (Admin)
    Notification::create(
        1,
        'Welcome to ClinixPro',
        'Your new notification system is now active. You will receive updates about appointments, records, and team management right here.',
        'success',
        '/dashboard'
    );
    
    Notification::create(
        1,
        'System Update Scheduled',
        'The hospital management system will undergo maintenance tonight at 2:00 AM.',
        'warning'
    );
    
    echo "Test notifications inserted successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
