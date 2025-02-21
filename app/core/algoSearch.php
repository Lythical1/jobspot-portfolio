<?php

class SearchHelper
{
    public static function normalizeString($string)
    {
        if ($string === null) {
            return '';
        }
        // Remove special characters and convert to lowercase
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        return trim($string);
    }

    public static function areSimilar($str1, $str2, $threshold = 3)
    {
        $str1 = self::normalizeString($str1);
        $str2 = self::normalizeString($str2);

        // Check exact match first
        if ($str1 === $str2) {
            return true;
        }

        // Check if strings are similar enough using Levenshtein distance
        $distance = levenshtein($str1, $str2);
        return $distance <= $threshold;
    }

    public static function formatSalary($salary)
    {
        if (empty($salary)) {
            return 'Salary not specified';
        }
        $salary = str_replace('EUR', '', $salary);
        $parts = explode('-', $salary);
        if (count($parts) === 2) {
            return '€ ' . trim($parts[0]) . '  -  € ' . trim($parts[1]);
        }
        return '€ ' . trim($salary);
    }
}
