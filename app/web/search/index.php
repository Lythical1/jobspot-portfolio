<?php
session_start();
// Capture the query parameter; default to an empty string if not set.
$searchQuery = $_GET['q'] ?? '';
$role = $_SESSION['user_role'] ?? 'guest';

require_once '../../core/jobs.php';
require_once '../../core/job_searchers.php';

$jobRepo = new JobRepository();
$jobs = $jobRepo->getJobs();

$jobSearcherRepo = new JobSearcher();
$searchers = $jobSearcherRepo->getSearchers();

if (!empty($searchQuery)) {
    $filteredSearchers = array_filter($searchers, function ($searcher) use ($searchQuery) {
        return stripos($searcher['name'], $searchQuery) !== false;
    });
} else {
    $filteredSearchers = $searchers;
}

include '../../core/navbar.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <!-- Tailwind CSS is already included -->
</head>
<body class="font-sans">
    <!-- ...existing navbar code... -->
    <div class="flex">
        <div class="w-64 bg-gray-100 p-3">
            <div class="mb-4">
                <h3 class="mb-2 text-lg font-bold">Jobs</h3>
                <div class="max-h-60 overflow-y-auto">
                    <ul class="space-y-2">
                        <?php foreach ($jobs as $job) : ?>
                            <li class="p-2 bg-white border border-gray-200">
                                <?php echo htmlspecialchars($job['title'] ?? 'Job Title'); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div>
                <h3 class="mb-2 text-lg font-bold">Job Seekers</h3>
                <div class="max-h-60 overflow-y-auto">
                    <ul class="space-y-2">
                        <?php foreach ($searchers as $searcher) : ?>
                            <li class="p-2 bg-white border border-gray-200">
                                <?php echo htmlspecialchars($searcher['title'] ?? 'Candidate'); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex-grow p-5">
            <!-- ...existing code for processing search query and displaying results... -->
            <p>Results for: <?php echo htmlspecialchars($searchQuery); ?></p>
        </div>
    </div>
</body>
</html>
