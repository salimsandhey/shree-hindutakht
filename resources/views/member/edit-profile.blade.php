@extends('layouts.member')

@section('title', 'Edit Profile - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 mobile-container page-transition">
    <!-- Header -->
    <div class="sticky top-0 bg-white border-b border-gray-200 z-30 mobile-header">
        <div class="flex items-center justify-between p-3">
            <div class="flex items-center space-x-2">
                <button onclick="history.back()" class="p-1.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors touch-target mobile-tap-highlight">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">Edit Profile</h1>
            </div>
            <button id="save-profile-btn" class="btn-primary text-sm px-3 py-1.5 touch-target mobile-button mobile-tap-highlight">
                <span id="save-text">Save</span>
                <div id="save-loading" class="hidden flex items-center">
                    <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving
                </div>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 space-y-6 pb-20 mobile-content">
        <!-- Error Messages -->
        <div id="error-message" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 animate-fade-in">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div id="error-text" class="ml-3 text-sm text-red-700"></div>
            </div>
        </div>

        <!-- Success Messages -->
        <div id="success-message" class="hidden bg-green-50 border border-green-200 rounded-xl p-4 animate-fade-in">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div id="success-text" class="ml-3 text-sm text-green-700">Profile updated successfully!</div>
            </div>
        </div>

        <!-- Profile Picture Section -->
        <div class="card animate-fade-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Picture</h2>
            <div class="flex items-center space-x-4">
                <div class="profile-picture-upload">
                    <img id="profile-picture-preview" class="avatar-xl" src="" alt="Profile Picture" style="display: none;">
                    <div id="profile-picture-placeholder" class="avatar-xl bg-primary text-white flex items-center justify-center text-2xl font-bold">
                        <!-- Initials will be populated by JavaScript -->
                    </div>
                    <div class="profile-picture-overlay touch-target mobile-tap-highlight">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <input type="file" id="profile-picture-input" class="hidden" accept="image/*,.webp">
                </div>
                <div>
                    <h3 class="font-medium text-gray-800">Update your photo</h3>
                    <p class="text-sm text-gray-500">Click on the image to upload a new photo (max 10MB)</p>
                    <p class="text-xs text-gray-400">Images will be compressed to WebP format (~50KB) for optimal performance</p>
                    <button id="remove-photo-btn" class="btn-secondary mt-2 text-sm touch-target mobile-button mobile-tap-highlight" style="display: none;">
                        Remove Photo
                    </button>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card animate-fade-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>
            <form id="edit-profile-form" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter your full name" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="input-field" placeholder="Enter your phone number">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" class="input-field" placeholder="Enter your address"></textarea>
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="input-field">
                </div>
            </form>
        </div>

        <!-- Member Information (Read-only) -->
        <div class="card animate-fade-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Member Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Member ID</span>
                    <span id="member-id" class="text-sm text-gray-800 font-medium">-</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Join Date</span>
                    <span id="join-date" class="text-sm text-gray-800">-</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    <span id="status" class="text-sm text-green-600 font-medium">Active</span>
                </div>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="card animate-fade-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
            <form id="change-password-form" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="input-field" placeholder="Enter current password">
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="input-field" placeholder="Enter new password">
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="input-field" placeholder="Confirm new password">
                </div>

                <button type="button" id="change-password-btn" class="btn-primary w-full touch-target mobile-button mobile-tap-highlight">
                    <span id="password-text">Change Password</span>
                    <div id="password-loading" class="hidden flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Changing...
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="flex justify-around">
            <a href="{{ route('member.dashboard') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs mt-1">Home</span>
            </a>
            <a href="{{ route('member.events') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs mt-1">Events</span>
            </a>
            <a href="{{ route('member.donations') }}" class="nav-item touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs mt-1">Donations</span>
            </a>
            <div class="nav-item active touch-target mobile-tap-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs mt-1">Profile</span>
            </div>
        </div>
    </div>

<script>
// Navigation function
function navigateToSection(section) {
    // Navigate to home page with specific section
    window.location.href = '/home#' + section;
}

// Initialize edit profile page
document.addEventListener('DOMContentLoaded', function() {
    loadUserProfile();
    initializeEventListeners();
});

let currentUser = null;

// Load user profile data
async function loadUserProfile() {
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        const response = await fetch('/api/auth/profile', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            currentUser = data.data;
            populateForm(currentUser);
        } else {
            throw new Error(data.message || 'Failed to load profile');
        }
    } catch (error) {
        console.error('Failed to load profile:', error);
        showError('Failed to load profile data. Please try again.');
        if (error.message.includes('Unauthorized')) {
            window.location.href = '/login';
        }
    }
}

// Populate form with user data
function populateForm(user) {
    // Personal information
    document.getElementById('name').value = user.name || '';
    document.getElementById('email').value = user.email || '';
    document.getElementById('phone').value = user.phone || '';
    document.getElementById('address').value = user.address || '';
    document.getElementById('date_of_birth').value = user.date_of_birth || '';

    // Member information (read-only)
    document.getElementById('member-id').textContent = user.member_id || '-';
    document.getElementById('join-date').textContent = user.join_date ? 
        new Date(user.join_date).toLocaleDateString() : '-';
    
    // Profile picture
    const preview = document.getElementById('profile-picture-preview');
    const placeholder = document.getElementById('profile-picture-placeholder');
    
    console.log('User photo data:', {
        photo: user.photo,
        full_photo_url: user.full_photo_url
    });
    
    if (user.photo && user.full_photo_url && user.full_photo_url !== 'undefined' && !user.full_photo_url.includes('undefined')) {
        preview.src = user.full_photo_url;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        document.getElementById('remove-photo-btn').style.display = 'block';
        
        // Add error handler for broken images
        preview.onerror = function() {
            console.log('Image failed to load, showing placeholder');
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            const initials = user.name ? user.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
            placeholder.textContent = initials;
            document.getElementById('remove-photo-btn').style.display = 'none';
        };
    } else {
        const initials = user.name ? user.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
        placeholder.textContent = initials;
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
        document.getElementById('remove-photo-btn').style.display = 'none';
    }
}

// Initialize event listeners
function initializeEventListeners() {
    // Profile picture upload
    const profilePictureUpload = document.querySelector('.profile-picture-upload');
    const profilePictureInput = document.getElementById('profile-picture-input');
    
    profilePictureUpload.addEventListener('click', () => {
        profilePictureInput.click();
    });
    
    profilePictureInput.addEventListener('change', handleProfilePictureChange);
    
    // Remove photo button
    document.getElementById('remove-photo-btn').addEventListener('click', removeProfilePicture);
    
    // Save profile button
    document.getElementById('save-profile-btn').addEventListener('click', saveProfile);
    
    // Change password button
    document.getElementById('change-password-btn').addEventListener('click', changePassword);
}

// Handle profile picture change
function handleProfilePictureChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showError('Please select a valid image file.');
        return;
    }
    
    // Validate file size (10MB max)
    if (file.size > 10 * 1024 * 1024) {
        showError('Image size must be less than 10MB.');
        return;
    }
    
    console.log('Selected image:', {
        name: file.name,
        size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
        type: file.type
    });
    
    // Preview the image
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('profile-picture-preview');
        const placeholder = document.getElementById('profile-picture-placeholder');
        
        preview.src = e.target.result;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        document.getElementById('remove-photo-btn').style.display = 'block';
        
        // Show compression info
        showSuccess(`Image selected (${(file.size / 1024 / 1024).toFixed(2)} MB). It will be compressed to WebP format (~50KB) during upload.`);
    };
    reader.readAsDataURL(file);
}

// Remove profile picture
async function removeProfilePicture() {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/auth/remove-photo', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const preview = document.getElementById('profile-picture-preview');
            const placeholder = document.getElementById('profile-picture-placeholder');
            
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            const initials = currentUser.name ? currentUser.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
            placeholder.textContent = initials;
            document.getElementById('remove-photo-btn').style.display = 'none';
            document.getElementById('profile-picture-input').value = '';
            
            showSuccess('Profile picture removed successfully!');
        } else {
            throw new Error(data.message || 'Failed to remove photo');
        }
    } catch (error) {
        console.error('Failed to remove photo:', error);
        showError('Failed to remove photo. Please try again.');
    }
}

// Save profile
async function saveProfile() {
    const saveBtn = document.getElementById('save-profile-btn');
    const saveText = document.getElementById('save-text');
    const saveLoading = document.getElementById('save-loading');
    
    // Show loading state
    saveBtn.disabled = true;
    saveText.classList.add('hidden');
    saveLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const formData = new FormData();
        
        // Add form data
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('date_of_birth', document.getElementById('date_of_birth').value);
        
        // Add profile picture if changed
        const photoInput = document.getElementById('profile-picture-input');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }
        
        const response = await fetch('/api/auth/update-profile', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            currentUser = data.data;
            console.log('Profile updated, new user data:', currentUser);
            populateForm(currentUser); // Refresh the form with updated data
            showSuccess('Profile updated successfully!');
        } else {
            throw new Error(data.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Failed to save profile:', error);
        showError('Failed to save profile. Please try again.');
    } finally {
        // Hide loading state
        saveBtn.disabled = false;
        saveText.classList.remove('hidden');
        saveLoading.classList.add('hidden');
    }
}

// Change password
async function changePassword() {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showError('Please fill in all password fields.');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showError('New passwords do not match.');
        return;
    }
    
    if (newPassword.length < 6) {
        showError('New password must be at least 6 characters long.');
        return;
    }
    
    const passwordBtn = document.getElementById('change-password-btn');
    const passwordText = document.getElementById('password-text');
    const passwordLoading = document.getElementById('password-loading');
    
    // Show loading state
    passwordBtn.disabled = true;
    passwordText.classList.add('hidden');
    passwordLoading.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/auth/change-password', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Clear password fields
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
            
            showSuccess('Password changed successfully!');
        } else {
            throw new Error(data.message || 'Failed to change password');
        }
    } catch (error) {
        console.error('Failed to change password:', error);
        showError('Failed to change password. Please try again.');
    } finally {
        // Hide loading state
        passwordBtn.disabled = false;
        passwordText.classList.remove('hidden');
        passwordLoading.classList.add('hidden');
    }
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const successDiv = document.getElementById('success-message');
    
    successDiv.classList.add('hidden');
    errorText.textContent = message;
    errorDiv.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorDiv.classList.add('hidden');
    }, 5000);
}

// Show success message
function showSuccess(message) {
    const successDiv = document.getElementById('success-message');
    const successText = document.getElementById('success-text');
    const errorDiv = document.getElementById('error-message');
    
    errorDiv.classList.add('hidden');
    successText.textContent = message;
    successDiv.classList.remove('hidden');
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 3000);
}
</script>
@endsection