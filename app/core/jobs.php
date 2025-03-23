<?php

require_once 'database.php';
require_once 'algoSearch.php';

class JobRepository
{
    public function getJobs()
    {
            $pdo = Database::connectDb();
            $stmt = $pdo->prepare("
                SELECT jobs.*, companies.name as company 
                FROM jobs 
                LEFT JOIN companies ON jobs.company_id = companies.id
            ");
            $stmt->execute();
            return $stmt->fetchAll();
    }

    public function getCategories()
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchJobs($query, $categoryFilters = [])
    {
        $pdo = Database::connectDb();
        $sql = "SELECT jobs.*, companies.name as company, categories.name as category 
                FROM jobs 
                LEFT JOIN companies ON jobs.company_id = companies.id
                LEFT JOIN categories ON jobs.category_id = categories.id";
        
        $params = [];
        $conditions = [];
        
        // Add category filter (we'll keep this in SQL for efficiency)
        if (!empty($categoryFilters)) {
            $placeholders = implode(',', array_fill(0, count($categoryFilters), '?'));
            $conditions[] = "jobs.category_id IN ($placeholders)";
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
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filteredJobs = $jobs;
        
        // Apply text search using areSimilar if query is provided
        if (!empty($query)) {
            $filteredJobs = [];
            foreach ($jobs as $job) {
                // Check if query matches title, description or required skills using areSimilar
                if (SearchHelper::areSimilar($query, $job['title']) ||
                    SearchHelper::areSimilar($query, $job['category'] ?? '')) {
                    $filteredJobs[] = $job;
                }
            }
        }
        
        $searchHelper = new SearchHelper();
        $formattedJobs = $searchHelper->formatSalaryRanges($filteredJobs);
        return ['jobs' => $formattedJobs];
    }

    public function getRandomJobs($amount = 3) 
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("
            SELECT jobs.*, companies.name as company, categories.name as category 
            FROM jobs 
            LEFT JOIN companies ON jobs.company_id = companies.id 
            LEFT JOIN categories ON jobs.category_id = categories.id
            ORDER BY RAND() LIMIT ?
        ");
        $stmt->bindValue(1, $amount, PDO::PARAM_INT);
        $stmt->execute();
        $searchHelper = new SearchHelper();
        return $searchHelper->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
