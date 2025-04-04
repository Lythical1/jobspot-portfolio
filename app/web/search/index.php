<?php
session_start();
$searchQuery = $_GET['search'] ?? '';
$role = $_SESSION['user_role'] ?? 'guest';
$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];

require_once '../../core/jobs.php';
require_once '../../core/job_searchers.php';

try {
    $jobRepo = new JobRepository();
    $jobSearcherRepo = new JobSearcher();

    // Update how we handle job results to fix null array error
    $jobResults = $jobRepo->searchJobs($searchQuery, $selectedCategories);
    // Ensure we have a jobs array even if the result isn't structured as expected
    $jobs = is_array($jobResults) && isset($jobResults['jobs']) ? $jobResults['jobs'] : 
            (is_array($jobResults) ? $jobResults : []);

    $searcherResults = $jobSearcherRepo->getSearchers($searchQuery, $selectedCategories);
    $filteredSearchers = is_array($searcherResults) && isset($searcherResults['searchers']) ? 
                        $searcherResults['searchers'] : 
                        (is_array($searcherResults) ? $searcherResults : []);
} catch (Exception $e) {
    $error = "An error occurred while searching: " . htmlspecialchars($e->getMessage());
    $jobs = [];
    $filteredSearchers = [];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Results</title>
    <style>
    .dropdown-container {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        cursor: pointer;
        padding: 8px 16px;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 200px;
    }

    .dropdown-toggle::after {
        content: "▼";
        font-size: 0.8em;
        margin-left: 8px;
    }

    .dropdown-menu {
        position: absolute;
        z-index: 1000;
        display: none;
        width: 250px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dropdown-menu.show {
        display: block;
    }
    </style>
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

            <div id="searchBar">
                <form action="index.php" method="GET" class="mb-4" id="searchForm">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>"
                        class="border rounded p-2 w-full"
                        placeholder="Search for <?= ($role == 'employer') ? 'job seekers' : 'jobs' ?>...">
                    <div id="categorySelectedContainer" class="mt-2 flex flex-wrap"></div>
                    <button type="submit" class="bg-blue-500 text-white rounded p-2 mt-2 w-full">Search</button>
                </form>
            </div>

            <div id="filterButtons" class="mb-4">
                <div class="dropdown-container">
                    <button id="categoryDropdownToggle" class="dropdown-toggle">Filter by Category</button>
                    <div id="categoryDropdown" class="dropdown-menu">
                        <div class="p-2 border-b">
                            <input type="text" id="categorySearch" class="border rounded p-1 w-full text-sm"
                                placeholder="Search categories...">
                        </div>
                        <ul id="categorylist" class="max-h-36 overflow-y-auto m-0 p-0 list-none text-sm">
                            <?php foreach ($jobRepo->getCategories() as $category) : ?>
                            <li class="p-1.5 hover:bg-gray-100 cursor-pointer category-item">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" value="<?= $category['id'] ?>"
                                        <?= in_array($category['id'], $selectedCategories) ? 'checked' : '' ?>
                                        class="category-checkbox mr-2">
                                    <?= htmlspecialchars($category['name']) ?>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (!empty($jobs) && ($role == 'user' || $role == 'admin' || $role == 'guest')) : ?>
            <h3 class="text-xl mb-3">Jobs</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <?php foreach ($jobs as $job) : ?>
                <div class="bg-white p-4 rounded shadow relative">
                    <?php if ($role == 'admin') : ?>
                    <div class="absolute top-2 right-2 cursor-pointer text-gray-500 hover:text-red-500 trash-icon"
                        data-type="job" data-id="<?= $job['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                            <path fill-rule="evenodd"
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg>
                    </div>
                    <?php endif; ?>
                    <h3 class="font-bold"><?= htmlspecialchars($job['title']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($job['company'] ?? 'Unknown Company') ?></p>
                    <p class="mt-2"><?= htmlspecialchars(substr($job['description'], 0, 150)) ?>...</p>
                    <p class="text-sm text-gray-700 mt-2">
                        <?= htmlspecialchars($job['salary_range'] ?? 'Salary not specified') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($filteredSearchers) && ($role == 'employer' || $role == 'admin' || $role == 'guest')) : ?>
            <h3 class="text-xl mb-3">Job Seekers</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($filteredSearchers as $searcher) : ?>
                <div class="bg-white p-4 rounded shadow relative">
                    <?php if ($role == 'admin') : ?>
                    <div class="absolute top-2 right-2 cursor-pointer text-gray-500 hover:text-red-500 trash-icon"
                        data-type="seeker" data-id="<?= $searcher['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                            <path fill-rule="evenodd"
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg>
                    </div>
                    <?php endif; ?>
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
        </div>
    </div>
</body>

<script>
// Essential dropdown functionality
const categoryToggle = document.getElementById('categoryDropdownToggle');
const categoryDropdown = document.getElementById('categoryDropdown');
const searchForm = document.getElementById('searchForm');
const categorySelectedContainer = document.getElementById('categorySelectedContainer');

// Toggle dropdown
categoryToggle.addEventListener('click', e => {
    e.stopPropagation();
    categoryDropdown.classList.toggle('show');
});

// Close dropdown when clicking outside
document.addEventListener('click', e => {
    if (!categoryDropdown.contains(e.target) && !categoryToggle.contains(e.target)) {
        categoryDropdown.classList.remove('show');
    }
});

// Prevent dropdown close on inside clicks
categoryDropdown.addEventListener('click', e => e.stopPropagation());

// Category search filter
document.getElementById('categorySearch').addEventListener('input', function() {
    const searchText = this.value.toLowerCase();
    document.querySelectorAll('.category-item').forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(searchText) ? 'block' : 'none';
    });
});

// Handle category selection
document.querySelectorAll('.category-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCategories);
});

// Update selected categories display
function updateSelectedCategories() {
    categorySelectedContainer.innerHTML = '';

    document.querySelectorAll('.category-checkbox:checked').forEach(box => {
        const categoryName = box.parentNode.textContent.trim();
        const categoryId = box.value;

        const badge = document.createElement('div');
        badge.className = 'bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2 mb-2 flex items-center';
        badge.innerHTML = `
            ${categoryName}
            <span class="ml-1 cursor-pointer" data-id="${categoryId}">&times;</span>
            <input type="hidden" name="categories[]" value="${categoryId}">
        `;

        badge.querySelector('span').addEventListener('click', function() {
            const checkbox = document.querySelector(`.category-checkbox[value="${this.dataset.id}"]`);
            if (checkbox) checkbox.checked = false;
            badge.remove();
        });

        categorySelectedContainer.appendChild(badge);
    });
}

// Initialize on page load
updateSelectedCategories();

// Handle trash icon clicks for admin deletion
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.trash-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;

            if (confirm(
                    `Are you sure you want to delete this ${type === 'job' ? 'job' : 'job seeker profile'}?`
                )) {
                // Send delete request to server
                fetch(`/api/delete.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            type: type,
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the card from the DOM
                            this.closest('.bg-white').remove();
                        } else {
                            alert('Error deleting: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting.');
                    });
            }
        });
    });
});
</script>

</html>
