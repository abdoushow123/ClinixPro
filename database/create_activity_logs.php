<?php
/**
 * Create activity_logs and login_logs tables
 * Run this script to create the missing tables
 */

require_once __DIR__ . '/../app/core/Database.php';
$config = require __DIR__ . '/../config/config.php';

use App\Core\Database;

try {
    Database::init($config['database']);
    
    // Create activity_logs table
    $activityLogsSql = "
        CREATE TABLE IF NOT EXISTS activity_logs (
            id SERIAL PRIMARY KEY,
            user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
            action VARCHAR(100) NOT NULL,
            entity_type VARCHAR(50),
            entity_id INTEGER,
            details JSONB,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";
    
    Database::query($activityLogsSql);
    
    // Create activity_logs indexes
    $activityIndexes = [
        "CREATE INDEX IF NOT EXISTS idx_activity_logs_user_id ON activity_logs(user_id);",
        "CREATE INDEX IF NOT EXISTS idx_activity_logs_action ON activity_logs(action);",
        "CREATE INDEX IF NOT EXISTS idx_activity_logs_created_at ON activity_logs(created_at);"
    ];
    
    foreach ($activityIndexes as $indexSql) {
        Database::query($indexSql);
    }
    
    echo "activity_logs table created successfully!\n";
    
    // Create login_logs table
    $loginLogsSql = "
        CREATE TABLE IF NOT EXISTS login_logs (
            id SERIAL PRIMARY KEY,
            user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            status VARCHAR(20) NOT NULL,
            failure_reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";
    
    Database::query($loginLogsSql);
    
    // Create login_logs indexes
    $loginIndexes = [
        "CREATE INDEX IF NOT EXISTS idx_login_logs_user_id ON login_logs(user_id);",
        "CREATE INDEX IF NOT EXISTS idx_login_logs_status ON login_logs(status);",
        "CREATE INDEX IF NOT EXISTS idx_login_logs_created_at ON login_logs(created_at);"
    ];
    
    foreach ($loginIndexes as $indexSql) {
        Database::query($indexSql);
    }
    
    echo "login_logs table created successfully!\n";
    
    // Add created_by column to users table if it doesn't exist
    try {
        $alterSql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS created_by INTEGER REFERENCES users(id);";
        Database::query($alterSql);
        echo "created_by column added to users table successfully!\n";
    } catch (Exception $e) {
        echo "Note: created_by column may already exist or error occurred: " . $e->getMessage() . "\n";
    }
    
    // Check if roles table exists and has required columns
    try {
        $checkRoles = Database::fetchColumn("SELECT COUNT(*) FROM information_schema.tables WHERE table_name = 'roles'");
        if ($checkRoles == 0) {
            echo "Creating roles table...\n";
            $rolesSql = "
                CREATE TABLE roles (
                    id SERIAL PRIMARY KEY,
                    name VARCHAR(50) NOT NULL UNIQUE,
                    description TEXT,
                    permissions JSONB,
                    can_approve_roles INTEGER[] DEFAULT ARRAY[]::INTEGER[],
                    requires_approval BOOLEAN DEFAULT true,
                    auto_approve BOOLEAN DEFAULT false,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ";
            Database::query($rolesSql);
            
            // Insert default roles
            $insertRoles = "
                INSERT INTO roles (name, description, permissions, can_approve_roles, requires_approval, auto_approve) VALUES
                ('Administrator', 'Full system access', '{\"all\": true}', '{1,2,3,4,5}', false, true),
                ('Doctor', 'Medical practitioner access', '{\"patients\": true, \"medical_records\": true, \"prescriptions\": true, \"appointments\": true}', '{3}', true, false),
                ('Nurse', 'Nursing staff access', '{\"patients\": true, \"medical_records\": true, \"appointments\": true}', '{3}', true, false),
                ('Pharmacist', 'Pharmacy management access', '{\"prescriptions\": true, \"pharmacy\": true}', '{3}', true, false),
                ('Lab Technician', 'Laboratory access', '{\"laboratory\": true}', '{3}', true, false);
            ";
            Database::query($insertRoles);
            echo "Roles table created and populated successfully!\n";
        }
    } catch (Exception $e) {
        echo "Note: Roles table check/creation error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
}
