<?php
/**
 * ClinixPro - Hospital Management System
 * Database Manager (PDO)
 * 
 * Handles all database connections and queries using prepared statements only.
 */

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config;

    /**
     * Initialize database with configuration
     */
    public static function init(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Get singleton PDO instance
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private static function connect(): void
    {
        try {
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s',
                self::$config['driver'],
                self::$config['host'],
                self::$config['port'],
                self::$config['database']
            );

            self::$instance = new PDO(
                $dsn,
                self::$config['username'],
                self::$config['password'],
                self::$config['options']
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \RuntimeException("Database connection failed");
        }
    }

    /**
     * Execute a prepared statement query
     * 
     * @param string $query SQL query with placeholders
     * @param array $params Parameters for prepared statement
     * @return \PDOStatement
     */
    public static function query(string $query, array $params = []): \PDOStatement
    {
        try {
            $pdo = self::getInstance();
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage() . " | Query: " . $query);
            throw new \RuntimeException("Database query failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch all rows from a query
     * 
     * @param string $query SQL query
     * @param array $params Parameters
     * @return array
     */
    public static function fetchAll(string $query, array $params = []): array
    {
        $stmt = self::query($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch a single row
     * 
     * @param string $query SQL query
     * @param array $params Parameters
     * @return array|false
     */
    public static function fetchOne(string $query, array $params = [])
    {
        $stmt = self::query($query, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch a single column value
     * 
     * @param string $query SQL query
     * @param array $params Parameters
     * @return mixed
     */
    public static function fetchColumn(string $query, array $params = [])
    {
        $stmt = self::query($query, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insert a record and return the last insert ID
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int
     */
    public static function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        // Convert boolean values to integers for PostgreSQL compatibility
        $values = array_values($data);
        foreach ($values as $key => $value) {
            if (is_bool($value)) {
                $values[$key] = $value ? 1 : 0;
            }
        }
        
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        self::query($query, $values);
        return (int) self::getInstance()->lastInsertId();
    }

    /**
     * Update records
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause
     * @param array $whereParams Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = ?";
        }

        $query = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            implode(', ', $setParts),
            $where
        );

        $params = array_merge(array_values($data), $whereParams);
        $stmt = self::query($query, $params);
        return $stmt->rowCount();
    }

    /**
     * Delete records
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $query = sprintf("DELETE FROM %s WHERE %s", $table, $where);
        $stmt = self::query($query, $params);
        return $stmt->rowCount();
    }

    /**
     * Begin a transaction
     */
    public static function beginTransaction(): void
    {
        self::getInstance()->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public static function commit(): void
    {
        self::getInstance()->commit();
    }

    /**
     * Rollback a transaction
     */
    public static function rollback(): void
    {
        self::getInstance()->rollBack();
    }

    /**
     * Check if currently in a transaction
     */
    public static function inTransaction(): bool
    {
        return self::getInstance()->inTransaction();
    }

    /**
     * Close database connection
     */
    public static function close(): void
    {
        self::$instance = null;
    }
}
