<?php

if (!function_exists('truncateDecimal')) {
    /**
     * Truncate a decimal value to show a minimum number of decimals
     * while preserving significant digits
     *
     * @param float|int|string $value The value to truncate
     * @param int $minDecimals Minimum number of decimal places to show (default: 2)
     *
     * @return string The truncated decimal as a string
     */
    function truncateDecimal($value, int $minDecimals = 2): string
    {
        $value = (string)$value;

        if (strpos($value, '.') === false) {
            // No decimals, just force minimum
            return number_format((int)$value, $minDecimals, '.', '');
        }

        [$int, $dec] = explode('.', $value, 2);

        // Remove trailing zeroes from original decimal string
        $dec = rtrim($dec, '0');

        // Find position of first non-zero digit
        $firstNonZero = strspn($dec, '0') + 1;

        // Needed decimals = max(minDecimals, position of first non-zero)
        $needed = max($minDecimals, $firstNonZero);

        // Cut (truncate) decimals to $needed
        $dec = substr($dec, 0, $needed);

        // Pad if too short
        $dec = str_pad($dec, $needed, '0');

        return $int . '.' . $dec;
    }
}
