<?php
require_once 'vendor/autoload.php';

use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;

// Test PDF generation
echo "Testing PDF generation...\n";

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
        }
        .card { 
            border: 2px solid #333; 
            padding: 20px; 
            width: 300px; 
        }
        h2 { color: #b93a20; }
        .field { margin: 10px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>SHREE HINDUTAKHT</h2>
        <h3>Member Identity Card</h3>
        <div class="field"><span class="label">Member ID:</span> TEST123</div>
        <div class="field"><span class="label">Name:</span> Test User</div>
        <div class="field"><span class="label">Email:</span> test@example.com</div>
        <div class="field"><span class="label">Website:</span> www.shreehindutakht.com</div>
    </div>
</body>
</html>';

try {
    $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
    $pdf->save('test_id_card.pdf');
    echo "PDF generated successfully: test_id_card.pdf\n";
} catch (Exception $e) {
    echo "PDF generation failed: " . $e->getMessage() . "\n";
}

// Test PNG generation
echo "Testing PNG generation...\n";

try {
    // Create image canvas
    $image = \Intervention\Image\ImageManagerStatic::canvas(400, 250, '#ffffff');
    
    // Add border
    $image->rectangle(0, 0, 399, 249, function ($draw) {
        $draw->border(2, '#333333');
    });
    
    // Add title
    $image->text('SHREE HINDUTAKHT', 200, 20, function($font) {
        $font->size(18);
        $font->color('#b93a20');
        $font->align('center');
    });
    
    $image->text('Member Identity Card', 200, 45, function($font) {
        $font->size(14);
        $font->color('#333333');
        $font->align('center');
    });
    
    // Add member details
    $details = [
        'Member ID: TEST123',
        'Name: Test User',
        'Email: test@example.com',
        'Website: www.shreehindutakht.com'
    ];
    
    $y = 80;
    foreach ($details as $detail) {
        $image->text($detail, 20, $y, function($font) {
            $font->size(11);
            $font->color('#333333');
            $font->align('left');
        });
        $y += 20;
    }
    
    // Save image
    $image->save('test_id_card.png');
    echo "PNG generated successfully: test_id_card.png\n";
} catch (Exception $e) {
    echo "PNG generation failed: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";