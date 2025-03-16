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
        
        // Add category filter
        if (!empty($categoryFilters)) {
            $placeholders = implode(',', array_fill(0, count($categoryFilters), '?'));
            $conditions[] = "job_searchers.category_id IN ($placeholders)";
            $params = array_values($categoryFilters);
        }
        
        if (!empty($query)) {
            $conditions[] = "job_searchers.name LIKE ?";
            $params[] = "%$query%";
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $pdo->prepare($sql);
        foreach ($params as $index => $value) {
            $stmt->bindValue($index + 1, $value);
        }
        $stmt->execute();
        $searchHelper = new SearchHelper();
        $formattedSearchers = $searchHelper->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));
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
}
