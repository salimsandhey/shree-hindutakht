<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public landing page
Route::get('/', function () {
    // Redirect authenticated users to home
    if (auth()->check()) {
        return redirect()->route('home');
    }
    
    // Add a simple log to see if this route is being hit
    \Log::info('Root route accessed');
    return view('landing');
})->name('landing');

// Public pages
Route::get('/about', function () {
    // Redirect authenticated users to home
    if (auth()->check()) {
        return redirect()->route('home');
    }
    
    return view('about');
})->name('about');

Route::get('/team', function () {
    // Redirect authenticated users to home
    if (auth()->check()) {
        return redirect()->route('home');
    }
    
    return view('team');
})->name('team');

Route::get('/mission-vision', function () {
    // Redirect authenticated users to home
    if (auth()->check()) {
        return redirect()->route('home');
    }
    
    return view('mission-vision');
})->name('mission-vision');

Route::get('/privacy-policy', function () {
    // Privacy policy should be accessible to everyone, including authenticated users
    return view('privacy-policy');
})->name('privacy-policy');

// Test route to check if redirects are happening
Route::get('/test-privacy', function () {
    return view('privacy-policy');
})->name('test.privacy');

// Test path debugging page
Route::get('/test-path', function () {
    return view('test-path');
})->name('test.path');

// Add a simple test route to verify routing is working
Route::get('/test-simple', function () {
    return 'Simple test route is working!';
});

// Add a test view route
Route::get('/test-view', function () {
    return view('test-simple');
});

// Test root path debugging page
Route::get('/test-root', function () {
    return view('test-root');
})->name('test.root');

Route::get('/test-events', function () {
    $events = Event::all();
    return response()->json([
        'total' => $events->count(),
        'events' => $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'status' => $event->status,
                'event_date' => $event->event_date,
                'is_future' => $event->event_date > now(),
                'upcoming_scope' => $event->status === 'upcoming' && $event->event_date > now()
            ];
        })
    ]);
});

// Test navigation page
Route::get('/test-nav', function () {
    return view('test-nav');
})->name('test-nav');

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Home page
Route::get('/home', function () {
    return view('home');
})->name('home');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->name('admin.login');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Dedicated admin pages
    Route::get('/posts', function () {
        return view('admin.posts');
    })->name('admin.posts');
    
    Route::get('/news', function () {
        return view('admin.news');
    })->name('admin.news');
    
    Route::get('/members', function () {
        return view('admin.members');
    })->name('admin.members');
    
    Route::get('/events', function () {
        return view('admin.events');
    })->name('admin.events');
    
    Route::get('/donations', function () {
        return view('admin.donations');
    })->name('admin.donations');
    

});

// Member dashboard and profile (protected routes)
Route::get('/member/dashboard', function () {
    return view('member.dashboard');
})->name('member.dashboard');

Route::get('/member/profile', function () {
    return view('member.profile');
})->name('member.profile');

Route::get('/member/posts', function () {
    return view('member.posts');
})->name('member.posts');

Route::get('/member/events', function () {
    return view('member.events');
})->name('member.events');

Route::get('/member/notifications', function () {
    return view('member.notifications');
})->name('member.notifications');

Route::get('/member/edit-profile', function () {
    return view('member.edit-profile');
})->name('member.edit-profile');

Route::get('/member/id-card', function () {
    return view('member.id-card');
})->name('member.id-card');

Route::get('/member/donations', function () {
    return view('member.donations');
})->name('member.donations');

// Member public profile (for QR code links) - Use more specific pattern
Route::get('/member/profile/{member_id}', function ($member_id) {
    return view('member.public', compact('member_id'));
})->name('member.public');

// Public news page
Route::get('/news', function () {
    return view('news');
})->name('news.index');

// Public news detail page
Route::get('/news/{news}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Test route
Route::get('/test', function () {
    return view('test');
});

// Debug route for testing optimizations
Route::get('/debug-optimizations', function () {
    return view('debug-optimizations');
})->name('debug.optimizations');

// Safety standards page for Google Play compliance
Route::get('/safety-standards', function () {
    return view('safety-standards');
})->name('safety.standards');

// Navigation test page
Route::get('/navigation-test', function () {
    return view('navigation-test');
})->name('navigation.test');

// Simple nav test page
Route::get('/nav-test', function () {
    return view('nav-test');
})->name('nav.test');

// Test route for ID card generation
Route::get('/test-id-card', function () {
    // Create a mock member for testing
    $member = new \stdClass();
    $member->member_id = 'HT-2025-001';
    $member->name = 'John Doe';
    $member->email = 'john.doe@example.com';
    $member->created_at = \Illuminate\Support\Carbon::now()->subMonths(6);
    $member->status = 'active';
    
    // Generate QR code for testing
    $qrCode = \Endroid\QrCode\Builder\Builder::create()
        ->data('https://www.shreehindutakht.com/verify/' . $member->member_id)
        ->size(300)
        ->margin(10)
        ->build();
        
    $qrCodeBase64 = base64_encode($qrCode->getString());
    
    // Return the ID card view
    return view('member.id-card', compact('member', 'qrCodeBase64'));
});

Route::get('/test-id-card-png', function () {
    // Create a mock member for testing
    $member = new \stdClass();
    $member->member_id = 'HT-2025-001';
    $member->name = 'John Doe';
    $member->email = 'john.doe@example.com';
    $member->created_at = \Illuminate\Support\Carbon::now()->subMonths(6);
    $member->status = 'active';
    
    // Generate QR code for testing
    $qrCode = \Endroid\QrCode\Builder\Builder::create()
        ->data('https://www.shreehindutakht.com/verify/' . $member->member_id)
        ->size(300)
        ->margin(10)
        ->build();
        
    $qrCodeBase64 = base64_encode($qrCode->getString());
    
    // Use our improved PNG generation method
    $controller = new \App\Http\Controllers\Api\AuthController();
    return $controller->generateIdCardPNG($member, $qrCodeBase64);
});

Route::get('/test-id-card-pdf', function () {
    // Create a mock member for testing
    $member = new \stdClass();
    $member->member_id = 'HT-2025-001';
    $member->name = 'John Doe';
    $member->email = 'john.doe@example.com';
    $member->created_at = \Illuminate\Support\Carbon::now()->subMonths(6);
    $member->status = 'active';
    
    // Generate QR code for testing
    $qrCode = \Endroid\QrCode\Builder\Builder::create()
        ->data('https://www.shreehindutakht.com/verify/' . $member->member_id)
        ->size(300)
        ->margin(10)
        ->build();
        
    $qrCodeBase64 = base64_encode($qrCode->getString());
    
    // Use our improved PDF generation method
    $controller = new \App\Http\Controllers\Api\AuthController();
    return $controller->generateIdCardPDF($member, $qrCodeBase64);
});


// Temporary Debug Route (Delete after fixing)
Route::get('/debug-manifest', function () {
    $path = public_path('build/manifest.json');
    $exists = file_exists($path);
    $content = $exists ? file_get_contents($path) : 'File not found';
    $decoded = json_decode($content, true);
    
    return [
        'php_looking_at_path' => $path,
        'file_exists_status' => $exists,
        'file_last_modified' => $exists ? date('Y-m-d H:i:s', filemtime($path)) : null,
        'raw_content_preview' => substr($content, 0, 500),
        'parsed_css_file' => $decoded['resources/css/app.css']['file'] ?? 'KEY MISSING',
        'server_time' => date('Y-m-d H:i:s'),
    ];
});
