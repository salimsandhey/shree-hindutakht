@extends('layouts.member')

@section('title', 'Posts - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 mobile-container page-transition">
    <!-- Header -->
    <div class="bg-white text-gray-800 p-4 shadow-sm border-b border-gray-200 mobile-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="history.back()" class="text-gray-600 hover:text-primary transition-colors touch-target mobile-tap-highlight">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800 animate-fade-in-down">Community Posts</h1>
            </div>
            <!-- Refresh Button -->
            <button id="refresh-posts-btn" onclick="refreshPosts()" class="flex items-center space-x-1 bg-primary hover:bg-orange-600 text-white px-3 py-1 rounded-full text-sm transition-colors touch-target mobile-tap-highlight">
                <svg class="w-4 h-4 refresh-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="mobile-content">
        <!-- Posts Container -->
        <div id="posts-container" class="space-y-4">
            <div class="text-center py-8 animate-fade-in">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading posts...</p>
            </div>
        </div>

        <!-- Load More Button -->
        <div id="load-more-container" class="text-center mt-6 hidden animate-fade-in">
            <button id="load-more-btn" class="btn-secondary touch-target mobile-button mobile-tap-highlight">
                <span id="load-more-text">Load More</span>
                <span id="load-more-loading" class="hidden">Loading...</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let hasMorePosts = true;
let currentMember = null;
let isLoading = false;
let lastPostLoadTime = 0;
const POST_REFRESH_INTERVAL = 30000; // 30 seconds

// Load posts with pagination
async function loadPosts(page = 1, append = false) {
    // Prevent too frequent requests
    const now = Date.now();
    if (!append && (now - lastPostLoadTime) < 1000) {
        console.log('Skipping post load - too frequent');
        return;
    }
    lastPostLoadTime = now;
    
    if (isLoading) return;
    
    try {
        isLoading = true;
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        console.log('Loading posts, token exists:', !!token);
        
        if (!token) {
            console.log('No token found, redirecting to login');
            window.location.href = '/login';
            return;
        }

        // Add multiple cache-busting parameters to ensure fresh data
        const timestamp = Date.now();
        const random = Math.random();
        const cacheBuster = `&_t=${timestamp}&_r=${random}`;
        
        const response = await fetch(`/api/posts?page=${page}&per_page=10${cacheBuster}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            // Disable browser caching
            cache: 'no-cache'
        });

        if (!response.ok) {
            if (response.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('member');
                window.location.href = '/login';
                return;
            }
            throw new Error('Failed to load posts: ' + response.status + ' ' + response.statusText);
        }

        const data = await response.json();
        console.log('Posts data received:', data);
        
        if (data.success) {
            const container = document.getElementById('posts-container');
            
            if (!append) {
                container.innerHTML = '';
            }
            
            if (data.data.posts && data.data.posts.length > 0) {
                console.log('Creating', data.data.posts.length, 'post elements');
                data.data.posts.forEach((post, index) => {
                    console.log(`Creating post ${index + 1}:`, post.id, post.content.substring(0, 30));
                    const postElement = createPostElement(post);
                    container.appendChild(postElement);
                });
                
                // Update pagination info
                hasMorePosts = data.data.pagination.current_page < data.data.pagination.last_page;
                
                // Show/hide load more button
                const loadMoreContainer = document.getElementById('load-more-container');
                if (hasMorePosts) {
                    loadMoreContainer.classList.remove('hidden');
                } else {
                    loadMoreContainer.classList.add('hidden');
                }
            } else if (!append) {
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
        } else {
            throw new Error('API returned unsuccessful response: ' + JSON.stringify(data));
        }
    } catch (error) {
        console.error('Error loading posts:', error);
        const container = document.getElementById('posts-container');
        if (!append) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Failed to load posts: ${error.message}</p>
                    <button onclick="loadPosts()" class="btn-primary mt-4">Try Again</button>
                </div>
            `;
        }
    } finally {
        isLoading = false;
    }
}

// Create post element
function createPostElement(post) {
    const postDiv = document.createElement('div');
    postDiv.className = 'card';
    
    // Generate member avatar URL
    const memberPhoto = post.created_by_admin && post.admin?.photo ? 
        `/storage/${post.admin.photo}` : 
        (post.member?.photo ? 
            `/storage/${post.member.photo}` : 
            generateAvatarUrl(post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Unknown User')));
    
    // Create media HTML if post has media
    let mediaHtml = '';
    if (post.media_urls && post.media_urls.length > 0) {
        const mediaItems = post.media_urls.map((url, index) => {
            const extension = url.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                return `<img src="${url}" class="w-full h-64 object-contain rounded-lg cursor-pointer" alt="Post image" onclick="openImageGallery([${post.media_urls.map(u => `'${u}'`).join(',')}], ${index})">`;
            } else if (['mp4', 'mov', 'avi', 'webm'].includes(extension)) {
                return `<video controls class="w-full h-64 rounded-lg"><source src="${url}" type="video/${extension}">Your browser does not support the video tag.</video>`;
            }
            return '';
        }).filter(item => item !== '').join('');
        
        if (mediaItems) {
            mediaHtml = `
                <div class="mt-3 grid gap-2 ${post.media_urls.length > 1 ? 'grid-cols-2' : 'grid-cols-1'}">
                    ${mediaItems}
                    ${post.media_urls.length > 4 ? `<div class="relative cursor-pointer" onclick="openImageGallery([${post.media_urls.map(u => `'${u}'`).join(',')}], 4)"><img src="${post.media_urls[4]}" class="w-full h-64 object-cover rounded-lg"><div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-xl font-bold rounded-lg">+${post.media_urls.length - 4}</div></div>` : ''}
                </div>
            `;
        }
    }
    
    postDiv.innerHTML = `
        <div class="flex items-start space-x-3">
            <img src="${memberPhoto}" class="w-12 h-12 rounded-full object-cover" alt="${post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'User')} avatar">
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-2">
                    <h3 class="font-semibold text-gray-900">${post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht')}</h3>
                    ${post.created_by_admin ? '<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' : ''}
                    <span class="text-sm text-gray-500">${formatTimeAgo(post.created_at)}</span>
                    ${post.created_by_admin ? '<span class="bg-primary text-white text-xs px-2 py-1 rounded-full">Admin</span>' : ''}
                    ${post.is_pinned ? '<span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">📌</span>' : ''}
                </div>
                <p class="text-gray-700 mb-3 whitespace-pre-wrap">${post.content}</p>
                ${mediaHtml}
                
                <!-- Post Actions -->
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleLike(${post.id})" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors ${post.is_liked ? 'text-red-500' : ''}" id="like-btn-${post.id}">
                            <svg class="w-5 h-5" fill="${post.is_liked ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="like-count-${post.id}">${post.likes_count || 0}</span>
                        </button>
                        
                        <button onclick="toggleComments(${post.id})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>${post.comments_count || 0}</span>
                        </button>
                        
                        <button onclick="sharePost(${post.id})" class="flex items-center space-x-1 text-gray-600 hover:text-green-500 transition-colors">
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
                            <input type="text" id="comment-input-${post.id}" placeholder="Write a comment..." class="flex-1 px-3 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <button onclick="addComment(${post.id})" class="bg-primary text-white px-4 py-2 rounded-full text-sm hover:bg-orange-600 transition-colors">Post</button>
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

// Toggle like
async function toggleLike(postId) {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        const response = await fetch(`/api/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const likeBtn = document.getElementById(`like-btn-${postId}`);
                const likeCount = document.getElementById(`like-count-${postId}`);
                const svg = likeBtn.querySelector('svg');
                
                if (data.data.is_liked) {
                    likeBtn.classList.add('text-red-500');
                    likeBtn.classList.remove('text-gray-600');
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    likeBtn.classList.remove('text-red-500');
                    likeBtn.classList.add('text-gray-600');
                    svg.setAttribute('fill', 'none');
                }
                
                likeCount.textContent = data.data.likes_count;
            }
        }
    } catch (error) {
        console.error('Error toggling like:', error);
    }
}

// Toggle comments visibility
function toggleComments(postId) {
    console.log('toggleComments function called with postId:', postId);
    const commentsSection = document.getElementById(`comments-${postId}`);
    console.log('Found comments section:', commentsSection);
    
    if (commentsSection) {
        if (commentsSection.classList.contains('hidden')) {
            console.log('Opening comments section');
            commentsSection.classList.remove('hidden');
            loadComments(postId);
        } else {
            console.log('Closing comments section');
            commentsSection.classList.add('hidden');
        }
    } else {
        console.error('Comments section not found for post:', postId);
        // Let's check if the element exists with a different approach
        const allElements = document.querySelectorAll('[id*="comments-"]');
        console.log('All comment-related elements found:', allElements);
    }
}

// Load comments for a post
async function loadComments(postId) {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        
        const response = await fetch(`/api/posts/${postId}/comments`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const commentsList = document.getElementById(`comments-list-${postId}`);
                commentsList.innerHTML = '';
                
                if (data.data.data && data.data.data.length > 0) {
                    data.data.data.forEach(comment => {
                        const commentElement = createCommentElement(comment);
                        commentsList.appendChild(commentElement);
                    });
                } else {
                    commentsList.innerHTML = '<p class="text-gray-500 text-sm py-2">No comments yet. Be the first to comment!</p>';
                }
            }
        } else {
            console.error('Failed to load comments:', response.status);
        }
    } catch (error) {
        console.error('Error loading comments:', error);
    }
}

// Create comment element
function createCommentElement(comment) {
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
    
    return commentDiv;
}

// Add comment
async function addComment(postId) {
    const input = document.getElementById(`comment-input-${postId}`);
    const comment = input.value.trim();
    
    if (!comment) return;
    
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        const response = await fetch(`/api/posts/${postId}/comment`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment })
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                input.value = '';
                loadComments(postId); // Reload comments
                
                // Update comment count in the post
                const commentBtn = document.querySelector(`#comments-${postId}`).parentElement.querySelector('[onclick*="toggleComments"]');
                const countSpan = commentBtn.querySelector('span');
                countSpan.textContent = parseInt(countSpan.textContent) + 1;
            }
        }
    } catch (error) {
        console.error('Error adding comment:', error);
    }
}

// Share post
async function sharePost(postId) {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
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
                    alert('Link copied to clipboard!');
                }
            }
        }
    } catch (error) {
        console.error('Error sharing post:', error);
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

// Initialize posts page
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
    if (!token) {
        alert('You are not logged in. Redirecting to login page.');
        window.location.href = '/login';     
        return;
    }
    
    console.log('Starting to load posts...');
    loadPosts();
    
    // Set up periodic refresh every 30 seconds
    setInterval(() => {
        // Only refresh if we're on the first page and not loading
        if (currentPage === 1 && !isLoading) {
            console.log('Auto-refreshing posts...');
            loadPosts(1, false);
        }
    }, POST_REFRESH_INTERVAL);
    
    // Set up load more button event listener
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            if (isLoading) return;
            
            currentPage++;
            loadPosts(currentPage, true);

            const loadMoreText = document.getElementById('load-more-text');
            const loadMoreLoading = document.getElementById('load-more-loading');

            loadMoreText.classList.add('hidden');
            loadMoreLoading.classList.remove('hidden');

            setTimeout(() => {
                loadMoreText.classList.remove('hidden');
                loadMoreLoading.classList.add('hidden');
            }, 1000);
        });
    }
});

// Refresh posts function - clears cache and fetches latest posts
async function refreshPosts() {
    const refreshBtn = document.getElementById('refresh-posts-btn');
    if (!refreshBtn) {
        console.error('Refresh button not found');
        return;
    }
    
    const refreshIcon = refreshBtn.querySelector('.refresh-icon');
    const buttonText = refreshBtn.querySelector('span');
    
    if (!refreshIcon || !buttonText) {
        console.error('Refresh button elements not found');
        return;
    }
    
    // Store original content
    const originalText = buttonText.textContent;
    
    // Show loading state
    refreshIcon.classList.add('spinning');
    refreshBtn.disabled = true;
    buttonText.textContent = 'Refreshing...';
    
    try {
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
        localStorage.removeItem('posts_cache');
        localStorage.removeItem('posts_last_fetch');
        console.log('Local storage cache cleared');
        
        // Reset pagination
        currentPage = 1;
        hasMorePosts = true;
        
        // Force reload posts with cache busting
        console.log('Reloading posts...');
        await loadPosts(1, false);
        console.log('Posts reloaded successfully');
        
        // Show success message
        showMessage('Posts refreshed successfully!', 'success');
    } catch (error) {
        console.error('Error refreshing posts:', error);
        showMessage('Failed to refresh posts. Please try again.', 'error');
    } finally {
        // Restore button after a short delay
        setTimeout(() => {
            refreshIcon.classList.remove('spinning');
            buttonText.textContent = originalText;
            refreshBtn.disabled = false;
        }, 1000);
    }
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

// Add CSS for spinning refresh icon
const style = document.createElement('style');
style.textContent = `
    .spinning {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
