// Public Page Enhancements for Mobile App-like Experience

document.addEventListener('DOMContentLoaded', function() {
    console.log('Public page enhancements loaded');
    
    // Detect mobile device for optimizations
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Apply mobile optimizations
    if (isMobile) {
        document.body.classList.add('mobile-device');
        
        // Reduce animation durations on mobile
        const style = document.createElement('style');
        style.textContent = `
            .animate-fade-in,
            .animate-fade-in-down,
            .animate-fade-in-up,
            .card,
            .nav-item,
            .mobile-button {
                animation-duration: 0.2s !important;
                transition-duration: 0.2s !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Add fade-in animations to elements with animate-fade-in class
    // Optimize animation performance
    const fadeElements = document.querySelectorAll('.animate-fade-in, .animate-fade-in-down, .animate-fade-in-up');
    
    // Use requestAnimationFrame for better performance
    fadeElements.forEach((el, index) => {
        // Reduce delay on mobile devices
        const delay = isMobile ? index * 50 : index * 100;
        
        setTimeout(() => {
            requestAnimationFrame(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                
                // Add performance hint
                el.style.willChange = 'auto';
            });
        }, delay);
    });
    
    // Enhance touch targets for better mobile experience
    const touchTargets = document.querySelectorAll('.touch-target');
    touchTargets.forEach(target => {
        // Add visual feedback for touch
        target.addEventListener('touchstart', function() {
            this.classList.add('touch-active');
        });
        
        target.addEventListener('touchend', function() {
            this.classList.remove('touch-active');
        });
    });
    
    // Add smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#' && document.querySelector(targetId)) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Implement lazy loading for images
    setupLazyLoading();
    
    // Add loading indicators for navigation
    const navLinks = document.querySelectorAll('.nav-item, .mobile-button');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            this.classList.add('opacity-75');
            
            // If it's a navigation link, add a slight delay for better UX
            if (this.classList.contains('nav-item') || this.classList.contains('mobile-button')) {
                setTimeout(() => {
                    this.classList.remove('opacity-75');
                }, 300);
            }
        });
    });
    
    // Mobile app-like page transition handling
    setupPageTransitions();
    
    // Add ripple effect to touch targets
    addRippleEffect();
    
    // Initialize member module specific enhancements
    initializeMemberModule();
    
    // Setup member module touch interactions
    setupMemberTouchInteractions();
    
    // Setup member module page transitions
    setupMemberPageTransitions();
});

// Setup mobile app-like page transitions
function setupPageTransitions() {
    // Add transition classes to the app container
    const appContainer = document.getElementById('app');
    if (appContainer) {
        appContainer.classList.add('mobile-container');
    }
    
    // Handle navigation link clicks for smooth transitions
    const navLinks = document.querySelectorAll('.nav-item a, .nav-transition');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't handle external links or anchor links
            if (this.hostname !== window.location.hostname || this.getAttribute('href').startsWith('#')) {
                return;
            }
            
            e.preventDefault();
            const targetUrl = this.href;
            
            // Add transition class to app container
            const appContainer = document.getElementById('app');
            if (appContainer) {
                appContainer.classList.add('page-transition-enter');
                
                // Show loading indicator
                const loadingIndicator = document.getElementById('mobile-loading');
                if (loadingIndicator) {
                    loadingIndicator.classList.remove('hidden');
                }
                
                // Navigate after animation completes
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            } else {
                // Show loading indicator
                const loadingIndicator = document.getElementById('mobile-loading');
                if (loadingIndicator) {
                    loadingIndicator.classList.remove('hidden');
                }
                
                // Fallback navigation
                window.location.href = targetUrl;
            }
        });
    });
}

// Setup member module page transitions
function setupMemberPageTransitions() {
    // Check if we're on a member page
    const isMemberPage = window.location.pathname.startsWith('/member');
    
    if (isMemberPage) {
        console.log('Setting up member module page transitions');
        
        // Add transition classes to the app container
        const appContainer = document.getElementById('app');
        if (appContainer) {
            appContainer.classList.add('mobile-container', 'member-page-transition');
        }
        
        // Handle navigation link clicks for smooth transitions
        const memberNavLinks = document.querySelectorAll('.bottom-nav .nav-item a, .nav-transition');
        memberNavLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Don't handle external links or anchor links
                if (this.hostname !== window.location.hostname || this.getAttribute('href').startsWith('#')) {
                    return;
                }
                
                e.preventDefault();
                const targetUrl = this.href;
                
                // Add transition class to app container
                const appContainer = document.getElementById('app');
                if (appContainer) {
                    appContainer.classList.add('member-page-transition-enter');
                    
                    // Show loading indicator
                    const loadingIndicator = document.getElementById('mobile-loading');
                    if (loadingIndicator) {
                        loadingIndicator.classList.remove('hidden');
                    }
                    
                    // Navigate after animation completes
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 300);
                } else {
                    // Show loading indicator
                    const loadingIndicator = document.getElementById('mobile-loading');
                    if (loadingIndicator) {
                        loadingIndicator.classList.remove('hidden');
                    }
                    
                    // Fallback navigation
                    window.location.href = targetUrl;
                }
            });
        });
    }
}

// Add touch active styles
const style = document.createElement('style');
style.textContent = `
    .touch-active {
        opacity: 0.7 !important;
        transform: scale(0.98) !important;
        transition: all 0.1s ease-in-out !important;
    }
    
    /* Prevent text selection on mobile */
    .mobile-button, .nav-item, .touch-target {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
`;
document.head.appendChild(style);

// Handle back button behavior for better mobile experience
window.addEventListener('popstate', function(event) {
    console.log('Navigation via back/forward button');
    // Add a subtle transition when navigating with back/forward buttons
    const appContainer = document.getElementById('app');
    if (appContainer) {
        appContainer.classList.add('page-transition-leave');
        setTimeout(() => {
            appContainer.classList.remove('page-transition-leave');
        }, 300);
    }
});

// Add mobile app-like pull to refresh functionality
function setupPullToRefresh() {
    let touchStartY = 0;
    let touchEndY = 0;
    
    document.addEventListener('touchstart', e => {
        touchStartY = e.changedTouches[0].screenY;
    }, false);
    
    document.addEventListener('touchend', e => {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        const swipeDistance = touchEndY - touchStartY;
        
        // Detect downward swipe at top of page
        if (swipeDistance > 100 && window.scrollY === 0) {
            console.log('Pull to refresh detected');
            // Add visual indicator
            const indicator = document.createElement('div');
            indicator.id = 'pull-refresh-indicator';
            indicator.innerHTML = `
                <div style="position: fixed; top: 0; left: 50%; transform: translateX(-50%); 
                    background: rgba(0,0,0,0.7); color: white; padding: 8px 16px; 
                    border-radius: 0 0 8px 8px; z-index: 1000; font-size: 14px;">
                    Refreshing...
                </div>`;
            document.body.appendChild(indicator);
            
            // Refresh page after delay
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    }
}

// Initialize pull to refresh on DOM loaded
document.addEventListener('DOMContentLoaded', setupPullToRefresh);

// Add ripple effect to touch targets
function addRippleEffect() {
    const touchTargets = document.querySelectorAll('.touch-target, .mobile-button, .nav-item');
    touchTargets.forEach(target => {
        target.classList.add('touch-ripple');
    });
}

// Add mobile app-like swipe navigation
function setupSwipeNavigation() {
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, false);
    
    document.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        const swipeThreshold = 100;
        const swipeDistance = touchEndX - touchStartX;
        
        // Detect swipe left (next page)
        if (swipeDistance < -swipeThreshold) {
            console.log('Swipe left detected - navigate to next page');
            // Add navigation logic here if needed
        }
        
        // Detect swipe right (previous page)
        if (swipeDistance > swipeThreshold) {
            console.log('Swipe right detected - navigate to previous page');
            // Add navigation logic here if needed
        }
    }
}

// Initialize swipe navigation on DOM loaded
document.addEventListener('DOMContentLoaded', setupSwipeNavigation);

// Implement lazy loading for images
function setupLazyLoading() {
    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Load the image
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                        img.removeAttribute('data-srcset');
                    }
                    
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        // Observe all images with the 'lazy' class
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
            
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
                img.removeAttribute('data-srcset');
            }
            
            img.classList.remove('lazy');
            img.classList.add('loaded');
        });
    }
}

// Initialize member module specific enhancements
function initializeMemberModule() {
    // Check if we're on a member page
    const isMemberPage = window.location.pathname.startsWith('/member');
    
    if (isMemberPage) {
        console.log('Initializing member module enhancements');
        
        // Add member-specific animations
        const memberElements = document.querySelectorAll('.member-animate');
        memberElements.forEach((el, index) => {
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Enhance member navigation
        const memberNavItems = document.querySelectorAll('.bottom-nav .nav-item');
        memberNavItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Add active state immediately
                memberNavItems.forEach(navItem => navItem.classList.remove('active'));
                this.classList.add('active');
                
                // Add visual feedback
                this.classList.add('scale-95');
                setTimeout(() => {
                    this.classList.remove('scale-95');
                }, 150);
            });
        });
    }
}

// Setup member module touch interactions
function setupMemberTouchInteractions() {
    // Check if we're on a member page
    const isMemberPage = window.location.pathname.startsWith('/member');
    
    if (isMemberPage) {
        console.log('Setting up member module touch interactions');
        
        // Enhance member-specific touch targets
        const memberTouchTargets = document.querySelectorAll('.member-touch-target');
        memberTouchTargets.forEach(target => {
            // Add visual feedback for touch
            target.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            target.addEventListener('touchend', function() {
                this.classList.remove('touch-active');
            });
            
            // Add ripple effect
            target.classList.add('touch-ripple');
        });
        
        // Enhance member buttons
        const memberButtons = document.querySelectorAll('.member-button');
        memberButtons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            button.addEventListener('touchend', function() {
                this.classList.remove('touch-active');
            });
        });
        
        // Add long press functionality for member cards
        const memberCards = document.querySelectorAll('.member-card');
        memberCards.forEach(card => {
            let pressTimer;
            
            card.addEventListener('touchstart', function() {
                pressTimer = setTimeout(() => {
                    // Long press action
                    console.log('Long press detected on member card');
                    // Add your long press functionality here
                }, 500); // 500ms for long press
            });
            
            card.addEventListener('touchend', function() {
                clearTimeout(pressTimer);
            });
            
            card.addEventListener('touchmove', function() {
                clearTimeout(pressTimer);
            });
        });
        
        // Add swipe functionality for member lists
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleMemberSwipe();
        }, false);
        
        function handleMemberSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            // Detect swipe left (next item)
            if (swipeDistance < -swipeThreshold) {
                console.log('Swipe left detected in member module');
                // Add navigation logic here if needed
            }
            
            // Detect swipe right (previous item)
            if (swipeDistance > swipeThreshold) {
                console.log('Swipe right detected in member module');
                // Add navigation logic here if needed
            }
        }
    }
}