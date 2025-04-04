<?php

// Include autoloader from Composer if available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Define a function to autoload our app classes
spl_autoload_register(function ($class) {
    // Look for classes in core directory (case-insensitive)
    $coreFile = __DIR__ . '/../app/core/' . strtolower($class) . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }
    
    // Try with exact case match
    $coreFileExact = __DIR__ . '/../app/core/' . $class . '.php';
    if (file_exists($coreFileExact)) {
        require_once $coreFileExact;
        return;
    }

    // Look for specific classes that might have different filenames
    $specialCases = [
        'FileUpload' => 'fileUpload.php',
        'JobRepository' => 'jobs.php',
        'Interview' => 'interviews.php',
        'Users' => 'users.php',
        'Database' => 'database.php',
        'JobSearcher' => 'job_searchers.php'
    ];
    
    if (isset($specialCases[$class])) {
        $file = __DIR__ . '/../app/core/' . $specialCases[$class];
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Set up test environment variables
putenv('APP_ENV=testing');

// Create a fixtures directory for test files if needed
$fixturesDir = __DIR__ . '/fixtures';
if (!is_dir($fixturesDir)) {
    mkdir($fixturesDir, 0777, true);
}
