<?php
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the public path
echo "Public path: " . public_path() . "\n";
echo "Base path: " . base_path() . "\n";

// Check if build manifest exists
$manifestPath = public_path('build/manifest.json');
echo "Manifest path: " . $manifestPath . "\n";
echo "Manifest exists: " . (file_exists($manifestPath) ? 'Yes' : 'No') . "\n";

if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    echo "Manifest content:\n";
    print_r($manifest);
}