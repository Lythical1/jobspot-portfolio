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

    public function searchJobs($query)
    {
        $results = [
            'jobs' => [],
            'searchers' => []
        ];

        $pdo = Database::connectDb();
        
        // Get all jobs
        $stmt = $pdo->prepare("
            SELECT jobs.*, companies.name as company 
            FROM jobs 
            LEFT JOIN companies ON jobs.company_id = companies.id
        ");
        $stmt->execute();
        $allJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all searchers
        $jobSearcherRepo = new JobSearcher();
        $allSearchers = $jobSearcherRepo->getSearchers();

        if (empty($query)) {
            $results['jobs'] = $this->formatSalaryRanges($allJobs);
            $results['searchers'] = $this->formatSalaryRanges($allSearchers);
            return $results;
        }

        // Filter jobs based on similarity
        $results['jobs'] = $this->formatSalaryRanges(array_filter($allJobs, function ($job) use ($query) {
            return SearchHelper::areSimilar($job['title'], $query);
        }));

        // Filter searchers based on similarity
        $results['searchers'] = $this->formatSalaryRanges(array_filter($allSearchers, function ($searcher) use ($query) {
            return SearchHelper::areSimilar($searcher['title'], $query);
        }));

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
