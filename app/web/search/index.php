<?php

session_start();
$searchQuery = $_GET['q'] ?? '';
$role = $_SESSION['user_role'] ?? 'guest';

require_once '../../core/jobs.php';

try {
    $jobRepo = new JobRepository();
    $results = $jobRepo->searchJobs($searchQuery);
    $jobs = $results['jobs'];
    $filteredSearchers = $results['searchers'];
} catch (Exception $e) {
    $error = "An error occurred while searching. Please try again later.";
    $jobs = [];
    $filteredSearchers = [];
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Results</title>
</head>

<body class="font-sans">
    <?php include '../../core/navbar.php'; ?>
    <div class="flex">
        <div class="flex-grow p-5">
            <h2 class="text-2xl mb-4">Search Results for: <?php echo htmlspecialchars($searchQuery); ?></h2>

            <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if (empty($jobs) && empty($filteredSearchers)) : ?>
            <p class="text-gray-600">No results found for your search.</p>
            <?php else : ?>
                <?php if (!empty($jobs)) : ?>
            <h3 class="text-xl mb-3">Jobs</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <?php foreach ($jobs as $job) : ?>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-bold"><?= htmlspecialchars($job['title']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($job['company'] ?? 'Unknown Company') ?></p>
                    <p class="mt-2"><?= htmlspecialchars(substr($job['description'], 0, 150)) ?>...</p>
                </div>
                    <?php endforeach; ?>
            </div>
                <?php endif; ?>

                <?php if (!empty($filteredSearchers)) : ?>
            <h3 class="text-xl mb-3">Job Seekers</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($filteredSearchers as $searcher) : ?>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-bold"><?= htmlspecialchars($searcher['title']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($searcher['location'] ?? 'Location not specified') ?>
                    </p>
                    <p class="mt-2">
                        <?= htmlspecialchars($searcher['work_hours'] ?? 'Hours not specified') ?> •
                        <?= htmlspecialchars($searcher['salary_range']) ?>
                    </p>
                </div>
                    <?php endforeach; ?>
            </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
