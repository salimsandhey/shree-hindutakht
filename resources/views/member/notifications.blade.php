@extends('layouts.member')

@section('title', 'Notifications - Shree Hindutakht')

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
                <h1 class="text-lg font-semibold text-gray-800 animate-fade-in-down">Notifications</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 mobile-content">
        <div id="notifications-container" class="space-y-4">
            <!-- Notifications will be loaded here -->
        </div>
        
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12 hidden animate-fade-in">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5 5-5h-5V7a7 7 0 11-14 0v5H1l5 5-5 5h5v2a7 7 0 0014 0v-2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No notifications yet</h3>
            <p class="text-gray-500">You'll see notifications about community events and updates here.</p>
        </div>
    </div>

</div>

<script>
    let currentPage = 1;
    let loading = false;

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
        if (!token) {
            alert('You are not logged in. Redirecting to login page.');
            window.location.href = '/login';
            return;
        }
        
        // Load notifications
        loadNotifications();
    });

    async function loadNotifications() {
        if (loading) return;
        loading = true;

        try {
            const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
            const response = await fetch(`/api/notifications?page=${currentPage}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                displayNotifications(data.notifications || []);
                
                // Show empty state if no notifications
                if (currentPage === 1 && (!data.notifications || data.notifications.length === 0)) {
                    document.getElementById('empty-state').classList.remove('hidden');
                }
            } else {
                console.error('Failed to load notifications');
                if (currentPage === 1) {
                    document.getElementById('empty-state').classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            if (currentPage === 1) {
                document.getElementById('empty-state').classList.remove('hidden');
            }
        } finally {
            loading = false;
        }
    }

    function displayNotifications(notifications) {
        const container = document.getElementById('notifications-container');
        
        if (currentPage === 1) {
            container.innerHTML = '';
        }

        notifications.forEach(notification => {
            const notificationElement = createNotificationElement(notification);
            container.appendChild(notificationElement);
        });
    }

    function createNotificationElement(notification) {
        const div = document.createElement('div');
        div.className = `card notification-item transition-colors cursor-pointer ${
            notification.read_at ? 'bg-gray-50' : 'bg-blue-50'
        }`;
        
        const timeAgo = getTimeAgo(notification.created_at);
        
        div.innerHTML = `
            <div class="flex items-start space-x-3 p-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5 5-5h-5V7a7 7 0 11-14 0v5H1l5 5-5 5h5v2a7 7 0 0014 0v-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-grow">
                    <h4 class="font-semibold text-gray-800 mb-1">${notification.title || 'Notification'}</h4>
                    <p class="text-gray-600 text-sm mb-2">${notification.message || notification.data?.message || 'New notification'}</p>
                    <span class="text-xs text-gray-500">${timeAgo}</span>
                </div>
                ${!notification.read_at ? '<div class="flex-shrink-0"><div class="w-2 h-2 bg-primary rounded-full"></div></div>' : ''}
            </div>
        `;
        
        if (!notification.read_at) {
            div.addEventListener('click', () => markAsRead(notification.id, div));
        }
        
        return div;
    }

    async function markAsRead(notificationId, element) {
        try {
            const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
            const response = await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                element.classList.remove('bg-blue-50');
                element.classList.add('bg-gray-50');
                const indicator = element.querySelector('.w-2.h-2');
                if (indicator) {
                    indicator.remove();
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInMs = now - date;
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
        const diffInMinutes = Math.floor(diffInMs / (1000 * 60));

        if (diffInDays > 0) {
            return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
        } else if (diffInHours > 0) {
            return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
        } else if (diffInMinutes > 0) {
            return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
        } else {
            return 'Just now';
        }
    }

    // Logout function
    function logout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('token');
        localStorage.removeItem('member');
        window.location.href = '/login';
    }
</script>
@endsection