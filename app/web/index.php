<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSpot - Find Your Dream Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800 leading-relaxed">
    <?php include '../core/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Find Your Dream Job Today</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Connect with top employers and discover opportunities that match your skills and career goals.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/search" class="bg-white text-indigo-600 hover:bg-gray-100 font-medium py-3 px-6 rounded-lg shadow-md transition duration-300">
                        Search Jobs
                    </a>
                    <a href="/user/register" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-300 border border-white">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Trusted by Professionals</h2>
                <p class="mt-4 text-xl text-gray-600">Join thousands who have found their perfect career match</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="text-indigo-600 text-4xl font-bold">10k+</div>
                    <div class="text-gray-700 mt-2">Active Job Listings</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="text-indigo-600 text-4xl font-bold">5k+</div>
                    <div class="text-gray-700 mt-2">Companies</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="text-indigo-600 text-4xl font-bold">25k+</</div>
                    <div class="text-gray-700 mt-2">Job Seekers</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="text-indigo-600 text-4xl font-bold">95%</div>
                    <div class="text-gray-700 mt-2">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">How JobSpot Works</h2>
                <p class="mt-4 text-xl text-gray-600">Simple, efficient, and effective job matching</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Create Profile</h3>
                    <p class="text-gray-600">Register and build your professional profile highlighting your skills and experience.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Discover Opportunities</h3>
                    <p class="text-gray-600">Browse thousands of job listings or let our matching algorithm find perfect fits for you.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-handshake text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Apply & Connect</h3>
                    <p class="text-gray-600">Apply to positions with a single click and connect directly with employers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-blue-500 to-indigo-700 py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Start Your Career Journey?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Join JobSpot today and connect with opportunities that match your unique skills and aspirations.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/user/register" class="bg-white text-indigo-600 hover:bg-gray-100 font-medium py-3 px-8 rounded-lg shadow-md transition duration-300">
                    Create Free Account
                </a>
                <a href="/search" class="bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-3 px-8 rounded-lg shadow-md transition duration-300 border border-white">
                    Browse Jobs
                </a>
            </div>
        </div>
    </section>
</body>
</html>
