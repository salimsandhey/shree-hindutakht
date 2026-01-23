@extends('admin.layouts.admin')

@section('title', 'Post Management - Shree Hindutak Admin')

@section('content')
<!-- Page Title -->
<div class="p-4 border-b border-gray-200">
    <h1 class="text-xl font-bold text-gray-800">Post Management</h1>
    <div class="flex justify-between items-center mt-2">
        <p class="text-gray-600 text-sm">Create and manage posts</p>
        <button onclick="openCreatePostModal()" class="btn-primary px-3 py-1 text-sm">
            Create Post
        </button>
    </div>
</div>

<!-- Content -->
<div class="p-4 space-y-4">
    <!-- Posts List -->
    <div id="posts-list" class="space-y-4">
        <div class="text-center py-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
            <p class="text-gray-500 mt-2">Loading posts...</p>
        </div>
    </div>
    
    <!-- Pagination -->
    <div id="posts-pagination" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
        <button id="prev-posts-page" class="btn-secondary px-4 py-2 text-sm hidden">Previous</button>
        <span id="posts-page-info" class="text-sm text-gray-600"></span>
        <button id="next-posts-page" class="btn-secondary px-4 py-2 text-sm hidden">Next</button>
    </div>
</div>

<!-- Create Post Modal -->
<div id="create-post-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Create New Post</h3>
                <button onclick="closeCreatePostModal()" class="text-gray-400 hover:text-gray-500" type="button">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="create-post-form" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-4">
                        <label for="post-content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea id="post-content" name="content" rows="4" class="input-field" placeholder="What would you like to share?"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Media (Optional)</label>
                        <div class="mt-1 flex justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="post-media" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-orange-600">
                                        <span>Upload files</span>
                                        <input id="post-media" name="media" type="file" class="sr-only" multiple accept="image/*,video/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, MP4 up to 50MB</p>
                            </div>
                        </div>
                        <!-- Image preview container -->
                        <div id="image-preview-container" class="mt-4 grid grid-cols-2 gap-2 hidden">
                            <!-- Preview images will be dynamically added here -->
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCreatePostModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm" id="create-post-submit-btn">
                            Create Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Post Modal -->
<div id="edit-post-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Edit Post</h3>
                <button onclick="closeEditPostModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="edit-post-form">
                    <input type="hidden" id="edit-post-id">
                    <div class="mb-4">
                        <label for="edit-post-content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea id="edit-post-content" rows="4" class="input-field" placeholder="Edit your post content"></textarea>
                    </div>
                    <!-- Existing images container -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Existing Media</label>
                        <div id="existing-media-container" class="grid grid-cols-2 gap-2">
                            <!-- Existing media will be populated here -->
                        </div>
                    </div>
                    <!-- Add new media -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Add New Media (Optional)</label>
                        <div class="mt-1 flex justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="edit-post-media" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-orange-600">
                                        <span>Upload files</span>
                                        <input id="edit-post-media" name="media" type="file" class="sr-only" multiple accept="image/*,video/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, MP4 up to 50MB</p>
                            </div>
                        </div>
                        <!-- New image preview container -->
                        <div id="edit-image-preview-container" class="mt-4 grid grid-cols-2 gap-2 hidden">
                            <!-- Preview images will be dynamically added here -->
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditPostModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm">
                            Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Post Details Modal -->
<div id="post-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Post Details</h3>
                <button onclick="closePostDetailsModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4" id="post-details-content">
                <!-- Post details will be loaded here -->
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="text-gray-500 mt-4">Loading post details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Confirm Action</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-700 mb-4" id="confirmation-message">Are you sure you want to proceed?</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmationModal()" class="btn-secondary px-4 py-2 text-sm">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmAction()" class="btn-primary px-4 py-2 text-sm" id="confirm-button">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2" style="z-index: 9999;"></div>

<script>
// Check authentication on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!AdminAuthManager.isAuthenticated()) {
        window.location.href = '/admin/login';
        return;
    }
    
    // Load posts when page is ready
    loadPosts();
    
    // Completely rewritten form submission setup with single event listener attachment
    setupFormSubmissions();
});

// Setup form submissions with complete duplicate prevention
function setupFormSubmissions() {
    console.log('🔄 Setting up form submissions with duplicate prevention');
    
    // Handle create post form with complete duplicate prevention
    const createForm = document.getElementById('create-post-form');
    if (createForm) {
        // Remove any existing event listeners by cloning
        const newForm = createForm.cloneNode(true);
        createForm.parentNode.replaceChild(newForm, createForm);
        
        // Attach single event listener with capture phase
        newForm.addEventListener('submit', handleCreatePostSubmit, true);
        console.log('✅ Create post form submission handler attached');
    }
    
    // Handle edit post form with complete duplicate prevention
    const editForm = document.getElementById('edit-post-form');
    if (editForm) {
        // Remove any existing event listeners by cloning
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        // Attach single event listener with capture phase
        newEditForm.addEventListener('submit', handleEditPostSubmit, true);
        console.log('✅ Edit post form submission handler attached');
    }
}

// Global submission tracking for admin forms
const adminSubmissionTracker = new Map();

// Handle create post submission with complete duplicate prevention
async function handleCreatePostSubmit(e) {
    // Prevent all default actions and stop propagation
    e.preventDefault();
    e.stopImmediatePropagation();
    
    const formKey = 'create_post';
    
    // Check if already processing
    if (adminSubmissionTracker.has(formKey)) {
        console.log('⚠️ Create post submission already in progress');
        return;
    }
    
    // Set processing flag
    adminSubmissionTracker.set(formKey, true);
    console.log('✅ Starting create post submission');
    
    // Get form elements
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const contentInput = document.getElementById('post-content');
    const mediaInput = document.getElementById('post-media');
    
    if (!contentInput) {
        adminSubmissionTracker.delete(formKey);
        return;
    }
    
    const content = contentInput.value.trim();
    
    // Validate content
    if (!content) {
        Toast.error('Please enter some content for the post');
        adminSubmissionTracker.delete(formKey);
        return;
    }
    
    // Store original button state and disable button
    let originalButtonText = 'Create Post';
    if (submitButton) {
        originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...</span>';
    }
    
    try {
        let response;
        
        // Check if we have media files to upload
        if (mediaInput && mediaInput.files.length > 0) {
            // Handle file upload with complete duplicate prevention
            const formData = new FormData();
            formData.append('content', content);
            
            // Add all media files
            for (let i = 0; i < mediaInput.files.length; i++) {
                formData.append('media[]', mediaInput.files[i]);
            }
            
            response = await AdminAPI.postWithFiles('/posts', formData);
        } else {
            // Simple post without media
            response = await AdminAPI.post('/posts', { content });
        }
        
        // Handle response
        if (response.success) {
            // Close modal and reset form
            closeCreatePostModal();
            
            // Reload posts
            loadPosts(currentPostsPage);
            
            // Show success message
            Toast.success('Post created successfully!');
        } else {
            // Show error message
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        console.error('Error creating post:', error);
        Toast.error('Error creating post: ' + error.message);
    } finally {
        // Clear processing flag
        adminSubmissionTracker.delete(formKey);
        console.log('🔄 Completed create post submission');
        
        // Re-enable button and restore original text
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    }
}

// Handle edit post submission with complete duplicate prevention
async function handleEditPostSubmit(e) {
    // Prevent all default actions and stop propagation
    e.preventDefault();
    e.stopImmediatePropagation();
    
    const formKey = 'edit_post';
    
    // Check if already processing
    if (adminSubmissionTracker.has(formKey)) {
        console.log('⚠️ Edit post submission already in progress');
        return;
    }
    
    // Set processing flag
    adminSubmissionTracker.set(formKey, true);
    console.log('✅ Starting edit post submission');
    
    // Get form elements
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const postIdInput = document.getElementById('edit-post-id');
    const contentInput = document.getElementById('edit-post-content');
    const newMediaInput = document.getElementById('edit-post-media');
    
    if (!postIdInput || !contentInput) {
        adminSubmissionTracker.delete(formKey);
        return;
    }
    
    const postId = postIdInput.value;
    const content = contentInput.value.trim();
    
    // Validate content
    if (!content) {
        Toast.error('Please enter some content for the post');
        adminSubmissionTracker.delete(formKey);
        return;
    }
    
    // Store original button state and disable button
    let originalButtonText = 'Update Post';
    if (submitButton) {
        originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...</span>';
    }
    
    try {
        // Prepare form data
        const formData = new FormData();
        formData.append('content', content);
        
        // Add media to remove if any
        if (window.mediaToRemove && window.mediaToRemove.length > 0) {
            window.mediaToRemove.forEach((url, index) => {
                formData.append('remove_media[' + index + ']', url);
            });
        }
        
        // Add new media files if any
        if (newMediaInput && newMediaInput.files.length > 0) {
            for (let i = 0; i < newMediaInput.files.length; i++) {
                formData.append('media[' + i + ']', newMediaInput.files[i]);
            }
        }
        
        // Make API call with complete duplicate prevention
        const response = await AdminAPI.putWithFiles(`/posts/${postId}`, formData);
        
        // Handle response
        if (response.success) {
            // Close modal
            closeEditPostModal();
            
            // Reload posts
            loadPosts(currentPostsPage);
            
            // Show success message
            Toast.success('Post updated successfully!');
        } else {
            // Show error message
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        console.error('Error updating post:', error);
        Toast.error('Error updating post: ' + error.message);
    } finally {
        // Clear processing flag
        adminSubmissionTracker.delete(formKey);
        console.log('🔄 Completed edit post submission');
        
        // Re-enable button and restore original text
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    }
}

// Helper function to get event listeners (for debugging)
function getEventListeners(element) {
    // This is a browser-specific function, may not be available in all browsers
    if (typeof getEventListeners === 'function') {
        return getEventListeners(element);
    }
    return 'getEventListeners function not available';
}

// Current page state
let currentPostsPage = 1;

// Load posts
async function loadPosts(page = 1) {
    currentPostsPage = page;
    try {
        const response = await AdminAPI.get(`/posts?page=${page}`);
        if (response.success) {
            renderPosts(response.data);
        } else {
            document.getElementById('posts-list').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-500">Error loading posts: ${response.message}</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('posts-list').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-500">Error loading posts: ${error.message}</p>
            </div>
        `;
    }
}

// Render posts list
function renderPosts(data) {
    const posts = data.data;
    const listContainer = document.getElementById('posts-list');
    
    if (posts && posts.length > 0) {
        listContainer.innerHTML = posts.map(post => {
            // Format date
            const date = new Date(post.created_at);
            const formattedDate = date.toLocaleDateString();
            const formattedTime = date.toLocaleTimeString();
            
            // Create media HTML if post has media
            let mediaHtml = '';
            // Use post.media_urls which is generated by the model
            if (post.media_urls && post.media_urls.length > 0) {
                const mediaItems = post.media_urls.map((url, index) => {
                    const extension = url.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                        return `
                            <div class="mt-2">
                                <img src="${url}" class="w-full h-48 object-cover rounded-lg" alt="Post image" onerror="this.style.display='none'">
                            </div>
                        `;
                    } else if (['mp4', 'mov', 'avi', 'webm'].includes(extension)) {
                        return `
                            <div class="mt-2">
                                <video controls class="w-full h-48 rounded-lg">
                                    <source src="${url}" type="video/${extension}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        `;
                    }
                    return '';
                }).filter(item => item !== '').join('');
                
                if (mediaItems) {
                    mediaHtml = mediaItems;
                }
            }
            
            // Get author name
            const authorName = post.admin ? (post.admin.name || post.admin.username || 'Shree Hindutakht') : (post.member ? post.member.name : 'Unknown');
            
            // Create stats HTML
            const likesCount = post.likes_count || 0;
            const commentsCount = post.comments_count || 0;
            const sharesCount = post.shares_count || 0;
            
            return `
                <div class="card">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">${authorName}</h3>
                            <p class="text-xs text-gray-500">${formattedDate} at ${formattedTime}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            ${post.type}
                        </span>
                    </div>
                    <p class="text-gray-700 mb-3">${post.content}</p>
                    ${mediaHtml}
                    <div class="flex justify-between text-sm text-gray-500 mt-3 pt-3 border-t border-gray-100">
                        <span>${likesCount} likes</span>
                        <span>${commentsCount} comments</span>
                        <span>${sharesCount} shares</span>
                    </div>
                    <div class="flex space-x-2 mt-3">
                        <button onclick="viewPostDetails(${post.id})" class="btn-secondary flex-1 text-sm py-1">Details</button>
                        <button onclick="editPost(${post.id})" class="btn-secondary flex-1 text-sm py-1">Edit</button>
                        <button onclick="deletePost(${post.id})" class="btn-secondary flex-1 text-sm py-1 text-red-600">Delete</button>
                    </div>
                </div>
            `;
        }).join('');
        
        updatePostsPagination(data);
    } else {
        listContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-gray-500">No posts found</p>
            </div>
        `;
    }
}

// Update pagination
function updatePostsPagination(data) {
    const pageInfo = document.getElementById('posts-page-info');
    const prevBtn = document.getElementById('prev-posts-page');
    const nextBtn = document.getElementById('next-posts-page');
    
    if (data.total > 0) {
        pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
    } else {
        pageInfo.textContent = 'No posts found';
    }
    
    // Show/hide pagination buttons
    if (data.prev_page_url) {
        prevBtn.classList.remove('hidden');
        prevBtn.onclick = () => loadPosts(data.current_page - 1);
    } else {
        prevBtn.classList.add('hidden');
    }
    
    if (data.next_page_url) {
        nextBtn.classList.remove('hidden');
        nextBtn.onclick = () => loadPosts(data.current_page + 1);
    } else {
        nextBtn.classList.add('hidden');
    }
}

// Global variables for confirmation modal
let confirmationCallback = null;

// Delete post with confirmation modal
async function deletePost(postId) {
    // Set the confirmation message
    document.getElementById('confirmation-message').textContent = 'Are you sure you want to delete this post? This action cannot be undone.';
    
    // Set the callback function
    confirmationCallback = async function() {
        try {
            const response = await AdminAPI.delete(`/posts/${postId}`);
            if (response.success) {
                // Reload the current page of posts
                loadPosts(currentPostsPage);
                closeConfirmationModal();
                Toast.success('Post deleted successfully!');
            } else {
                closeConfirmationModal();
                Toast.error('Error: ' + response.message);
            }
        } catch (error) {
            closeConfirmationModal();
            Toast.error('Error deleting post: ' + error.message);
        }
    };
    
    // Show the confirmation modal
    document.getElementById('confirmation-modal').classList.remove('hidden');
}

// Close confirmation modal
function closeConfirmationModal() {
    document.getElementById('confirmation-modal').classList.add('hidden');
    confirmationCallback = null;
}

// Confirm action handler
function confirmAction() {
    if (confirmationCallback) {
        confirmationCallback();
    }
}

// Toast notification system
const Toast = {
    // Create a toast notification
    show: function(message, type = 'info', duration = 3000) {
        const toastContainer = document.getElementById('toast-container');
        
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastElement = document.createElement('div');
        toastElement.id = toastId;
        toastElement.className = `transform transition-all duration-300 ease-in-out max-w-md w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden`;
        
        // Set styles based on type
        let bgColor = 'bg-blue-500';
        let textColor = 'text-white';
        
        switch(type) {
            case 'success':
                bgColor = 'bg-green-500';
                break;
            case 'error':
                bgColor = 'bg-red-500';
                break;
            case 'warning':
                bgColor = 'bg-yellow-500';
                textColor = 'text-gray-900';
                break;
            case 'info':
            default:
                bgColor = 'bg-blue-500';
                break;
        }
        
        toastElement.innerHTML = `
            <div class="p-4 ${bgColor} ${textColor}">
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button id="close-${toastId}" class="rounded-md inline-flex text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add to container
        toastContainer.appendChild(toastElement);
        
        // Add close event
        document.getElementById(`close-${toastId}`).addEventListener('click', () => {
            this.hide(toastId);
        });
        
        // Auto hide after duration
        if (duration > 0) {
            setTimeout(() => {
                this.hide(toastId);
            }, duration);
        }
        
        // Trigger enter animation
        setTimeout(() => {
            toastElement.classList.add('translate-x-0', 'opacity-100');
        }, 10);
        
        return toastId;
    },
    
    // Hide a toast notification
    hide: function(toastId) {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            toastElement.classList.remove('translate-x-0', 'opacity-100');
            toastElement.classList.add('translate-x-full', 'opacity-0');
            
            // Remove element after animation
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300);
        }
    },
    
    // Show success toast
    success: function(message, duration = 3000) {
        return this.show(message, 'success', duration);
    },
    
    // Show error toast
    error: function(message, duration = 5000) {
        return this.show(message, 'error', duration);
    },
    
    // Show warning toast
    warning: function(message, duration = 0) {
        return this.show(message, 'warning', duration);
    },
    
    // Show info toast
    info: function(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
};

// View post details (show all details on same page)
async function viewPostDetails(postId) {
    try {
        // Show the details modal with loading state
        const modal = document.getElementById('post-details-modal');
        const content = document.getElementById('post-details-content');
        
        // Set loading state
        content.innerHTML = `
            <div class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                <p class="text-gray-500 mt-4">Loading post details...</p>
            </div>
        `;
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Fetch full post details
        const response = await AdminAPI.get(`/posts/${postId}`);
        if (response.success) {
            const post = response.data;
            
            // Format date
            const date = new Date(post.created_at);
            const formattedDate = date.toLocaleDateString();
            const formattedTime = date.toLocaleTimeString();
            
            // Create media HTML if post has media
            let mediaHtml = '';
            // Use post.media_urls which is generated by the model
            if (post.media_urls && post.media_urls.length > 0) {
                const mediaItems = post.media_urls.map((url, index) => {
                    const extension = url.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                        return `
                            <div class="mt-2">
                                <img src="${url}" class="w-full h-64 object-cover rounded-lg" alt="Post image" onerror="this.style.display='none'">
                            </div>
                        `;
                    } else if (['mp4', 'mov', 'avi', 'webm'].includes(extension)) {
                        return `
                            <div class="mt-2">
                                <video controls class="w-full h-64 rounded-lg">
                                    <source src="${url}" type="video/${extension}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        `;
                    }
                    return '';
                }).filter(item => item !== '').join('');
                
                if (mediaItems) {
                    mediaHtml = mediaItems;
                }
            }
            
            // Get author name
            const authorName = post.admin ? (post.admin.name || post.admin.username || 'Shree Hindutakht') : (post.member ? post.member.name : 'Unknown');
            
            // Create stats HTML
            const likesCount = post.likes_count || 0;
            const commentsCount = post.comments_count || 0;
            const sharesCount = post.shares_count || 0;
            
            // Render post details
            content.innerHTML = `
                <div class="card">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">${authorName}</h3>
                            <p class="text-sm text-gray-500">${formattedDate} at ${formattedTime}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            ${post.type}
                        </span>
                    </div>
                    <p class="text-gray-700 mb-4">${post.content}</p>
                    ${mediaHtml}
                    <div class="flex justify-between text-sm text-gray-500 mt-4 pt-4 border-t border-gray-100">
                        <span>${likesCount} likes</span>
                        <span>${commentsCount} comments</span>
                        <span>${sharesCount} shares</span>
                    </div>
                    <div class="mt-4">
                        <h4 class="font-medium text-gray-900 mb-2">Post Statistics</h4>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">${likesCount}</div>
                                <div class="text-sm text-gray-600">Likes</div>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">${commentsCount}</div>
                                <div class="text-sm text-gray-600">Comments</div>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg">
                                <div class="text-xl font-bold text-purple-600">${sharesCount}</div>
                                <div class="text-sm text-gray-600">Shares</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 mt-4">
                    <button onclick="closePostDetailsModal()" class="btn-secondary flex-1 py-2 text-sm">
                        Close
                    </button>
                    <button onclick="editPost(${post.id})" class="btn-primary flex-1 py-2 text-sm">
                        Edit Post
                    </button>
                    <button onclick="deletePost(${post.id})" class="btn-secondary py-2 text-sm text-red-600">
                        Delete
                    </button>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-red-500">Error loading post details: ${response.message}</p>
                </div>
                <div class="mt-4 text-center">
                    <button onclick="closePostDetailsModal()" class="btn-secondary px-4 py-2 text-sm">
                        Close
                    </button>
                </div>
            `;
        }
    } catch (error) {
        const content = document.getElementById('post-details-content');
        content.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-red-500">Error loading post details: ${error.message}</p>
            </div>
            <div class="mt-4 text-center">
                <button onclick="closePostDetailsModal()" class="btn-secondary px-4 py-2 text-sm">
                    Close
                </button>
            </div>
        `;
    }
}

// Edit post
async function editPost(postId) {
    try {
        // First close any open modals
        closePostDetailsModal();
        
        // Fetch post data
        const response = await AdminAPI.get(`/posts/${postId}`);
        if (response.success) {
            const post = response.data;
            
            // Populate edit form
            document.getElementById('edit-post-id').value = post.id;
            document.getElementById('edit-post-content').value = post.content;
            
            // Store the original post data for reference
            window.currentEditingPost = post;
            
            // Populate existing media
            populateExistingMedia(post);
            
            // Show edit modal
            const modal = document.getElementById('edit-post-modal');
            modal.classList.remove('hidden');
        } else {
            alert('Error loading post: ' + response.message);
        }
    } catch (error) {
        alert('Error loading post: ' + error.message);
    }
}

// Populate existing media in the edit form
function populateExistingMedia(post) {
    const container = document.getElementById('existing-media-container');
    if (!container) return;
    
    // Clear existing content
    container.innerHTML = '';
    
    // Store media to remove
    window.mediaToRemove = [];
    
    // Check if post has media
    if (post.media_urls && post.media_urls.length > 0) {
        post.media_urls.forEach((url, index) => {
            const extension = url.split('.').pop().toLowerCase();
            
            // Extract the path from the URL for storage
            // Assuming the URL format is http://domain/storage/path/to/file
            const pathFromUrl = url.replace(window.location.origin + '/storage/', '');
            
            // Create media element based on file type
            let mediaElement = '';
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                mediaElement = `
                    <div class="relative group" data-media-index="${index}">
                        <img src="${url}" class="w-full h-32 object-cover rounded-lg" alt="Post image">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center" onclick="toggleMediaRemoval(this, ${index}, '${url}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="hidden" name="existing_media[]" value="${pathFromUrl}" data-media-url="${url}">
                    </div>
                `;
            } else if (['mp4', 'mov', 'avi', 'webm'].includes(extension)) {
                mediaElement = `
                    <div class="relative group" data-media-index="${index}">
                        <video class="w-full h-32 rounded-lg">
                            <source src="${url}" type="video/${extension}">
                            Your browser does not support the video tag.
                        </video>
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center" onclick="toggleMediaRemoval(this, ${index}, '${url}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="hidden" name="existing_media[]" value="${pathFromUrl}" data-media-url="${url}">
                    </div>
                `;
            }
            
            container.innerHTML += mediaElement;
        });
    } else {
        container.innerHTML = '<p class="text-gray-500 text-sm">No existing media</p>';
    }
}

// Toggle media removal (mark for removal instead of immediate removal)
function toggleMediaRemoval(button, index, url) {
    console.log('toggleMediaRemoval called with:', { button, index, url });
    
    const mediaElement = button.closest('.relative');
    console.log('mediaElement:', mediaElement);
    
    // Check if already marked for removal
    const isMarked = mediaElement.classList.contains('opacity-50');
    console.log('isMarked:', isMarked);
    
    if (isMarked) {
        // Unmark for removal
        console.log('Unmarking media for removal');
        mediaElement.classList.remove('opacity-50');
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        `;
        
        // Remove from mediaToRemove array
        if (window.mediaToRemove) {
            console.log('Before removal:', window.mediaToRemove);
            window.mediaToRemove = window.mediaToRemove.filter(item => item !== url);
            console.log('After removal:', window.mediaToRemove);
        }
    } else {
        // Mark for removal
        console.log('Marking media for removal');
        mediaElement.classList.add('opacity-50');
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
        
        // Add to mediaToRemove array
        if (!window.mediaToRemove) {
            window.mediaToRemove = [];
        }
        console.log('Before adding:', window.mediaToRemove);
        if (!window.mediaToRemove.includes(url)) {
            window.mediaToRemove.push(url);
        }
        console.log('After adding:', window.mediaToRemove);
    }
}

// Handle new media file selection in edit form
function handleEditMediaChange(e) {
    showEditImagePreviews(e.target.files);
}

// Function to show image previews for edit form
function showEditImagePreviews(files) {
    const previewContainer = document.getElementById('edit-image-preview-container');
    if (!previewContainer) return;
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (files.length === 0) {
        previewContainer.classList.add('hidden');
        return;
    }
    
    // Show the preview container
    previewContainer.classList.remove('hidden');
    
    // Create previews for each file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check if file is an image
        if (!file.type.match('image.*')) {
            continue;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewElement = document.createElement('div');
            previewElement.className = 'relative';
            
            previewElement.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg" alt="Preview">
                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs" onclick="removeEditImagePreview(this)">
                    ×
                </button>
            `;
            
            previewContainer.appendChild(previewElement);
        };
        
        reader.readAsDataURL(file);
    }
}

// Function to remove a specific image preview in edit form
function removeEditImagePreview(button) {
    const previewElement = button.parentElement;
    previewElement.remove();
    
    // If no previews left, hide container
    const previewContainer = document.getElementById('edit-image-preview-container');
    if (previewContainer && previewContainer.children.length === 0) {
        previewContainer.classList.add('hidden');
    }
}

// Function to show image previews
function showImagePreviews(files) {
    const previewContainer = document.getElementById('image-preview-container');
    if (!previewContainer) return;
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (files.length === 0) {
        previewContainer.classList.add('hidden');
        return;
    }
    
    // Show the preview container
    previewContainer.classList.remove('hidden');
    
    // Create previews for each file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check if file is an image
        if (!file.type.match('image.*')) {
            continue;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewElement = document.createElement('div');
            previewElement.className = 'relative';
            
            previewElement.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg" alt="Preview">
                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs" onclick="removeImagePreview(this)">
                    ×
                </button>
            `;
            
            previewContainer.appendChild(previewElement);
        };
        
        reader.readAsDataURL(file);
    }
}

// Function to remove a specific image preview
function removeImagePreview(button) {
    const previewElement = button.parentElement;
    previewElement.remove();
    
    // If no previews left, hide container
    const previewContainer = document.getElementById('image-preview-container');
    if (previewContainer && previewContainer.children.length === 0) {
        previewContainer.classList.add('hidden');
    }
}

// Function to clear image previews
function clearImagePreviews() {
    const previewContainer = document.getElementById('image-preview-container');
    if (previewContainer) {
        previewContainer.innerHTML = '';
        previewContainer.classList.add('hidden');
    }
    
    const fileInput = document.getElementById('post-media');
    if (fileInput) {
        fileInput.value = '';
    }
}

// Modal functions
function openCreatePostModal() {
    const modal = document.getElementById('create-post-modal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Create post modal opened');
    }
}

function closeCreatePostModal() {
    const modal = document.getElementById('create-post-modal');
    if (modal) {
        modal.classList.add('hidden');
        const form = document.getElementById('create-post-form');
        if (form) {
            form.reset();
            // Clear image previews when closing modal
            clearImagePreviews();
            // Reset form submission state
            form.dataset.submitting = 'false';
            console.log('Create post modal closed and form reset');
        }
    }
}

function closeEditPostModal() {
    const modal = document.getElementById('edit-post-modal');
    if (modal) {
        modal.classList.add('hidden');
        // Clear new image previews when closing modal
        const previewContainer = document.getElementById('edit-image-preview-container');
        if (previewContainer) {
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
        }
        
        // Clear file input
        const fileInput = document.getElementById('edit-post-media');
        if (fileInput) {
            fileInput.value = '';
        }
        
        // Clear media to remove
        window.mediaToRemove = [];
    }
}

function closePostDetailsModal() {
    const modal = document.getElementById('post-details-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

</script>
@endsection