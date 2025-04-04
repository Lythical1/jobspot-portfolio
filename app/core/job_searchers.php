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
                if (
                    SearchHelper::areSimilar($query, $searcher['title']) ||
                    SearchHelper::areSimilar($query, $searcher['category'] ?? '')
                ) {
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

            $data['salary_range'] = str_replace('.', '', $data['salary_range']);

            $data['salary_range'] = str_replace(',', '', $data['salary_range']);
            
            // Format with commas
            $cleanSalary = preg_replace('/[^0-9]/', '', $data['salary_range']);
            $data['salary_range'] = 'EUR' . number_format((int)$cleanSalary);
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
        
        // Get the UUID of the inserted record
        $sql = 'SELECT id FROM job_searchers WHERE user_id = ? ORDER BY id DESC LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['id'] ?? false;
    }
    
    public function getCategories()
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteSearcher($searcherId)
    {
        try {
            $pdo = Database::connectDb();
            
            // Begin transaction to ensure all or nothing is deleted
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("DELETE FROM job_searchers WHERE id = ?");
            $stmt->execute([$searcherId]);
            $success = $stmt->rowCount() > 0;
            
            // Commit the transaction if successful
            $pdo->commit();
            
            return $success;
        } catch (PDOException $e) {
            // Rollback the transaction if there's an error
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error deleting job seeker: " . $e->getMessage());
            return false;
        }
    }
}
