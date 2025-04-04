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
            $conditions[] = "job_searchers.title LIKE ?";
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


    public function getUserApplications($userId)
    {
        $pdo = Database::connectDb();
        $sql = 'SELECT * FROM job_searchers WHERE user_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $searchHelper = new SearchHelper();
        $formattedApplications = $searchHelper->formatSalaryRanges($applications);
        return $formattedApplications;
    }

    public function createApplication($data)
    {
        $pdo = Database::connectDb();
        
        // Clean salary: remove euro symbol if present
        if (!empty($data['salary_range'])) {
            // Remove € symbol if present
            $data['salary_range'] = str_replace('€', '', $data['salary_range']);
            
            // Add EUR prefix if not already present
            if (strpos($data['salary_range'], 'EUR') !== 0) {
                $data['salary_range'] = 'EUR' . $data['salary_range'];
            }
        }
        
        $sql = 'INSERT INTO job_searchers (user_id, title, category_id, work_hours, location, salary_range) 
                VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['category'],
            $data['work_hours'],
            $data['location'],
            $data['salary_range']
        ]);
        
        return $pdo->lastInsertId();
    }
    
    public function getCategories()
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
