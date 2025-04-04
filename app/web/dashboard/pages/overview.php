<?php

if (!defined('DASHBOARD_ROUTED')) {
    header('Location: dashboard');
    exit();
}

$role = $_SESSION['user_role'] ?? '';
$selected_dashboard = $_SESSION['selected_dashboard'] ?? $role;
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Dashboard Overview</h2>
    
    <?php if ($role === 'admin') : ?>
        <p class="mb-4">You are viewing the <strong><?= ucfirst($selected_dashboard) ?></strong> dashboard as an administrator.</p>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Recent Activity</h3>
            <p class="text-gray-700">Your dashboard is now loading content dynamically!</p>
            <ul class="mt-4 list-disc pl-5 text-gray-600">
                <li>All navigation happens without page reloads</li>
                <li>URL updates using History API</li>
                <li>Loading indicators show while content loads</li>
            </ul>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Quick Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500">Applications</p>
                    <p class="text-xl font-bold">12</p>
                </div>
                <div>
                    <p class="text-gray-500">Interviews</p>
                    <p class="text-xl font-bold">3</p>
                </div>
                <div>
                    <p class="text-gray-500">Job Offers</p>
                    <p class="text-xl font-bold">2</p>
                </div>
                <div>
                    <p class="text-gray-500">Active Jobs</p>
                    <p class="text-xl font-bold">8</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Upcoming Events</h3>
            <div class="space-y-3">
                <div class="border-l-4 border-blue-500 pl-3">
                    <p class="font-medium">Technical Interview</p>
                    <p class="text-sm text-gray-600">Tomorrow, 2:00 PM</p>
                </div>
                <div class="border-l-4 border-green-500 pl-3">
                    <p class="font-medium">Follow-up Call</p>
                    <p class="text-sm text-gray-600">Wed, 11:30 AM</p>
                </div>
            </div>
        </div>
    </div>
</div>
