@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard - Shree Hindutakht')

@section('content')
<div class="p-4 space-y-4">
    <!-- Page Title -->
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-600 text-sm mt-1">Overview of your admin panel</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3">
        <div class="card text-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 w-12 h-12 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600">Total Members</p>
            <p class="text-xl font-bold" id="total-members">0</p>
        </div>
        
        <div class="card text-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 w-12 h-12 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600">Active Members</p>
            <p class="text-xl font-bold" id="active-members">0</p>
        </div>
        
        <div class="card text-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 w-12 h-12 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600">Total Posts</p>
            <p class="text-xl font-bold" id="total-posts">0</p>
        </div>
        
        <div class="card text-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 w-12 h-12 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600">Upcoming Events</p>
            <p class="text-xl font-bold" id="upcoming-events">0</p>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Recent Members</h3>
        <div id="recent-members">
            <div class="text-center py-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading recent members...</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Management</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.members') }}" class="btn-secondary text-center p-4 rounded-xl">
                <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm font-medium">Members</span>
            </a>
            <a href="{{ route('admin.posts') }}" class="btn-secondary text-center p-4 rounded-xl">
                <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="text-sm font-medium">Posts</span>
            </a>
            <a href="{{ route('admin.events') }}" class="btn-secondary text-center p-4 rounded-xl">
                <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium">Events</span>
            </a>
            <a href="{{ route('admin.donations') }}" class="btn-secondary text-center p-4 rounded-xl">
                <svg class="w-6 h-6 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Donations</span>
            </a>
        </div>
    </div>
</div>

<script>
// Load dashboard stats
async function loadDashboardStats() {
    try {
        // Load member stats
        const memberStats = await AdminAPI.get('/members/stats');
        if (memberStats.success) {
            document.getElementById('total-members').textContent = memberStats.data.total;
            document.getElementById('active-members').textContent = memberStats.data.active;
            
            // Render recent members
            renderRecentMembers(memberStats.data.recent);
        }
        
        // TODO: Load other stats (posts, events, etc.)
        document.getElementById('total-posts').textContent = '0';
        document.getElementById('upcoming-events').textContent = '0';
        
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

// Render recent members
function renderRecentMembers(members) {
    const container = document.getElementById('recent-members');
    
    if (members && members.length > 0) {
        container.innerHTML = `
            <div class="space-y-3">
                ${members.map(member => `
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium">${member.name}</h4>
                            <p class="text-sm text-gray-600">${member.email}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">${new Date(member.created_at).toLocaleDateString()}</p>
                            <p class="text-xs text-gray-500">${member.member_id}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500">No recent members</p>
            </div>
        `;
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard stats
    loadDashboardStats();
});
</script>
@endsection