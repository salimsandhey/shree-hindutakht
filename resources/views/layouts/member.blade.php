<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f97316">
    
    <title>@yield('title', 'Shree Hindutakht')</title>
    
    <!-- Tailwind CSS with custom configuration -->
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifestPath = public_path('build/manifest.json');
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $version = file_exists($manifestPath) ? filemtime($manifestPath) : time();
            $appCss = asset('build/' . $manifest['resources/css/app.css']['file']) . "?v=" . $version;
            $appJs = asset('build/' . $manifest['resources/js/app.js']['file']) . "?v=" . $version;
        @endphp
        <link rel="stylesheet" href="{{ $appCss }}">
        <script type="module" src="{{ $appJs }}"></script>
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('logo3.png') }}">
    
    <!-- Custom Styles -->
    <style>
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #b93a20;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a02f1a;
        }
        
        /* Smooth scrolling for all elements */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading indicator */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #b93a20;
            transform-origin: 0%;
            transform: scaleX(0);
            z-index: 9999;
            transition: transform 0.3s ease;
        }
        
        /* Touch targets for mobile */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Prevent pull-to-refresh on mobile */
        body {
            overscroll-behavior-y: contain;
        }
        
        /* Member header styles */
        .member-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 60; /* Increased z-index to ensure it's above public navigation */
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        /* Member bottom nav styles */
        .member-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 60; /* Increased z-index to ensure it's above public navigation */
            background-color: white;
            border-top: 1px solid #e5e7eb;
            padding: 0.5rem;
        }
        
        /* Content padding to account for fixed header and nav */
        .with-member-header {
            padding-top: 70px;
        }
        
        .with-member-bottom-nav {
            padding-bottom: 60px;
        }
        
        /* Active nav item */
        .nav-item.active {
            color: #b93a20; /* primary color */
        }
        
        .nav-item:hover {
            color: #b93a20; /* primary color */
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Profile dropdown styles */
        .profile-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: 280px;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            display: none;
        }
        
        .profile-dropdown.show {
            display: block;
        }
        
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e5e7eb;
        }
        
        .avatar-lg {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    
    @yield('head')
</head>
<body class="bg-gray-50 min-h-screen antialiased">
    <!-- Loading indicator -->
    <div class="loading-indicator" id="loadingIndicator"></div>
    
    <!-- Member Header -->
    <div class="member-header bg-white text-gray-800 p-4 shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- Logo -->
                <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht Logo" class="h-8 w-auto">
            </div>
            
            <!-- Profile Info and Dropdown -->
            <div class="relative">
                <button id="profile-toggle" class="flex items-center space-x-2 focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900" id="header-name">Loading...</p>
                        <p class="text-xs text-gray-500" id="header-member-id">Member ID: ...</p>
                    </div>
                    <img id="header-avatar" class="avatar" src="" alt="Profile Picture">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Profile Dropdown -->
                <div id="profile-dropdown" class="profile-dropdown">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <img id="dropdown-avatar" class="avatar-lg" src="" alt="Profile Picture">
                            <div>
                                <h3 class="font-medium text-gray-800" id="dropdown-name">Loading...</h3>
                                <p class="text-gray-600 text-sm" id="dropdown-member-id">ID: ...</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="/member/edit-profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profile
                        </a>
                        <a href="/member/id-card" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0v2m0 0h4"></path>
                            </svg>
                            View ID Card
                        </a>
                    </div>
                    <div class="border-t border-gray-200"></div>
                    <div class="py-1">
                        <button id="logout-btn" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="with-member-header with-member-bottom-nav">
        @yield('content')
    </main>
    
    <!-- Member Bottom Navigation -->
    <div class="member-bottom-nav">
        <div class="flex justify-around">
            <a href="{{ route('member.dashboard') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs mt-1">Home</span>
            </a>
            <a href="{{ route('member.posts') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="text-xs mt-1">Posts</span>
            </a>
            <a href="{{ route('member.events') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs mt-1">Events</span>
            </a>
            <a href="{{ route('news.index') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="text-xs mt-1">News</span>
            </a>
            <a href="{{ route('member.donations') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs mt-1">Donations</span>
            </a>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Loading indicator
        window.addEventListener('beforeunload', function() {
            document.getElementById('loadingIndicator').style.transform = 'scaleX(1)';
        });
        
        // Hide loading indicator when page is loaded
        window.addEventListener('load', function() {
            document.getElementById('loadingIndicator').style.transform = 'scaleX(0)';
        });
        
        // Initialize profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle profile dropdown
            const profileToggle = document.getElementById('profile-toggle');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }
            
            // Load member profile data
            loadMemberProfile();
            
            // Logout functionality
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to logout?')) {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('token');
                        localStorage.removeItem('member');
                        window.location.href = '/login';
                    }
                });
            }
        });
        
        // Load member profile data
        async function loadMemberProfile() {
            try {
                const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch('/api/auth/profile', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        localStorage.removeItem('token');
                        localStorage.removeItem('member');
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error('Failed to load profile');
                }

                const data = await response.json();
                const member = data.data;

                // Update header elements
                document.getElementById('header-name').textContent = member.name || 'Member';
                document.getElementById('header-member-id').textContent = `Member ID: ${member.member_id || '...'}`;
                
                // Update dropdown elements
                document.getElementById('dropdown-name').textContent = member.name || 'Member';
                document.getElementById('dropdown-member-id').textContent = `ID: ${member.member_id || '...'}`;
                
                // Set avatar or use default
                const avatarUrl = member.photo ? `/storage/${member.photo}` : generateDefaultAvatar(member.name || 'M');
                document.getElementById('header-avatar').src = avatarUrl;
                document.getElementById('dropdown-avatar').src = avatarUrl;
                
            } catch (error) {
                console.error('Error loading member profile:', error);
            }
        }
        
        // Generate default avatar
        function generateDefaultAvatar(name) {
            const initials = name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
            const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57'];
            const color = colors[name.length % colors.length];
            
            return `data:image/svg+xml,${encodeURIComponent(`
                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" fill="${color}"/>
                    <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="16" font-weight="bold">${initials}</text>
                </svg>
            `)}`;
        }
        
        // Set active tab based on current URL
        function setActiveTab() {
            // Get all nav items
            const navItems = document.querySelectorAll('.member-bottom-nav .nav-item');
            const currentPath = window.location.pathname;
            
            // Remove active class from all items
            navItems.forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to the current page's nav item
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href) {
                    // Create a URL object to properly parse the href
                    try {
                        const hrefUrl = new URL(href, window.location.origin);
                        const hrefPath = hrefUrl.pathname;
                        
                        // Check for exact matches or if current path starts with href path
                        if (currentPath === hrefPath || currentPath.startsWith(hrefPath + '/')) {
                            item.classList.add('active');
                        }
                    } catch (e) {
                        // Fallback for relative URLs
                        if (currentPath === href || currentPath.startsWith(href + '/')) {
                            item.classList.add('active');
                        }
                    }
                }
            });
            
            // Special case for dashboard (exact match)
            if (currentPath === '/member' || currentPath === '/member/dashboard' || currentPath === '/member/') {
                const homeItem = document.querySelector('.member-bottom-nav a[href*="dashboard"], .member-bottom-nav a[href="/"]');
                if (homeItem) {
                    homeItem.classList.add('active');
                }
            }
        }
        
        // Initialize active tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            setActiveTab();
        });
        
        // Service worker disabled as per requirements
    </script>
    
    @yield('scripts')
</body>
</html>