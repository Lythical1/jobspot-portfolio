<?php

// Simple script to run the tests

echo "Running JobSpot Unit Tests\n";
echo "=========================\n\n";

// Check if PHPUnit is installed
$vendorPhpunit = __DIR__ . '/vendor/bin/phpunit';
$globalPhpunit = 'phpunit';

$phpunitPath = file_exists($vendorPhpunit) ? $vendorPhpunit : $globalPhpunit;

// Run the tests with increased verbosity
$command = $phpunitPath . ' --colors=always --display-notices --display-warnings --display-errors --display-deprecations';
echo "Executing: $command\n\n";
passthru($command, $exitCode);

echo "\nTests completed with exit code: $exitCode\n";

exit($exitCode);
