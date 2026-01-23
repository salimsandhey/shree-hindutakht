@extends('admin.layouts.admin')

@section('title', 'News Management - Shree Hindutakht Admin')

@section('content')
<!-- Page Title -->
<div class="p-4 border-b border-gray-200">
    <h1 class="text-xl font-bold text-gray-800">News Management</h1>
    <div class="flex justify-between items-center mt-2">
        <p class="text-gray-600 text-sm">Create and manage news articles</p>
        <button onclick="openCreateNewsModal()" class="btn-primary px-3 py-1 text-sm">
            Create News
        </button>
    </div>
</div>

<!-- Content -->
<div class="p-4 space-y-4">
    <!-- News List -->
    <div id="news-list" class="space-y-4">
        <div class="text-center py-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
            <p class="text-gray-500 mt-2">Loading news...</p>
        </div>
    </div>
    
    <!-- Pagination -->
    <div id="news-pagination" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
        <button id="prev-news-page" class="btn-secondary px-4 py-2 text-sm hidden">Previous</button>
        <span id="news-page-info" class="text-sm text-gray-600"></span>
        <button id="next-news-page" class="btn-secondary px-4 py-2 text-sm hidden">Next</button>
    </div>
</div>

<!-- Create/Edit News Modal -->
<div id="news-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 bg-white rounded-md shadow-lg">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="news-modal-title" class="text-lg font-semibold">Create News</h3>
                <button onclick="closeNewsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="news-form" enctype="multipart/form-data">
                    <input type="hidden" id="news-id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" id="news-title" name="title" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="news-category" name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="general">General</option>
                                <option value="events">Events</option>
                                <option value="spiritual">Spiritual</option>
                                <option value="charity">Charity</option>
                                <option value="education">Education</option>
                                <option value="community">Community</option>
                                <option value="infrastructure">Infrastructure</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                        <textarea id="news-content" name="content" rows="6" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                            <input type="datetime-local" id="news-published-at" name="published_at"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="news-featured" name="featured" value="1"
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label class="text-sm font-medium text-gray-700">Featured News</label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="news-status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Media Files</label>
                        <input type="file" id="news-media" name="media[]" multiple
                               accept="image/*,video/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Select multiple images or videos (Max 10 files)</p>
                    </div>
                    
                    <div id="current-media" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Media</label>
                        <div id="current-media-list" class="grid grid-cols-2 md:grid-cols-3 gap-2"></div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeNewsModal()" class="btn-secondary px-4 py-2">
                            Cancel
                        </button>
                        <button type="submit" id="news-submit-btn" class="btn-primary px-4 py-2">
                            <span id="news-submit-text">Save News</span>
                            <span id="news-submit-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- News Details Modal -->
<div id="news-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 bg-white rounded-md shadow-lg">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="news-details-title" class="text-lg font-semibold">News Details</h3>
                <button onclick="closeNewsDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4" id="news-details-content">
                <!-- News details will be loaded here -->
            </div>
            <div class="flex justify-end pt-4 border-t">
                <button type="button" onclick="closeNewsDetailsModal()" class="btn-primary px-4 py-2">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentNewsPage = 1;

// Load news
async function loadNews(page = 1) {
    currentNewsPage = page;
    try {
        const response = await AdminAPI.getNews(page);
        if (response.success) {
            renderNews(response.data);
        } else {
            document.getElementById('news-list').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-500">Error loading news: ${response.message}</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('news-list').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-500">Error loading news: ${error.message}</p>
            </div>
        `;
    }
}

// Render news list
function renderNews(data) {
    const news = data.data;
    const listContainer = document.getElementById('news-list');
    
    if (news && news.length > 0) {
        listContainer.innerHTML = news.map(item => {
            // Format date
            const date = new Date(item.published_at);
            const formattedDate = date.toLocaleDateString();
            
            // Format views count
            const viewsCount = item.views_count?.toLocaleString() || '0';
            
            return `
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${item.title}</h4>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">${item.content.replace(/<[^>]*>/g, '')}</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span class="bg-gray-100 px-2 py-1 rounded">${item.category}</span>
                                <span>${formattedDate}</span>
                                <span>${item.status}</span>
                                ${item.featured ? '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Featured</span>' : ''}
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="viewNews(${item.id})" class="btn-secondary py-1 px-2 text-xs">
                                View
                            </button>
                            <button onclick="editNews(${item.id})" class="btn-primary py-1 px-2 text-xs">
                                Edit
                            </button>
                            <button onclick="deleteNews(${item.id})" class="btn-secondary py-1 px-2 text-xs text-red-600">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        listContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-gray-500">No news found</p>
            </div>
        `;
    }
    
    // Update pagination
    updateNewsPagination(data);
}

// Update pagination
function updateNewsPagination(data) {
    const pageInfo = document.getElementById('news-page-info');
    const prevBtn = document.getElementById('prev-news-page');
    const nextBtn = document.getElementById('next-news-page');
    
    if (data.total > 0) {
        pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
    } else {
        pageInfo.textContent = 'No news found';
    }
    
    // Show/hide pagination buttons
    if (data.prev_page_url) {
        prevBtn.classList.remove('hidden');
        prevBtn.onclick = () => loadNews(data.current_page - 1);
    } else {
        prevBtn.classList.add('hidden');
    }
    
    if (data.next_page_url) {
        nextBtn.classList.remove('hidden');
        nextBtn.onclick = () => loadNews(data.current_page + 1);
    } else {
        nextBtn.classList.add('hidden');
    }
}

// Open create news modal
function openCreateNewsModal() {
    document.getElementById('news-modal-title').textContent = 'Create News';
    document.getElementById('news-form').reset();
    document.getElementById('news-id').value = '';
    document.getElementById('current-media').classList.add('hidden');
    document.getElementById('current-media-list').innerHTML = '';
    
    // Set default publish date to now
    const now = new Date();
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    document.getElementById('news-published-at').value = localDateTime;
    
    document.getElementById('news-modal').classList.remove('hidden');
}

// Open edit news modal
async function editNews(id) {
    try {
        const response = await AdminAPI.getNewsById(id);
        if (response.success) {
            const news = response.data;
            
            document.getElementById('news-modal-title').textContent = 'Edit News';
            document.getElementById('news-id').value = news.id;
            document.getElementById('news-title').value = news.title;
            document.getElementById('news-content').value = news.content;
            document.getElementById('news-category').value = news.category;
            document.getElementById('news-status').value = news.status;
            
            // Set featured checkbox
            document.getElementById('news-featured').checked = news.featured;
            
            // Set publish date
            const publishDate = new Date(news.published_at);
            const localDateTime = new Date(publishDate.getTime() - publishDate.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            document.getElementById('news-published-at').value = localDateTime;
            
            // Handle media
            if (news.media_urls && news.media_urls.length > 0) {
                document.getElementById('current-media').classList.remove('hidden');
                const mediaList = document.getElementById('current-media-list');
                mediaList.innerHTML = news.media_urls.map((url, index) => `
                    <div class="relative group">
                        <img src="${url}" alt="News media" class="w-full h-20 object-cover rounded">
                        <button type="button" onclick="removeMedia('${url}', ${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            ×
                        </button>
                    </div>
                `).join('');
            } else {
                document.getElementById('current-media').classList.add('hidden');
            }
            
            document.getElementById('news-modal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading news for edit:', error);
        alert('Failed to load news for editing.');
    }
}

// Remove media from current media list
function removeMedia(url, index) {
    // This would require more complex logic to handle removal
    // For now, we'll just show a message
    alert('Media removal functionality requires backend support for this specific implementation.');
}

// View news details
function viewNews(id) {
    // For now, just open edit modal in view-only mode
    editNews(id);
}

// Close news modal
function closeNewsModal() {
    document.getElementById('news-modal').classList.add('hidden');
}

// Close news details modal
function closeNewsDetailsModal() {
    document.getElementById('news-details-modal').classList.add('hidden');
}

// Handle news form submission
document.getElementById('news-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const newsId = document.getElementById('news-id').value;
    const submitBtn = document.getElementById('news-submit-btn');
    const submitText = document.getElementById('news-submit-text');
    const submitLoading = document.getElementById('news-submit-loading');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoading.classList.remove('hidden');
    
    try {
        let response;
        if (newsId) {
            // Update existing news
            response = await AdminAPI.updateNews(newsId, formData);
        } else {
            // Create new news
            response = await AdminAPI.createNews(formData);
        }
        
        if (response.success) {
            closeNewsModal();
            loadNews(currentNewsPage);
            showMessage(response.message || 'News saved successfully!', 'success');
        } else {
            showMessage(response.message || 'Failed to save news.', 'error');
        }
    } catch (error) {
        console.error('Error saving news:', error);
        showMessage('Failed to save news: ' + error.message, 'error');
    } finally {
        // Re-enable button and hide loading
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitLoading.classList.add('hidden');
    }
});

// Delete news
async function deleteNews(id) {
    if (confirm('Are you sure you want to delete this news?')) {
        try {
            const response = await AdminAPI.deleteNews(id);
            if (response.success) {
                loadNews(currentNewsPage);
                showMessage('News deleted successfully!', 'success');
            } else {
                showMessage(response.message || 'Failed to delete news.', 'error');
            }
        } catch (error) {
            console.error('Error deleting news:', error);
            showMessage('Failed to delete news: ' + error.message, 'error');
        }
    }
}

// Initialize news page
document.addEventListener('DOMContentLoaded', function() {
    loadNews();
});
</script>
@endsection