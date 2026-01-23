#!/usr/bin/env php
<?php

// Hostinger Storage Debug Script (CLI Version)
// Run this script via command line: php hostinger_debug_cli.php

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use statements must be at the top level
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

echo "Starting Hostinger Debug Script...\n";
echo "Current working directory: " . getcwd() . "\n";
echo "PHP version: " . phpversion() . "\n\n";

// Check if we can find the autoloader
$autoloadPaths = [
    'vendor/autoload.php',
    '../vendor/autoload.php',
    '../../vendor/autoload.php'
];

$autoloadFound = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        echo "Found autoloader at: $path\n";
        require_once $path;
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    echo "ERROR: Could not find Composer autoloader. Please run 'composer install' first.\n";
    echo "Searched paths:\n";
    foreach ($autoloadPaths as $path) {
        echo "  - $path\n";
    }
    exit(1);
}

echo "Autoloader loaded successfully.\n\n";

try {
    // Try to bootstrap Laravel
    echo "Attempting to bootstrap Laravel...\n";
    
    // Check if bootstrap file exists
    $bootstrapPath = 'bootstrap/app.php';
    if (!file_exists($bootstrapPath)) {
        echo "ERROR: Bootstrap file not found at $bootstrapPath\n";
        exit(1);
    }
    
    echo "Found bootstrap file.\n";
    
    $app = require_once $bootstrapPath;
    echo "Laravel app bootstrapped successfully.\n";
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "Console kernel created.\n";
    
    $kernel->bootstrap();
    echo "Laravel kernel bootstrapped.\n\n";
    
    // Use different function names to avoid conflicts
    function debugGetHostingerBasePath() {
        $basePath = base_path();
        echo "Base path: $basePath\n";
        
        // Check if we're in a typical Hostinger setup
        if (strpos($basePath, '/home/') === 0) {
            // We're on a Linux server, likely Hostinger
            $parts = explode('/', $basePath);
            if (count($parts) >= 4 && $parts[1] === 'home') {
                // Reconstruct the base path: /home/userid/domain/files
                $basePathParts = array_slice($parts, 0, 4);
                return implode('/', $basePathParts);
            }
        }
        
        // For Windows-based Hostinger or fallback
        return dirname($basePath);
    }

    function debugGetHostingerPublicHtmlPath() {
        $hostingerBase = debugGetHostingerBasePath();
        return $hostingerBase . '/public_html';
    }

    function debugGetHostingerStoragePath() {
        return debugGetHostingerPublicHtmlPath() . '/storage';
    }

    function testDirectory($path, $name) {
        echo "=== $name ===\n";
        echo "Path: $path\n";
        echo "Exists: " . (is_dir($path) ? 'YES' : 'NO') . "\n";
        if (is_dir($path)) {
            echo "Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
            
            // Test file creation
            $testFile = $path . '/write_test_' . time() . '.txt';
            $result = @file_put_contents($testFile, 'Test content');
            if ($result !== false) {
                echo "File creation: SUCCESS\n";
                @unlink($testFile);
            } else {
                echo "File creation: FAILED\n";
            }
        }
        echo "\n";
    }

    function testStorageDisk($diskName) {
        echo "=== Disk: $diskName ===\n";
        
        try {
            $config = Config::get('filesystems.disks.' . $diskName);
            if (!$config) {
                echo "Disk configuration not found\n\n";
                return;
            }
            
            echo "Disk configuration:\n";
            print_r($config);
            
            // Test storing a file
            $testContent = 'Debug test file created at ' . date('Y-m-d H:i:s');
            $testPath = 'posts/media/debug_test_' . time() . '.txt';
            
            echo "Attempting to store file...\n";
            $result = Storage::disk($diskName)->put($testPath, $testContent);
            echo "Storage result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
            
            if ($result) {
                $fullPath = Storage::disk($diskName)->path($testPath);
                echo "Full file path: $fullPath\n";
                
                $exists = Storage::disk($diskName)->exists($testPath);
                echo "File exists check: " . ($exists ? 'YES' : 'NO') . "\n";
                
                $url = Storage::disk($diskName)->url($testPath);
                echo "Generated URL: $url\n";
                
                // Check if stored in correct location
                if (strpos($fullPath, 'public_html') !== false) {
                    echo "✓ File stored in correct location (public_html)\n";
                } else {
                    echo "✗ File stored in incorrect location\n";
                }
                
                // Clean up
                if ($exists) {
                    Storage::disk($diskName)->delete($testPath);
                    echo "Test file cleaned up\n";
                }
            }
            
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
        
        echo "\n";
    }

    echo "=========================================\n";
    echo "Hostinger Storage Debug Tool (CLI Version)\n";
    echo "=========================================\n\n";

    echo "1. ENVIRONMENT INFORMATION\n";
    echo "-------------------------\n";
    echo "Laravel version: " . App::version() . "\n";
    echo "FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'NOT SET') . "\n";
    echo "APP_URL: " . env('APP_URL', 'NOT SET') . "\n";
    echo "BASE_PATH: " . base_path() . "\n";
    echo "HOSTINGER_BASE_PATH: " . debugGetHostingerBasePath() . "\n";
    echo "PUBLIC_HTML_PATH: " . debugGetHostingerPublicHtmlPath() . "\n";
    echo "STORAGE_PATH: " . debugGetHostingerStoragePath() . "\n\n";

    echo "2. DIRECTORY STATUS\n";
    echo "------------------\n";
    testDirectory(base_path(), 'Application Base Directory');
    testDirectory(storage_path(), 'Laravel Storage Directory');
    testDirectory(public_path(), 'Public Directory');
    testDirectory(debugGetHostingerPublicHtmlPath(), 'Hostinger Public HTML Directory');
    testDirectory(debugGetHostingerStoragePath(), 'Hostinger Storage Directory');

    echo "3. STORAGE DISK TESTS\n";
    echo "--------------------\n";
    $disks = ['local', 'public', 'hostinger'];
    foreach ($disks as $disk) {
        testStorageDisk($disk);
    }

    echo "4. RECOMMENDATIONS\n";
    echo "-----------------\n";
    echo "Immediate Actions:\n";
    echo "1. Ensure your .env file contains: FILESYSTEM_DISK=hostinger\n";
    echo "2. Clear Laravel caches by running:\n";
    echo "   php artisan config:clear\n";
    echo "   php artisan cache:clear\n";
    echo "   php artisan route:clear\n";
    echo "   php artisan view:clear\n";
    echo "3. Make sure the directory " . debugGetHostingerStoragePath() . "/posts/media exists and is writable\n\n";

    echo "If issues persist:\n";
    echo "- Check that " . debugGetHostingerPublicHtmlPath() . " directory exists\n";
    echo "- Verify file permissions (should be 755 for directories, 644 for files)\n";
    echo "- Contact Hostinger support if paths seem incorrect\n\n";

    echo "Debug completed.\n";
    
} catch (Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}