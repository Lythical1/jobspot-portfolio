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
        
        $searchHelper = new SearchHelper();
        $formattedJobs = $searchHelper->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));
        return ['jobs' => $formattedJobs];
    }

    public function getCompanyID($userId)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getOffersByCompany($userId)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("
            SELECT jobs.*, companies.name as company 
            FROM jobs 
            LEFT JOIN companies ON jobs.company_id = companies.id
            WHERE jobs.company_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createJobOffer($data)
    {
        $pdo = Database::connectDb();
        
        // Clean salary: remove euro symbol if present
        if (!empty($data['salary_range'])) {
            // Remove € symbol if present
            $data['salary_range'] = str_replace('€', '', $data['salary_range']);

            $data['salary_range'] = str_replace('.', '', $data['salary_range']);

            $data['salary_range'] = str_replace(',', '', $data['salary_range']);
            
            // Add EUR prefix if not already present
            if (strpos($data['salary_range'], 'EUR') !== 0) {
                $data['salary_range'] = 'EUR' . $data['salary_range'];
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO jobs (title, description, salary_range, category_id, company_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['salary_range'],
            $data['category_id'],
            $data['company_id']
        ]);
        $jobId = $pdo->lastInsertId();
        return $jobId;
    }
}
