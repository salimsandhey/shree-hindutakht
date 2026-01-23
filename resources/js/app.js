import './bootstrap';

// API Base URL
const API_BASE = '/api';

// Performance and Caching Configuration
const CACHE_KEYS = {
    POSTS: 'hindutakht_posts_cache',
    USER_PROFILE: 'hindutakht_user_profile',
    EVENTS: 'hindutakht_events_cache',
    NOTIFICATIONS: 'hindutakht_notifications_cache'
};

const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes
const IMAGE_PLACEHOLDER = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><rect width="400" height="300" fill="%23f3f4f6"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="%23d1d5db" font-family="Arial" font-size="18">Loading...</text></svg>';

// Intersection Observer for lazy loading
let imageObserver = null;
let postObserver = null;

// Infinite scroll state
let hasMorePosts = true;
let isLoadingPosts = false;
let postsCurrentPage = 1;
const POSTS_PER_PAGE = 10;

// Cache Management Utilities
class CacheManager {
    static set(key, data, duration = CACHE_DURATION) {
        const cacheData = {
            data: data,
            timestamp: Date.now(),
            duration: duration
        };
        try {
            localStorage.setItem(key, JSON.stringify(cacheData));
        } catch (error) {
            console.warn('Failed to cache data:', error);
            // If localStorage is full, clear old caches
            this.clearExpiredCaches();
        }
    }

    static get(key) {
        try {
            const cached = localStorage.getItem(key);
            if (!cached) return null;

            const cacheData = JSON.parse(cached);
            const now = Date.now();
            
            if (now - cacheData.timestamp > cacheData.duration) {
                localStorage.removeItem(key);
                return null;
            }
            
            return cacheData.data;
        } catch (error) {
            console.warn('Failed to retrieve cache:', error);
            return null;
        }
    }

    static remove(key) {
        localStorage.removeItem(key);
    }

    static clearExpiredCaches() {
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
            if (key.startsWith('hindutakht_')) {
                try {
                    const cached = localStorage.getItem(key);
                    if (cached) {
                        const cacheData = JSON.parse(cached);
                        if (Date.now() - cacheData.timestamp > cacheData.duration) {
                            localStorage.removeItem(key);
                        }
                    }
                } catch (error) {
                    localStorage.removeItem(key);
                }
            }
        });
    }

    static clear() {
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
            if (key.startsWith('hindutakht_')) {
                localStorage.removeItem(key);
            }
        });
    }
}

// Skeleton Loader Utilities
class SkeletonLoader {
    static createPostSkeleton() {
        const div = document.createElement('div');
        div.className = 'card post-skeleton animate-pulse';
        div.innerHTML = `
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                <div class="flex-1">
                    <div class="h-4 bg-gray-300 rounded w-1/3 mb-2"></div>
                    <div class="h-3 bg-gray-300 rounded w-1/4"></div>
                </div>
            </div>
            <div class="space-y-2 mb-4">
                <div class="h-4 bg-gray-300 rounded"></div>
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
            </div>
            <div class="h-48 bg-gray-300 rounded-lg mb-4"></div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="h-6 bg-gray-300 rounded w-12"></div>
                    <div class="h-6 bg-gray-300 rounded w-12"></div>
                    <div class="h-6 bg-gray-300 rounded w-12"></div>
                </div>
            </div>
        `;
        return div;
    }

    static createImageSkeleton() {
        const div = document.createElement('div');
        div.className = 'image-skeleton animate-pulse bg-gray-300 rounded-lg';
        div.style.aspectRatio = '16/9';
        return div;
    }

    static showPostsLoading(container) {
        for (let i = 0; i < 3; i++) {
            container.appendChild(this.createPostSkeleton());
        }
    }

    static hidePostsLoading(container) {
        const skeletons = container.querySelectorAll('.post-skeleton');
        skeletons.forEach(skeleton => skeleton.remove());
    }
}

// Lazy Loading Implementation
class LazyLoader {
    static init() {
        // Image lazy loading observer
        if ('IntersectionObserver' in window) {
            imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        this.loadImage(img);
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });

            // Posts infinite scroll observer
            postObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    console.log('👁️ Intersection observed:', {
                        isIntersecting: entry.isIntersecting,
                        hasMorePosts,
                        isLoadingPosts,
                        target: entry.target.id
                    });
                    
                    if (entry.isIntersecting && hasMorePosts && !isLoadingPosts) {
                        console.log('🚀 Triggering loadMorePosts()');
                        // Use global function reference
                        if (window.loadMorePosts) {
                            window.loadMorePosts();
                        }
                    }
                });
            }, {
                rootMargin: '100px 0px',
                threshold: 0.1
            });
        }
    }

    static observeImage(img) {
        if (imageObserver && img) {
            imageObserver.observe(img);
        } else {
            // Fallback for browsers without IntersectionObserver
            this.loadImage(img);
        }
    }

    static loadImage(img) {
        const src = img.dataset.src;
        if (src) {
            // Create a new image to preload
            const newImg = new Image();
            newImg.onload = () => {
                // Once loaded, set the src and add fade-in class
                img.src = src;
                img.classList.add('fade-in', 'loaded');
                img.classList.remove('lazy-image');
                img.removeAttribute('data-src');
                
                // Remove placeholder after animation
                setTimeout(() => {
                    const placeholder = img.parentElement?.querySelector('.lazy-placeholder');
                    if (placeholder) {
                        placeholder.style.opacity = '0';
                        setTimeout(() => placeholder.remove(), 300);
                    }
                }, 100);
            };
            newImg.onerror = () => {
                img.src = this.generateFallbackImage(img.alt || 'Image');
                img.classList.add('loaded');
                img.classList.remove('lazy-image');
                img.removeAttribute('data-src');
            };
            // Start loading
            newImg.src = src;
        }
    }

    static generateFallbackImage(text) {
        return `data:image/svg+xml,${encodeURIComponent(`
            <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
                <rect width="400" height="300" fill="#f3f4f6"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af" font-family="Arial" font-size="16">${text}</text>
            </svg>
        `)}`;
    }

    static observeInfiniteScroll(element) {
        if (postObserver && element) {
            console.log('🔍 Observing element for infinite scroll:', element);
            postObserver.observe(element);
        } else {
            console.warn('⚠️ Cannot observe infinite scroll - observer or element missing');
        }
    }

    static destroy() {
        if (imageObserver) {
            imageObserver.disconnect();
            imageObserver = null;
        }
        if (postObserver) {
            postObserver.disconnect();
            postObserver = null;
        }
    }
}

// Service Worker Registration
class ServiceWorkerManager {
    static async register() {
        if ('serviceWorker' in navigator) {
            try {
                // Skip service worker in development with Vite
                if (window.location.port === '5173' || window.location.hostname === '::1') {
                    console.log('Skipping Service Worker registration in Vite dev mode');
                    return;
                }
                
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker registered:', registration.scope);
                
                // Listen for updates
                registration.addEventListener('updatefound', () => {
                    console.log('Service Worker update found');
                });

                // Handle messages from service worker
                navigator.serviceWorker.addEventListener('message', (event) => {
                    const { type, data } = event.data;
                    if (type === 'CACHE_UPDATED') {
                        console.log('Cache updated by service worker');
                    }
                });

                // Send token to service worker
                const token = AuthManager.getToken();
                if (token) {
                    this.sendMessage({ type: 'UPDATE_TOKEN', token });
                }

                return registration;
            } catch (error) {
                console.warn('Service Worker registration failed:', error);
            }
        }
    }

    static sendMessage(message) {
        if (navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage(message);
        }
    }

    static prefetchPosts() {
        this.sendMessage({ type: 'PREFETCH_POSTS' });
    }

    static clearCache() {
        this.sendMessage({ type: 'CLEAR_CACHE' });
    }
}
// Auth token management
class AuthManager {
    static getToken() {
        return localStorage.getItem('auth_token');
    }

    static setToken(token) {
        localStorage.setItem('auth_token', token);
        // Update service worker with new token
        ServiceWorkerManager.sendMessage({ type: 'UPDATE_TOKEN', token });
    }

    static removeToken() {
        localStorage.removeItem('auth_token');
        // Clear caches when token is removed
        CacheManager.clear();
        ServiceWorkerManager.clearCache();
    }

    static isAuthenticated() {
        return !!this.getToken();
    }

    static getAuthHeaders() {
        const token = this.getToken();
        return token ? { 'Authorization': `Bearer ${token}` } : {};
    }
}

// API helper functions with caching
class API {
    static async request(endpoint, options = {}) {
        const url = `${API_BASE}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                ...AuthManager.getAuthHeaders(),
                ...options.headers
            },
            ...options
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            // For validation errors (422), we want to return the data so the calling code can handle it properly
            if (!response.ok && response.status !== 422) {
                throw new Error(data.message || 'Request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    static async post(endpoint, body = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body)
        });
    }

    static async put(endpoint, body = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(body)
        });
    }

    static async get(endpoint, useCache = false, cacheKey = null) {
        // Handle caching if requested
        if (useCache && cacheKey) {
            const cached = CacheManager.get(cacheKey);
            if (cached) {
                return cached;
            }
        }
        
        const response = await this.request(endpoint, {
            method: 'GET'
        });
        
        // Cache the response if requested
        if (useCache && cacheKey && response.success) {
            CacheManager.set(cacheKey, response);
        }
        
        return response;
    }

    static async delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }
}

// Login functionality
if (document.getElementById('login-form')) {
    const form = document.getElementById('login-form');
    const errorDiv = document.getElementById('error-message');
    const loginBtn = document.getElementById('login-btn');
    const loginText = document.getElementById('login-text');
    const loginLoading = document.getElementById('login-loading');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Show loading state
        loginBtn.disabled = true;
        loginText.classList.add('hidden');
        loginLoading.classList.remove('hidden');
        errorDiv.classList.add('hidden');

        try {
            const response = await API.post('/auth/login', { email, password });
            
            if (response.success) {
                AuthManager.setToken(response.data.token);
                window.location.href = '/member/dashboard';
            } else {
                // Handle API errors with detailed information
                let errorMessage = response.message || 'Login failed';
                
                // Check for validation errors
                if (response.errors) {
                    errorMessage += '<br>' + Object.values(response.errors).flat().join('<br>');
                }
                // Check for detailed error information
                else if (response.error_details) {
                    errorMessage += '<br>' + response.error_details;
                }
                
                errorDiv.innerHTML = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Login error:', error);
            // Show more detailed error information in development
            let errorMessage = 'Network error. Please try again.';
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                errorMessage += '<br>Error details: ' + error.message;
            }
            errorDiv.innerHTML = errorMessage;
            errorDiv.classList.remove('hidden');
        } finally {
            loginBtn.disabled = false;
            loginText.classList.remove('hidden');
            loginLoading.classList.add('hidden');
        }
    });
}

// Registration functionality
if (document.getElementById('register-form')) {
    const form = document.getElementById('register-form');
    const errorDiv = document.getElementById('error-message');
    const registerBtn = document.getElementById('register-btn');
    const registerText = document.getElementById('register-text');
    const registerLoading = document.getElementById('register-loading');
    let isSubmitting = false;

    // Photo preview functionality
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-24 h-24 rounded-full object-cover border-2 border-dashed border-gray-300">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove any existing event listeners by cloning the form
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);
    
    newForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Prevent duplicate submissions
        if (isSubmitting) {
            console.log('Registration already in progress, ignoring duplicate submission');
            return;
        }
        
        isSubmitting = true;
        
        // Get form values
        const formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('password', document.getElementById('password').value);
        formData.append('password_confirmation', document.getElementById('password_confirmation').value);
        formData.append('address', document.getElementById('address').value);
        
        // Add photo if selected
        const photoFile = document.getElementById('photo').files[0];
        if (photoFile) {
            formData.append('photo', photoFile);
        }
        
        // Show loading state
        registerBtn.disabled = true;
        registerText.classList.add('hidden');
        registerLoading.classList.remove('hidden');
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store token and redirect to member dashboard
                localStorage.setItem('auth_token', data.data.token);
                window.location.href = '/member/dashboard';
            } else {
                // Handle API errors with detailed information
                let errorMessage = data.message || 'Registration failed';
                
                // Check for validation errors
                if (data.errors) {
                    errorMessage += '<br>' + Object.values(data.errors).flat().join('<br>');
                }
                // Check for detailed error information
                else if (data.error_details) {
                    errorMessage += '<br>' + data.error_details;
                }
                
                errorDiv.innerHTML = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Registration error:', error);
            // Show more detailed error information in development
            let errorMessage = 'Network error. Please try again.';
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                errorMessage += '<br>Error details: ' + error.message;
            }
            errorDiv.innerHTML = errorMessage;
            errorDiv.classList.remove('hidden');
        } finally {
            registerBtn.disabled = false;
            registerText.classList.remove('hidden');
            registerLoading.classList.add('hidden');
            isSubmitting = false;
        }
    });
}

// Global currentUser variable
let currentUser = null;

// Home page functionality with optimizations
if (window.location.pathname === '/home') {
    let isLoading = false;

    // Initialize optimizations
    document.addEventListener('DOMContentLoaded', () => {
        // Only register service worker in production or when not using Vite dev server
        if (window.location.protocol === 'https:' || 
            (window.location.hostname === 'localhost' && window.location.port !== '5173')) {
            ServiceWorkerManager.register();
        } else {
            console.log('🚧 Development mode: Service Worker disabled');
        }
        
        // Initialize lazy loading
        LazyLoader.init();
        
        // Clear expired caches on startup
        CacheManager.clearExpiredCaches();
        
        // Debug cache status
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            console.log('🚀 Hindutakht Optimizations Loaded!');
            console.log('📊 Cache Status:', {
                'localStorage available': !!window.localStorage,
                'Service Worker supported': 'serviceWorker' in navigator,
                'Intersection Observer supported': 'IntersectionObserver' in window,
                'Current cache keys': Object.keys(localStorage).filter(k => k.startsWith('hindutakht_'))
            });
        }
    });

    // Optimized posts loading with infinite scroll
    window.loadPosts = async function(page = 1, replace = true) {
        if (isLoadingPosts && page > 1) return;
        
        isLoadingPosts = true;
        const container = document.getElementById('posts-container');
        
        try {
            // Show skeleton loading for initial load
            if (replace) {
                SkeletonLoader.showPostsLoading(container);
            }

            // Try cache for first page
            const cacheKey = `${CACHE_KEYS.POSTS}_page_${page}`;
            let response;
            
            if (page === 1) {
                response = await API.get(`/posts?page=${page}&per_page=${POSTS_PER_PAGE}`, true, cacheKey);
            } else {
                response = await API.get(`/posts?page=${page}&per_page=${POSTS_PER_PAGE}`);
            }
            
            if (response.success) {
                // Handle different response structures
                let posts = [];
                let hasMore = false;
                
                console.log('🔍 API Response structure:', response.data);
                
                if (response.data.posts) {
                    // Structure: { data: { posts: [], pagination: {} } }
                    posts = response.data.posts;
                    const pagination = response.data.pagination;
                    hasMore = pagination?.current_page < pagination?.last_page;
                    
                    console.log('📏 Pagination details:', {
                        current_page: pagination?.current_page,
                        last_page: pagination?.last_page,
                        total: pagination?.total,
                        per_page: pagination?.per_page
                    });
                } else if (response.data.data) {
                    // Structure: { data: { data: [], meta: {} } }
                    posts = response.data.data;
                    hasMore = response.data.meta?.current_page < response.data.meta?.last_page;
                } else if (Array.isArray(response.data)) {
                    // Structure: { data: [] }
                    posts = response.data;
                    hasMore = posts.length === POSTS_PER_PAGE;
                } else {
                    console.warn('⚠️ Unknown API response structure:', response.data);
                    posts = [];
                    hasMore = false;
                }
                
                console.log(`📊 Loaded ${posts.length} posts for page ${page}, hasMore: ${hasMore}`);
                
                renderPosts(posts, replace);
                
                hasMorePosts = hasMore;
                postsCurrentPage = page;

                // Prefetch next page in background
                if (hasMore && page === 1) {
                    setTimeout(() => {
                        ServiceWorkerManager.prefetchPosts();
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('❌ Failed to load posts:', error);
            console.error('Current state:', {
                page,
                replace,
                isLoadingPosts,
                hasMorePosts,
                postsCurrentPage
            });
            
            // Try to show cached posts as fallback
            if (replace) {
                const cached = CacheManager.get(`${CACHE_KEYS.POSTS}_page_1`);
                if (cached && cached.success) {
                    const posts = cached.data.posts || cached.data.data || [];
                    renderPosts(posts, true);
                }
            }
        } finally {
            isLoadingPosts = false;
            
            // Hide skeleton loading
            if (replace) {
                SkeletonLoader.hidePostsLoading(container);
            }
        }
    }

    // Load more posts for infinite scroll (make it global)
    window.loadMorePosts = async function() {
        if (!hasMorePosts || isLoadingPosts) {
            console.log('🚨 Skipping load more:', { hasMorePosts, isLoadingPosts });
            return;
        }
        
        console.log('🚀 Loading more posts, current page:', postsCurrentPage);
        
        // Show loading indicator
        const loadingIndicator = document.getElementById('loading-more');
        if (loadingIndicator) {
            loadingIndicator.classList.remove('hidden');
        }
        
        const nextPage = postsCurrentPage + 1;
        await loadPosts(nextPage, false);
        
        // Hide loading indicator
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
    };

    // Render posts with optimizations
    function renderPosts(posts, replace = true) {
        const container = document.getElementById('posts-container');
        if (!container) return;

        // Create document fragment for better performance
        const fragment = document.createDocumentFragment();
        const postsHtml = posts.map(post => createPostElement(post)).join('');
        
        if (replace) {
            container.innerHTML = postsHtml || '<div class="text-center py-8 text-gray-500">No posts found</div>';
        } else {
            container.insertAdjacentHTML('beforeend', postsHtml);
        }

        // Initialize lazy loading for new images
        LazyLoader.init();

        // Update posts count
        const postsCount = document.getElementById('posts-count');
        if (postsCount) {
            postsCount.textContent = `${posts.length} posts`;
        }

        // Hide loading indicator
        const loadingIndicator = document.getElementById('loading-more');
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
    }

    // Create post element
    function createPostElement(post) {
        // Format post date
        const postDate = new Date(post.created_at);
        const formattedDate = postDate.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        
        // Check if current user liked this post
        const hasLiked = currentUser && post.likes?.some(like => like.member_id === currentUser.id);
        const likeCount = post.likes?.length || 0;
        
        // Check if post has image
        const hasImage = post.image_path && post.full_image_url;
        
        return `
            <div class="post-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 animate-fadeIn touch-target" data-post-id="${post.id}">
                <!-- Post Header -->
                <div class="p-4 border-b border-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                ${post.member?.full_photo_url ? 
                                    `<img src="${post.member.full_photo_url}" alt="${post.member.name}" class="w-10 h-10 rounded-full object-cover lazy" loading="lazy">` :
                                    `<div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center text-white font-bold">${post.member?.name?.charAt(0)?.toUpperCase() || 'U'}</div>`
                                }
                                ${post.member?.is_admin ? '<div class="absolute -bottom-1 -right-1 bg-blue-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">✓</div>' : ''}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 flex items-center">
                                    ${post.member?.name || 'Shri Hindutakht'}
                                    ${post.member?.is_admin ? '<span class="ml-1 text-blue-500 text-xs">● Admin</span>' : ''}
                                </div>
                                <div class="text-xs text-gray-500">${formattedDate}</div>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="p-4">
                    <p class="text-gray-800 mb-3">${post.content || ''}</p>
                    
                    <!-- Post Image -->
                    ${hasImage ? `
                    <div class="mb-3 rounded-lg overflow-hidden">
                        <img src="${post.full_image_url}" alt="Post image" class="w-full h-auto max-h-96 object-cover lazy" loading="lazy">
                    </div>` : ''}
                </div>

                <!-- Post Actions -->
                <div class="px-4 py-3 border-t border-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors touch-target like-btn" data-post-id="${post.id}" data-liked="${post.is_liked ? 'true' : 'false'}">
                                <svg class="w-5 h-5 ${post.is_liked ? 'fill-current text-red-500' : ''}" fill="${post.is_liked ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm like-count">${post.likes_count || 0}</span>
                            </button>
                            
                            <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors touch-target">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-sm">${post.comments_count || 0}</span>
                            </button>
                        </div>
                        
                        <button class="flex items-center space-x-1 text-gray-600 hover:text-green-500 transition-colors touch-target">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span class="text-sm">Share</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Update user UI
    function updateUserUI() {
        if (!currentUser) return;
        
        // Update profile image
        const profileImg = document.getElementById('profile-image');
        if (profileImg) {
            profileImg.src = currentUser.full_photo_url || '/default-avatar.png';
            profileImg.alt = currentUser.name;
        }
        
        // Update user name
        const userName = document.getElementById('user-name');
        if (userName) {
            userName.textContent = currentUser.name;
        }
        
        // Update member ID
        const memberId = document.getElementById('member-id');
        if (memberId) {
            memberId.textContent = currentUser.member_id;
        }
    }

    // Initialize like functionality (only when not on home page)
    if (window.location.pathname !== '/home') {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.like-btn')) {
                const button = e.target.closest('.like-btn');
                const postId = button.dataset.postId;
                toggleLike(postId, button);
            }
            
            // Initialize comment functionality
            if (e.target.closest('.comment-btn')) {
                const button = e.target.closest('.comment-btn');
                const postId = button.dataset.postId;
                toggleComments(postId);
            }
            
            // Initialize share functionality
            if (e.target.closest('.share-btn')) {
                const button = e.target.closest('.share-btn');
                const postId = button.dataset.postId;
                sharePost(postId);
            }
        });
    }

    // Toggle like
    async function toggleLike(postId) {
        try {
            const response = await API.post(`/posts/${postId}/like`);
            
            if (response.success) {
                // Update UI
                const likeBtn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
                if (likeBtn) {
                    const isLiked = response.data.is_liked;
                    const likeCount = response.data.likes_count;
                    
                    // Update button state
                    const svg = likeBtn.querySelector('svg');
                    const countSpan = likeBtn.querySelector('.like-count');
                    
                    if (isLiked) {
                        svg.classList.add('fill-current', 'text-red-500');
                        svg.setAttribute('fill', 'currentColor');
                    } else {
                        svg.classList.remove('fill-current', 'text-red-500');
                        svg.setAttribute('fill', 'none');
                    }
                    
                    countSpan.textContent = likeCount;
                    likeBtn.dataset.liked = isLiked;
                }
            }
        } catch (error) {
            console.error('Like error:', error);
            // Show error message
            const errorMsg = document.createElement('div');
            errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            errorMsg.textContent = 'Failed to like post. Please try again.';
            document.body.appendChild(errorMsg);
            
            // Remove error message after 3 seconds
            setTimeout(() => {
                errorMsg.remove();
            }, 3000);
        }
    }
}

// Toggle comments section
async function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    const isHidden = commentsSection.classList.contains('hidden');
    
    if (isHidden) {
        commentsSection.classList.remove('hidden');
        await loadComments(postId);
    } else {
        commentsSection.classList.add('hidden');
    }
}

// Load comments for a post
async function loadComments(postId) {
    try {
        console.log('Loading comments for post:', postId);
        const response = await API.get(`/posts/${postId}/comments`);
        console.log('Comments response:', response);
        
        if (response.success) {
            // Extract comments from paginated response structure
            let comments = [];
            if (response.data && response.data.data) {
                // Paginated response: response.data.data contains the comments array
                comments = Array.isArray(response.data.data) ? response.data.data : [];
            } else if (response.data && Array.isArray(response.data)) {
                // Direct array response
                comments = response.data;
            } else if (response.data && response.data.comments) {
                // Alternative structure
                comments = Array.isArray(response.data.comments) ? response.data.comments : [];
            }
            
            console.log('Extracted comments array:', comments);
            console.log('Comments count:', comments.length);
            renderComments(postId, comments);
        } else {
            console.error('Failed to load comments:', response.message);
            renderComments(postId, []);
        }
    } catch (error) {
        console.error('Failed to load comments:', error);
        const container = document.getElementById(`comments-list-${postId}`);
        if (container) {
            container.innerHTML = '<p class="text-red-500 text-sm">Failed to load comments. Please try again.</p>';
        }
    }
}

// Render comments
function renderComments(postId, comments) {
    console.log('Rendering comments for post:', postId, 'Comments:', comments);
    const container = document.getElementById(`comments-list-${postId}`);
    
    if (!container) {
        console.error('Comments container not found for post:', postId);
        return;
    }
    
    container.innerHTML = '';
    
    if (!Array.isArray(comments) || comments.length === 0) {
        console.log('No comments to render, showing empty message');
        container.innerHTML = '<p class="text-gray-500 text-sm">No comments yet. Be the first to comment!</p>';
        return;
    }
    
    console.log('Creating comment elements for', comments.length, 'comments');
    comments.forEach((comment, index) => {
        try {
            console.log(`Creating comment ${index + 1}:`, comment);
            
            // Validate comment structure
            if (!comment || !comment.member) {
                console.warn(`Skipping invalid comment ${index + 1}:`, comment);
                return;
            }
            
            const commentElement = createCommentElement(comment);
            container.appendChild(commentElement);
            console.log(`Successfully added comment ${index + 1} to DOM`);
        } catch (error) {
            console.error(`Error creating comment ${index + 1}:`, error, comment);
        }
    });
    
    console.log('Finished rendering all comments for post:', postId);
}

// Create comment element
function createCommentElement(comment) {
    console.log('Creating comment element for:', comment);
    
    const div = document.createElement('div');
    div.className = 'flex space-x-3 mb-3';
    
    // Safely get member data
    const memberName = comment.member?.name || 'Shri Hindutakht';
    const memberPhoto = comment.member?.photo;
    const avatarUrl = memberPhoto ? '/storage/' + memberPhoto : generateAvatarUrl(memberName);
    const commentText = comment.comment || '';
    const timeAgo = comment.time_ago || 'Just now';
    
    console.log('Comment element data:', {
        memberName,
        avatarUrl,
        commentText,
        timeAgo
    });
    
    div.innerHTML = `
        <img class="w-8 h-8 rounded-full object-cover" src="${avatarUrl}" alt="${memberName}" onerror="this.src='${generateAvatarUrl(memberName)}'">
        <div class="flex-1">
            <div class="bg-gray-100 rounded-lg px-3 py-2">
                <div class="font-medium text-sm text-gray-800">${memberName}</div>
                <div class="text-gray-700 text-sm">${commentText}</div>
            </div>
            <div class="text-xs text-gray-500 mt-1">${timeAgo}</div>
        </div>
    `;
    
    console.log('Created comment element:', div);
    return div;
}

// Add comment
async function addComment(postId) {
    const input = document.getElementById(`comment-input-${postId}`);
    const comment = input.value.trim();
    
    if (!comment) return;
    
    try {
        const response = await API.post(`/posts/${postId}/comment`, { comment });
        if (response.success) {
            input.value = '';
            await loadComments(postId);
            
            // Update comment count
            const commentCountElement = document.querySelector(`[data-post-id="${postId}"] .comment-count`);
            if (commentCountElement) {
                const currentCount = parseInt(commentCountElement.textContent) || 0;
                commentCountElement.textContent = currentCount + 1;
            }
        }
    } catch (error) {
        console.error('Failed to add comment:', error);
        alert('Failed to add comment. Please try again.');
    }
}

// Load notifications list
async function loadNotificationsList() {
    try {
        const response = await API.get('/notifications');
        if (response.success) {
            renderNotificationsList(response.data.notifications || []);
        }
    } catch (error) {
        console.error('Failed to load notifications:', error);
        document.getElementById('notifications-list').innerHTML = '<p class="text-gray-500 text-sm">Failed to load notifications</p>';
    }
}

// Render notifications list
function renderNotificationsList(notifications) {
    const container = document.getElementById('notifications-list');
    
    if (notifications.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">No new notifications</p>';
        return;
    }
    
    container.innerHTML = notifications.map(notification => `
        <div class="notification-item p-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 cursor-pointer" data-notification-id="${notification.id}">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-2 h-2 bg-blue-500 rounded-full ${notification.read_at ? 'opacity-0' : ''}"></div>
                </div>
                <div class="flex-grow">
                    <h4 class="font-medium text-sm text-gray-800">${notification.title || 'Notification'}</h4>
                    <p class="text-gray-600 text-xs mt-1">${notification.message || notification.data?.message || 'New notification'}</p>
                    <span class="text-xs text-gray-400 mt-1">${getTimeAgo(notification.created_at)}</span>
                </div>
            </div>
        </div>
    `).join('');
    
    // Add click handlers for notifications
    container.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', () => {
            const notificationId = item.dataset.notificationId;
            markNotificationAsRead(notificationId, item);
        });
    });
}

// Mark notification as read
async function markNotificationAsRead(notificationId, element) {
    try {
        const response = await API.post(`/notifications/${notificationId}/read`);
        if (response.success) {
            // Update UI to show as read
            const indicator = element.querySelector('.w-2.h-2');
            if (indicator) {
                indicator.classList.add('opacity-0');
            }
        }
    } catch (error) {
        console.error('Failed to mark notification as read:', error);
    }
}

// Get time ago helper
function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return 'Just now';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes}m ago`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours}h ago`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days}d ago`;
    }
}

// Image gallery function
window.openImageGallery = function(images, startIndex = 0) {
    // Create modal overlay
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="relative max-w-full max-h-full flex items-center justify-center">
            <img id="gallery-image" class="max-w-full max-h-full object-contain rounded-lg" src="${images[startIndex]}" alt="Gallery image">
            <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center" onclick="this.closest('.fixed').remove()">&times;</button>
            ${images.length > 1 ? `
                <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center" onclick="window.previousImage()">&larr;</button>
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center" onclick="window.nextImage()">&rarr;</button>
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm bg-black bg-opacity-50 px-3 py-1 rounded-full">${startIndex + 1} / ${images.length}</div>
            ` : ''}
        </div>
    `;
    
    // Add navigation functions
    let currentIndex = startIndex;
    window.previousImage = () => {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        modal.querySelector('#gallery-image').src = images[currentIndex];
        const counter = modal.querySelector('.absolute.bottom-4');
        if (counter) counter.textContent = `${currentIndex + 1} / ${images.length}`;
    };
    
    window.nextImage = () => {
        currentIndex = (currentIndex + 1) % images.length;
        modal.querySelector('#gallery-image').src = images[currentIndex];
        const counter = modal.querySelector('.absolute.bottom-4');
        if (counter) counter.textContent = `${currentIndex + 1} / ${images.length}`;
    };
    
    // Close on background click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
            delete window.previousImage;
            delete window.nextImage;
        }
    });
    
    // Keyboard navigation
    const handleKeyPress = (e) => {
        if (e.key === 'Escape') {
            modal.remove();
            delete window.previousImage;
            delete window.nextImage;
            document.removeEventListener('keydown', handleKeyPress);
        } else if (e.key === 'ArrowLeft' && images.length > 1) {
            window.previousImage();
        } else if (e.key === 'ArrowRight' && images.length > 1) {
            window.nextImage();
        }
    };
    
    document.addEventListener('keydown', handleKeyPress);
    document.body.appendChild(modal);
};

/*
// Like/Unlike post
async function toggleLike(postId, button) {
    try {
        const response = await API.post(`/posts/${postId}/like`);
        if (response.success) {
            const heart = button.querySelector('svg');
            const count = button.querySelector('span');
            
            if (response.data.is_liked) {
                heart.classList.add('text-red-500');
                heart.setAttribute('fill', 'currentColor');
            } else {
                heart.classList.remove('text-red-500');
                heart.setAttribute('fill', 'none');
            }
            
            count.textContent = response.data.likes_count;
        }
    } catch (error) {
        console.error('Failed to toggle like:', error);
    }
}
*/

// Share post
async function sharePost(postId) {
    try {
        const response = await API.post(`/posts/${postId}/share`);
        if (response.success) {
            if (navigator.share) {
                navigator.share({
                    title: 'Shree Hindutakht Post',
                    url: response.data.share_link
                });
            } else {
                navigator.clipboard.writeText(response.data.share_link);
                alert('Link copied to clipboard!');
            }
        }
    } catch (error) {
        console.error('Failed to share post:', error);
    }
}

// Load events
async function loadEvents() {
    const container = document.getElementById('events-container');
    
    try {
        // Show loading state
        container.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto mb-2"></div><p class="text-gray-500 text-sm">Loading events...</p></div>';
        
        const response = await API.get('/events');
        if (response.success) {
            const events = response.data.data || response.data || [];
            renderEvents(events);
        } else {
            renderEventsError('Failed to load events');
        }
    } catch (error) {
        console.error('Failed to load events:', error);
        renderEventsError('Unable to connect to server');
    }
}

function renderEvents(events) {
    const container = document.getElementById('events-container');
    
    if (events.length === 0) {
        // Show the enhanced "no events" design that's already in the HTML
        // The static content in the events section will be visible
        container.innerHTML = '';
        return;
    }

    // Clear the container and show actual events
    container.innerHTML = '';
    
    // Hide the static "Events Coming Soon" card when we have real events
    const staticCard = document.querySelector('#events-section .card');
    if (staticCard) {
        staticCard.style.display = 'none';
    }

    events.forEach(event => {
        const eventElement = createEventElement(event);
        container.appendChild(eventElement);
    });
}

function renderEventsError(message) {
    const container = document.getElementById('events-container');
    container.innerHTML = `
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-500 font-medium">${message}</p>
            <button onclick="loadEvents()" class="mt-3 text-primary text-sm hover:underline">Try Again</button>
        </div>
    `;
}

function createEventElement(event) {
    const div = document.createElement('div');
    div.className = 'card';
    div.innerHTML = `
        <div class="mb-4">
            <h3 class="font-semibold text-gray-800 mb-2">${event.title}</h3>
            <p class="text-gray-600 text-sm mb-3">${event.description}</p>
            <div class="flex items-center text-sm text-gray-500 space-x-4">
                <span>📅 ${new Date(event.event_date).toLocaleDateString()}</span>
                <span>📍 ${event.location}</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="rsvp-btn btn-${event.user_rsvp === 'interested' ? 'primary' : 'secondary'} flex-1 text-sm" data-event-id="${event.id}" data-response="interested">
                ${event.user_rsvp === 'interested' ? '✓' : ''} Interested (${event.interested_count})
            </button>
            <button class="rsvp-btn btn-${event.user_rsvp === 'going' ? 'primary' : 'secondary'} flex-1 text-sm" data-event-id="${event.id}" data-response="going">
                ${event.user_rsvp === 'going' ? '✓' : ''} Going (${event.going_count})
            </button>
        </div>
    `;

    // Add RSVP functionality
    div.querySelectorAll('.rsvp-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const eventId = btn.dataset.eventId;
            const response = btn.dataset.response;
            rsvpToEvent(eventId, response, div);
        });
    });

    return div;
}

// RSVP to event
async function rsvpToEvent(eventId, response, eventElement) {
    try {
        const apiResponse = await API.post(`/events/${eventId}/rsvp`, { response });
        if (apiResponse.success) {
            // Update button states
            const buttons = eventElement.querySelectorAll('.rsvp-btn');
            buttons.forEach(btn => {
                const btnResponse = btn.dataset.response;
                if (btnResponse === response) {
                    btn.className = btn.className.replace('btn-secondary', 'btn-primary');
                    btn.innerHTML = `✓ ${btnResponse === 'interested' ? 'Interested' : 'Going'} (${apiResponse.data[btnResponse + '_count']})`;
                } else {
                    btn.className = btn.className.replace('btn-primary', 'btn-secondary');
                    btn.innerHTML = `${btnResponse === 'interested' ? 'Interested' : 'Going'} (${apiResponse.data[btnResponse + '_count']})`;
                }
            });
        }
    } catch (error) {
        console.error('Failed to RSVP:', error);
    }
}

// Load profile data
async function loadProfile() {
    // Profile data is already loaded in currentUser
    if (currentUser) {
        updateUserUI();
    }
}

// View ID Card
document.getElementById('view-id-card')?.addEventListener('click', () => {
    window.location.href = '/member/id-card';
});

// Edit Profile
document.getElementById('edit-profile')?.addEventListener('click', () => {
    window.location.href = '/member/edit-profile';
});

// Change Password
document.getElementById('change-password')?.addEventListener('click', () => {
    showChangePasswordModal();
});

// Show Change Password Modal
function showChangePasswordModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-xl p-6 max-w-md w-full">
            <h3 class="text-lg font-bold mb-4">Change Password</h3>
            <form id="change-password-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" id="current-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="new-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" minlength="6" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" id="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" minlength="6" required>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" id="save-password" class="btn-primary flex-1">Change Password</button>
                    <button type="button" id="cancel-change-password" class="btn-secondary flex-1">Cancel</button>
                </div>
            </form>
        </div>
    `;

    modal.querySelector('#change-password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const currentPassword = modal.querySelector('#current-password').value;
        const newPassword = modal.querySelector('#new-password').value;
        const confirmPassword = modal.querySelector('#confirm-password').value;

        if (newPassword !== confirmPassword) {
            alert('New passwords do not match!');
            return;
        }

        try {
            const response = await API.post('/auth/change-password', {
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            });
            
            if (response.success) {
                document.body.removeChild(modal);
                alert('Password changed successfully! Please login again.');
                AuthManager.removeToken();
                window.location.href = '/login';
            }
        } catch (error) {
            alert('Failed to change password: ' + error.message);
        }
    });

    modal.querySelector('#cancel-change-password').addEventListener('click', () => {
        document.body.removeChild(modal);
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });

    document.body.appendChild(modal);
}

// Logout functionality
document.getElementById('logout-btn')?.addEventListener('click', async () => {
    try {
        await API.post('/auth/logout');
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        AuthManager.removeToken();
        window.location.href = '/login';
    }
});

// Disable any existing load more button functionality
const oldLoadMoreBtn = document.getElementById('load-more-posts');
if (oldLoadMoreBtn) {
    oldLoadMoreBtn.style.display = 'none';
}
const oldLoadMoreContainer = document.getElementById('load-more-container');
if (oldLoadMoreContainer) {
    oldLoadMoreContainer.style.display = 'none';
}

// Initialize the home page with optimizations
function initHomePage() {
    if (!AuthManager.isAuthenticated()) {
        window.location.href = '/login';
        return;
    }

    // Initialize all optimizations
    initNavigation();
    loadUserProfile();
    loadPosts();
    initProfileDropdown();
    
    // Handle hash navigation (from edit profile page)
    if (window.location.hash) {
        const section = window.location.hash.substring(1); // Remove # from hash
        if (['feed', 'events', 'donation', 'profile'].includes(section)) {
            showSection(section);
            // Update active navigation item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(nav => nav.classList.remove('active'));
            const targetNav = document.querySelector(`[data-section="${section}"]`);
            if (targetNav) {
                targetNav.classList.add('active');
            }
        }
        // Clean up the hash
        history.replaceState(null, null, window.location.pathname);
    }
}

// Load user profile with caching
async function loadUserProfile() {
    try {
        // Try cache first
        const cached = CacheManager.get(CACHE_KEYS.USER_PROFILE);
        if (cached && cached.success) {
            currentUser = cached.data;
            updateUserUI();
        }

        // Always fetch fresh data in background
        const response = await API.get('/auth/profile', true, CACHE_KEYS.USER_PROFILE);
        if (response.success) {
            currentUser = response.data;
            updateUserUI();
        }
    } catch (error) {
        console.error('Failed to load user profile:', error);
        // Redirect to login if unauthorized
        if (error.message.includes('Unauthorized')) {
            window.location.href = '/login';
        }
    }
}

// Update user UI
function updateUserUI() {
    if (!currentUser) return;
    
    // Update profile image
    const profileImg = document.getElementById('profile-image');
    if (profileImg) {
        profileImg.src = currentUser.full_photo_url || '/default-avatar.png';
        profileImg.alt = currentUser.name;
    }
    
    // Update user name
    const userName = document.getElementById('user-name');
    if (userName) {
        userName.textContent = currentUser.name;
    }
    
    // Update member ID
    const memberId = document.getElementById('member-id');
    if (memberId) {
        memberId.textContent = currentUser.member_id;
    }
    
    // Update dropdown profile information
    const dropdownName = document.getElementById('dropdown-name');
    if (dropdownName) {
        dropdownName.textContent = currentUser.name;
    }
    
    const dropdownMemberId = document.getElementById('dropdown-member-id');
    if (dropdownMemberId) {
        dropdownMemberId.textContent = `ID: ${currentUser.member_id}`;
    }
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

// Load user profile data
async function loadUserProfileData() {
    try {
        const response = await API.get('/auth/profile');
        if (response.success) {
            const data = response.data;
            
            // Update profile information
            document.getElementById('profile-name').textContent = data.name || '';
            document.getElementById('profile-email').textContent = data.email || '';
            document.getElementById('profile-phone').textContent = data.phone || 'Not provided';
            document.getElementById('profile-address').textContent = data.address || 'Not provided';
            document.getElementById('profile-member-id').textContent = data.member_id || '';
            document.getElementById('profile-join-date').textContent = data.created_at ? new Date(data.created_at).toLocaleDateString() : '';
            
            // Update profile picture
            const profileImg = document.getElementById('profile-image');
            if (profileImg) {
                profileImg.src = data.full_photo_url || '/default-avatar.png';
            }
        }
    } catch (error) {
        console.error('Failed to load user profile data:', error);
    }
}

// Load edit profile data
async function loadEditProfileData() {
    try {
        const response = await API.get('/auth/profile');
        if (response.success) {
            const data = response.data;
            
            // Populate form fields
            document.getElementById('edit-name').value = data.name || '';
            document.getElementById('edit-email').value = data.email || '';
            document.getElementById('edit-phone').value = data.phone || '';
            document.getElementById('edit-address').value = data.address || '';
            document.getElementById('edit-date_of_birth').value = data.date_of_birth || '';
            
            // Update profile picture
            const profileImg = document.getElementById('edit-profile-image');
            if (profileImg) {
                profileImg.src = data.full_photo_url || '/default-avatar.png';
            }
        }
    } catch (error) {
        console.error('Failed to load edit profile data:', error);
    }
}

// Load donation data
async function loadDonationData() {
    try {
        const response = await fetch('/api/donation-info');
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
    }
}

// Initialize profile dropdown functionality
function initProfileDropdown() {
    const profileMenu = document.getElementById('profile-menu');
    const profileDropdown = document.getElementById('profile-dropdown');
    const notificationsBtn = document.getElementById('notifications-btn');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const viewIdCardDropdown = document.getElementById('view-id-card-dropdown');
    const editProfileDropdown = document.getElementById('edit-profile-dropdown');
    const logoutDropdown = document.getElementById('logout-dropdown');
    
    // Toggle profile dropdown on profile menu click
    if (profileMenu && profileDropdown) {
        profileMenu.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
            // Close notifications dropdown if open
            if (notificationsDropdown) {
                notificationsDropdown.classList.add('hidden');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileMenu.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    }
    
    // Toggle notifications dropdown
    if (notificationsBtn && notificationsDropdown) {
        notificationsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            notificationsDropdown.classList.toggle('hidden');
            // Close profile dropdown if open
            if (profileDropdown) {
                profileDropdown.classList.add('hidden');
            }
            // Load notifications when opened
            if (!notificationsDropdown.classList.contains('hidden')) {
                loadNotificationsList();
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationsBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });
    }
    
    // Main profile card buttons
    const editProfileMainBtn = document.getElementById('edit-profile-main');
    if (editProfileMainBtn) {
        // Remove any existing event listeners by cloning
        const newEditProfileMainBtn = editProfileMainBtn.cloneNode(true);
        editProfileMainBtn.parentNode.replaceChild(newEditProfileMainBtn, editProfileMainBtn);
        
        newEditProfileMainBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (window.location.pathname === '/home') {
                // On home page, show the edit profile section
                if (typeof showSection === 'function') {
                    showSection('edit-profile');
                }
            } else {
                // On edit profile page, submit the form
                document.getElementById('edit-profile-form').submit();
            }
        });
    }
}

    document.getElementById('view-id-card-main')?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        window.location.href = '/member/id-card';
    });
    
    document.getElementById('change-password-main')?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        showChangePasswordModal();
    });
    
    // Profile section buttons (consistent with main card)
    const editProfileBtn = document.getElementById('edit-profile');
    if (editProfileBtn) {
        console.log('Found edit profile button in app.js, setting up event listener');
        // Remove any existing event listeners by cloning
        const newEditProfileBtn = editProfileBtn.cloneNode(true);
        editProfileBtn.parentNode.replaceChild(newEditProfileBtn, editProfileBtn);
        
        newEditProfileBtn.addEventListener('click', (e) => {
            console.log('Edit profile button clicked in app.js');
            e.preventDefault();
            e.stopPropagation();
            if (window.location.pathname === '/home') {
                console.log('On home page, showing edit profile section');
                // On home page, show the edit profile section
                if (typeof showSection === 'function') {
                    console.log('showSection function found in app.js, calling it');
                    showSection('edit-profile');
                } else {
                    console.log('showSection function not found in app.js');
                }
            } else {
                console.log('Not on home page, redirecting to edit profile page');
                // On other pages, redirect to edit profile page
                window.location.href = '/member/edit-profile';
            }
        });
    } else {
        console.log('Edit profile button not found in app.js');
    }
    
    const viewIdCardBtn = document.getElementById('view-id-card');
    if (viewIdCardBtn) {
        // Remove any existing event listeners by cloning
        const newViewIdCardBtn = viewIdCardBtn.cloneNode(true);
        viewIdCardBtn.parentNode.replaceChild(newViewIdCardBtn, viewIdCardBtn);
        
        newViewIdCardBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = '/member/id-card';
        });
    }
    
    const changePasswordBtn = document.getElementById('change-password');
    if (changePasswordBtn) {
        // Remove any existing event listeners by cloning
        const newChangePasswordBtn = changePasswordBtn.cloneNode(true);
        changePasswordBtn.parentNode.replaceChild(newChangePasswordBtn, changePasswordBtn);
        
        newChangePasswordBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            showChangePasswordModal();
        });
    }
    
    // Handle edit profile from dropdown
    const editProfileDropdown = document.getElementById('edit-profile-dropdown');
    if (editProfileDropdown) {
        console.log('Found edit profile dropdown in app.js, setting up event listener');
        // Remove any existing event listeners by cloning
        const newEditProfileDropdown = editProfileDropdown.cloneNode(true);
        editProfileDropdown.parentNode.replaceChild(newEditProfileDropdown, editProfileDropdown);
        
        newEditProfileDropdown.addEventListener('click', (e) => {
            console.log('Edit profile dropdown button clicked in app.js');
            e.preventDefault();
            e.stopPropagation();
            if (window.location.pathname === '/home') {
                console.log('On home page, showing edit profile section from dropdown');
                // On home page, show the edit profile section
                if (typeof showSection === 'function') {
                    console.log('showSection function found in app.js, calling it from dropdown');
                    showSection('edit-profile');
                } else {
                    console.log('showSection function not found in app.js from dropdown');
                }
                const profileDropdown = document.getElementById('profile-dropdown');
                if (profileDropdown) {
                    profileDropdown.classList.add('hidden');
                }
            } else {
                console.log('Not on home page, redirecting to edit profile page from dropdown');
                // On other pages, redirect to edit profile page
                window.location.href = '/member/edit-profile';
            }
        });
    } else {
        console.log('Edit profile dropdown not found in app.js');
    }
    
    // Handle view ID card from dropdown
    const viewIdCardDropdown = document.getElementById('view-id-card-dropdown');
    if (viewIdCardDropdown) {
        viewIdCardDropdown.addEventListener('click', () => {
            window.location.href = '/member/id-card';
            const profileDropdown = document.getElementById('profile-dropdown');
            if (profileDropdown) {
                profileDropdown.classList.add('hidden');
            }
        });
    }
    
    // Handle logout from dropdown
    const logoutDropdown = document.getElementById('logout-dropdown');
    if (logoutDropdown) {
        logoutDropdown.addEventListener('click', async () => {
            try {
                await API.post('/auth/logout');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                AuthManager.removeToken();
                window.location.href = '/login';
            }
        });
    }
    
    // Handle logout from profile section
    document.getElementById('logout-btn')?.addEventListener('click', async () => {
        try {
            await API.post('/auth/logout');
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            AuthManager.removeToken();
            window.location.href = '/login';
        }
    });
    
    // Handle logout from profile section
    document.getElementById('logout-btn')?.addEventListener('click', async () => {
        try {
            await API.post('/auth/logout');
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            AuthManager.removeToken();
            window.location.href = '/login';
        }
    });

// Start the application only on home page
if (window.location.pathname === '/home') {
    console.log('Initializing home page app');
    initHomePage();
    
    // Test if showSection is accessible
    setTimeout(() => {
        if (typeof showSection === 'function') {
            console.log('showSection function is accessible');
        } else {
            console.log('showSection function is NOT accessible');
        }
    }, 2000);
}

// Check authentication on page load (only for member pages)
console.log('=== AUTH CHECK DEBUG ===');
console.log('Current pathname:', window.location.pathname);
console.log('Is admin page:', window.location.pathname.startsWith('/admin'));
console.log('Is authenticated:', AuthManager.isAuthenticated());
console.log('Is public page:', ['/', '/about', '/team', '/mission-vision', '/login', '/register', '/test', '/debug-optimizations', '/privacy-policy','/safety-standards', '/news'].includes(window.location.pathname) || window.location.pathname.startsWith('/news/') && window.location.pathname !== '/news/create');

if (window.location.pathname.startsWith('/admin')) {
    console.log('Skipping auth check for admin page');
    // Skip member authentication check for admin pages
} else if (!AuthManager.isAuthenticated() && !(['/', '/about', '/team', '/mission-vision', '/login', '/register', '/test', '/debug-optimizations', '/privacy-policy','/safety-standards', '/news'].includes(window.location.pathname) || (window.location.pathname.startsWith('/news/') && window.location.pathname !== '/news/create'))) {
    console.log('Redirecting to login page');
    window.location.href = '/login';
} else {
    console.log('No redirect needed');
}

// Generate avatar URL helper
function generateAvatarUrl(name) {
    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    return `data:image/svg+xml,${encodeURIComponent(`
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <rect width="100" height="100" fill="#3b82f6"/>
            <text x="50" y="50" text-anchor="middle" dy=".3em" fill="white" font-family="Arial" font-size="40">${initials}</text>
        </svg>
    `)}`;
}


