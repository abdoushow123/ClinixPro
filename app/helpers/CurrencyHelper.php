<?php
/**
 * ClinixPro - Hospital Management System
 * Currency Helper
 */

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format a number as currency
     * 
     * @param float|int $amount
     * @param string $symbol
     * @return string
     */
    public static function format(float|int $amount, string $symbol = '$'): string
    {
        return $symbol . number_format((float)$amount, 2);
    }
}
