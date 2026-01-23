@extends('layouts.app')

@section('title', 'Register - Shree Hindutakht')

@section('content')

<div class="min-h-screen flex items-center justify-center p-4 mobile-content">
    <div class="w-full max-w-md">

        <!-- Logo Section -->
        <div class="text-center mb-6">
            <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht" class="h-16 mx-auto mb-3 object-contain lazy" data-src="{{ asset('logo3.png') }}" loading="lazy">
            <p class="text-gray-600 text-sm mt-1">Become part of our community</p>
        </div>

        <!-- Registration Form -->
        <div class="card">
            <form id="register-form" enctype="multipart/form-data">
                <div class="space-y-4">
                    <!-- Profile Picture Upload -->
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div id="photo-preview" class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-2 overflow-hidden">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="file" id="photo" name="photo" accept="image/*,.webp" class="hidden">
                            <button type="button" onclick="document.getElementById('photo').click()" class="absolute bottom-0 right-0 bg-primary text-white rounded-full p-1 shadow-lg hover:bg-primary-dark transition-colors touch-target nav-transition" style="display: flex; justify-content: center; align-items: center;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">Optional: Upload profile picture (max 10MB)</p>
                        <p class="text-xs text-gray-400">Images will be compressed to WebP format (~50KB) for optimal performance</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" class="input-field" placeholder="Enter your full name" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" class="input-field" placeholder="Enter your email" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" class="input-field" placeholder="Enter your phone number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" class="input-field" placeholder="Create a password" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" class="input-field" placeholder="Confirm your password" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address (Optional)</label>
                        <textarea id="address" class="input-field" rows="2" placeholder="Enter your address"></textarea>
                    </div>

                    <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    </div>

                    <button type="submit" id="register-btn" class="btn-primary w-full mobile-button touch-target nav-transition">
                        <span id="register-text">Create Account</span>
                        <span id="register-loading" class="hidden">Creating account...</span>
                    </button>
                </div>
            </form>

            <!-- Additional Navigation Options -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm mb-4">Already have an account? 
                    <a href="/login" class="text-primary font-medium hover:text-primary-dark nav-transition">Sign in</a>
                </p>
                
                <!-- Mobile App-like Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue as</span>
                    </div>
                </div>
                
                <!-- Guest Access Button -->
                <a href="/" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary nav-transition touch-target">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path>
                    </svg>
                    Continue as Guest
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Photo preview functionality with validation
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');
    const errorDiv = document.getElementById('error-message');
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            errorDiv.innerHTML = 'Please select a valid image file.';
            errorDiv.classList.remove('hidden');
            e.target.value = ''; // Clear the input
            return;
        }
        
        // Validate file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            errorDiv.innerHTML = 'Image size must be less than 10MB.';
            errorDiv.classList.remove('hidden');
            e.target.value = ''; // Clear the input
            return;
        }
        
        // Hide any previous errors
        errorDiv.classList.add('hidden');
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Profile preview">`;
        };
        reader.readAsDataURL(file);
        
        // Show compression info
        const compressionInfo = document.createElement('p');
        compressionInfo.className = 'text-xs text-green-600 mt-1';
        compressionInfo.textContent = `Image selected (${(file.size / 1024 / 1024).toFixed(2)} MB). Will be compressed to WebP format (~50KB).`;
        
        // Remove any existing compression info
        const existingInfo = preview.parentNode.querySelector('.text-green-600');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        preview.parentNode.appendChild(compressionInfo);
    } else {
        preview.innerHTML = `
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        `;
        
        // Remove compression info
        const compressionInfo = preview.parentNode.querySelector('.text-green-600');
        if (compressionInfo) {
            compressionInfo.remove();
        }
    }
});
</script>
@endsection