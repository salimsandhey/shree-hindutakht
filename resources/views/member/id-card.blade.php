@extends('layouts.member')

@section('title', 'Member ID Card - Shree Hindutakht')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 mobile-container page-transition">
    <!-- Header -->
    <div class="sticky top-0 bg-white border-b border-gray-200 z-30 mobile-header">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center space-x-3">
                <button onclick="history.back()" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors touch-target mobile-tap-highlight">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 animate-fade-in-down">Member ID Card</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 flex justify-center min-h-screen bg-gray-50 mobile-content">
        <!-- Error Messages -->
        <div id="error-message" class="hidden fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-50 border border-red-200 rounded-xl p-4 z-40 animate-fade-in">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div id="error-text" class="ml-3 text-sm text-red-700"></div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="text-center animate-fade-in">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
            <p class="text-gray-600">Loading ID Card...</p>
        </div>

        <!-- ID Card -->
        <div id="id-card-container" class="hidden animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden card" style="width: 400px;  border: 3px solid #b93a20;">
                <!-- Header with Logo and Organization Name -->
                <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white p-3 relative" style="background: linear-gradient(135deg, #b93a20 0%, #d4472a 100%);">
                    <div class="flex items-center justify-center space-x-3">
                        <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht Logo" class="h-8 w-8 object-contain bg-white rounded-full p-1">
                        <div class="text-center">
                            <h1 class="text-lg font-bold tracking-wide">SHREE HINDUTAKHT</h1>
                            <p class="text-xs opacity-90 font-medium">MEMBER IDENTITY CARD</p>
                        </div>
                    </div>
                    <div class="absolute top-2 right-2 text-xs opacity-75">
                        <span id="card-year">2024</span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 bg-white">
                    <div class="flex space-x-4">
                        <!-- Member Photo -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img id="member-photo" class="w-16 h-20 object-cover rounded-lg border-2 border-gray-200 shadow-sm" src="" alt="Member Photo" style="display: none;">
                                <div id="member-photo-placeholder" class="w-16 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                                <!-- Status Badge -->
                                <div class="absolute -top-1 -right-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded-full text-center" style="font-size: 8px;">
                                    <span id="member-status">ACTIVE</span>
                                </div>
                            </div>
                        </div>

                        <!-- Member Details -->
                        <div class="flex-1 space-y-1">
                            <div class="border-b border-gray-100 pb-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Member ID</p>
                                <p id="member-id" class="font-bold text-sm text-primary">Loading...</p>
                            </div>
                            <div class="border-b border-gray-100 pb-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Full Name</p>
                                <p id="member-name" class="font-semibold text-sm text-gray-800">Loading...</p>
                            </div>
                            <div class="border-b border-gray-100 pb-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                                <p id="member-email" class="text-xs text-gray-600">Loading...</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-gray-500 uppercase tracking-wide">Joined</p>
                                    <p id="join-date" class="text-gray-700 font-medium">Loading...</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 uppercase tracking-wide">Valid Until</p>
                                    <p id="valid-until" class="text-gray-700 font-medium">Loading...</p>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="flex-shrink-0 text-center">
                            <div id="qr-code-container" class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mb-1">
                                <img id="qr-code" class="w-full h-full object-contain rounded" src="" alt="QR Code" style="display: none;">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zM12 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500">Scan to verify</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <div class="flex justify-between items-center text-xs">
                            <div class="text-gray-500">
                                <span class="font-medium">Valid for community access</span>
                            </div>
                            <div class="text-right text-gray-400">
                                <p>www.shreehindutakht.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <div class="mt-6 flex justify-center">
                <button id="download-png-btn" class="btn-primary flex items-center touch-target mobile-button mobile-tap-highlight">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download ID Card
                </button>
            </div>
        </div>
    </div>

</div>

<script>
// Navigation function
function navigateToSection(section) {
    window.location.href = '/home#' + section;
}

// Initialize ID card page
document.addEventListener('DOMContentLoaded', function() {
    loadIdCardData();
    initializeEventListeners();
});

// Load ID card data
async function loadIdCardData() {
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        const response = await fetch('/api/member/id-card', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            populateIdCard(data.data);
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('id-card-container').classList.remove('hidden');
        } else {
            throw new Error(data.message || 'Failed to load ID card data');
        }
    } catch (error) {
        console.error('Failed to load ID card:', error);
        showError('Failed to load ID card data. Please try again.');
        document.getElementById('loading-state').classList.add('hidden');
        
        if (error.message.includes('Unauthorized')) {
            window.location.href = '/login';
        }
    }
}

// Populate ID card with data
function populateIdCard(data) {
    // Basic info
    document.getElementById('member-id').textContent = data.member_id || '-';
    document.getElementById('member-name').textContent = data.name || '-';
    document.getElementById('member-email').textContent = data.email || '-';
    document.getElementById('join-date').textContent = data.join_date || '-';
    document.getElementById('valid-until').textContent = data.valid_until || '-';
    document.getElementById('member-status').textContent = (data.status || 'active').toUpperCase();
    document.getElementById('card-year').textContent = new Date().getFullYear();

    // Member photo
    const memberPhoto = document.getElementById('member-photo');
    const memberPhotoPlaceholder = document.getElementById('member-photo-placeholder');
    
    if (data.photo && data.photo !== 'undefined' && !data.photo.includes('undefined')) {
        memberPhoto.src = data.photo;
        memberPhoto.style.display = 'block';
        memberPhotoPlaceholder.style.display = 'none';
        
        // Handle broken images
        memberPhoto.onerror = function() {
            memberPhoto.style.display = 'none';
            memberPhotoPlaceholder.style.display = 'flex';
            
            // Show initials in placeholder
            const initials = data.name ? data.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
            memberPhotoPlaceholder.innerHTML = `<span class="text-gray-600 font-bold text-sm">${initials}</span>`;
        };
    } else {
        // Show initials in placeholder
        const initials = data.name ? data.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase() : '??';
        memberPhotoPlaceholder.innerHTML = `<span class="text-gray-600 font-bold text-sm">${initials}</span>`;
    }

    // QR Code
    const qrCode = document.getElementById('qr-code');
    if (data.qr_code) {
        qrCode.src = data.qr_code;
        qrCode.style.display = 'block';
        qrCode.parentElement.querySelector('svg').style.display = 'none';
    }
}

// Initialize event listeners
function initializeEventListeners() {
    // Download button
    document.getElementById('download-png-btn').addEventListener('click', downloadIdCard('png'));
}

// Download ID card as PNG
function downloadIdCard(format) {
    return async function() {
        // Show loading state
        const button = document.getElementById('download-png-btn');
        const originalText = button.innerHTML;
        button.innerHTML = `<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Generating...`;
        button.disabled = true;
        
        try {
            // For PNG, capture the exact visual appearance using html2canvas
            await downloadIdCardAsImage();
        } catch (error) {
            console.error('Download error:', error);
            showError(`Failed to download ID card. ${error.message || 'Please try again.'}`);
        } finally {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        }
    };
}

// Share ID card
function shareIdCard() {
    if (navigator.share) {
        navigator.share({
            title: 'My Shree Hindutakht Member ID Card',
            text: 'Check out my member ID card for Shree Hindutakht',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback: copy URL to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('ID card link copied to clipboard!');
        }).catch(() => {
            alert('Unable to share. Please copy the URL manually.');
        });
    }
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    errorText.textContent = message;
    errorDiv.classList.remove('hidden');
    errorDiv.classList.remove('bg-green-50', 'border-green-200');
    errorDiv.classList.add('bg-red-50', 'border-red-200');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorDiv.classList.add('hidden');
    }, 5000);
}

// Show success message
function showSuccess(message) {
    const successDiv = document.getElementById('error-message');
    const successText = document.getElementById('error-text');
    successText.textContent = message;
    successDiv.classList.remove('hidden');
    successDiv.classList.remove('bg-red-50', 'border-red-200');
    successDiv.classList.add('bg-green-50', 'border-green-200');
    
    // Change icon to checkmark
    const icon = successDiv.querySelector('svg');
    if (icon) {
        icon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>';
        icon.classList.remove('text-red-400');
        icon.classList.add('text-green-400');
    }
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
        // Reset icon
        if (icon) {
            icon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
            icon.classList.remove('text-green-400');
            icon.classList.add('text-red-400');
        }
    }, 3000);
}

// Download ID card as image using html2canvas
async function downloadIdCardAsImage() {
    try {
        const cardElement = document.querySelector('.card');
        
        // Use html2canvas to capture the element
        const canvas = await html2canvas(cardElement, {
            backgroundColor: '#ffffff',
            scale: 2, // Higher resolution
            useCORS: true,
            logging: false
        });
        
        // Convert to blob
        canvas.toBlob((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `hindutakht_id_card_${new Date().getTime()}.png`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            // Show success message
            showSuccess('PNG downloaded successfully!');
        }, 'image/png', 0.95); // 95% quality
        
    } catch (error) {
        console.error('Image capture error:', error);
        throw new Error('Failed to capture ID card as image');
    }
}

// Print styles
const style = document.createElement('style');
style.textContent = `
    @media print {
        body * {
            visibility: hidden;
        }
        #id-card-container, #id-card-container * {
            visibility: visible;
        }
        #id-card-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .bottom-nav, header, nav {
            display: none !important;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection