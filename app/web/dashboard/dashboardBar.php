<?php

// Dashboard navigation options
$dashboardOptions = [
    [
        'title' => 'Overview',
        'icon' => 'fas fa-home',
        'link' => 'overview'
    ],
    [
        'title' => 'Interviews',
        'icon' => 'fas fa-calendar-check',
        'link' => 'interviews'
    ],
    [
        'title' => 'Applications',
        'icon' => 'fas fa-file-alt',
        'link' => 'applications'
    ],
    [
        'title' => 'Job Offers',
        'icon' => 'fas fa-handshake',
        'link' => 'offers'
    ],
    [
        'title' => 'Statistics',
        'icon' => 'fas fa-chart-line',
        'link' => 'statistics'
    ],
    [
        'title' => 'Settings',
        'icon' => 'fas fa-cog',
        'link' => 'settings'
    ]
];

// Get current page from URL parameter
$currentPage = $_GET['page'] ?? 'overview';
?>

<!-- Dashboard Navigation Bar -->
<div class="w-[250px] bg-gray-800 text-white p-4">
    <h3 class="text-xl font-bold mb-4">Dashboard</h3>
    <h4 class="text-m mb-4">
        Welcome, <?= $_SESSION['user_name'] ?>
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
