<?php
/**
 * ClinixPro - Hospital Management System
 * Base Model
 * 
 * All models extend this base class for database operations.
 */

namespace App\Core;

use PDO;

abstract class Model
{
    protected static ?PDO $db = null;
    protected string $table;
    protected string $primaryKey = 'id';

    /**
     * Initialize database connection
     */
    public static function initDb(): void
    {
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
    }

    /**
     * Find record by ID
     * 
     * @param int $id Record ID
     * @return array|false
     */
    public static function find(int $id)
    {
        self::initDb();
        $instance = new static();
        $query = "SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ? LIMIT 1";
        return Database::fetchOne($query, [$id]);
    }

    /**
     * Find record by column value
     * 
     * @param string $column Column name
     * @param mixed $value Column value
     * @return array|false
     */
    public static function findBy(string $column, $value)
    {
        self::initDb();
        $instance = new static();
        $query = "SELECT * FROM {$instance->table} WHERE $column = ? LIMIT 1";
        return Database::fetchOne($query, [$value]);
    }

    /**
     * Get all records
     * 
     * @param array $conditions WHERE conditions
     * @param string $orderBy ORDER BY clause
     * @param int $limit LIMIT value
     * @return array
     */
    public static function all(array $conditions = [], string $orderBy = '', int $limit = 0): array
    {
        self::initDb();
        $instance = new static();
        
        $query = "SELECT * FROM {$instance->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "$column = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }
        
        if ($limit > 0) {
            $query .= " LIMIT $limit";
        }
        
        return Database::fetchAll($query, $params);
    }

    /**
     * Create new record
     * 
     * @param array $data Record data
     * @return int Inserted ID
     */
    public static function create(array $data): int
    {
        self::initDb();
        $instance = new static();
        
        // Remove primary key from data if present
        unset($data[$instance->primaryKey]);
        
        return Database::insert($instance->table, $data);
    }

    /**
     * Update record
     * 
     * @param int $id Record ID
     * @param array $data Data to update
     * @return int Number of affected rows
     */
    public static function update(int $id, array $data): int
    {
        self::initDb();
        $instance = new static();
        
        // Remove primary key from data
        unset($data[$instance->primaryKey]);
        
        $where = "{$instance->primaryKey} = ?";
        return Database::update($instance->table, $data, $where, [$id]);
    }

    /**
     * Delete record
     * 
     * @param int $id Record ID
     * @return int Number of affected rows
     */
    public static function delete(int $id): int
    {
        self::initDb();
        $instance = new static();
        
        $where = "{$instance->primaryKey} = ?";
        return Database::delete($instance->table, $where, [$id]);
    }

    /**
     * Count records
     * 
     * @param array $conditions WHERE conditions
     * @return int
     */
    public static function count(array $conditions = []): int
    {
        self::initDb();
        $instance = new static();
        
        $query = "SELECT COUNT(*) FROM {$instance->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "$column = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        return (int) Database::fetchColumn($query, $params);
    }

    /**
     * Check if record exists
     * 
     * @param int $id Record ID
     * @return bool
     */
    public static function exists(int $id): bool
    {
        return self::find($id) !== false;
    }

    /**
     * Execute custom query
     * 
     * @param string $query SQL query
     * @param array $params Query parameters
     * @return array
     */
    public static function query(string $query, array $params = []): array
    {
        self::initDb();
        return Database::fetchAll($query, $params);
    }

    /**
     * Get table name
     * 
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get primary key
     * 
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}
