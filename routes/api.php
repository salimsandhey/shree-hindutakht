<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\MemberController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\EventController;
use App\Http\Controllers\Api\Admin\DonationController;
use App\Http\Controllers\PostController as MemberPostController;
use App\Http\Controllers\EventController as MemberEventController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Public routes (no authentication required)
Route::get('donation-info', [DonationController::class, 'index']);

// Public news routes (no authentication required)
Route::prefix('news')->group(function () {
    Route::get('/', [\App\Http\Controllers\NewsController::class, 'index']);
    Route::get('/{news}', [\App\Http\Controllers\NewsController::class, 'show']);
    // Removed featured route
});

// Admin public routes
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
});

// Protected routes (authentication required)
Route::middleware('api.auth')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']); // Alternative endpoint for file uploads
        Route::post('remove-photo', [AuthController::class, 'removePhoto']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Posts/Feed routes (read-only for members)
    Route::prefix('posts')->group(function () {
        Route::get('/', [MemberPostController::class, 'index']);
        Route::get('{post}', [MemberPostController::class, 'show']);
        Route::post('{post}/like', [MemberPostController::class, 'toggleLike']);
        Route::post('{post}/comment', [MemberPostController::class, 'addComment']);
        Route::get('{post}/comments', [MemberPostController::class, 'getComments']);
        Route::post('{post}/share', [MemberPostController::class, 'sharePost']);
    });

    // Events routes
    Route::prefix('events')->group(function () {
        Route::get('/', [MemberEventController::class, 'index']);
        Route::get('{event}', [MemberEventController::class, 'show']);
        Route::post('{event}/rsvp', [MemberEventController::class, 'rsvp']);
        Route::get('{event}/rsvps', [MemberEventController::class, 'getRsvps']);
    });

    // Notifications routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('unread-count', [NotificationController::class, 'getUnreadCount']);
    });

    // Member utilities
    Route::prefix('member')->group(function () {
        Route::get('id-card', [AuthController::class, 'generateIdCard']);
        Route::get('id-card/download', [AuthController::class, 'downloadIdCard']);
    });
});

// Admin API routes (protected)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::post('logout', [AdminController::class, 'logout']);
    Route::get('profile', [AdminController::class, 'profile']);
    Route::post('refresh', [AdminController::class, 'refresh']);
    
    // Dashboard stats
    Route::get('dashboard/stats', [AdminController::class, 'dashboardStats']);
    
    // Member management routes
    Route::prefix('members')->group(function () {
        Route::get('/', [MemberController::class, 'index']);
        Route::get('/stats', [MemberController::class, 'stats']);
        Route::get('/{member}', [MemberController::class, 'show']);
        Route::put('/{member}', [MemberController::class, 'update']);
        Route::delete('/{member}', [MemberController::class, 'destroy']);
        Route::post('/{member}/toggle-status', [MemberController::class, 'toggleStatus']);
    });
    
    // Post management routes
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::put('/{post}', [PostController::class, 'update']);
        Route::delete('/{post}', [PostController::class, 'destroy']);
    });
    
    // News management routes
    Route::prefix('news')->group(function () {
        Route::get('/', [\App\Http\Controllers\NewsController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\NewsController::class, 'store']);
        Route::get('/{news}', [\App\Http\Controllers\NewsController::class, 'show']);
        Route::put('/{news}', [\App\Http\Controllers\NewsController::class, 'update']);
        Route::delete('/{news}', [\App\Http\Controllers\NewsController::class, 'destroy']);
    });
    
    // Event management routes
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::get('/{event}', [EventController::class, 'show']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
    });
    
    // Donation management routes
    Route::prefix('donations')->group(function () {
        Route::get('/', [DonationController::class, 'index']);
        Route::put('/', [DonationController::class, 'update']);
    });
});