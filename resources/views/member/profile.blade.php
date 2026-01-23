@extends('layouts.member')

@section('title', 'Edit Profile - Shree Hindutakht')

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
                <h1 class="text-lg font-semibold text-gray-800 animate-fade-in-down">Edit Profile</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 mobile-content">
        <div class="card animate-fade-in">
            <form id="profile-form" enctype="multipart/form-data">
                <div class="space-y-6">
                    <!-- Profile Picture Section -->
                    <div class="text-center animate-fade-in-down">
                        <div class="profile-picture-upload inline-block">
                            <img id="profile-avatar" class="avatar-xl mx-auto" src="" alt="Profile Picture">
                            <div class="profile-picture-overlay touch-target mobile-tap-highlight" onclick="document.getElementById('photo').click()">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                        <div class="mt-4">
                            <button type="button" onclick="document.getElementById('photo').click()" class="btn-secondary text-sm touch-target mobile-button mobile-tap-highlight">
                                Change Profile Picture
                            </button>
                            <button type="button" onclick="removePhoto()" class="text-red-500 hover:text-red-700 text-sm ml-4 touch-target mobile-button mobile-tap-highlight">
                                Remove
                            </button>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" class="input-field" required>
                        </div>

                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (cannot be changed)</label>
                            <input type="email" id="email" class="input-field bg-gray-100" disabled>
                        </div>

                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="input-field">
                        </div>

                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" class="input-field" rows="3"></textarea>
                        </div>

                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="input-field">
                        </div>

                        <div class="animate-fade-in">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select id="gender" name="gender" class="input-field">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="border-t pt-6 animate-fade-in">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="input-field">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="input-field">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="input-field">
                            </div>
                        </div>
                    </div>

                    <!-- Error/Success Messages -->
                    <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm animate-fade-in"></div>
                    <div id="success-message" class="hidden bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl text-sm animate-fade-in"></div>

                    <!-- Submit Buttons -->
                    <div class="space-y-3">
                        <button type="submit" id="save-btn" class="btn-primary w-full touch-target mobile-button mobile-tap-highlight">
                            <span id="save-text">Save Changes</span>
                            <span id="save-loading" class="hidden">Saving...</span>
                        </button>

                        <button type="button" onclick="changePassword()" id="password-btn" class="btn-secondary w-full touch-target mobile-button mobile-tap-highlight">
                            <span id="password-text">Update Password</span>
                            <span id="password-loading" class="hidden">Updating...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentMember = null;
let photoToRemove = false;

// Load member profile data
async function loadProfile() {
    try {
        const token = localStorage.getItem('token');
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

        // Populate form fields
        document.getElementById('name').value = currentMember.name || '';
        document.getElementById('email').value = currentMember.email || '';
        document.getElementById('phone').value = currentMember.phone || '';
        document.getElementById('address').value = currentMember.address || '';
        document.getElementById('date_of_birth').value = currentMember.date_of_birth || '';
        document.getElementById('gender').value = currentMember.gender || '';

        // Set profile picture
        updateProfilePicture();

    } catch (error) {
        console.error('Error loading profile:', error);
        showError('Failed to load profile data');
    }
}

// Update profile picture display
function updateProfilePicture() {
    const avatar = document.getElementById('profile-avatar');
    if (currentMember.photo && !photoToRemove) {
        avatar.src = `/storage/${currentMember.photo}`;
    } else {
        avatar.src = generateAvatarUrl(currentMember.name);
    }
}

// Generate avatar URL for users without photos
function generateAvatarUrl(name) {
    const initials = name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57'];
    const color = colors[name.length % colors.length];
    
    return `data:image/svg+xml,${encodeURIComponent(`
        <svg width="128" height="128" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg">
            <rect width="128" height="128" fill="${color}"/>
            <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="48" font-weight="bold">${initials}</text>
        </svg>
    `)}`;
}

// Handle photo selection
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const avatar = document.getElementById('profile-avatar');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            avatar.src = e.target.result;
            photoToRemove = false;
        };
        reader.readAsDataURL(file);
    }
});

// Remove photo
function removePhoto() {
    photoToRemove = true;
    document.getElementById('photo').value = '';
    updateProfilePicture();
}

// Submit profile form
document.getElementById('profile-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const token = localStorage.getItem('token');
    
    // Add form data
    formData.append('name', document.getElementById('name').value);
    formData.append('phone', document.getElementById('phone').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('date_of_birth', document.getElementById('date_of_birth').value);
    formData.append('gender', document.getElementById('gender').value);
    
    // Handle photo
    const photoFile = document.getElementById('photo').files[0];
    if (photoFile) {
        formData.append('photo', photoFile);
    } else if (photoToRemove) {
        formData.append('remove_photo', '1');
    }
    
    const saveBtn = document.getElementById('save-btn');
    const saveText = document.getElementById('save-text');
    const saveLoading = document.getElementById('save-loading');
    
    try {
        saveBtn.disabled = true;
        saveText.classList.add('hidden');
        saveLoading.classList.remove('hidden');
        hideMessages();
        
        // Remove Content-Type header to let browser set it correctly for FormData
        const response = await fetch('/api/auth/profile', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentMember = data.data;
            photoToRemove = false;
            showSuccess('Profile updated successfully!');
            
            // Update localStorage
            localStorage.setItem('member', JSON.stringify(currentMember));
        } else {
            showError(data.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Profile update error:', error);
        showError('Network error. Please try again.');
    } finally {
        saveBtn.disabled = false;
        saveText.classList.remove('hidden');
        saveLoading.classList.add('hidden');
    }
});

// Change password
async function changePassword() {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showError('Please fill in all password fields');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showError('New passwords do not match');
        return;
    }
    
    if (newPassword.length < 6) {
        showError('New password must be at least 6 characters');
        return;
    }
    
    const passwordBtn = document.getElementById('password-btn');
    const passwordText = document.getElementById('password-text');
    const passwordLoading = document.getElementById('password-loading');
    const token = localStorage.getItem('token');
    
    try {
        passwordBtn.disabled = true;
        passwordText.classList.add('hidden');
        passwordLoading.classList.remove('hidden');
        hideMessages();
        
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
            showSuccess('Password changed successfully! Please log in again.');
            
            // Clear password fields
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
            
            // Redirect to login after 2 seconds
            setTimeout(() => {
                localStorage.removeItem('token');
                localStorage.removeItem('member');
                window.location.href = '/login';
            }, 2000);
        } else {
            showError(data.message || 'Failed to change password');
        }
    } catch (error) {
        console.error('Password change error:', error);
        showError('Network error. Please try again.');
    } finally {
        passwordBtn.disabled = false;
        passwordText.classList.remove('hidden');
        passwordLoading.classList.add('hidden');
    }
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.innerHTML = message;
    errorDiv.classList.remove('hidden');
    document.getElementById('success-message').classList.add('hidden');
}

// Show success message
function showSuccess(message) {
    const successDiv = document.getElementById('success-message');
    successDiv.innerHTML = message;
    successDiv.classList.remove('hidden');
    document.getElementById('error-message').classList.add('hidden');
}

// Hide all messages
function hideMessages() {
    document.getElementById('error-message').classList.add('hidden');
    document.getElementById('success-message').classList.add('hidden');
}

// Load profile when page loads
document.addEventListener('DOMContentLoaded', loadProfile);
</script>
@endsection