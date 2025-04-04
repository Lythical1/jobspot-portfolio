<?php

require_once("../../core/jobs.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$jobRepo = new JobRepository();
$message = '';

// Get all categories
$categories = $jobRepo->getCategories();

// Initialize company ID (in a real application, you would check if the logged-in user is associated with a company)
// For demonstration purposes, we'll use a placeholder company ID
$companyId = $jobRepo->getCompanyID($_SESSION['user_id']);

// Get job offers by this company
$companyJobs = $jobRepo->getOffersByCompany($companyId); // In a real app, you'd filter by company ID

// Handle job offer creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job'])) {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $work_hours = trim($_POST['work_hours']);
    $location = trim($_POST['location']);
    $salary_range = trim($_POST['salary']);
    $description = trim($_POST['description']);
    
    // Validate required fields
    if (empty($title) || empty($category) || empty($work_hours) || empty($location)) {
        $message = '<div class="alert alert-danger">Please fill in all required fields</div>';
    } else {
        // Here you would save the job offer
        // For now, we'll just show a success message
        $message = '<div class="alert alert-success">Your job listing has been successfully created!</div>';
        
        // In a real application, you would call a method like:
        $result = $jobRepo->createJobOffer([
            'company_id' => $companyId,
            'title' => $title,
            'category_id' => $category,
            'work_hours' => $work_hours,
            'location' => $location,
            'salary_range' => $salary_range,
            'description' => $description
        ]);
        
        // And then refresh the job listings
        $companyJobs = $jobRepo->getOffersByCompany($companyId);
    }
}

?>

<div class="max-w-full px-6 py-4">
    <?php echo $message; ?>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Job Creation Form Column -->
        <div class="lg:w-2/5">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-blue-600 text-white px-4 py-3">
                    <h5 class="text-lg font-semibold m-0"><i class="fas fa-briefcase mr-2"></i>Post New Job</h5>
                </div>
                <div class="p-5">
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">Job Title*</label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                id="title" name="title" required>
                        </div>

                        <div class="mb-4">
                            <label for="category"
                                class="block text-gray-700 text-sm font-semibold mb-2">Category*</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                                id="category" name="category" required>
                                <option value="" selected disabled>Choose a category...</option>
                                <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="work_hours" class="block text-gray-700 text-sm font-semibold mb-2">Work
                                Hours*</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                                id="work_hours" name="work_hours" required>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="contract">Contract</option>
                                <option value="freelance">Freelance</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="location"
                                class="block text-gray-700 text-sm font-semibold mb-2">Location*</label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                id="location" name="location" required>
                        </div>

                        <div class="mb-4">
                            <label for="salary" class="block text-gray-700 text-sm font-semibold mb-2">Salary Range</label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                id="salary" name="salary" placeholder="e.g. €40,000 - €50,000">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">Job Description</label>
                            <textarea
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" name="submit_job"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Post
                                Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Posted Jobs Column -->
        <div class="lg:w-3/5">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-blue-600 text-white px-4 py-3">
                    <h5 class="text-lg font-semibold m-0"><i class="fas fa-list mr-2"></i>Your Posted Jobs</h5>
                </div>
                <div class="p-5">
                    <?php if (count($companyJobs) > 0) : ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($companyJobs as $job) : ?>
                        <div class="py-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($job['title']); ?></h3>
                                <div>
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-2">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Company:</span> <?php echo htmlspecialchars($job['company']); ?>
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <?php if (!empty($job['category_name'])) : ?>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($job['category_name']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($job['work_hours'])) : ?>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($job['work_hours']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($job['location'])) : ?>
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($job['location']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($job['description'])) : ?>
                            <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars(substr($job['description'], 0, 150)) . (strlen($job['description']) > 150 ? '...' : ''); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($job['salary_range'])) : ?>
                            <p class="text-sm text-gray-600 mt-2 font-semibold">Salary:
                                <?php echo htmlspecialchars($job['salary_range']); ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-2">Posted: <?php echo isset($job['created_at']) ? htmlspecialchars(date('M j, Y', strtotime($job['created_at']))) : 'Recently'; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">You haven't posted any jobs yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
    }
    
    // Category dropdown handler
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            // You could add any category-specific logic here
        });
    }

    // Add character counter for description
    const descriptionTextarea = document.getElementById('description');
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', function() {
            // Optional: Add character count display logic here
        });
    }
});
</script>
