
<!-- Member Navigation Component (Hidden by default) -->
<div id="member-navigation" class="hidden">
    <style>
        /* Member specific styles */
        .member-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 60;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .member-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 60;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            padding: 0.5rem;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e5e7eb;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: 280px;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 70;
            display: none;
        }
        
        .profile-dropdown.show {
            display: block;
        }
    </style>

    <!-- Member Header -->
    <div class="member-header bg-white text-gray-800 p-4 shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- Logo -->
                <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht Logo" class="h-8 w-auto">
            </div>
            
            <!-- Profile Info and Dropdown -->
            <div class="relative">
                <button id="profile-toggle" class="flex items-center space-x-2 focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900" id="header-name">Member</p>
                        <p class="text-xs text-gray-500" id="header-member-id">...</p>
                    </div>
                    <img id="header-avatar" class="avatar" src="" alt="Profile" onerror="this.onerror=null;this.src='{{ asset('logo3.png') }}';">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Profile Dropdown -->
                <div id="profile-dropdown" class="profile-dropdown">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <img id="dropdown-avatar" class="h-16 w-16 rounded-full object-cover" src="" alt="Profile" onerror="this.onerror=null;this.src='{{ asset('logo3.png') }}';">
                            <div>
                                <h3 class="font-medium text-gray-800" id="dropdown-name">Member</h3>
                                <p class="text-gray-600 text-sm" id="dropdown-member-id">...</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="/member/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                        <a href="/member/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            My Profile
                        </a>
                        <div class="border-t border-gray-200"></div>
                        <button id="logout-btn" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Bottom Navigation -->
    <div class="member-bottom-nav">
        <div class="flex justify-around">
            <a href="{{ route('member.dashboard') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs mt-1">Home</span>
            </a>
            <a href="{{ route('member.posts') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="text-xs mt-1">Posts</span>
            </a>
            <a href="{{ route('member.events') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs mt-1">Events</span>
            </a>
            <a href="{{ route('news.index') }}" class="nav-item touch-target mobile-tap-highlight active">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="text-xs mt-1">News</span>
            </a>
            <a href="{{ route('member.donations') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs mt-1">Donations</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for auth token
    const token = localStorage.getItem('auth_token');
    
    if (token) {
        // Toggle Navigations
        const publicNav = document.getElementById('public-navigation');
        const memberNav = document.getElementById('member-navigation');
        const mainContent = document.querySelector('main');
        
        if (memberNav) {
            memberNav.classList.remove('hidden');
            if (publicNav) publicNav.classList.add('hidden');
            
            // Adjust main padding for member layout
            if (mainContent) {
                mainContent.classList.remove('pt-20', 'pb-20', 'md:pb-16');
                mainContent.style.paddingTop = '70px';
                mainContent.style.paddingBottom = '60px'; // For bottom nav
            }

            // Adjust sticky headers inside the content (like News page)
            const stickyHeaders = document.querySelectorAll('.sticky.top-0');
            stickyHeaders.forEach(header => {
                header.classList.remove('top-0');
                header.style.top = '70px'; // Below member header
            });
            
            // Load Profile Data
            loadMemberProfileData(token);
        }
    }

    function loadMemberProfileData(token) {
        // Safe check for member data in localstorage first for instant render
        const memberData = localStorage.getItem('member');
        if (memberData) {
            updateProfileUI(JSON.parse(memberData));
        }

        // Fetch fresh data
        fetch('/api/auth/profile', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Unauthorized');
            return response.json();
        })
        .then(data => {
            updateProfileUI(data.data);
            localStorage.setItem('member', JSON.stringify(data.data));
        })
        .catch(err => {
            console.error('Session expired', err);
            // Optionally logout? Or just leave UI as is until interaction fails?
            // For now, let's just let it be, or fallback to public nav? 
            // If API fails with 401, we should probably clear token.
            if (err.message === 'Unauthorized') {
                 // localStorage.removeItem('auth_token');
                 // window.location.reload(); // Reloads to show public nav
            }
        });
    }

    function updateProfileUI(member) {
        const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(member.name)}&background=random`;
        const avatarUrl = member.photo ? `/storage/${member.photo}` : defaultAvatar;
        
        // Update Header
        const headerName = document.getElementById('header-name');
        const headerId = document.getElementById('header-member-id');
        const headerAvatar = document.getElementById('header-avatar');
        
        if(headerName) headerName.textContent = member.name;
        if(headerId) headerId.textContent = member.member_id || '';
        if(headerAvatar) headerAvatar.src = avatarUrl;
        
        // Update Dropdown
        const dropName = document.getElementById('dropdown-name');
        const dropId = document.getElementById('dropdown-member-id');
        const dropAvatar = document.getElementById('dropdown-avatar');
        
        if(dropName) dropName.textContent = member.name;
        if(dropId) dropId.textContent = member.member_id || '';
        if(dropAvatar) dropAvatar.src = avatarUrl;
    }

    // Dropdown Toggle
    const toggle = document.getElementById('profile-toggle');
    const dropdown = document.getElementById('profile-dropdown');
    
    if (toggle && dropdown) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }

    // Logout
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if(confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('member');
                window.location.href = '/login'; // Or reload
            }
        });
    }
});
</script>
