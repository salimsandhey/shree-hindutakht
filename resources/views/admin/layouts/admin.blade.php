<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Shree Hindutakht')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @if(app()->environment('local'))
        @vite('resources/css/app.css')
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $appCss = asset('build/' . $manifest['resources/css/app.css']['file']);
        @endphp
        <link rel="stylesheet" href="{{ $appCss }}">
    @endif
    
    <style>
        /* Fixed header and navigation styles */
        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .fixed-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background-color: white;
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
        }
        
        /* Override the default bottom-nav styles for admin panel */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: none;
            transform: none;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 0.5rem;
            z-index: 40;
        }
        
        /* Content padding to account for fixed header and nav */
        .with-fixed-header {
            padding-top: 70px; /* Reduced padding since header is simpler */
        }
        
        .with-fixed-bottom-nav {
            padding-bottom: 60px;
        }
        
        /* Logo styles */
        .admin-logo {
            height: 32px;
            width: auto;
        }
        
        /* Button styles - using primary color from app.css */
        .btn-primary {
            background-color: #b93a20; /* primary color */
            color: white;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #a02f1a; /* darker primary color */
        }
        
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        
        /* Active nav item - using primary color */
        .nav-item.active {
            color: #b93a20; /* primary color */
            background-color: #fff7ed;
        }
        
        .nav-item:hover {
            color: #b93a20; /* primary color */
        }
        
        /* Card styles */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            padding: 1rem;
        }
        
        /* Input styles - using primary color */
        .input-field {
            width: 100%;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        
        .input-field:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #b93a20; /* primary color */
            box-shadow: 0 0 0 3px rgba(185, 58, 32, 0.1); /* primary color with opacity */
        }
        
        /* Primary color utility classes */
        .text-primary {
            color: #b93a20;
        }
        
        .bg-primary {
            background-color: #b93a20;
        }
        
        .border-primary {
            border-color: #b93a20;
        }
        
        /* Profile picture styles */
        .profile-picture-upload {
            position: relative;
            display: inline-block;
        }
        
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e5e7eb;
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
        
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 49;
            display: none;
        }
        
        .dropdown-backdrop.show {
            display: block;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen bg-gray-50">
        <!-- Fixed Header -->
        <div class="fixed-header bg-white text-gray-800 p-4 shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Logo -->
                    <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht Logo" class="admin-logo">
                </div>
                
                <!-- Profile Section -->
                <div class="relative">
                    <button id="profile-toggle" class="flex items-center space-x-2 focus:outline-none">
                        <div class="profile-picture-upload">
                            <img id="admin-header-avatar" class="avatar" src="" alt="Admin Profile Picture">
                        </div>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div id="profile-dropdown" class="profile-dropdown">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="profile-picture-upload">
                                    <img id="dropdown-avatar" class="avatar" src="" alt="Admin Profile Picture">
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800" id="dropdown-name">Admin</h3>
                                    <p class="text-gray-600 text-sm" id="dropdown-role">Administrator</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <button onclick="logout()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </div>
                    </div>
                    
                    <!-- Dropdown Backdrop -->
                    <div id="dropdown-backdrop" class="dropdown-backdrop"></div>
                </div>
            </div>
        </div>

        <!-- Content Container with padding for fixed header -->
        <div id="admin-content-container" class="with-fixed-header with-fixed-bottom-nav">
            @yield('content')
        </div>

        <!-- Fixed Bottom Navigation -->
        <div class="fixed-bottom-nav bottom-nav">
            <div class="flex justify-around">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path>
                    </svg>
                    <span class="text-xs mt-1">Dashboard</span>
                </a>
                <a href="{{ route('admin.members') }}" class="nav-item {{ request()->routeIs('admin.members') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Members</span>
                </a>
                <a href="{{ route('admin.posts') }}" class="nav-item {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span class="text-xs mt-1">Posts</span>
                </a>
                <a href="{{ route('admin.news') }}" class="nav-item {{ request()->routeIs('admin.news') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span class="text-xs mt-1">News</span>
                </a>
                <a href="{{ route('admin.events') }}" class="nav-item {{ request()->routeIs('admin.events') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Events</span>
                </a>
                <a href="{{ route('admin.donations') }}" class="nav-item {{ request()->routeIs('admin.donations') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Donations</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @if(app()->environment('local'))
        @vite('resources/js/app.js')
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $appJs = asset('build/' . $manifest['resources/js/app.js']['file']);
        @endphp
        <script type="module" src="{{ $appJs }}"></script>
    @endif
    
    <script>
        // Admin Auth token management
        class AdminAuthManager {
            static getToken() {
                // Check multiple storage locations
                let token = localStorage.getItem('admin_auth_token');
                if (!token) {
                    token = sessionStorage.getItem('admin_auth_token');
                }
                if (!token) {
                    // Try to get from cookies
                    const cookies = document.cookie.split(';');
                    for (let cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');
                        if (name === 'admin_auth_token') {
                            token = value;
                            break;
                        }
                    }
                }
                return token;
            }

            static setToken(token) {
                // Store in multiple locations for redundancy
                localStorage.setItem('admin_auth_token', token);
                sessionStorage.setItem('admin_auth_token', token);
                
                // Also set in a cookie for web routes with proper settings
                document.cookie = `admin_auth_token=${token}; path=/; SameSite=Lax`;
            }

            static removeToken() {
                localStorage.removeItem('admin_auth_token');
                sessionStorage.removeItem('admin_auth_token');
                // Also remove cookie
                document.cookie = 'admin_auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=Lax';
            }

            static isAuthenticated() {
                const token = this.getToken();
                return !!token;
            }

            static getAuthHeaders() {
                const token = this.getToken();
                const headers = token ? { 'Authorization': `Bearer ${token}` } : {};
                return headers;
            }
        }

        // Completely rewritten AdminAPI with robust duplicate request prevention
        class AdminAPI {
            // Global request tracking with Map for better performance
            static pendingRequests = new Map();
            
            static async request(endpoint, options = {}) {
                const url = `/api/admin${endpoint}`;
                
                // Create a more robust unique key for this request
                let requestKey;
                if (options.body && typeof options.body === 'string') {
                    // For JSON requests, create a more stable key
                    try {
                        const parsedBody = JSON.parse(options.body);
                        requestKey = `${options.method || 'GET'}:${url}:${JSON.stringify(parsedBody)}`;
                    } catch (e) {
                        // If parsing fails, use the raw body
                        requestKey = `${options.method || 'GET'}:${url}:${options.body}`;
                    }
                } else {
                    // For other requests (like FormData), use a simpler approach
                    requestKey = `${options.method || 'GET'}:${url}`;
                }
                
                // Check if this request is already pending
                if (this.pendingRequests.has(requestKey)) {
                    console.log(`⚠️ Duplicate request detected, returning existing promise for:`, requestKey);
                    return this.pendingRequests.get(requestKey);
                }
                
                const config = {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        ...AdminAuthManager.getAuthHeaders(),
                        ...options.headers
                    },
                    ...options
                };

                // Create a new promise for this request
                const requestPromise = (async () => {
                    try {
                        console.log(`🚀 Sending fetch request to:`, url);
                        const response = await fetch(url, config);
                        console.log(`📥 Received response from:`, url, 'Status:', response.status);
                        
                        // Check if response is OK before trying to parse JSON
                        if (!response.ok) {
                            console.error(`❌ HTTP Error:`, response.status, response.statusText);
                            const errorText = await response.text();
                            console.error(`❌ Error response body:`, errorText);
                            
                            if (response.status === 401) {
                                // Token expired or invalid
                                AdminAuthManager.removeToken();
                                window.location.href = '/admin/login';
                                throw new Error('Unauthorized');
                            }
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();
                        console.log(`📄 Response data:`, data);
                        
                        return data;
                    } catch (error) {
                        console.error(`💥 Error in request:`, error);
                        throw error;
                    } finally {
                        // Remove the request from pending requests
                        this.pendingRequests.delete(requestKey);
                        console.log(`🧹 Cleaned up request key:`, requestKey);
                    }
                })();
                
                // Store the promise in pending requests
                this.pendingRequests.set(requestKey, requestPromise);
                console.log(`➕ Added request to pending requests:`, requestKey);
                
                return requestPromise;
            }

            static async postWithFiles(endpoint, formData) {
                const url = `/api/admin${endpoint}`;
                
                // Create a more specific key for this request
                const requestKey = `POST_FILE:${url}`;
                
                // Check if this request is already pending
                if (this.pendingRequests.has(requestKey)) {
                    console.log(`⚠️ Duplicate file upload request detected, returning existing promise for:`, requestKey);
                    return this.pendingRequests.get(requestKey);
                }
                
                const config = {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        ...AdminAuthManager.getAuthHeaders()
                    },
                    body: formData
                };

                // Create a new promise for this request
                const requestPromise = (async () => {
                    try {
                        console.log(`🚀 Sending file upload request to:`, url);
                        const response = await fetch(url, config);
                        console.log(`📥 Received file upload response from:`, url, 'Status:', response.status);
                        
                        // Check if response is OK before trying to parse JSON
                        if (!response.ok) {
                            console.error(`❌ HTTP Error:`, response.status, response.statusText);
                            const errorText = await response.text();
                            console.error(`❌ Error response body:`, errorText);
                            
                            if (response.status === 401) {
                                // Token expired or invalid
                                AdminAuthManager.removeToken();
                                window.location.href = '/admin/login';
                                throw new Error('Unauthorized');
                            }
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();
                        console.log(`📄 File upload response data:`, data);
                        
                        return data;
                    } catch (error) {
                        console.error(`💥 Error in file upload request:`, error);
                        throw error;
                    } finally {
                        // Remove the request from pending requests
                        this.pendingRequests.delete(requestKey);
                        console.log(`🧹 Cleaned up file upload request key:`, requestKey);
                    }
                })();
                
                // Store the promise in pending requests
                this.pendingRequests.set(requestKey, requestPromise);
                console.log(`➕ Added file upload request to pending requests:`, requestKey);
                
                return requestPromise;
            }
            
            static async putWithFiles(endpoint, formData) {
                const url = `/api/admin${endpoint}`;
                
                // Create a more specific key for this request
                const requestKey = `PUT_FILE:${url}`;
                
                // Check if this request is already pending
                if (this.pendingRequests.has(requestKey)) {
                    console.log(`⚠️ Duplicate file update request detected, returning existing promise for:`, requestKey);
                    return this.pendingRequests.get(requestKey);
                }
                
                // Add _method parameter to simulate PUT request
                formData.append('_method', 'PUT');
                
                const config = {
                    method: 'POST', // Laravel requires POST for file uploads, we'll add _method parameter
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        ...AdminAuthManager.getAuthHeaders()
                    },
                    body: formData
                };

                // Create a new promise for this request
                const requestPromise = (async () => {
                    try {
                        console.log(`🚀 Sending file update request to:`, url);
                        const response = await fetch(url, config);
                        console.log(`📥 Received file update response from:`, url, 'Status:', response.status);
                        
                        // Check if response is OK before trying to parse JSON
                        if (!response.ok) {
                            console.error(`❌ HTTP Error:`, response.status, response.statusText);
                            const errorText = await response.text();
                            console.error(`❌ Error response body:`, errorText);
                            
                            if (response.status === 401) {
                                // Token expired or invalid
                                AdminAuthManager.removeToken();
                                window.location.href = '/admin/login';
                                throw new Error('Unauthorized');
                            }
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();
                        console.log(`📄 File update response data:`, data);
                        
                        return data;
                    } catch (error) {
                        console.error(`💥 Error in file update request:`, error);
                        throw error;
                    } finally {
                        // Remove the request from pending requests
                        this.pendingRequests.delete(requestKey);
                        console.log(`🧹 Cleaned up file update request key:`, requestKey);
                    }
                })();
                
                // Store the promise in pending requests
                this.pendingRequests.set(requestKey, requestPromise);
                console.log(`➕ Added file update request to pending requests:`, requestKey);
                
                return requestPromise;
            }

            static async post(endpoint, body = {}) {
                console.log('AdminAPI.post called with:', { endpoint, body });
                return this.request(endpoint, {
                    method: 'POST',
                    body: JSON.stringify(body)
                });
            }

            static async get(endpoint) {
                return this.request(endpoint, {
                    method: 'GET'
                });
            }

            static async put(endpoint, body = {}) {
                return this.request(endpoint, {
                    method: 'PUT',
                    body: JSON.stringify(body)
                });
            }

            static async delete(endpoint) {
                return this.request(endpoint, {
                    method: 'DELETE'
                });
            }
            
            // News specific methods
            static async getNews(page = 1) {
                return this.get(`/news?page=${page}`);
            }
            
            static async getNewsById(id) {
                return this.get(`/news/${id}`);
            }
            
            static async createNews(formData) {
                return this.postWithFiles('/news', formData);
            }
            
            static async updateNews(id, formData) {
                return this.putWithFiles(`/news/${id}`, formData);
            }
            
            static async deleteNews(id) {
                return this.delete(`/news/${id}`);
            }
        }

        // Load admin profile
        async function loadAdminProfile() {
            try {
                const response = await AdminAPI.get('/profile');
                
                if (response.success) {
                    const admin = response.data;
                    
                    // Update header with admin info
                    document.getElementById('dropdown-name').textContent = admin.name || 'Admin';
                    document.getElementById('dropdown-role').textContent = admin.role || 'Administrator';
                    
                    // Set avatar or use default
                    const headerAvatar = document.getElementById('admin-header-avatar');
                    const dropdownAvatar = document.getElementById('dropdown-avatar');
                    
                    if (admin.photo) {
                        const photoUrl = `/storage/${admin.photo}`;
                        headerAvatar.src = photoUrl;
                        dropdownAvatar.src = photoUrl;
                    } else {
                        // Generate a default avatar based on name
                        const initials = (admin.name || 'A').split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
                        const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57'];
                        const color = colors[(admin.name || 'A').length % colors.length];
                        
                        const avatarSvg = `data:image/svg+xml,${encodeURIComponent(`
                            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <rect width="40" height="40" fill="${color}"/>
                                <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="16" font-weight="bold">${initials}</text>
                            </svg>
                        `)}`;
                        
                        headerAvatar.src = avatarSvg;
                        dropdownAvatar.src = avatarSvg;
                    }
                }
            } catch (error) {
                console.error('Error loading admin profile:', error);
                // Use default values
                document.getElementById('dropdown-name').textContent = 'Admin';
                document.getElementById('dropdown-role').textContent = 'Administrator';
                
                // Default avatar
                const avatarSvg = `data:image/svg+xml,${encodeURIComponent(`
                    <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" fill="#45B7D1"/>
                        <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="16" font-weight="bold">A</text>
                    </svg>
                `)}`;
                
                document.getElementById('admin-header-avatar').src = avatarSvg;
                document.getElementById('dropdown-avatar').src = avatarSvg;
            }
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const backdrop = document.getElementById('dropdown-backdrop');
            dropdown.classList.toggle('show');
            backdrop.classList.toggle('show');
        }

        // Close profile dropdown
        function closeProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const backdrop = document.getElementById('dropdown-backdrop');
            dropdown.classList.remove('show');
            backdrop.classList.remove('show');
        }

        // Initialize admin profile when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadAdminProfile();
            
            // Add event listeners for profile dropdown
            const profileToggle = document.getElementById('profile-toggle');
            const backdrop = document.getElementById('dropdown-backdrop');
            
            if (profileToggle) {
                profileToggle.addEventListener('click', toggleProfileDropdown);
            }
            
            if (backdrop) {
                backdrop.addEventListener('click', closeProfileDropdown);
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const profileDropdown = document.getElementById('profile-dropdown');
                const profileToggle = document.getElementById('profile-toggle');
                
                if (profileDropdown && profileToggle && 
                    !profileDropdown.contains(event.target) && 
                    !profileToggle.contains(event.target) &&
                    profileDropdown.classList.contains('show')) {
                    closeProfileDropdown();
                }
            });
        });

        // Logout
        async function logout() {
            if (confirm('Are you sure you want to logout?')) {
                try {
                    await AdminAPI.post('/logout');
                } catch (error) {
                    console.error('Logout error:', error);
                } finally {
                    AdminAuthManager.removeToken();
                    window.location.href = '/admin/login';
                }
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>