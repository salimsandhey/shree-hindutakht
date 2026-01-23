@extends('layouts.member')

@section('title', 'Dashboard - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 mobile-container page-transition">
    <!-- Content -->
    <div class="mobile-content space-y-4">
        <!-- Hindu Religious Books Slider -->
        <div class="card animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Hindu Religious Books</h3>
                <button class="text-primary text-sm font-medium hover:underline">View All</button>
            </div>
            
            <!-- Books Slider Container -->
            <div class="relative">
                <div class="overflow-hidden">
                    <div id="books-slider" class="flex transition-transform duration-300 ease-in-out pb-5">
                        <!-- Books will be loaded dynamically -->
                    </div>
                </div>
                
                <!-- Slider Navigation Arrows -->
                <button id="prev-book" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-2 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="next-book" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-2 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card animate-fade-in">
            <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="location.href='/member/edit-profile'" class="btn-secondary text-center p-4 rounded-xl touch-target mobile-button mobile-tap-highlight">
                    <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm font-medium">Edit Profile</span>
                </button>
                <button onclick="location.href='/member/id-card'" class="btn-secondary text-center p-4 rounded-xl touch-target mobile-button mobile-tap-highlight">
                    <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    <span class="text-sm font-medium">ID Card</span>
                </button>
            </div>
        </div>

        <!-- All Posts -->
        <div class="card animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Community Posts</h3>
                <button onclick="location.href='/member/posts'" class="text-primary text-sm font-medium hover:underline">View All</button>
            </div>
            <div id="posts-container">
                <p class="text-gray-500 text-center py-4">Loading posts...</p>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card animate-fade-in">
            <h3 class="font-semibold text-gray-800 mb-4">Upcoming Events</h3>
            <div id="events-container">
                <p class="text-gray-500 text-center py-4">Loading events...</p>
            </div>
        </div>
    </div>

    <!-- Remove the duplicate bottom navigation since it's already provided by the member layout -->
</div>

<script>
let currentMember = null;
let currentBookIndex = 0;
const booksPerPage = 2;

// Load member data and populate dashboard
async function loadDashboard() {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        // Get member profile
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
        currentMember = data.data;

        // Load posts and events
        loadAllPosts();
        loadUpcomingEvents();
        loadNotificationCount();

    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

// Generate avatar URL for users without photos
function generateAvatarUrl(name) {
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

// Load all posts (instead of just recent posts)
async function loadAllPosts() {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        // Load all posts with pagination, but limit to first 10 on dashboard
        const response = await fetch('/api/posts?page=1&per_page=10', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            cache: 'no-cache'
        });

        if (response.ok) {
            const data = await response.json();
            const container = document.getElementById('posts-container');
            
            if (data.success && data.data.posts && data.data.posts.length > 0) {
                // Create post elements similar to the member posts page
                container.innerHTML = '';
                data.data.posts.forEach(post => {
                    const postElement = createPostElement(post);
                    container.appendChild(postElement);
                });
            } else {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No posts yet</p>';
            }
        }
    } catch (error) {
        console.error('Error loading posts:', error);
        const container = document.getElementById('posts-container');
        container.innerHTML = '<p class="text-red-500 text-center py-4">Error loading posts</p>';
    }
}

// Create post element (similar to member posts page)
function createPostElement(post) {
    const postDiv = document.createElement('div');
    postDiv.className = 'border-b border-gray-100 last:border-b-0 pb-3 mb-3 last:mb-0';
    
    // Generate member avatar URL
    const memberPhoto = post.created_by_admin && post.admin?.photo ? 
        `/storage/${post.admin.photo}` : 
        (post.member?.photo ? 
            `/storage/${post.member.photo}` : 
            generateAvatarUrl(post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht')));
    
    // Format date
    const postDate = new Date(post.created_at);
    const formattedDate = postDate.toLocaleDateString();
    
    postDiv.innerHTML = `
        <div class="flex items-start space-x-3">
            <img src="${memberPhoto}" class="w-10 h-10 rounded-full object-cover" alt="${post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'User')} avatar">
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-1">
                    <h4 class="font-medium text-gray-800">${post.created_by_admin && post.admin ? (post.admin.username || post.admin.name) : (post.member?.name || 'Shri Hindutakht')}</h4>
                    ${post.created_by_admin ? '<span class="bg-primary text-white text-xs px-1.5 py-0.5 rounded-full">Admin</span>' : ''}
                </div>
                <p class="text-gray-600 text-sm line-clamp-2">${post.content}</p>
                <p class="text-gray-400 text-xs mt-1">${formattedDate}</p>
            </div>
        </div>
    `;
    
    return postDiv;
}

// Load upcoming events
async function loadUpcomingEvents() {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        const response = await fetch('/api/events?limit=3&upcoming=true', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            const container = document.getElementById('events-container');
            
            if (data.data && data.data.length > 0) {
                container.innerHTML = data.data.map(event => `
                    <div class="border-b border-gray-100 last:border-b-0 pb-3 mb-3 last:mb-0">
                        <h4 class="font-medium text-gray-800 mb-1">${event.title}</h4>
                        <p class="text-gray-600 text-sm">${event.location}</p>
                        <p class="text-primary text-xs mt-1">${new Date(event.event_date).toLocaleDateString()}</p>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No upcoming events</p>';
            }
        }
    } catch (error) {
        console.error('Error loading events:', error);
    }
}

// Load notification count
async function loadNotificationCount() {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        const response = await fetch('/api/notifications/unread-count', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            const badge = document.getElementById('notification-badge');
            if (data.data.count > 0) {
                badge.textContent = data.data.count;
                badge.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error loading notification count:', error);
    }
}

// Download ID card
async function downloadIdCard() {
    try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        const response = await fetch('/api/member/id-card/download?format=pdf', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `hindutakht_id_${currentMember.member_id}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
    } catch (error) {
        console.error('Error downloading ID card:', error);
        alert('Failed to download ID card');
    }
}

// Fetch Hindu religious books with images from Google Books API with caching
async function loadHinduBooks() {
    // Check if we have cached books data in localStorage
    const cachedBooks = localStorage.getItem('hinduBooksData');
    const cacheTimestamp = localStorage.getItem('hinduBooksTimestamp');
    
    // Use cached data if it's less than 1 hour old
    if (cachedBooks && cacheTimestamp && (Date.now() - parseInt(cacheTimestamp)) < 3600000) {
        try {
            const books = JSON.parse(cachedBooks);
            renderBooksSlider(books);
            return;
        } catch (e) {
            // If parsing fails, continue to fetch fresh data
        }
    }
    
    try {
        // Hindu religious books search terms
        const bookQueries = [
            'Bhagavad Gita Hinduism',
            'Ramayana Hinduism',
            'Mahabharata Hinduism',
            'Vedas Hinduism',
            'Upanishads Hinduism',
            'Puranas Hinduism',
            'Yoga Sutras Hinduism'
        ];
        
        // Get books for each query
        const books = [];
        for (const query of bookQueries) {
            const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=1&startIndex=0`);
            if (response.ok) {
                const data = await response.json();
                if (data.items && data.items.length > 0) {
                    const book = data.items[0].volumeInfo;
                    books.push({
                        title: book.title,
                        authors: book.authors ? book.authors.join(', ') : 'Unknown Author',
                        description: book.description ? book.description.substring(0, 100) + '...' : 'No description available',
                        image: book.imageLinks ? book.imageLinks.thumbnail : null,
                        publishedDate: book.publishedDate || 'Unknown',
                        infoLink: book.infoLink || '#'
                    });
                }
            }
            // Add small delay to avoid rate limiting
            await new Promise(resolve => setTimeout(resolve, 100));
        }
        
        // Cache the books data
        localStorage.setItem('hinduBooksData', JSON.stringify(books));
        localStorage.setItem('hinduBooksTimestamp', Date.now().toString());
        
        // Render books in slider
        renderBooksSlider(books);
    } catch (error) {
        console.error('Error loading books:', error);
        // Fallback to static books if API fails
        const staticBooks = [
            {
                title: "Bhagavad Gita",
                authors: "Vyasa",
                description: "Ancient Hindu scripture",
                image: "https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80",
                infoLink: "https://books.google.com/books/about/Bhagavad_Gita.html"
            },
            {
                title: "Ramayana",
                authors: "Valmiki",
                description: "Epic of Lord Rama",
                image: "https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80",
                infoLink: "https://books.google.com/books/about/Ramayana.html"
            },
            {
                title: "Mahabharata",
                authors: "Vyasa",
                description: "Great Indian epic",
                image: "https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80",
                infoLink: "https://books.google.com/books/about/Mahabharata.html"
            },
            {
                title: "Vedas",
                authors: "Ancient Sages",
                description: "Ancient sacred texts",
                image: "https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80",
                infoLink: "https://books.google.com/books/about/Vedas.html"
            },
            {
                title: "Upanishads",
                authors: "Ancient Philosophers",
                description: "Philosophical texts",
                image: "https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80",
                infoLink: "https://books.google.com/books/about/Upanishads.html"
            }
        ];
        renderBooksSlider(staticBooks);
    }
}

// Render books in the slider
function renderBooksSlider(books) {
    const slider = document.getElementById('books-slider');
    
    if (books.length === 0) {
        slider.innerHTML = '<p class="text-gray-500 text-center py-4">No books available</p>';
        return;
    }
    
    slider.innerHTML = books.map(book => `
        <div class="flex-shrink-0 w-40 mr-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden h-full cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="openBook('${book.infoLink || '#'}')">
                <div class="h-48 overflow-hidden bg-gray-100">
                    ${book.image ? 
                        `<img src="${book.image}" alt="${book.title}" class="w-full h-full object-cover lazy" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1593508572102-603c3f19a9e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=300&q=80'; this.onerror=null;">` :
                        `<div class="bg-gray-200 w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>`
                    }
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-gray-900 text-sm line-clamp-2">${book.title}</h4>
                    <p class="text-gray-600 text-xs mt-1 line-clamp-1">${book.authors || 'Unknown Author'}</p>
                </div>
            </div>
        </div>
    `).join('');
    
    // Initialize slider navigation
    initializeBookSlider();
}

// Open book in Google Books
function openBook(url) {
    if (url && url !== '#') {
        window.open(url, '_blank');
    }
}

// Initialize book slider navigation
function initializeBookSlider() {
    const slider = document.getElementById('books-slider');
    const prevBtn = document.getElementById('prev-book');
    const nextBtn = document.getElementById('next-book');
    
    if (!slider || !prevBtn || !nextBtn) return;
    
    let currentBookIndex = 0;
    const bookWidth = 176; // width + margin of each book (w-40 = 160px + mr-4 = 16px)
    
    prevBtn.addEventListener('click', function() {
        currentBookIndex = Math.max(0, currentBookIndex - 1);
        updateBookSlider();
    });
    
    nextBtn.addEventListener('click', function() {
        const totalBooks = slider.children.length;
        const containerWidth = slider.parentElement.offsetWidth;
        const visibleBooks = Math.floor(containerWidth / bookWidth);
        const maxIndex = Math.max(0, totalBooks - visibleBooks);
        
        currentBookIndex = Math.min(maxIndex, currentBookIndex + 1);
        updateBookSlider();
    });
    
    function updateBookSlider() {
        const translateX = -currentBookIndex * bookWidth;
        slider.style.transform = `translateX(${translateX}px)`;
    }
    
    // Initialize slider position
    updateBookSlider();
    
    // Update slider on window resize
    window.addEventListener('resize', updateBookSlider);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboard();
    
    // Load Hindu religious books
    loadHinduBooks();
});
</script>
@endsection