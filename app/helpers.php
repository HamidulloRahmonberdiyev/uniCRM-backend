<?php

use Carbon\Carbon;

if (!function_exists('formatDateTime')) {

    /**
     * Format the given timestamp into "Month Day, HH:MM" format with Uzbek month names.
     *
     * @param string|\Illuminate\Support\Carbon $timestamp The timestamp to format.
     * @return string Formatted date string in Uzbek.
     */

    function formatDateTime($timestamp)
    {
        $months = [
            'January' => 'yanvar',
            'February' => 'fevral',
            'March' => 'mart',
            'April' => 'aprel',
            'May' => 'may',
            'June' => 'iyun',
            'July' => 'iyul',
            'August' => 'avgust',
            'September' => 'sentabr',
            'October' => 'oktabr',
            'November' => 'noyabr',
            'December' => 'dekabr',
        ];

        $date = Carbon::parse($timestamp);
        $month = $months[$date->format('F')];
        return $date->format('j') . ' ' . $month . ', ' . $date->format('H:i');
    }
}

/**
 * Converts a given date into a readable format
 *
 * @param string|null $date The date string to be formatted
 * @return string|null Formatted date without time (e.g., "22.05.2025"), or null if $date is null
 */
if (!function_exists('formatDate')) {
    function formatDate($date): ?string
    {
        if ($date == null) return null;
        return Carbon::parse($date)->format('d.m.Y');
    }
}
