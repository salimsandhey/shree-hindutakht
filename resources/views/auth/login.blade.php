@extends('layouts.app')

@section('title', 'Login - Shree Hindutakht')

@section('content')


<div class="min-h-screen flex items-center justify-center p-4 mobile-content">
    <div class="w-full max-w-md">
        <!-- Back to Home Button for Mobile App-like Experience -->
        <!-- <div class="md:hidden mb-4">
            <a href="/" class="flex items-center text-primary font-medium hover:text-primary-dark transition-colors touch-target nav-transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </a>
        </div> -->

        <!-- Logo Section -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht" class="h-20 mx-auto mb-4 object-contain lazy" data-src="{{ asset('logo3.png') }}" loading="lazy">
            <p class="text-gray-600 mt-2">Welcome back to our community</p>
        </div>

        <!-- Login Form -->
        <div class="card">
            <form id="login-form">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" class="input-field" placeholder="Enter your email" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" class="input-field" placeholder="Enter your password" required>
                    </div>

                    <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    </div>

                    <button type="submit" id="login-btn" class="btn-primary w-full mobile-button touch-target nav-transition">
                        <span id="login-text">Sign In</span>
                        <span id="login-loading" class="hidden">Signing in...</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Don't have an account? 
                    <a href="/register" class="text-primary font-medium hover:text-primary-dark nav-transition">Sign up</a>
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

        <!-- Features Preview -->
        <div class="mt-8 grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-white rounded-xl shadow-soft touch-target nav-transition">
                <div class="text-2xl mb-2">📱</div>
                <p class="text-sm text-gray-600">Stay Connected</p>
            </div>
            <div class="text-center p-4 bg-white rounded-xl shadow-soft touch-target nav-transition">
                <div class="text-2xl mb-2">📅</div>
                <p class="text-sm text-gray-600">Join Events</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const loginBtn = document.getElementById('login-btn');
    const loginText = document.getElementById('login-text');
    const loginLoading = document.getElementById('login-loading');
    const errorMessage = document.getElementById('error-message');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Show loading state
        loginBtn.disabled = true;
        loginText.classList.add('hidden');
        loginLoading.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store token in localStorage
                localStorage.setItem('auth_token', data.data.token);
                localStorage.setItem('member', JSON.stringify(data.data.member));
                
                // Redirect to member dashboard instead of home
                window.location.href = '/member/dashboard';
            } else {
                // Show error message
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('hidden');
            }
        } catch (error) {
            errorMessage.textContent = 'An error occurred during login. Please try again.';
            errorMessage.classList.remove('hidden');
        } finally {
            // Reset loading state
            loginBtn.disabled = false;
            loginText.classList.remove('hidden');
            loginLoading.classList.add('hidden');
        }
    });
});
</script>
@endsection