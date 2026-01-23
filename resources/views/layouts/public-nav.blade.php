<div id="public-navigation">
<!-- Fixed Header for all devices -->
<div class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50 transition-all duration-300 mobile-header">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <!-- Logo -->
            <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht Logo" class="h-8 w-auto lazy" data-src="{{ asset('logo3.png') }}" loading="lazy">
            <!-- <span class="text-xl font-bold text-gray-900">Shree Hindutakht</span> -->
        </div>
        
        <!-- Empty div for spacing (matching admin layout) -->
        <div></div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 transition-all duration-300 mobile-nav nav-transition" style="z-index: 9998 !important;">
    <div class="flex justify-around">
        <a href="{{ route('landing') }}" class="nav-item {{ request()->routeIs('landing') ? 'active' : '' }} flex flex-col items-center justify-center py-3 px-3 transition-all duration-200 ease-in-out transform hover:scale-105 nav-transition touch-target">
            <svg class="w-6 h-6 {{ request()->routeIs('landing') ? 'text-primary' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path>
            </svg>
            <span class="text-xs mt-1 {{ request()->routeIs('landing') ? 'text-primary font-medium' : 'text-gray-500' }}">Home</span>
        </a>
        <a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'active' : '' }} flex flex-col items-center justify-center py-3 px-3 transition-all duration-200 ease-in-out transform hover:scale-105 nav-transition touch-target">
            <svg class="w-6 h-6 {{ request()->routeIs('about') ? 'text-primary' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-xs mt-1 {{ request()->routeIs('about') ? 'text-primary font-medium' : 'text-gray-500' }}">About</span>
        </a>
        <a href="{{ route('team') }}" class="nav-item {{ request()->routeIs('team') ? 'active' : '' }} flex flex-col items-center justify-center py-3 px-3 transition-all duration-200 ease-in-out transform hover:scale-105 nav-transition touch-target">
            <svg class="w-6 h-6 {{ request()->routeIs('team') ? 'text-primary' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="text-xs mt-1 {{ request()->routeIs('team') ? 'text-primary font-medium' : 'text-gray-500' }}">Team</span>
        </a>
        <a href="{{ route('mission-vision') }}" class="nav-item {{ request()->routeIs('mission-vision') ? 'active' : '' }} flex flex-col items-center justify-center py-3 px-3 transition-all duration-200 ease-in-out transform hover:scale-105 nav-transition touch-target">
            <svg class="w-6 h-6 {{ request()->routeIs('mission-vision') ? 'text-primary' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <span class="text-xs mt-1 {{ request()->routeIs('mission-vision') ? 'text-primary font-medium' : 'text-gray-500' }}">Mission</span>
        </a>
        <a href="{{ route('news.index') }}" class="nav-item {{ request()->routeIs('news.*') ? 'active' : '' }} flex flex-col items-center justify-center py-3 px-3 transition-all duration-200 ease-in-out transform hover:scale-105 nav-transition touch-target">
            <svg class="w-6 h-6 {{ request()->routeIs('news.*') ? 'text-primary' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <span class="text-xs mt-1 {{ request()->routeIs('news.*') ? 'text-primary font-medium' : 'text-gray-500' }}">News</span>
        </a>
    </div>
</div>

<style>
    .nav-item {
        color: #6b7280; /* gray-500 */
        position: relative;
    }
    
    .nav-item.active {
        color: #b93a20; /* primary color */
    }
    
    .nav-item:hover {
        color: #b93a20; /* primary color */
    }
    
    /* Add a subtle active indicator */
    .nav-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background-color: #b93a20;
        border-radius: 50%;
    }
    
    /* Mobile app-like navigation transition */
    .mobile-nav {
        transition: transform 0.3s ease-in-out;
        /* Ensure navigation is always visible */
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
    }
    
    /* Ensure navigation is always on top */
    .mobile-header, .mobile-nav {
        z-index: 9999 !important;
    }
    
    /* Fix for sections that might hide the navigation */
    .hero-section, .bg-gradient-to-r, .relative {
        z-index: auto !important;
    }
    
    /* Prevent navigation from being hidden by other elements */
    .mobile-header {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
    }
</style>
</div>