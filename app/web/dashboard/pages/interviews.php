<?php

if (!defined('DASHBOARD_ROUTED')) {
    header('Location: dashboard');
    exit();
}

require_once '../../core/interviews.php';

$upcomingInterviews = Interview::getUpcomingInterviews($_SESSION['user_id']);
$pastInterviews = Interview::getPastInterviews($_SESSION['user_id']);

?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Interviews</h2>
    
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="font-bold text-lg mb-4">Upcoming Interviews</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Company</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Position</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Date</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Time</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Status</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($upcomingInterviews)) : ?>
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">No upcoming interviews found.</td>
                    </tr>
                    <?php else : ?>
                        <?php foreach ($upcomingInterviews as $interview) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($interview->company_name) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($interview->job_title) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?= date('F j, Y', strtotime($interview->scheduled_at)) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?= date('g:i A', strtotime($interview->scheduled_at)) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <?php if ($interview->status == 'completed') : ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                <?php elseif ($interview->status == 'cancelled') : ?>
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span>
                                <?php else : ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs"><?= ucfirst(htmlspecialchars($interview->status)) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Details</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold text-lg mb-4">Past Interviews</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Company</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Position</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Date</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Status</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pastInterviews)) : ?>
                    <tr>
                        <td colspan="5" class="py-4 px-4 text-center text-gray-500">No past interviews found.</td>
                    </tr>
                    <?php else : ?>
                        <?php foreach ($pastInterviews as $interview) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($interview->company_name) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($interview->job_title) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?= date('F j, Y', strtotime($interview->scheduled_at)) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <?php if ($interview->status == 'completed') : ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                <?php elseif ($interview->status == 'cancelled') : ?>
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span>
                                <?php else : ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs"><?= ucfirst($interview->status) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <button class="bg-gray-500 text-white px-3 py-1 rounded text-sm">View Notes</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
