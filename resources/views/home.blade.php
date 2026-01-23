@extends('layouts.app-no-nav')

@section('title', 'Shree Hindutakht - Home')

@section('content')
<div class="flex flex-col h-screen">
    <!-- Fixed Header - Changed to responsive positioning -->
    <header class="bg-white text-gray-800 fixed top-0 left-0 right-0 z-50 shadow-lg border-b border-gray-200 mobile-header">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht" class="h-8 object-contain lazy" data-src="{{ asset('logo3.png') }}" loading="lazy">
        </div>
        <div class="flex items-center space-x-3 relative">
            <!-- Refresh Button -->
            <button id="refresh-posts-btn" class="p-2 text-gray-600 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            
            <button id="notifications-btn" class="relative p-2 text-gray-600 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
                <span id="notification-badge" class="notification-badge hidden">0</span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div id="notifications-dropdown" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50 max-h-96 overflow-y-auto">
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Notifications</h3>
                    <div id="notifications-list">
                        <p class="text-gray-500 text-sm">No new notifications</p>
                    </div>
                </div>
            </div>
            
            <button id="profile-menu" class="relative flex items-center space-x-2">
                <img id="user-avatar" class="avatar" src="" alt="Profile" style="display: none;">
                <div id="default-avatar" class="avatar bg-primary text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Profile Dropdown -->
            <div id="profile-dropdown" class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                <div class="py-2">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p id="dropdown-name" class="font-medium text-gray-900">Loading...</p>
                        <p id="dropdown-member-id" class="text-sm text-gray-500">ID: Loading...</p>
                    </div>
                    <button id="edit-profile-dropdown" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Edit Profile
                    </button>
                    <button id="view-id-card-dropdown" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0v2m0 0h4"></path>
                        </svg>
                        View ID Card
                    </button>
                    <div class="border-t border-gray-100 my-1"></div>
                    <button id="logout-dropdown" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
    </header>

    <!-- Main Content with top padding for fixed header -->
    <main class="flex-1 overflow-y-auto pt-20 pb-20">
    <!-- Feed Section -->
    <div id="feed-section" class="p-4 space-y-4">
        <!-- Feed welcome message -->
        <div class="card">
            <div class="text-center p-4">
                <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht" class="h-12 mx-auto mb-4 object-contain lazy" data-src="{{ asset('logo3.png') }}" loading="lazy">
                <p class="text-gray-600 text-sm">Stay connected with your community. View posts, join events, and explore our services.</p>
            </div>
        </div>

        <!-- Posts Container -->
        <div id="posts-container">
            <!-- Posts will be loaded here -->
        </div>

        <!-- Infinite Scroll Trigger (invisible) -->
        <div id="infinite-scroll-trigger" class="h-4 w-full"></div>
        
        <!-- Loading Indicator for Infinite Scroll -->
        <div id="loading-more" class="hidden text-center py-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto mb-2"></div>
            <p class="text-gray-500 text-sm">Loading more posts...</p>
        </div>
    </div>

    <!-- Events Section -->
    <div id="events-section" class="p-4 space-y-4 hidden">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Community Events</h2>
        </div>
        
        <!-- Events Content -->
        <div class="card text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Events Coming Soon</h3>
            <p class="text-gray-500 mb-4">Community events will be displayed here</p>
            
            <!-- Event Categories Placeholder -->
            <div class="grid grid-cols-2 gap-4 mt-6 max-w-md mx-auto">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="text-sm font-medium text-gray-700">Religious</p>
                    <p class="text-xs text-gray-500">Ceremonies & Prayers</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-sm font-medium text-gray-700">Educational</p>
                    <p class="text-xs text-gray-500">Learning & Workshops</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-gray-700">Community</p>
                    <p class="text-xs text-gray-500">Social Gatherings</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-gray-700">Charitable</p>
                    <p class="text-xs text-gray-500">Service & Donations</p>
                </div>
            </div>
        </div>
        
        <div id="events-container">
            <!-- Future events will be loaded here dynamically -->
        </div>
    </div>

    <!-- Donation Section -->
    <div id="donation-section" class="p-4 hidden">
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Support Our Cause</h2>
            <p class="text-gray-600 mb-6">Your contribution helps us serve the community better.</p>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Bank Details</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div>Bank: <span id="home-bank-name">Loading...</span></div>
                    <div>Account: <span id="home-account-name">Loading...</span></div>
                    <div>Number: <span id="home-account-number">Loading...</span></div>
                    <div>IFSC: <span id="home-ifsc-code">Loading...</span></div>
                </div>
            </div>

            <div class="text-center">
                <div id="home-qr-container" class="w-48 h-48 bg-gray-200 rounded-xl mx-auto mb-4 flex items-center justify-center hidden">
                    <img id="home-qr-code" src="" alt="Donation QR Code" class="w-full h-full object-contain">
                </div>
                <div id="home-qr-placeholder" class="w-48 h-48 bg-gray-200 rounded-xl mx-auto mb-4 flex items-center justify-center">
                    <span class="text-gray-500">QR Code</span>
                </div>
                <p class="text-sm text-gray-600">Scan to donate via UPI</p>
                <p class="text-sm font-medium text-primary" id="home-upi-id">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div id="profile-section" class="p-4 hidden">
        <div class="card">
            <div class="text-center mb-6">
                <img id="profile-avatar" class="avatar-lg mx-auto mb-4" src="" alt="Profile" style="display: none;">
                <div id="profile-default-avatar" class="avatar-lg mx-auto mb-4 bg-gray-200 text-gray-600 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
                <h2 id="profile-name" class="text-xl font-bold text-gray-800">Loading...</h2>
                <p id="profile-member-id" class="text-gray-600 text-sm">ID: Loading...</p>
            </div>

            <div class="space-y-4">
                <button id="edit-profile" class="btn-primary w-full">
                    ✏️ Edit Profile
                </button>
                <button id="view-id-card" class="btn-secondary w-full">
                    🆔 View ID Card
                </button>
                <button id="change-password" class="btn-secondary w-full">
                    🔒 Change Password
                </button>
                <button id="logout-btn" class="w-full py-3 px-6 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                    🚪 Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Section -->
    <div id="edit-profile-section" class="p-4 hidden">
        <!-- Error Messages -->
        <div id="edit-profile-error-message" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 animate-fade-in">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div id="edit-profile-error-text" class="ml-3 text-sm text-red-700"></div>
            </div>
        </div>

        <!-- Success Messages -->
        <div id="edit-profile-success-message" class="hidden bg-green-50 border border-green-200 rounded-xl p-4 animate-fade-in">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div id="edit-profile-success-text" class="ml-3 text-sm text-green-700">Profile updated successfully!</div>
            </div>
        </div>

        <!-- Profile Picture Section -->
        <div class="card animate-fade-in mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Picture</h2>
            <div class="flex items-center space-x-4">
                <div class="profile-picture-upload">
                    <img id="edit-profile-picture-preview" class="avatar-xl" src="" alt="Profile Picture" style="display: none;">
                    <div id="edit-profile-picture-placeholder" class="avatar-xl bg-primary text-white flex items-center justify-center text-2xl font-bold">
                        <!-- Initials will be populated by JavaScript -->
                    </div>
                    <div class="profile-picture-overlay touch-target mobile-tap-highlight">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <input type="file" id="edit-profile-picture-input" class="hidden" accept="image/*,.webp">
                </div>
                <div>
                    <h3 class="font-medium text-gray-800">Update your photo</h3>
                    <p class="text-sm text-gray-500">Click on the image to upload a new photo (max 10MB)</p>
                    <p class="text-xs text-gray-400">Images will be compressed to WebP format (~50KB) for optimal performance</p>
                    <button id="edit-remove-photo-btn" class="btn-secondary mt-2 text-sm touch-target mobile-button mobile-tap-highlight" style="display: none;">
                        Remove Photo
                    </button>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card animate-fade-in mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>
            <form id="edit-profile-form" class="space-y-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="edit-name" name="name" class="input-field" placeholder="Enter your full name" required>
                </div>

                <div>
                    <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="edit-email" name="email" class="input-field" placeholder="Enter your email" required>
                </div>

                <div>
                    <label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="edit-phone" name="phone" class="input-field" placeholder="Enter your phone number">
                </div>

                <div>
                    <label for="edit-address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="edit-address" name="address" rows="3" class="input-field" placeholder="Enter your address"></textarea>
                </div>

                <div>
                    <label for="edit-date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="edit-date_of_birth" name="date_of_birth" class="input-field">
                </div>
            </form>
        </div>

        <!-- Member Information (Read-only) -->
        <div class="card animate-fade-in mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Member Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Member ID</span>
                    <span id="edit-member-id" class="text-sm text-gray-800 font-medium">-</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Join Date</span>
                    <span id="edit-join-date" class="text-sm text-gray-800">-</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    <span id="edit-status" class="text-sm text-green-600 font-medium">Active</span>
                </div>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="card animate-fade-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
            <form id="edit-change-password-form" class="space-y-4">
                <div>
                    <label for="edit-current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="edit-current_password" name="current_password" class="input-field" placeholder="Enter current password">
                </div>

                <div>
                    <label for="edit-new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="edit-new_password" name="new_password" class="input-field" placeholder="Enter new password">
                </div>

                <div>
                    <label for="edit-new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="edit-new_password_confirmation" name="new_password_confirmation" class="input-field" placeholder="Confirm new password">
                </div>

                <button type="button" id="edit-change-password-btn" class="btn-primary w-full touch-target mobile-button mobile-tap-highlight">
                    <span id="edit-password-text">Change Password</span>
                    <div id="edit-password-loading" class="hidden flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Changing...
                    </div>
                </button>
            </form>
        </div>

        <!-- Save Profile Button -->
        <div class="mt-6">
            <button id="edit-save-profile-btn" class="btn-primary w-full touch-target mobile-button mobile-tap-highlight">
                <span id="edit-save-text">Save Changes</span>
                <div id="edit-save-loading" class="hidden flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </div>
            </button>
        </div>
    </div>
</main>

<!-- Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div class="flex justify-around">
        <button class="nav-item active" data-section="feed">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 001.414 1.414L2 12.414V17a1 1 0 001 1h14a1 1 0 001-1v-4.586l.293.293a1 1 0 001.414-1.414l-9-9z"/>
            </svg>
            <span class="text-xs">Feed</span>
        </button>
        <button class="nav-item" data-section="events">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs">Events</span>
        </button>
        <button class="nav-item" data-section="donation">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
            <span class="text-xs">Donate</span>
        </button>
        <button class="nav-item" data-section="profile">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
            </svg>
            <span class="text-xs">Profile</span>
        </button>
    </div>
</nav>
```

<!-- Modals will be added via JavaScript -->
<style>
/* Spinning animation for refresh button */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Profile picture upload styles */
.profile-picture-upload {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.profile-picture-upload .avatar-xl {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-picture-upload #profile-picture-placeholder,
.profile-picture-upload #edit-profile-picture-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #3b82f6;
    color: white;
    font-weight: bold;
}

.profile-picture-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100px;
    height: 100px;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-picture-upload:hover .profile-picture-overlay {
    opacity: 1;
}

/* Input field styles */
.input-field {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.input-field:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button styles */
.btn-primary {
    background-color: #3b82f6;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #f3f4f6;
    color: #374151;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #e5e7eb;
}

/* Card styles */
.card {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 16px;
}

/* Animation styles */
.animate-fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Avatar styles */
.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-lg {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-xl {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
<script>
// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing home page');
    
    // Check authentication
    let isAuthenticated = false;
    
    // Try to use AuthManager from app.js
    if (typeof AuthManager !== 'undefined' && AuthManager.isAuthenticated) {
        isAuthenticated = AuthManager.isAuthenticated();
    } else {
        // Fallback: manually check token
        isAuthenticated = !!localStorage.getItem('auth_token');
    }
    
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    
    // Load initial posts
    loadPosts();
    
    // Initialize like buttons with event delegation
    initializeLikeButtons();
    
    // Initialize edit profile listeners
    initializeEditProfileListeners();
    
    // Initialize navigation after a short delay to ensure all sections are ready
    setTimeout(() => {
        initNavigation();
    }, 100);
    // Add profile link event listener
    const profileLink = document.getElementById('profile-link');
    if (profileLink) {
        // Remove any existing event listeners
        const newProfileLink = profileLink.cloneNode(true);
        profileLink.parentNode.replaceChild(newProfileLink, profileLink);
        
        newProfileLink.addEventListener('click', function(e) {
            e.preventDefault();
            showSection('profile');
        }, true); // Use capture phase
    }
    
    // Add logout event listener
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        // Remove any existing event listeners
        const newLogoutBtn = logoutBtn.cloneNode(true);
        logoutBtn.parentNode.replaceChild(newLogoutBtn, logoutBtn);
        
        newLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Use the AuthManager from app.js
            if (typeof AuthManager !== 'undefined' && AuthManager.removeToken) {
                AuthManager.removeToken();
            } else {
                // Fallback: manually remove token
                localStorage.removeItem('auth_token');
            }
            window.location.href = '/login';
        }, true); // Use capture phase
    }
    
    // Add edit profile button event listener
    const editProfileBtn = document.getElementById('edit-profile');
    if (editProfileBtn) {
        // Remove any existing event listeners
        const newEditProfileBtn = editProfileBtn.cloneNode(true);
        editProfileBtn.parentNode.replaceChild(newEditProfileBtn, editProfileBtn);
        
        newEditProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Edit profile button clicked');
            
            // Check if we're already handling this in app.js
            if (window.location.pathname === '/home') {
                console.log('On home page, attempting to show edit profile section');
                // Use the showSection function from app.js if available
                if (typeof showSection === 'function') {
                    console.log('showSection function found, calling it');
                    showSection('edit-profile');
                } else {
                    console.log('showSection function not found, using fallback');
                    // Fallback to manual section showing
                    showSection('edit-profile');
                }
            } else {
                console.log('Not on home page, redirecting to edit profile page');
                window.location.href = '/member/edit-profile';
            }
        }, true); // Use capture phase
    }
    
    // Add edit profile dropdown button event listener
    const editProfileDropdownBtn = document.getElementById('edit-profile-dropdown');
    if (editProfileDropdownBtn) {
        // Remove any existing event listeners
        const newEditProfileDropdownBtn = editProfileDropdownBtn.cloneNode(true);
        editProfileDropdownBtn.parentNode.replaceChild(newEditProfileDropdownBtn, editProfileDropdownBtn);
        
        newEditProfileDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Edit profile dropdown button clicked');
            
            // Check if we're already handling this in app.js
            if (window.location.pathname === '/home') {
                console.log('On home page, attempting to show edit profile section from dropdown');
                // Use the showSection function from app.js if available
                if (typeof showSection === 'function') {
                    console.log('showSection function found, calling it from dropdown');
                    showSection('edit-profile');
                } else {
                    console.log('showSection function not found, using fallback from dropdown');
                    // Fallback to manual section showing
                    showSection('edit-profile');
                }
                // Hide the profile dropdown
                const profileDropdown = document.getElementById('profile-dropdown');
                if (profileDropdown) {
                    profileDropdown.classList.add('hidden');
                }
            } else {
                console.log('Not on home page, redirecting to edit profile page from dropdown');
                window.location.href = '/member/edit-profile';
            }
        }, true); // Use capture phase
    }
    
// Initialize navigation
function initNavigation() {
    // Add event listeners to navigation items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            showSection(section);
            
            // Update active state
            navItems.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Handle hash navigation
    if (window.location.hash) {
        const section = window.location.hash.substring(1);
        if (['feed', 'events', 'donation', 'profile', 'edit-profile'].includes(section)) {
            showSection(section);
            // Update active navigation item
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            const targetNav = document.querySelector(`[data-section="${section}"]`);
            if (targetNav) {
                targetNav.classList.add('active');
            }
        }
    } else {
        // Show initial section (feed by default)
        showSection('feed');
    }
}

// Show specific section and hide others
function showSection(sectionName) {
    console.log('showSection called with:', sectionName);
    
    // Hide all sections
    const sections = ['feed', 'events', 'donation', 'profile', 'edit-profile'];
    sections.forEach(section => {
        const element = document.getElementById(`${section}-section`);
        if (element) {
            element.classList.add('hidden');
        }
    });
    
    // Show the requested section
    const targetSection = document.getElementById(`${sectionName}-section`);
    console.log('Target section element:', targetSection);
    
    if (targetSection) {
        targetSection.classList.remove('hidden');
        console.log('Section shown successfully');
    } else {
        console.log('Target section not found');
    }
    
    // Update URL hash
    window.location.hash = sectionName;
    
    // Special handling for donation section
    if (sectionName === 'donation') {
        loadDonationData();
    }
    
    // Special handling for profile section - load user data
    if (sectionName === 'profile') {
        loadUserProfileData();
    }
    
    // Special handling for edit profile section - load user data
    if (sectionName === 'edit-profile') {
        console.log('Loading edit profile data');
        loadEditProfileData();
    }
}

async function loadDonationData() {
    try {
        // Fetch donation details from the API with cache busting
        const response = await fetch(`/api/donation-info?t=${new Date().getTime()}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update bank details
            document.getElementById('home-bank-name').textContent = data.bank_details.bank_name || 'Not available';
            document.getElementById('home-account-name').textContent = data.bank_details.account_name || 'Not available';
            document.getElementById('home-account-number').textContent = data.bank_details.account_number || 'Not available';
            document.getElementById('home-ifsc-code').textContent = data.bank_details.ifsc_code || 'Not available';
            
            // Update UPI details
            document.getElementById('home-upi-id').textContent = data.upi_id || 'Not available';
            
            // Show QR code if available
            if (data.qr_code) {
                const qrContainer = document.getElementById('home-qr-container');
                const qrPlaceholder = document.getElementById('home-qr-placeholder');
                const qrImage = document.getElementById('home-qr-code');
                
                if (qrContainer && qrPlaceholder && qrImage) {
                    qrImage.src = data.qr_code;
                    qrContainer.classList.remove('hidden');
                    qrPlaceholder.classList.add('hidden');
                }
            }
        }
    } catch (error) {
        console.error('Error loading donation details:', error);
        
        // Show error message
        document.getElementById('home-bank-name').textContent = 'Error loading data';
        document.getElementById('home-account-name').textContent = 'Error loading data';
        document.getElementById('home-account-number').textContent = 'Error loading data';
        document.getElementById('home-ifsc-code').textContent = 'Error loading data';
        document.getElementById('home-upi-id').textContent = 'Error loading data';
    }
}

// Refresh posts function - clears cache and fetches latest posts
async function refreshPosts() {
    console.log('Refresh button clicked');
    const refreshBtn = document.getElementById('refresh-posts-btn');
    
    if (!refreshBtn) {
        console.error('Refresh button not found');
        return;
    }
    
    // Store original content
    const originalHTML = refreshBtn.innerHTML;
    
    try {
        // Show loading state
        refreshBtn.innerHTML = `
            <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        `;
        refreshBtn.disabled = true;
        
        console.log('Refreshing posts...');
        
        // Clear any existing cache
        if ('caches' in window) {
            try {
                const cacheNames = await caches.keys();
                await Promise.all(cacheNames.map(name => caches.delete(name)));
                console.log('Caches cleared');
            } catch (cacheError) {
                console.warn('Could not clear caches:', cacheError);
            }
        }
        
        // Clear localStorage items that might be cached
        localStorage.removeItem('hindutakht_posts_cache');
        localStorage.removeItem('hindutakht_posts_cache_page_1');
        localStorage.removeItem('posts_cache');
        localStorage.removeItem('posts_last_fetch');
        console.log('Local storage cleared');
        
        // Reload posts
        await loadPosts(true);
        console.log('Posts reloaded');
        
        // Show success message
        showMessage('Posts refreshed successfully!', 'success');
    } catch (error) {
        console.error('Error refreshing posts:', error);
        showMessage('Failed to refresh posts. Please try again.', 'error');
    } finally {
        // Restore button after a short delay
        setTimeout(() => {
            if (refreshBtn) {
                refreshBtn.innerHTML = originalHTML;
                refreshBtn.disabled = false;
            }
        }, 1000);
    }
}

// Fallback function to load posts directly
async function loadPostsDirect() {
    const container = document.getElementById('posts-container');
    if (!container) return;
    
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        // Add cache-busting parameters
        const timestamp = Date.now();
        const response = await fetch(`/api/posts?page=1&per_page=10&_t=${timestamp}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            cache: 'no-cache'
        });
        
        if (!response.ok) {
            if (response.status === 401) {
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
                return;
            }
            throw new Error('Failed to load posts');
        }
        
        const data = await response.json();
        
        if (data.success) {
            container.innerHTML = '';
            
            if (data.data.posts && data.data.posts.length > 0) {
                data.data.posts.forEach(post => {
                    const postElement = createPostElement(post);
                    container.appendChild(postElement);
                });
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No posts yet</p>
                        <p class="text-gray-400 text-sm">Check back later for community updates</p>
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading posts:', error);
        container.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Failed to load posts</p>
                <button onclick="loadPostsDirect()" class="btn-primary mt-4">Try Again</button>
            </div>
        `;
    }
}

// Load posts with refresh capability
async function loadPosts(refresh = false) {
    console.log('Loading posts, refresh:', refresh);
    const container = document.getElementById('posts-container');
    const refreshBtn = document.getElementById('refresh-posts-btn');
    
    // Validate container exists
    if (!container) {
        console.error('Posts container not found');
        return;
    }
    
    // Show loading state
    if (refresh && refreshBtn) {
        refreshBtn.innerHTML = `
            <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        `;
        refreshBtn.disabled = true;
    } else {
        container.innerHTML = `
            <div class="space-y-4">
                ${Array.from({length: 3}, () => `
                    <div class="card post-skeleton">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full animate-pulse"></div>
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/4 mt-2 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded animate-pulse w-5/6"></div>
                            <div class="h-4 bg-gray-200 rounded animate-pulse w-3/4"></div>
                        </div>
                        <div class="flex justify-between mt-4 pt-3 border-t border-gray-100">
                            <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    try {
        // Get token with better error handling
        let token = null;
        
        // Try to use AuthManager from app.js
        if (typeof AuthManager !== 'undefined' && AuthManager.getToken) {
            token = AuthManager.getToken();
        } else {
            // Fallback: manually get token
            token = localStorage.getItem('auth_token');
        }
        
        if (!token) {
            console.error('No auth token found');
            window.location.href = '/login';
            return;
        }
        
        // Add cache-busting parameters
        const timestamp = Date.now();
        const response = await fetch(`/api/posts?_t=${timestamp}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            cache: 'no-cache'
        });
        
        console.log('Posts API response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('Posts data received:', data);
            
            if (data.success) {
                // Clear container and add posts
                container.innerHTML = '';
                
                if (data.data.data && data.data.data.length > 0) {
                    data.data.data.forEach(post => {
                        const postElement = createPostElement(post);
                        container.appendChild(postElement);
                    });
                    
                    // Add event listeners to the new post elements
                    console.log('Adding event listeners to', data.data.data.length, 'posts');
                    console.log('Comment buttons before adding listeners:', document.querySelectorAll('.comment-btn').length);
                    addPostEventListeners();
                    console.log('Comment buttons after adding listeners:', document.querySelectorAll('.comment-btn').length);
                } else {
                    container.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <p class="text-gray-500">No posts available</p>
                        </div>
                    `;
                }
                
                if (refresh && refreshBtn) {
                    showMessage('Feed refreshed successfully!', 'success');
                }
            } else {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-500">Error loading posts: ${data.message || 'Unknown error'}</p>
                        <button onclick="loadPosts()" class="mt-4 btn-primary px-4 py-2 text-sm">Try Again</button>
                    </div>
                `;
            }
        } else {
            if (response.status === 401) {
                // Token expired, redirect to login
                if (typeof AuthManager !== 'undefined' && AuthManager.removeToken) {
                    AuthManager.removeToken();
                } else {
                    localStorage.removeItem('auth_token');
                }
                window.location.href = '/login';
                return;
            }
            
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-500">Failed to connect to server (Status: ${response.status})</p>
                    <button onclick="loadPosts()" class="mt-4 btn-primary px-4 py-2 text-sm">Try Again</button>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading posts:', error);
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-500">Error loading posts: ${error.message}</p>
                <button onclick="loadPosts()" class="mt-4 btn-primary px-4 py-2 text-sm">Try Again</button>
            </div>
        `;
    } finally {
        if (refresh && refreshBtn) {
            setTimeout(() => {
                if (refreshBtn) {
                    refreshBtn.innerHTML = `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    `;
                    refreshBtn.disabled = false;
                }
            }, 1000);
        }
    }
}

// Create post element
function createPostElement(post) {
    const postDiv = document.createElement('div');
    postDiv.className = 'card post-card mb-4';
    
    // Generate media HTML
    let mediaHtml = '';
    if (post.media_urls && post.media_urls.length > 0) {
        const images = post.media_urls.filter(url => {
            const ext = url.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
        });
        
        const videos = post.media_urls.filter(url => {
            const ext = url.split('.').pop().toLowerCase();
            return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
        });
        
        if (images.length > 0) {
            if (images.length === 1) {
                mediaHtml += `
                    <div class="mt-3">
                        <img src="${images[0]}" class="w-full h-64 object-cover rounded-lg cursor-pointer" alt="Post image" onclick="openImageGallery(['${images[0]}'])" onerror="this.style.display='none'">
                    </div>
                `;
            } else {
                mediaHtml += `
                    <div class="mt-3 grid grid-cols-2 gap-2 cursor-pointer" onclick="openImageGallery([${images.map(url => `'${url}'`).join(',')}])">
                        ${images.slice(0, 4).map((url, index) => {
                            if (index === 3 && images.length > 4) {
                                return `<div class="relative"><img src="${url}" class="w-full h-32 object-cover rounded-lg" alt="Post image" onerror="this.style.display='none'"><div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg"><span class="text-white font-bold text-xl">+${images.length - 4}</span></div></div>`;
                            }
                            return `<img src="${url}" class="w-full h-32 object-cover rounded-lg" alt="Post image" onerror="this.style.display='none'">`;
                        }).join('')}
                    </div>
                `;
            }
        }
        
        if (videos.length > 0) {
            mediaHtml += videos.map(url => `
                <div class="mt-3">
                    <video controls class="w-full h-64 rounded-lg">
                        <source src="${url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            `).join('');
        }
    }
    
    // Get user photo or generate avatar
    let userPhoto;
    if (post.created_by_admin && post.admin && post.admin.photo) {
        userPhoto = `/storage/${post.admin.photo}`;
    } else if (!post.created_by_admin && post.member && post.member.photo) {
        userPhoto = `/storage/${post.member.photo}`;
    } else {
        userPhoto = generateAvatarUrl(post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht'));
    }
    
    postDiv.innerHTML = `
        <div class="flex space-x-3">
            <img src="${userPhoto}" class="w-10 h-10 rounded-full object-cover" alt="User avatar" onerror="this.src='${generateAvatarUrl(post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht'))}'">
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-1">
                    <h3 class="font-semibold text-gray-900 truncate">${post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht')}</h3>
                    ${post.created_by_admin ? '<svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' : ''}
                    ${post.created_by_admin ? '<span class="bg-primary text-white text-xs px-2 py-1 rounded-full whitespace-nowrap">Admin</span>' : ''}
                    <span class="text-sm text-gray-500 whitespace-nowrap">${formatTimeAgo(post.created_at)}</span>
                    ${post.is_pinned ? '<span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">📌</span>' : ''}
                </div>
                <p class="text-gray-700 mb-3 whitespace-pre-wrap">${post.content}</p>
                ${mediaHtml}
                
                <!-- Post Actions -->
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center space-x-4">
                        <button class="like-btn flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors ${post.is_liked ? 'text-red-500' : ''}" data-post-id="${post.id}">
                            <svg class="w-5 h-5" fill="${post.is_liked ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="like-count">${post.likes_count || 0}</span>
                        </button>
                        
                        <button class="comment-btn flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors" data-post-id="${post.id}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>${post.comments_count || 0}</span>
                        </button>
                        
                        <button class="share-btn flex items-center space-x-1 text-gray-600 hover:text-green-500 transition-colors" data-post-id="${post.id}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span id="share-count-${post.id}">${post.shares_count || 0}</span>
                        </button>
                    </div>
                </div>
                
                <!-- Comments Section (initially hidden) -->
                <div id="comments-${post.id}" class="hidden mt-4 pt-4 border-t border-gray-100">
                    <div id="comments-list-${post.id}"></div>
                    <div class="mt-3">
                        <div class="flex space-x-2">
                            <input type="text" id="comment-input-${post.id}" placeholder="Write a comment..." class="flex-1 px-3 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary" data-post-id="${post.id}">
                            <button class="add-comment-btn bg-primary text-white px-4 py-2 rounded-full text-sm hover:bg-orange-600 transition-colors" data-post-id="${post.id}">Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    return postDiv;
}

// Generate avatar URL for users without photos
function generateAvatarUrl(name) {
    const initials = name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57'];
    const color = colors[name.length % colors.length];
    
    return `data:image/svg+xml,${encodeURIComponent(`
        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <rect width="48" height="48" fill="${color}"/>
            <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="18" font-weight="bold">${initials}</text>
        </svg>
    `)}`;
}

// Format time ago
function formatTimeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    
    return date.toLocaleDateString();
}

// Add post event listeners - fixed version
// NOTE: This function does NOT handle like buttons since they use event delegation
// Adding event listeners to like buttons here would interfere with the event delegation approach
function addPostEventListeners() {
    console.log('🔄 Adding post event listeners');
    console.log('Number of comment buttons found:', document.querySelectorAll('.comment-btn').length);
    console.log('Number of like buttons found:', document.querySelectorAll('.like-btn').length);
    console.log('Number of share buttons found:', document.querySelectorAll('.share-btn').length);
    
    // Share buttons
    document.querySelectorAll('.share-btn').forEach(button => {
        const postId = button.dataset.postId;
        console.log('Setting up share button for post:', postId);
        
        // Remove and re-add to prevent duplicates
        const newButton = button.cloneNode(true);
        newButton.dataset.postId = postId;
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Share button clicked for post:', postId);
            sharePost(postId);
        }, true);
    });
    
    // Comment toggle buttons
    document.querySelectorAll('.comment-btn').forEach(button => {
        const postId = button.dataset.postId;
        console.log('Setting up comment button for post:', postId);
        
        // Remove and re-add to prevent duplicates
        const newButton = button.cloneNode(true);
        newButton.dataset.postId = postId;
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function(e) {
            console.log('Comment button clicked for post:', postId);
            e.preventDefault();
            e.stopPropagation();
            toggleComments(postId);
        }, true);
    });
    
    // Add comment buttons
    document.querySelectorAll('.add-comment-btn').forEach(button => {
        const postId = button.dataset.postId;
        console.log('Setting up add comment button for post:', postId);
        
        // Remove and re-add to prevent duplicates
        const newButton = button.cloneNode(true);
        newButton.dataset.postId = postId;
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Add comment button clicked for post:', postId);
            handleCommentSubmit(postId);
        }, true);
    });
    
    // Comment input keypress events
    document.querySelectorAll('[id^="comment-input-"]').forEach(input => {
        const postId = input.dataset.postId;
        console.log('Setting up comment input for post:', postId);
        
        // Remove and re-add to prevent duplicates
        const newInput = input.cloneNode(true);
        newInput.dataset.postId = postId;
        input.parentNode.replaceChild(newInput, input);
        
        newInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                console.log('Enter key pressed in comment input for post:', postId);
                handleCommentSubmit(postId);
            }
        }, true);
    });
    
    console.log('✅ Post event listeners initialized');
}

// Initialize like functionality with event delegation
function initializeLikeButtons() {
    console.log('[LikeSystem] Initializing like buttons with event delegation');
    
    // Remove any existing event listeners by removing and re-adding the event listener
    document.removeEventListener('click', handleLikeClick);
    
    // Add single event listener to the document for event delegation
    document.addEventListener('click', handleLikeClick);
    
    console.log('[LikeSystem] Added event delegation listener for like buttons');
}

// Handle like button clicks
function handleLikeClick(e) {
    const likeBtn = e.target.closest('.like-btn');
    if (likeBtn) {
        e.preventDefault();
        e.stopPropagation();
        const postId = likeBtn.getAttribute('data-post-id');
        if (postId) {
            console.log(`[LikeSystem] Like button clicked for post ${postId}`);
            toggleLike(postId);
        }
    }
}

// Toggle like for a post
async function toggleLike(postId) {
    console.log(`[LikeSystem] Toggling like for post ${postId}`);
    
    // Get auth token
    const token = localStorage.getItem('auth_token');
    if (!token) {
        alert('Please login to like posts');
        return;
    }
    
    // Get the like button
    const likeBtn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
    if (!likeBtn) {
        console.error(`[LikeSystem] Like button not found for post ${postId}`);
        return;
    }
    
    // Show loading state
    const wasLiked = likeBtn.classList.contains('text-red-500');
    const likeCount = likeBtn.querySelector('.like-count');
    console.log(`[LikeSystem] Looking for like count element by class: .like-count`);
    console.log(`[LikeSystem] Found like count element by class:`, likeCount);
    
    const count = parseInt(likeCount?.textContent || '0');
    
    // Disable button during request
    likeBtn.disabled = true;
    
    try {
        // Send like request
        const response = await fetch(`/api/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Update UI
                if (data.data.is_liked) {
                    likeBtn.classList.add('text-red-500');
                    likeBtn.querySelector('svg').setAttribute('fill', 'currentColor');
                    if (likeCount) {
                        likeCount.textContent = data.data.likes_count;
                        console.log(`[LikeSystem] Updated like count to ${data.data.likes_count} for post ${postId}`);
                    } else {
                        console.log(`[LikeSystem] Could not find like count element for post ${postId}`);
                    }
                } else {
                    likeBtn.classList.remove('text-red-500');
                    likeBtn.querySelector('svg').setAttribute('fill', 'none');
                    if (likeCount) {
                        likeCount.textContent = data.data.likes_count;
                        console.log(`[LikeSystem] Updated like count to ${data.data.likes_count} for post ${postId}`);
                    } else {
                        console.log(`[LikeSystem] Could not find like count element for post ${postId}`);
                    }
                }
                
                console.log(`[LikeSystem] Like toggled successfully for post ${postId}`);
            } else {
                throw new Error(data.message || 'Failed to toggle like');
            }
        } else {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
    } catch (error) {
        console.error(`[LikeSystem] Failed to toggle like for post ${postId}:`, error);
        alert('Failed to process like. Please try again.');
        
        // Revert UI changes on error
        if (wasLiked) {
            likeBtn.classList.add('text-red-500');
            likeBtn.querySelector('svg').setAttribute('fill', 'currentColor');
        } else {
            likeBtn.classList.remove('text-red-500');
            likeBtn.querySelector('svg').setAttribute('fill', 'none');
        }
        if (likeCount) likeCount.textContent = count;
    } finally {
        // Re-enable button
        likeBtn.disabled = false;
    }
}

// Load user profile data for profile section
async function loadUserProfileData() {
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        const response = await fetch('/api/user/profile', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                console.log('User profile data loaded:', data.data);
                document.querySelector('#profile-name').textContent = data.data.name;
                document.querySelector('#profile-email').textContent = data.data.email;
                document.querySelector('#profile-avatar').src = data.data.avatar_url;
            } else {
                console.error('Failed to load user profile data:', data.message);
            }
        } else {
            console.error('Failed to load user profile data:', response.statusText);
        }
            return;
        }

        const response = await fetch('/api/auth/profile', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            // Update profile section with user data
            document.getElementById('profile-name').textContent = data.data.name || 'Shri Hindutakht';
            document.getElementById('profile-member-id').textContent = `ID: ${data.data.member_id || 'N/A'}`;
            
            // Update profile picture
            const profileAvatar = document.getElementById('profile-avatar');
            const profileDefaultAvatar = document.getElementById('profile-default-avatar');
            
            if (data.data.photo && data.data.full_photo_url) {
                profileAvatar.src = data.data.full_photo_url;
                profileAvatar.style.display = 'block';
                profileDefaultAvatar.style.display = 'none';
            } else {
                // Show initials in default avatar
                const initials = data.data.name ? data.data.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
                profileDefaultAvatar.innerHTML = `<span class="text-xl font-bold">${initials}</span>`;
                profileAvatar.style.display = 'none';
                profileDefaultAvatar.style.display = 'flex';
            }
        }
    } catch (error) {
        console.error('Failed to load profile data:', error);
    }
}

// Load user profile data for edit profile section
async function loadEditProfileData() {
    console.log('loadEditProfileData called');
    
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            console.log('No auth token found, redirecting to login');
            window.location.href = '/login';
            return;
        }

        const response = await fetch('/api/auth/profile', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        console.log('Profile data received:', data);
        
        if (data.success) {
            // Populate form with user data
            document.getElementById('edit-name').value = data.data.name || '';
            document.getElementById('edit-email').value = data.data.email || '';
            document.getElementById('edit-phone').value = data.data.phone || '';
            document.getElementById('edit-address').value = data.data.address || '';
            document.getElementById('edit-date_of_birth').value = data.data.date_of_birth || '';

            // Member information (read-only)
            document.getElementById('edit-member-id').textContent = data.data.member_id || '-';
            document.getElementById('edit-join-date').textContent = data.data.join_date ? 
                new Date(data.data.join_date).toLocaleDateString() : '-';
            
            // Profile picture
            const preview = document.getElementById('edit-profile-picture-preview');
            const placeholder = document.getElementById('edit-profile-picture-placeholder');
            
            if (data.data.photo && data.data.full_photo_url) {
                preview.src = data.data.full_photo_url;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                document.getElementById('edit-remove-photo-btn').style.display = 'block';
            } else {
                const initials = data.data.name ? data.data.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
                placeholder.innerHTML = `<span class="text-2xl font-bold">${initials}</span>`;
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                document.getElementById('edit-remove-photo-btn').style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Failed to load edit profile data:', error);
        showEditProfileError('Failed to load profile data. Please try again.');
    }
}

// Initialize edit profile event listeners
function initializeEditProfileListeners() {
    // Profile picture upload
    const profilePictureUpload = document.querySelector('#edit-profile-section .profile-picture-upload');
    const profilePictureInput = document.getElementById('edit-profile-picture-input');
    
    if (profilePictureUpload && profilePictureInput) {
        profilePictureUpload.addEventListener('click', () => {
            profilePictureInput.click();
        });
        
        profilePictureInput.addEventListener('change', handleEditProfilePictureChange);
    }
    
    // Remove photo button
    const removePhotoBtn = document.getElementById('edit-remove-photo-btn');
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', removeEditProfilePicture);
    }
    
    // Save profile button
    const saveProfileBtn = document.getElementById('edit-save-profile-btn');
    if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', saveEditProfile);
    }
    
    // Change password button
    const changePasswordBtn = document.getElementById('edit-change-password-btn');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', changeEditPassword);
    }
}

// Handle profile picture change for edit profile
function handleEditProfilePictureChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showEditProfileError('Please select a valid image file.');
        return;
    }
    
    // Validate file size (10MB max)
    if (file.size > 10 * 1024 * 1024) {
        showEditProfileError('Image size must be less than 10MB.');
        return;
    }
    
    // Preview the image
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('edit-profile-picture-preview');
        const placeholder = document.getElementById('edit-profile-picture-placeholder');
        
        preview.src = e.target.result;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        document.getElementById('edit-remove-photo-btn').style.display = 'block';
        
        showEditProfileSuccess(`Image selected (${(file.size / 1024 / 1024).toFixed(2)} MB). It will be compressed during upload.`);
    };
    reader.readAsDataURL(file);
}

// Remove profile picture for edit profile
async function removeEditProfilePicture() {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/auth/remove-photo', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const preview = document.getElementById('edit-profile-picture-preview');
            const placeholder = document.getElementById('edit-profile-picture-placeholder');
            
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            const initials = document.getElementById('edit-name').value ? 
                document.getElementById('edit-name').value.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
            placeholder.innerHTML = `<span class="text-2xl font-bold">${initials}</span>`;
            document.getElementById('edit-remove-photo-btn').style.display = 'none';
            document.getElementById('edit-profile-picture-input').value = '';
            
            showEditProfileSuccess('Profile picture removed successfully!');
        } else {
            throw new Error(data.message || 'Failed to remove photo');
        }
    } catch (error) {
        console.error('Failed to remove photo:', error);
        showEditProfileError('Failed to remove photo. Please try again.');
    }
}

// Save profile for edit profile
async function saveEditProfile() {
    const saveBtn = document.getElementById('edit-save-profile-btn');
    const saveText = document.getElementById('edit-save-text');
    const saveLoading = document.getElementById('edit-save-loading');
    
    // Show loading state
    saveBtn.disabled = true;
    saveText.classList.add('hidden');
    saveLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const formData = new FormData();
        
        // Add form data
        formData.append('name', document.getElementById('edit-name').value);
        formData.append('email', document.getElementById('edit-email').value);
        formData.append('phone', document.getElementById('edit-phone').value);
        formData.append('address', document.getElementById('edit-address').value);
        formData.append('date_of_birth', document.getElementById('edit-date_of_birth').value);
        
        // Add profile picture if changed
        const photoInput = document.getElementById('edit-profile-picture-input');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }
        
        const response = await fetch('/api/auth/update-profile', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            showEditProfileSuccess('Profile updated successfully!');
            // Reload profile data
            loadEditProfileData();
            // Also update the profile section
            loadUserProfileData();
        } else {
            throw new Error(data.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Failed to save profile:', error);
        showEditProfileError('Failed to save profile. Please try again.');
    } finally {
        // Hide loading state
        saveBtn.disabled = false;
        saveText.classList.remove('hidden');
        saveLoading.classList.add('hidden');
    }
}

// Change password for edit profile
async function changeEditPassword() {
    const currentPassword = document.getElementById('edit-current_password').value;
    const newPassword = document.getElementById('edit-new_password').value;
    const confirmPassword = document.getElementById('edit-new_password_confirmation').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showEditProfileError('Please fill in all password fields.');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showEditProfileError('New passwords do not match.');
        return;
    }
    
    if (newPassword.length < 6) {
        showEditProfileError('New password must be at least 6 characters long.');
        return;
    }
    
    const passwordBtn = document.getElementById('edit-change-password-btn');
    const passwordText = document.getElementById('edit-password-text');
    const passwordLoading = document.getElementById('edit-password-loading');
    
    // Show loading state
    passwordBtn.disabled = true;
    passwordText.classList.add('hidden');
    passwordLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/auth/change-password', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Clear password fields
            document.getElementById('edit-current_password').value = '';
            document.getElementById('edit-new_password').value = '';
            document.getElementById('edit-new_password_confirmation').value = '';
            
            showEditProfileSuccess('Password changed successfully!');
        } else {
            throw new Error(data.message || 'Failed to change password');
        }
    } catch (error) {
        console.error('Failed to change password:', error);
        showEditProfileError('Failed to change password. Please try again.');
    } finally {
        // Hide loading state
        passwordBtn.disabled = false;
        passwordText.classList.remove('hidden');
        passwordLoading.classList.add('hidden');
    }
}

// Show error message for edit profile
function showEditProfileError(message) {
    const errorDiv = document.getElementById('edit-profile-error-message');
    const errorText = document.getElementById('edit-profile-error-text');
    const successDiv = document.getElementById('edit-profile-success-message');
    
    successDiv.classList.add('hidden');
    errorText.textContent = message;
    errorDiv.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorDiv.classList.add('hidden');
    }, 5000);
}

// Show success message for edit profile
function showEditProfileSuccess(message) {
    const successDiv = document.getElementById('edit-profile-success-message');
    const successText = document.getElementById('edit-profile-success-text');
    const errorDiv = document.getElementById('edit-profile-error-message');
    
    errorDiv.classList.add('hidden');
    successText.textContent = message;
    successDiv.classList.remove('hidden');
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 3000);
}

// Save profile for edit profile
async function saveEditProfile() {
    const saveBtn = document.getElementById('edit-save-profile-btn');
    const saveText = document.getElementById('edit-save-text');
    const saveLoading = document.getElementById('edit-save-loading');
    
    // Show loading state
    saveBtn.disabled = true;
    saveText.classList.add('hidden');
    saveLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const formData = new FormData();
        
        // Add form data
        formData.append('name', document.getElementById('edit-name').value);
        formData.append('email', document.getElementById('edit-email').value);
        formData.append('phone', document.getElementById('edit-phone').value);
        formData.append('address', document.getElementById('edit-address').value);
        formData.append('date_of_birth', document.getElementById('edit-date_of_birth').value);
        
        // Add profile picture if changed
        const photoInput = document.getElementById('edit-profile-picture-input');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }
        
        const response = await fetch('/api/auth/update-profile', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            showEditProfileSuccess('Profile updated successfully!');
            // Reload profile data
            loadEditProfileData();
            // Also update the profile section
            loadUserProfileData();
        } else {
            throw new Error(data.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Failed to save profile:', error);
        showEditProfileError('Failed to save profile. Please try again.');
    } finally {
        // Hide loading state
        saveBtn.disabled = false;
        saveText.classList.remove('hidden');
        saveLoading.classList.add('hidden');
    }
}

// Change password for edit profile
async function changeEditPassword() {
    const currentPassword = document.getElementById('edit-current_password').value;
    const newPassword = document.getElementById('edit-new_password').value;
    const confirmPassword = document.getElementById('edit-new_password_confirmation').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showEditProfileError('Please fill in all password fields.');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showEditProfileError('New passwords do not match.');
        return;
    }
    
    if (newPassword.length < 6) {
        showEditProfileError('New password must be at least 6 characters long.');
        return;
    }
    
    const passwordBtn = document.getElementById('edit-change-password-btn');
    const passwordText = document.getElementById('edit-password-text');
    const passwordLoading = document.getElementById('edit-password-loading');
    
    // Show loading state
    passwordBtn.disabled = true;
    passwordText.classList.add('hidden');
    passwordLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/auth/change-password', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Clear password fields
            document.getElementById('edit-current_password').value = '';
            document.getElementById('edit-new_password').value = '';
            document.getElementById('edit-new_password_confirmation').value = '';
            
            showEditProfileSuccess('Password changed successfully!');
        } else {
            throw new Error(data.message || 'Failed to change password');
        }
    } catch (error) {
        console.error('Failed to change password:', error);
        showEditProfileError('Failed to change password. Please try again.');
    } finally {
        // Hide loading state
        passwordBtn.disabled = false;
        passwordText.classList.remove('hidden');
        passwordLoading.classList.add('hidden');
    }
}

// Show error message for edit profile
function showEditProfileError(message) {
    const errorDiv = document.getElementById('edit-profile-error-message');
    const errorText = document.getElementById('edit-profile-error-text');
    const successDiv = document.getElementById('edit-profile-success-message');
    
    successDiv.classList.add('hidden');
    errorText.textContent = message;
    errorDiv.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorDiv.classList.add('hidden');
    }, 5000);
}

// Show success message for edit profile
function showEditProfileSuccess(message) {
    const successDiv = document.getElementById('edit-profile-success-message');
    const successText = document.getElementById('edit-profile-success-text');
    const errorDiv = document.getElementById('edit-profile-error-message');
    
    errorDiv.classList.add('hidden');
    successText.textContent = message;
    successDiv.classList.remove('hidden');
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 3000);
}

// Update the DOMContentLoaded event listener to initialize edit profile listeners
document.addEventListener('DOMContentLoaded', function() {
    // Check authentication
    let isAuthenticated = false;
    
    // Try to use AuthManager from app.js
    if (typeof AuthManager !== 'undefined' && AuthManager.isAuthenticated) {
        isAuthenticated = AuthManager.isAuthenticated();
    } else {
        // Fallback: manually check token
        isAuthenticated = !!localStorage.getItem('auth_token');
    }
    
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    
    // Load initial posts
    loadPosts();
    
    // Initialize like buttons with event delegation
    initializeLikeButtons();
    
    // Refresh button event listener is handled in the second DOMContentLoaded handler
    
    // Add profile link event listener
    const profileLink = document.getElementById('profile-link');
    if (profileLink) {
        // Remove any existing event listeners
        const newProfileLink = profileLink.cloneNode(true);
        profileLink.parentNode.replaceChild(newProfileLink, profileLink);
        
        newProfileLink.addEventListener('click', function(e) {
            e.preventDefault();
            showSection('profile');
        }, true); // Use capture phase
    }
    
    // Add logout event listener
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        // Remove any existing event listeners
        const newLogoutBtn = logoutBtn.cloneNode(true);
        logoutBtn.parentNode.replaceChild(newLogoutBtn, logoutBtn);
        
        newLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Use the AuthManager from app.js
            if (typeof AuthManager !== 'undefined' && AuthManager.removeToken) {
                AuthManager.removeToken();
            } else {
                // Fallback: manually remove token
                localStorage.removeItem('auth_token');
            }
            window.location.href = '/login';
        }, true); // Use capture phase
    }
    
    // Initialize edit profile listeners
    initializeEditProfileListeners();
});

// Load donation information when the donation section is shown
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="comment-input-"]').forEach(input => {
        const postId = input.dataset.postId;
        
        // Remove and re-add to prevent duplicates
        const newInput = input.cloneNode(true);
        newInput.dataset.postId = postId;
        input.parentNode.replaceChild(newInput, input);
        
        newInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                handleCommentSubmit(postId);
            }
        }, true);
    });
    
    console.log('✅ Post event listeners initialized');
});

// Handle comment submission with complete duplicate prevention
const submissionTracker = new Map();

async function handleCommentSubmit(postId) {
    const commentKey = `comment_${postId}`;
    
    // Check if already processing
    if (submissionTracker.has(commentKey)) {
        console.log('⚠️ Comment submission already in progress for post', postId);
        return;
    }
    
    // Get input and validate
    const input = document.getElementById(`comment-input-${postId}`);
    const comment = input.value.trim();
    
    if (!comment) {
        return;
    }
    
    // Set processing flag
    submissionTracker.set(commentKey, true);
    console.log('✅ Starting comment submission for post', postId);
    
    // Get button and disable during submission
    const button = input.closest('.flex').querySelector('.add-comment-btn');
    let originalButtonText = 'Post';
    
    if (button) {
        originalButtonText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Posting...</span>';
    }
    
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch(`/api/posts/${postId}/comment`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ comment })
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Clear the input
                input.value = '';
                
                // Reload comments
                await loadComments(postId);
                
                // Update comment count in the post
                const commentBtn = document.querySelector(`#comments-${postId}`).previousElementSibling.querySelector('.comment-btn');
                if (commentBtn) {
                    const countSpan = commentBtn.querySelector('span');
                    if (countSpan) {
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;
                    }
                }
            }
        } else {
            console.error('Comment request failed with status', response.status);
            showMessage('Error adding comment. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error adding comment:', error);
        showMessage('Error adding comment. Please try again.', 'error');
    } finally {
        // Clear processing flag
        submissionTracker.delete(commentKey);
        console.log('🔄 Completed comment submission for post', postId);
        
        // Re-enable button and restore original text
        if (button) {
            button.disabled = false;
            button.innerHTML = originalButtonText;
        }
    }
}

// Toggle comments visibility
function toggleComments(postId) {
    console.log('Toggle comments called for post:', postId);
    const commentsSection = document.getElementById(`comments-${postId}`);
    console.log('Comments section element:', commentsSection);
    if (commentsSection) {
        console.log('Comments section is hidden:', commentsSection.classList.contains('hidden'));
        if (commentsSection.classList.contains('hidden')) {
            commentsSection.classList.remove('hidden');
            console.log('Loading comments for post:', postId);
            loadComments(postId);
        } else {
            commentsSection.classList.add('hidden');
            console.log('Hiding comments for post:', postId);
        }
    } else {
        console.error('Comments section not found for post:', postId);
    }
}

// Load comments for a post
async function loadComments(postId) {
    console.log('Loading comments for post:', postId);
    try {
        const token = localStorage.getItem('auth_token');
        console.log('Auth token:', token);
        
        const response = await fetch(`/api/posts/${postId}/comments`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });
        
        console.log('Comments API response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('Comments data:', data);
            if (data.success) {
                const commentsList = document.getElementById(`comments-list-${postId}`);
                console.log('Comments list element:', commentsList);
                if (commentsList) {
                    commentsList.innerHTML = '';
                    
                    if (data.data.data && data.data.data.length > 0) {
                        console.log('Rendering', data.data.data.length, 'comments');
                        data.data.data.forEach(comment => {
                            const commentElement = createCommentElement(comment);
                            commentsList.appendChild(commentElement);
                        });
                    } else {
                        console.log('No comments found');
                        commentsList.innerHTML = '<p class="text-gray-500 text-sm py-2">No comments yet. Be the first to comment!</p>';
                    }
                } else {
                    console.error('Comments list element not found for post:', postId);
                }
            } else {
                console.error('API returned error:', data.message);
            }
        } else {
            console.error('API request failed with status:', response.status);
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        const commentsList = document.getElementById(`comments-list-${postId}`);
        if (commentsList) {
            commentsList.innerHTML = '<p class="text-red-500 text-sm py-2">Error loading comments. Please try again.</p>';
        }
    }
}

// Create comment element
function createCommentElement(comment) {
    console.log('Creating comment element for:', comment);
    const commentDiv = document.createElement('div');
    commentDiv.className = 'flex space-x-2 mb-3';
    
    const memberPhoto = comment.member?.photo ? 
        `/storage/${comment.member.photo}` : 
        generateAvatarUrl(comment.member?.name || 'Shri Hindutakht');
    
    commentDiv.innerHTML = `
        <img src="${memberPhoto}" class="w-8 h-8 rounded-full object-cover" alt="${comment.member?.name || 'User'} avatar">
        <div class="flex-1">
            <div class="bg-gray-100 rounded-lg px-3 py-2">
                <p class="font-medium text-sm text-gray-900">${comment.member?.name || 'Shri Hindutakht'}</p>
                <p class="text-gray-700 text-sm">${comment.comment}</p>
            </div>
            <p class="text-xs text-gray-500 mt-1">${formatTimeAgo(comment.created_at)}</p>
        </div>
    `;
    
    console.log('Created comment element:', commentDiv);
    return commentDiv;
}

// Share post
async function sharePost(postId) {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch(`/api/posts/${postId}/share`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const shareCount = document.getElementById(`share-count-${postId}`);
                shareCount.textContent = data.data.shares_count;
                
                // Copy link to clipboard
                if (navigator.share) {
                    navigator.share({
                        title: 'Check out this post from Shree Hindutakht',
                        url: data.data.share_link
                    });
                } else {
                    navigator.clipboard.writeText(data.data.share_link);
                    showMessage('Link copied to clipboard!', 'success');
                }
            }
        }
    } catch (error) {
        console.error('Error sharing post:', error);
        showMessage('Error sharing post. Please try again.', 'error');
    }
}

// Open image gallery
function openImageGallery(images, startIndex = 0) {
    let currentIndex = startIndex;
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50';
    modal.onclick = (e) => {
        if (e.target === modal) modal.remove();
    };
    
    modal.innerHTML = `
        <div class="max-w-4xl max-h-full p-4 relative">
            <!-- Close button -->
            <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 text-white text-2xl z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75">
                ×
            </button>
            
            <!-- Navigation arrows -->
            ${images.length > 1 ? `
                <button id="prev-btn" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-75 z-10">
                    ‹
                </button>
                <button id="next-btn" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-75 z-10">
                    ›
                </button>
            ` : ''}
            
            <!-- Image container -->
            <div id="image-container" class="flex items-center justify-center h-full">
                <img id="gallery-image" src="${images[currentIndex]}" class="max-w-full max-h-full object-contain rounded-lg" alt="Gallery image">
            </div>
            
            <!-- Image counter -->
            ${images.length > 1 ? `
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full text-sm">
                    <span id="current-index">${currentIndex + 1}</span> / ${images.length}
                </div>
            ` : ''}
            
            <!-- Thumbnail strip -->
            ${images.length > 1 ? `
                <div class="absolute bottom-16 left-1/2 transform -translate-x-1/2 flex space-x-2 max-w-full overflow-x-auto">
                    ${images.map((img, idx) => `
                        <img src="${img}" class="w-16 h-16 object-cover rounded cursor-pointer border-2 ${idx === currentIndex ? 'border-white' : 'border-transparent opacity-70'}" onclick="showImage(${idx})">
                    `).join('')}
                </div>
            ` : ''}
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Gallery functions
    window.showImage = function(index) {
        currentIndex = index;
        const galleryImage = modal.querySelector('#gallery-image');
        const currentIndexSpan = modal.querySelector('#current-index');
        const thumbnails = modal.querySelectorAll('.w-16');
        
        galleryImage.src = images[currentIndex];
        if (currentIndexSpan) currentIndexSpan.textContent = currentIndex + 1;
        
        // Update thumbnail borders
        thumbnails.forEach((thumb, idx) => {
            if (idx === currentIndex) {
                thumb.classList.remove('border-transparent', 'opacity-70');
                thumb.classList.add('border-white');
            } else {
                thumb.classList.add('border-transparent', 'opacity-70');
                thumb.classList.remove('border-white');
            }
        });
    };
    
    // Navigation event listeners
    const prevBtn = modal.querySelector('#prev-btn');
    const nextBtn = modal.querySelector('#next-btn');
    
    if (prevBtn) {
        prevBtn.onclick = () => {
            currentIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1;
            showImage(currentIndex);
        };
    }
    
    if (nextBtn) {
        nextBtn.onclick = () => {
            currentIndex = currentIndex < images.length - 1 ? currentIndex + 1 : 0;
            showImage(currentIndex);
        };
    }
    
    // Keyboard navigation
    const handleKeyPress = (e) => {
        if (e.key === 'ArrowLeft' && currentIndex > 0) {
            currentIndex--;
            showImage(currentIndex);
        } else if (e.key === 'ArrowRight' && currentIndex < images.length - 1) {
            currentIndex++;
            showImage(currentIndex);
        } else if (e.key === 'Escape') {
            modal.remove();
            document.removeEventListener('keydown', handleKeyPress);
        }
    };
    
    document.addEventListener('keydown', handleKeyPress);
    
    // Clean up event listener when modal is removed
    modal.addEventListener('DOMNodeRemoved', () => {
        document.removeEventListener('keydown', handleKeyPress);
    });
}

// Show message function
function showMessage(message, type = 'info') {
    // Remove any existing messages
    const existingMessage = document.getElementById('refresh-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.id = 'refresh-message';
    messageDiv.className = `fixed top-20 right-4 z-50 px-4 py-2 rounded-lg text-white text-sm font-medium shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    messageDiv.textContent = message;
    
    // Add to document
    document.body.appendChild(messageDiv);
    
    // Remove after 3 seconds
    setTimeout(() => {
        messageDiv.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection