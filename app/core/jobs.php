<?php

require_once 'database.php';

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
    
    public function filterJobs($filters)
    {
        $pdo = Database::connectDb();
        if (!is_array($filters)) {
            $filters = [$filters];
        }
        $filterPlaceholders = implode(',', array_fill(0, count($filters), '?'));
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE category_id IN ($filterPlaceholders)");
        
        foreach ($filters as $index => $filter) {
            $stmt->bindValue($index + 1, $filter, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
