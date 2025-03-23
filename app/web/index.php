<?php
session_start();

include ("../core/jobs.php");
include ("../core/job_searchers.php");

$jobRepository = new JobRepository();
$jobSearcher = new JobSearcher();

$randomJobs = $jobRepository->getRandomJobs(3);
$randomSearchers = $jobSearcher->getRandomSearchers(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSpot - Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50 text-gray-800">
    <?php include '../core/navbar.php'; ?>
    
    <div class="bg-indigo-700 text-white py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
                Welcome to <span class="text-yellow-300">JobSpot</span>
            </h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto mb-8">
                Your one-stop solution for job searching and recruitment in the digital age.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#jobs" class="bg-white text-indigo-700 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition">
                    Find Jobs
                </a>
                <a href="#seekers" class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-indigo-700 transition">
                    Find Talent
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-4xl font-bold text-indigo-700 mb-2">2,500+</p>
                <p class="text-gray-600">Active Jobs</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-4xl font-bold text-indigo-700 mb-2">1M+</p>
                <p class="text-gray-600">Job Seekers</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-4xl font-bold text-indigo-700 mb-2">10K+</p>
                <p class="text-gray-600">Companies</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-4xl font-bold text-indigo-700 mb-2">95%</p>
                <p class="text-gray-600">Success Rate</p>
            </div>
        </div>
        
        <div id="jobs" class="mb-20">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-800 relative">
                    <span class="inline-block relative">
                        Featured Jobs
                        <span class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500"></span>
                    </span>
                </h2>
                <a href="#" class="text-indigo-700 hover:underline font-medium flex items-center">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($randomJobs as $job): ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover border-t-4 border-indigo-700">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($job['title']); ?></h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded">New</span>
                            </div>
                            <div class="flex items-center mb-4 text-gray-600 text-sm">
                                <i class="fas fa-building mr-2"></i>
                                <?php echo htmlspecialchars($job['company'] ?? 'Company'); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?php echo htmlspecialchars($job['location'] ?? 'On Site'); ?>
                            </div>
                            <p class="text-gray-600 mb-6 line-clamp-3"><?php echo htmlspecialchars($job['description']); ?></p>
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded"><?php echo htmlspecialchars($job['location'] ?? 'On Site'); ?></span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded"><?php echo htmlspecialchars($job['salary_range'] ?? 'Salary Not Specified'); ?></span>
                            </div>
                            <a href='<?php echo htmlspecialchars($job['apply_link']); ?>' class="block w-full text-center bg-indigo-700 hover:bg-indigo-800 text-white py-3 px-4 rounded-lg transition duration-300 font-semibold">
                                Apply Now <i class="fas fa-paper-plane ml-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div id="seekers" class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-800 relative">
                    <span class="inline-block relative">
                        Featured Job Seekers
                        <span class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500"></span>
                    </span>
                </h2>
                <a href="#" class="text-indigo-700 hover:underline font-medium flex items-center">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($randomSearchers as $searcher): ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover border-t-4 border-emerald-500">
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gray-200 rounded-full overflow-hidden flex items-center justify-center text-2xl font-bold text-gray-400">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($searcher['title']); ?></h3>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded"><?php echo htmlspecialchars($searcher['category'] ?? 'Uncategorized'); ?></span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded"><?php echo htmlspecialchars($searcher['salary_range'] ?? 'Salary Not Specified'); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="ml-2 text-gray-600 text-sm">4.5</span>
                                </div>
                                <a href='<?php echo htmlspecialchars($searcher['apply_link']); ?>' class="bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-5 rounded-lg transition duration-300 font-semibold">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="bg-gray-800 text-white p-8 md:p-12 rounded-2xl shadow-xl text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-0 left-0 w-24 h-24 bg-indigo-700 rounded-full -ml-12 -mt-12"></div>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-emerald-500 rounded-full -mr-16 -mb-16"></div>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Take Your Career to the Next Level?</h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-80">
                    Whether you're looking for a job or seeking talent, JobSpot has you covered.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#" class="bg-white text-gray-800 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition shadow-lg">
                        Create Account
                    </a>
                    <a href="#" class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-gray-800 transition shadow-lg">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
