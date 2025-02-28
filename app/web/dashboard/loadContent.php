<?php

session_start();
if (!isset($_SESSION['user_role'])) {
    echo "Please log in to access this content.";
    exit();
}

// Define constant so included pages know they're being routed correctly
define('DASHBOARD_ROUTED', true);

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'overview';

// Sanitize page parameter to prevent directory traversal
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', $page);

// Check if file exists
$filePath = "pages/{$page}.php";
if (file_exists($filePath)) {
    include $filePath;
} else {
    echo "<div class='p-6'>";
    echo "<h2 class='text-2xl font-bold mb-6'>Page Not Found</h2>";
    echo "<p>The requested page does not exist.</p>";
    echo "<p><a href='/dashboard' class='text-blue-500 hover:underline'>Return to Overview</a></p>";
    echo "<p>If you believe this is an error, please contact support.</p>";
    echo "</div>";
}
