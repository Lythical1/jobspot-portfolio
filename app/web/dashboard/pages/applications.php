<?php

require_once("../../core/job_searchers.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
$jobs = new JobSearcher();
$userId = $_SESSION['user_id'];
$message = '';

// Get all categories
$categories = $jobs->getCategories();

$applications = $jobs->getUserApplications($userId);

// Handle application submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    $userId = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $work_hours = trim($_POST['work_hours']);
    $location = trim($_POST['location']);
    $salary_range = trim($_POST['salary']);
    
    // Validate required fields
    if (empty($title) || empty($category) || empty($work_hours) || empty($location)) {
        $message = '<div class="alert alert-danger">Please fill in all required fields</div>';
    } else {
        // Save the application
        $result = $jobs->createApplication([
            'user_id' => $userId,
            'title' => $title,
            'category' => $category,
            'work_hours' => $work_hours,
            'location' => $location,
            'salary_range' => $salary_range
        ]);

        
        if ($result) {
            $message = '<div class="alert alert-success">Your application has been successfully created!</div>';
            // Refresh applications list
            $applications = $jobs->getUserApplications($userId);
        } else {
            $message = '<div class="alert alert-danger">Failed to create application. Please try again.</div>';
        }
    }
}

?>

<div class="max-w-full px-6 py-4">
    <?php echo $message; ?>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Application Form Column -->
        <div class="lg:w-2/5">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-blue-600 text-white px-4 py-3">
                    <h5 class="text-lg font-semibold m-0"><i class="fas fa-file-alt mr-2"></i>Create New Application
                    </h5>
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
                            <label for="salary" class="block text-gray-700 text-sm font-semibold mb-2">Salary
                                Expectation*</label>
                            <input type="number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                id="salary" name="salary" placeholder="e.g. €40,000 (euro is added automatically)" required>
                        </div>

                        <div class="mt-6">
                            <button type="submit" name="submit_application"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300
                                ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Create
                                Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add a section to display existing applications -->
        <div class="lg:w-3/5">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-blue-600 text-white px-4 py-3">
                    <h5 class="text-lg font-semibold m-0"><i class="fas fa-list mr-2"></i>Your Applications</h5>
                </div>
                <div class="p-5">
                    <?php if (count($applications) > 0) : ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($applications as $app) : ?>
                        <div class="py-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($app['title']); ?></h3>
                                <div>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <?php if (!empty($app['category_name'])) : ?>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($app['category_name']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($app['work_hours'])) : ?>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($app['work_hours']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($app['location'])) : ?>
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($app['location']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($app['salary_range'])) : ?>
                            <p class="text-sm text-gray-600 mt-2">Salary:
                                <?php echo htmlspecialchars($app['salary_range']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">You haven't created any applications yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Category dropdown handler
    const categoryDropdown = document.getElementById('categoryDropdownList');
    const categoryDetails = document.getElementById('categoryDetails');
    const categoryName = document.getElementById('selectedCategoryName');
    const categoryDescription = document.getElementById('selectedCategoryDescription');
    const viewDetailsBtn = document.getElementById('viewCategoryDetailsBtn');

    // Link main form category with category details
    const formCategorySelect = document.getElementById('category');
    if (formCategorySelect && categoryDropdown) {
        formCategorySelect.addEventListener('change', function() {
            categoryDropdown.value = this.value;
            // Trigger change event on category dropdown
            const event = new Event('change');
            categoryDropdown.dispatchEvent(event);
        });
    }

    if (categoryDropdown) {
        // Store category data for quick access
        const categoryData = {};
        <?php foreach ($categories as $category) : ?>
        categoryData['<?php echo $category['id']; ?>'] = {
            name: '<?php echo addslashes(htmlspecialchars($category['name'])); ?>',
            description: '<?php echo addslashes(htmlspecialchars($category['description'] ?? 'No description available.')); ?>',
            id: '<?php echo $category['id']; ?>'
        };
        <?php endforeach; ?>

        // Show category details when selected
        categoryDropdown.addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId && categoryData[selectedId]) {
                categoryName.textContent = categoryData[selectedId].name;
                categoryDescription.textContent = categoryData[selectedId].description;
                categoryDetails.classList.remove('d-none');
            } else {
                categoryDetails.classList.add('d-none');
            }
        });

        // View category details button
        if (viewDetailsBtn) {
            viewDetailsBtn.addEventListener('click', function(e) {
                const selectedId = categoryDropdown.value;
                if (selectedId && categoryData[selectedId]) {
                    categoryName.textContent = categoryData[selectedId].name;
                    categoryDescription.textContent = categoryData[selectedId].description;
                    categoryDetails.classList.remove('d-none');
                } else {
                    alert('Please select a category first');
                }
            });
        }
    }
});
</script>