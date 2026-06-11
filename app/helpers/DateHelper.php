<?php
/**
 * ClinixPro - Hospital Management System
 * Date Helper
 */

namespace App\Helpers;

class DateHelper
{
    /**
     * Format a date string to a relative time (e.g., "2 hours ago")
     * 
     * @param string $dateString
     * @return string
     */
    public static function formatRelativeTime(string $dateString): string
    {
        $date = strtotime($dateString);
        if (!$date) {
            return 'Invalid date';
        }

        $now = time();
        $diff = $now - $date;

        if ($diff < 60) {
            return 'Just now';
        }

        $minutes = floor($diff / 60);
        if ($minutes < 60) {
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        }

        $hours = floor($minutes / 60);
        if ($hours < 24) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        }

        $days = floor($hours / 24);
        if ($days < 30) {
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        }

        $months = floor($days / 30);
        if ($months < 12) {
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        }

        $years = floor($months / 12);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }

    /**
     * Format date
     */
    public static function formatDate(string $dateString, string $format = 'M j, Y'): string
    {
        $date = strtotime($dateString);
        return $date ? date($format, $date) : 'Invalid date';
    }

    /**
     * Format time
     */
    public static function formatTime(string $timeString, string $format = 'g:i A'): string
    {
        $time = strtotime($timeString);
        return $time ? date($format, $time) : 'Invalid time';
    }
}
