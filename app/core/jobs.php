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
                if (
                    SearchHelper::areSimilar($query, $job['title']) ||
                    SearchHelper::areSimilar($query, $job['category'] ?? '')
                ) {
                    $filteredJobs[] = $job;
                }
            }
        }
        
        // Return the results with the expected structure
        return ['jobs' => $filteredJobs];
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

        $searchHelper = new SearchHelper();

        $formattedJobs = $searchHelper->formatSalaryRanges($stmt->fetchAll(PDO::FETCH_ASSOC));
        return $formattedJobs;
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
            
            // Format with commas after every 3 digits
            $cleanSalary = preg_replace('/[^0-9]/', '', $data['salary_range']);
            $data['salary_range'] = 'EUR' . number_format((int)$cleanSalary);
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

    public function deleteJob($jobId)
    {
        try {
            $pdo = Database::connectDb();
            
            // Begin transaction to ensure all or nothing is deleted
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("DELETE FROM job_skills WHERE job_id = ?");
            $stmt->execute([$jobId]);
            
            $stmt = $pdo->prepare("DELETE FROM applications WHERE job_id = ?");
            $stmt->execute([$jobId]);
            
            $stmt = $pdo->prepare("DELETE FROM saved_jobs WHERE job_id = ?");
            $stmt->execute([$jobId]);
            
            $stmt = $pdo->prepare("DELETE FROM interviews WHERE job_id = ?");
            $stmt->execute([$jobId]);
            
            $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
            $stmt->execute([$jobId]);
            
            $success = $stmt->rowCount() > 0;
            
            // Commit the transaction if everything was successful
            $pdo->commit();
            
            return $success;
        } catch (PDOException $e) {
            // Rollback the transaction if there's an error
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error deleting job: " . $e->getMessage());
            return false;
        }
    }
}
