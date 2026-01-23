@extends('layouts.app')

@section('title', 'News - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky {{ auth()->check() ? 'top-[70px]' : 'top-0' }} z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <button onclick="history.back()" class="text-gray-600 hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">News & Updates</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- All News Section -->
        <section id="all-news">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Latest News</h2>
            <div id="news-container">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading news...</p>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-6">
                <button id="load-more-btn" class="hidden btn-primary px-6 py-2 text-sm">
                    <span id="load-more-text">Load More</span>
                    <span id="load-more-loading" class="hidden">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </section>
    </main>


</div>

<script>
let currentPage = 1;
let hasMoreNews = true;
let isLoading = false;

// Format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return `${Math.floor(diffInSeconds)} seconds ago`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)} months ago`;
    return `${Math.floor(diffInSeconds / 31536000)} years ago`;
}

// Create news card element
function createNewsCard(news) {
    const card = document.createElement('div');
    card.className = 'bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow';
    
    let mediaHtml = '';
    if (news.media_urls && news.media_urls.length > 0) {
        mediaHtml = `
            <div class="relative">
                <a href="/news/${news.id}">
                    <img src="${news.media_urls[0]}" alt="${news.title}" class="w-full h-48 object-contain" onerror="this.src='{{ asset('placeholder.jpg') }}'; this.onerror=null;">
                </a>
                ${news.featured ? '<div class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">Featured</div>' : ''}
            </div>
        `;
    } else {
        mediaHtml = `
            <div class="bg-gray-100 h-48 flex items-center justify-center">
                <a href="/news/${news.id}">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </a>
                ${news.featured ? '<div class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">Featured</div>' : ''}
            </div>
        `;
    }
    
    card.innerHTML = `
        ${mediaHtml}
        <div class="p-4">
            <a href="/news/${news.id}" class="block">
                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">${news.title}</h3>
            </a>
            <p class="text-gray-600 text-sm mb-3 line-clamp-3">${news.content.replace(/<[^>]*>/g, '').substring(0, 150)}${news.content.length > 150 ? '...' : ''}</p>
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>${news.author_name || 'Admin'}</span>
                <span>${formatTimeAgo(news.published_at)}</span>
            </div>
        </div>
    `;
    
    return card;
}





// Load news with pagination
async function loadNews(page = 1, append = false) {
    if (isLoading) return;
    
    try {
        isLoading = true;
        const response = await fetch(`/api/news?page=${page}&per_page=10`);
        const data = await response.json();
        
        const container = document.getElementById('news-container');
        
        if (data.success) {
            if (!append) {
                container.innerHTML = '';
            }
            
            if (data.data.data && data.data.data.length > 0) {
                data.data.data.forEach(news => {
                    const card = createNewsCard(news);
                    container.appendChild(card);
                });
                
                // Update pagination
                currentPage = data.data.current_page;
                hasMoreNews = data.data.current_page < data.data.last_page;
                
                // Show/hide load more button
                const loadMoreBtn = document.getElementById('load-more-btn');
                if (hasMoreNews) {
                    loadMoreBtn.classList.remove('hidden');
                } else {
                    loadMoreBtn.classList.add('hidden');
                }
            } else {
                if (!append) {
                    container.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <p class="text-gray-500">No news articles available</p>
                        </div>
                    `;
                }
                
                // Hide load more button if no data
                document.getElementById('load-more-btn').classList.add('hidden');
                hasMoreNews = false;
            }
        } else {
            if (!append) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-500">Failed to load news</p>
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading news:', error);
        if (!append) {
            document.getElementById('news-container').innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-500">Error loading news: ${error.message}</p>
                </div>
            `;
        }
    } finally {
        isLoading = false;
    }
}



// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadNews();
    
    // Set up load more button event listener
    const loadMoreBtn = document.getElementById('load-more-btn');
    loadMoreBtn.addEventListener('click', () => {
        if (isLoading) return;
        
        currentPage++;
        loadNews(currentPage, true);
        
        const loadMoreText = document.getElementById('load-more-text');
        const loadMoreLoading = document.getElementById('load-more-loading');
        
        loadMoreText.classList.add('hidden');
        loadMoreLoading.classList.remove('hidden');
        
        setTimeout(() => {
            loadMoreText.classList.remove('hidden');
            loadMoreLoading.classList.add('hidden');
        }, 1000);
    });
    
});


</script>
@endsection