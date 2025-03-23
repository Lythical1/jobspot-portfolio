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

        if ($str1 === $str2) {
            return true;
        }

        if (empty($str1) || empty($str2)) {
            return false;
        }

        $words1 = array_filter(explode(' ', $str1));
        $words2 = array_filter(explode(' ', $str2));
        
        if (empty($words1) || empty($words2)) {
            return false;
        }

        // Check each word in the first string against each word in the second string
        foreach ($words1 as $word1) {
            if (strlen($word1) < 3) continue;
            
            foreach ($words2 as $word2) {
                if (strlen($word2) < 3) continue;
                
                // String containment check
                // This checks if one word is a substring of the other
                if (strpos($word2, $word1) !== false || strpos($word1, $word2) !== false) {
                    return true;
                }
                
        
                $distance = levenshtein($word1, $word2);
                
                // Dynamic threshold based on word length
                $wordThreshold = min(strlen($word1), strlen($word2)) > 5 ? $threshold : 3;

                if ($distance <= $wordThreshold) {
                    return true;
                }
            }
        }

        return false;
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

    public function formatSalaryRanges($items)
    {
        return array_map(function ($item) {
            if (isset($item['salary_range'])) {
                $item['salary_range'] = self::formatSalary($item['salary_range']);
            }
            return $item;
        }, $items);
    }
}
