<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header('Location: /app/web/user/login.php');
    exit();
}

define('DASHBOARD_ROUTED', true);

$role = $_SESSION['user_role'];

if ($role === 'admin') {
    // If the admin has already selected a dashboard, then include the corresponding file.
    if (isset($_POST['dashboard'])) {
        $dashboard = $_POST['dashboard'];
        switch ($dashboard) {
            case 'admin':
                include 'admin.php';  // admin dashboard page
                exit();
            case 'employer':
                include 'employer.php';  // employer dashboard page
                exit();
            case 'user':
                include 'user.php';  // user dashboard page
                exit();
            default:
                include 'admin.php';
                exit();
        }
    } else {
        // Display a selection menu for the admin to choose the dashboard.
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Select Dashboard</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        </head>
        <body class="bg-gray-100 flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded shadow-md w-80">
                <h2 class="text-2xl font-bold mb-4 text-center">Dashboard Selection</h2>
                <form method="post">
                    <div class="mb-4">
                        <label for="dashboard" class="block text-gray-700">Choose a dashboard:</label>
                        <select name="dashboard" id="dashboard" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                            <option value="admin">Admin Dashboard</option>
                            <option value="employer">Employer Dashboard</option>
                            <option value="user">User Dashboard</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Go</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
} elseif ($role === 'employer') {
    include 'employer.php';  // employer dashboard page
    exit();
} else {
    include 'user.php';  // user dashboard page
    exit();
}
