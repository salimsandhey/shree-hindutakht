<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

echo "Hostinger Path Debug\n";
echo "===================\n\n";

// Get all disk configurations
$config = Config::get('filesystems.disks');

echo "All disk configurations:\n";
foreach ($config as $diskName => $diskConfig) {
    echo "Disk: " . $diskName . "\n";
    echo "  Driver: " . ($diskConfig['driver'] ?? 'N/A') . "\n";
    echo "  Root: " . ($diskConfig['root'] ?? 'N/A') . "\n";
    echo "  URL: " . ($diskConfig['url'] ?? 'N/A') . "\n";
    
    // Try to resolve the root path
    if (isset($diskConfig['root'])) {
        $resolvedPath = realpath($diskConfig['root']);
        echo "  Resolved root: " . ($resolvedPath ?: 'NOT RESOLVABLE') . "\n";
    }
    echo "\n";
}

// Test what disk is actually being used
$disk = env('FILESYSTEM_DISK', 'public');
echo "Currently configured disk: " . $disk . "\n\n";

// Test actual file storage with each disk
$testContent = 'Test content';
$testPath = 'posts/media/test_file.txt';

echo "Testing file storage with each disk:\n\n";

foreach (['local', 'public', 'hostinger'] as $testDisk) {
    echo "--- Testing " . $testDisk . " disk ---\n";
    
    try {
        // Store file
        $result = Storage::disk($testDisk)->put($testPath, $testContent);
        echo "Store result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
        
        // Get full path
        $fullPath = Storage::disk($testDisk)->path($testPath);
        echo "Full path: " . $fullPath . "\n";
        
        // Check if exists
        $exists = Storage::disk($testDisk)->exists($testPath);
        echo "File exists: " . ($exists ? 'YES' : 'NO') . "\n";
        
        // Generate URL
        $url = Storage::disk($testDisk)->url($testPath);
        echo "Generated URL: " . $url . "\n";
        
        // Clean up
        if ($exists) {
            Storage::disk($testDisk)->delete($testPath);
            echo "File cleaned up\n";
        }
        
    } catch (Exception $e) {
        echo "Error with " . $testDisk . " disk: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "Debug completed.\n";