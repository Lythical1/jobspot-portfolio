<?php

require_once 'database.php';
require_once 'algoSearch.php';

class JobSearcher
{
    public function getSearchers($query, $categoryFilters = [])
    {
        $pdo = Database::connectDb();
        $sql = "SELECT job_searchers.*, categories.name as category 
                FROM job_searchers 
                LEFT JOIN categories ON job_searchers.category_id = categories.id";
        
        $params = [];
        $conditions = [];
        
        // Add category filter (keep this in SQL for efficiency)
        if (!empty($categoryFilters)) {
            $placeholders = implode(',', array_fill(0, count($categoryFilters), '?'));
            $conditions[] = "job_searchers.category_id IN ($placeholders)";
            $params = array_values($categoryFilters);
        }
        
        // We're not using LIKE search anymore, but we'll fetch all potential matches
        // and filter later with areSimilar
        
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $stmt = $pdo->prepare($sql);
        foreach ($params as $index => $value) {
            $stmt->bindValue($index + 1, $value);
        }
        $stmt->execute();
        
        $searchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filteredSearchers = $searchers;
        
        // Apply text search using areSimilar if query is provided
        if (!empty($query)) {
            $filteredSearchers = [];
            foreach ($searchers as $searcher) {
                // Check if query matches title, skills or other relevant fields
                if (SearchHelper::areSimilar($query, $searcher['title']) ||
                    SearchHelper::areSimilar($query, $searcher['category'] ?? '')) {
                    $filteredSearchers[] = $searcher;
                }
            }
        }
        
        $searchHelper = new SearchHelper();
        $formattedSearchers = $searchHelper->formatSalaryRanges($filteredSearchers);
        return ['searchers' => $formattedSearchers];
    }

    public function filterSearchers($filters)
    {
        $pdo = Database::connectDb();
        if (!is_array($filters)) {
            $filters = [$filters];
        }
        $filterPlaceholders = implode(',', array_fill(0, count($filters), '?'));
        $stmt = $pdo->prepare("SELECT * FROM job_searchers WHERE category_id IN ($filterPlaceholders)");
        
        foreach ($filters as $index => $filter) {
            $stmt->bindValue($index + 1, $filter, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRandomSearchers($amount = 3)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT job_searchers.*, categories.name as category 
                               FROM job_searchers 
                               LEFT JOIN categories ON job_searchers.category_id = categories.id 
                               ORDER BY RAND() LIMIT ?");
        $stmt->bindValue(1, $amount, PDO::PARAM_INT);
        $stmt->execute();
        $searchHelper = new SearchHelper();
        return $searchHelper->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
