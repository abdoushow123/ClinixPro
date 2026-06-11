<?php
/**
 * ClinixPro - Hospital Management System
 * Laboratory Test Model
 */

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class LaboratoryTest extends Model
{
    protected string $table = 'laboratory_tests';
    protected string $primaryKey = 'id';

    /**
     * Get all active tests
     */
    public static function getActive(): array
    {
        return self::all(['is_active' => true], 'test_name');
    }

    /**
     * Get tests by category
     */
    public static function getByCategory(string $category): array
    {
        return self::all(['category' => $category, 'is_active' => true], 'test_name');
    }

    /**
     * Get categories
     */
    public static function getCategories(): array
    {
        self::initDb();
        $query = "SELECT DISTINCT category FROM laboratory_tests WHERE is_active = true ORDER BY category";
        $results = Database::fetchAll($query);
        return array_column($results, 'category');
    }

    /**
     * Find by test code
     */
    public static function findByCode(string $code)
    {
        return self::findBy('test_code', $code);
    }
}
