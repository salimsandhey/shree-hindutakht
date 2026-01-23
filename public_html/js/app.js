// Basic JavaScript functionality for Hindutakht application
document.addEventListener('DOMContentLoaded', function() {
    console.log('Hindutakht application loaded');
    
    // Mobile menu toggle if you have one
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            const menu = document.querySelector('.mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    }
    
    // Handle any alert messages
    const alertBoxes = document.querySelectorAll('.alert');
    if (alertBoxes.length > 0) {
        setTimeout(() => {
            alertBoxes.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    }
    
    // Login form handling
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        const errorDiv = document.getElementById('error-message');
        const loginBtn = document.getElementById('login-btn');
        const loginText = document.getElementById('login-text');
        const loginLoading = document.getElementById('login-loading');
        
        if (errorDiv && loginBtn && loginText && loginLoading) {
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                // Show loading state
                loginBtn.disabled = true;
                loginText.classList.add('hidden');
                loginLoading.classList.remove('hidden');
                errorDiv.classList.add('hidden');
                
                try {
                    // Simple form validation
                    if (!email || !password) {
                        throw new Error('Please fill in all fields');
                    }
                    
                    // In a real app, you would make an API call here
                    console.log('Login attempt with:', { email, password });
                    
                    // Simulate API call delay
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    
                    // For demo purposes, show success
                    alert('Login successful! In a real application, you would be redirected to the dashboard.');
                    
                } catch (error) {
                    errorDiv.textContent = error.message || 'Login failed. Please try again.';
                    errorDiv.classList.remove('hidden');
                } finally {
                    loginBtn.disabled = false;
                    loginText.classList.remove('hidden');
                    loginLoading.classList.add('hidden');
                }
            });
        }
    }
});