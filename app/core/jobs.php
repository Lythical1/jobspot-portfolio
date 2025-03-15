<?php

require_once 'database.php';
require_once 'algoSearch.php';
require_once 'job_searchers.php';

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
        $results = ['jobs' => [], 'searchers' => []];
        $pdo = Database::connectDb();
    
        $sql = "SELECT jobs.*, companies.name as company 
                FROM jobs 
                LEFT JOIN companies ON jobs.company_id = companies.id";
        
        $params = [];
        $conditions = [];
        
        // Add category filter
        if (!empty($categoryFilters)) {
            $placeholders = implode(',', array_fill(0, count($categoryFilters), '?'));
            $conditions[] = "jobs.category_id IN ($placeholders)";
            $params = array_values($categoryFilters);
        }
        
        if (!empty($query)) {
            $conditions[] = "jobs.title LIKE ?";
            $params[] = "%$query%";
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        // Execute job query
        $stmt = $pdo->prepare($sql);
        foreach ($params as $index => $value) {
            $stmt->bindValue($index + 1, $value);
        }
        $stmt->execute();
        $results['jobs'] = $this->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));

        // Get job seekers
        $jobSearcherRepo = new JobSearcher();
        
        // Apply category filter to job searchers
        if (!empty($categoryFilters)) {
            $allSearchers = $jobSearcherRepo->filterSearchers($categoryFilters);
        } else {
            $allSearchers = $jobSearcherRepo->getSearchers();
        }
        
        // Apply text filter to job seekers if query exists
        if (!empty($query)) {
            $allSearchers = array_filter($allSearchers, function ($searcher) use ($query) {
                return SearchHelper::areSimilar($searcher['title'], $query);
            });
        }
        
        $results['searchers'] = $this->formatSalaryRanges($allSearchers);
        return $results;
    }

    private function formatSalaryRanges($items)
    {
        return array_map(function ($item) {
            if (isset($item['salary_range'])) {
                $item['salary_range'] = SearchHelper::formatSalary($item['salary_range']);
            }
            return $item;
        }, $items);
    }
}
