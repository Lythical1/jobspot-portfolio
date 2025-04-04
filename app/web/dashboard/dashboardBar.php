<?php

// Dashboard navigation options based on user type
$commonOptions = [
    [
        'title' => 'Overview',
        'icon' => 'fas fa-home',
        'link' => 'overview'
    ],
    [
        'title' => 'Interviews',
        'icon' => 'fas fa-calendar-check',
        'link' => 'interviews'
    ]
];

$userSpecificOptions = [];

// Add different options based on user type
if ($_SESSION['user_role'] === 'company' || $_SESSION['user_role'] === 'admin') {
    // Company specific options
    $userSpecificOptions[] = [
        'title' => 'Job Offers',
        'icon' => 'fas fa-handshake',
        'link' => 'offers'
    ];
}
if ($_SESSION['user_role'] === 'job_seeker' || $_SESSION['user_role'] === 'admin') {
    // Job seeker specific options
    $userSpecificOptions[] = [
        'title' => 'Applications',
        'icon' => 'fas fa-file-alt',
        'link' => 'applications'
    ];
}

// Settings option for all users
$settingsOption = [
    [
        'title' => 'Settings',
        'icon' => 'fas fa-cog',
        'link' => 'settings'
    ]
];

// Combine all options
$dashboardOptions = array_merge($commonOptions, $userSpecificOptions, $settingsOption);

// Get current page from URL parameter
$currentPage = $_GET['page'] ?? 'overview';
?>

<!-- Dashboard Navigation Bar -->
<div class="w-[250px] bg-gray-800 text-white p-4">
    <h3 class="text-xl font-bold mb-4">Dashboard</h3>
    <h4 class="text-m mb-4">
        Welcome, <?= $_SESSION['user_name'] ?>
        <?php if (isset($_SESSION['user_role'])) : ?>
        <span class="text-xs block text-gray-400">(<?= ucfirst($_SESSION['user_role']) ?>)</span>
        <?php endif; ?>
    </h4>

    <nav class="space-y-2">
        <?php foreach ($dashboardOptions as $option) : ?>
            <?php 
                $pageName = pathinfo($option['link'], PATHINFO_FILENAME);
                $isActive = $currentPage == $pageName;
            ?>
        <a href="./?page=<?= $pageName ?>"
            class="dashboard-link flex items-center p-2 rounded hover:bg-gray-700 <?= $isActive ? 'bg-gray-700' : '' ?>"
            data-page="<?= $pageName ?>">
            <i class="<?= $option['icon'] ?> w-6"></i>
            <span class="ml-2"><?= $option['title'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>
</div>
