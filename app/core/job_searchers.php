<?php

require_once 'database.php';

class JobSearcher
{
    public function getSearchers()
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("
            SELECT js.*, u.first_name, u.last_name 
            FROM job_searchers js 
            LEFT JOIN users u ON js.user_id = u.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
