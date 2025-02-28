<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header('Location: ../../user/login.php');
    exit();
}

define('DASHBOARD_ROUTED', true);

$role = $_SESSION['user_role'];

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'overview';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSpot Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include '../../core/navbar.php'; ?>
    <div class="flex min-h-screen">
        <!-- Dashboard Sidebar -->
        <?php include 'dashboardBar.php'; ?>

        <!-- Dashboard Content -->
        <div class="flex-1 p-4 bg-gray-100">
            <div id="loading" class="text-center p-8 hidden">
                <i class="fas fa-spinner fa-spin fa-3x"></i>
                <p>Loading...</p>
            </div>
            <div id="content-area">
                <?php 
                // Load the requested page
                include "pages/$page.php";
                ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all dashboard links
            const dashboardLinks = document.querySelectorAll('.dashboard-link');
            
            // Add click event handlers to each link
            dashboardLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const page = this.getAttribute('data-page');
                    loadPage(page);
                    
                    // Update active state
                    dashboardLinks.forEach(l => l.classList.remove('bg-gray-700'));
                    this.classList.add('bg-gray-700');
                });
            });
            
            // Function to load page content via AJAX
            function loadPage(page) {
                // Show loading indicator
                document.getElementById('loading').classList.remove('hidden');
                document.getElementById('content-area').classList.add('hidden');
                
                // Update URL without reloading the page
                const newUrl = `./?page=${page}`;
                history.pushState({page: page}, '', newUrl);
                
                // Fetch page content
                fetch(`loadContent.php?page=${page}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('content-area').innerHTML = html;
                        document.getElementById('loading').classList.add('hidden');
                        document.getElementById('content-area').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        document.getElementById('loading').classList.add('hidden');
                        document.getElementById('content-area').classList.remove('hidden');
                        document.getElementById('content-area').innerHTML = '<p class="text-red-500">Error loading content. Please try again.</p>';
                    });
            }
            
            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                const page = e.state ? e.state.page : 'overview';
                loadPage(page);
                
                // Update active state in navigation
                dashboardLinks.forEach(link => {
                    if (link.getAttribute('data-page') === page) {
                        link.classList.add('bg-gray-700');
                    } else {
                        link.classList.remove('bg-gray-700');
                    }
                });
            });
        });
    </script>
</body>

</html>