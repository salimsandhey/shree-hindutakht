@extends('layouts.app')

@section('title', 'Admin Login - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="form-container w-full max-w-md">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <div class="mx-auto bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
                <p class="text-gray-600 mt-2">Sign in to your admin account</p>
            </div>
            
            <form id="login-form">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" class="input-field" required>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="input-field" required>
                </div>
                
                <button type="submit" class="btn-primary w-full">
                    <span id="button-text">Sign In</span>
                    <span id="button-spinner" class="hidden">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing in...
                    </span>
                </button>
                
                <div id="error-message" class="mt-4 text-center text-red-500 hidden"></div>
            </form>
        </div>
        
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>© {{ date('Y') }} Hindutakht. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
// Admin Auth token management
class AdminAuthManager {
    static setToken(token) {
        // Store in multiple locations for redundancy
        localStorage.setItem('admin_auth_token', token);
        sessionStorage.setItem('admin_auth_token', token);
        
        // Also set in a cookie for web routes with proper settings
        document.cookie = `admin_auth_token=${token}; path=/; SameSite=Lax`;
    }

    static removeToken() {
        localStorage.removeItem('admin_auth_token');
        sessionStorage.removeItem('admin_auth_token');
        // Also remove cookie
        document.cookie = 'admin_auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=Lax';
    }
}

// Admin Login API
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    
    // Hide error message
    errorMessage.classList.add('hidden');
    
    // Show loading state
    buttonText.classList.add('hidden');
    buttonSpinner.classList.remove('hidden');
    document.querySelector('.btn-primary').disabled = true;
    
    try {
        const response = await fetch('/api/admin/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Store token
            AdminAuthManager.setToken(data.data.token);
            
            // Redirect to dashboard
            window.location.href = '/admin/dashboard';
        } else {
            errorMessage.textContent = data.message || 'Login failed';
            errorMessage.classList.remove('hidden');
        }
    } catch (error) {
        errorMessage.textContent = 'Network error. Please try again.';
        errorMessage.classList.remove('hidden');
    } finally {
        // Restore button state
        buttonText.classList.remove('hidden');
        buttonSpinner.classList.add('hidden');
        document.querySelector('.btn-primary').disabled = false;
    }
});
</script>
@endsection