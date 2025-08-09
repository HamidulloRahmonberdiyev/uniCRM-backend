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

if (!function_exists('sanitizePhone')) {
    /**
     * Remove all non-numeric characters from a phone number.
     *  
     * Example:
     *   Input: "+1 (234) 567-8900"
     *   Output: "12345678900"
     *
     * @param string|null $phone The phone number to sanitize.
     * @return string|null The sanitized phone number or null if input is null.
     */
    function sanitizePhone(?string $phone): ?string
    {
        return $phone ? preg_replace('/\D/', '', $phone) : null;
    }
}

if (!function_exists('getUzbekMonth')) {
    /**
     * Inglizcha oy nomlarini o‘zbek tiliga tarjima qiladi.
     *
     * @param string $englishMonth Inglizcha oy nomi (masalan, "January").
     * @return string O‘zbekcha oy nomi (masalan, "Yanvar").
     */
    function getUzbekMonth($englishMonth)
    {
        $months = [
            'January' => 'Yanvar',
            'February' => 'Fevral',
            'March' => 'Mart',
            'April' => 'Aprel',
            'May' => 'May',
            'June' => 'Iyun',
            'July' => 'Iyul',
            'August' => 'Avgust',
            'September' => 'Sentyabr',
            'October' => 'Oktabr',
            'November' => 'Noyabr',
            'December' => 'Dekabr'
        ];
        return $months[$englishMonth] ?? $englishMonth;
    }
}

if (!function_exists('formatPhone')) {
    /** 
     * This function ensures the phone number is correctly formatted by:
     * - Formatting it into +998 XX XXX-XX-XX
     *
     * Example:
     *   Input: "998901234567" or "901234567"
     *   Output: "+998 90 123-45-67"
     *
     * @param string|null $phone The raw phone number.
     */
    function formatPhone(?string $phone): ?string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (!$phone || strlen($phone) < 9) {
            return null;
        }
        if (strlen($phone) === 9) {
            $phone = '998' . $phone;
        }
        if (strlen($phone) !== 12 || substr($phone, 0, 3) !== '998') {
            return null;
        }

        return sprintf(
            '+998 %s %s-%s-%s',
            substr($phone, 3, 2),
            substr($phone, 5, 3),
            substr($phone, 8, 2),
            substr($phone, 10, 2)
        );
    }

    if (!function_exists('extractNameAndPhone')) {
        /**
         * Berilgan matndan ism va telefon raqamini ajratib oladi.
         *
         * @param string $input Foydalanuvchidan olingan ism yoki telefon raqami
         * @return array ['name' => '...', 'phone' => '...']
         */
        function extractNameAndPhone(string $input): array
        {
            $name = trim(preg_replace('/[^a-zA-Z\s]/', '', $input)) ?: null;
            $phone = trim(preg_replace('/[^0-9]/', '', $input)) ?: null;

            return [
                'name' => $name ?? '',
                'phone' => $phone ?? '',
            ];
        }
    }

    if (!function_exists('formatPrice')) {
        /**
         * @param float|int $amount The price to format (e.g. 15000 or 15000.75)
         * @param string $decimal_point The decimal point (default: '.')
         *
         * @return string The formatted price, without currency (e.g. 15 000)
         */

        function formatPrice($amount, $decimals = 0, $thousands_sep = ' ', $decimal_point = '.')
        {
            return number_format($amount, $decimals, $decimal_point, $thousands_sep);
        }
    }
}
