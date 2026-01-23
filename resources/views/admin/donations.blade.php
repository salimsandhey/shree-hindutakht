@extends('admin.layouts.admin')

@section('title', 'Donation Management - Shree Hindutakht Admin')

@section('content')
<div id="donations-view" class="section-view with-fixed-header with-fixed-bottom-nav">
    <!-- Page Title -->
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">Donation Management</h1>
        <div class="flex justify-between items-center mt-2">
            <p class="text-gray-600 text-sm">Manage donation details and settings</p>
            <button onclick="openEditDonationModal()" class="btn-primary px-3 py-1 text-sm">
                Edit Donation Details
            </button>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-4 space-y-4">
        <!-- Bank Details Card -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800">Bank Details</h3>
            </div>
            <div id="bank-details" class="space-y-4">
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading bank details...</p>
                </div>
            </div>
        </div>

        <!-- UPI Information Card -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800">UPI Information</h3>
            </div>
            <div id="upi-details" class="space-y-4">
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading UPI details...</p>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Edit Donation Modal -->
<div id="edit-donation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Edit Donation Details</h3>
                <button onclick="closeEditDonationModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="edit-donation-form">
                    <div class="space-y-4">
                        <!-- Bank Details Section -->
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">Bank Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label for="bank-name" class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                                    <input type="text" id="bank-name" class="input-field">
                                </div>
                                <div>
                                    <label for="account-name" class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                                    <input type="text" id="account-name" class="input-field">
                                </div>
                                <div>
                                    <label for="account-number" class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                    <input type="text" id="account-number" class="input-field">
                                </div>
                                <div>
                                    <label for="ifsc-code" class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                                    <input type="text" id="ifsc-code" class="input-field">
                                </div>
                                <div>
                                    <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                                    <input type="text" id="branch" class="input-field">
                                </div>
                            </div>
                        </div>
                        
                        <!-- UPI Details Section -->
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">UPI Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label for="upi-id" class="block text-sm font-medium text-gray-700 mb-1">UPI ID</label>
                                    <input type="text" id="upi-id" class="input-field">
                                </div>
                                <div>
                                    <label for="donation-message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea id="donation-message" rows="2" class="input-field"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">QR Code (Optional)</label>
                                    <div class="mt-1 flex justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="qr-code" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-orange-600">
                                                    <span>Upload QR code</span>
                                                    <input id="qr-code" name="qr_code" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        </div>
                                    </div>
                                    <div id="current-qr-container" class="mt-2 hidden">
                                        <p class="text-sm text-gray-600 mb-1">Current QR Code:</p>
                                        <img id="current-qr-image" src="" alt="Current QR code" class="w-32 h-32 object-contain">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEditDonationModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm">
                            Update Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2" style="z-index: 9999;"></div>

<script>
// Toast notification system
const Toast = {
    // Create a toast notification
    show: function(message, type = 'info', duration = 3000) {
        const toastContainer = document.getElementById('toast-container');
        
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastElement = document.createElement('div');
        toastElement.id = toastId;
        toastElement.className = `transform transition-all duration-300 ease-in-out max-w-md w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden`;
        
        // Set styles based on type
        let bgColor = 'bg-blue-500';
        let textColor = 'text-white';
        
        switch(type) {
            case 'success':
                bgColor = 'bg-green-500';
                break;
            case 'error':
                bgColor = 'bg-red-500';
                break;
            case 'warning':
                bgColor = 'bg-yellow-500';
                textColor = 'text-gray-900';
                break;
            case 'info':
            default:
                bgColor = 'bg-blue-500';
                break;
        }
        
        toastElement.innerHTML = `
            <div class="p-4 ${bgColor} ${textColor}">
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button id="close-${toastId}" class="rounded-md inline-flex text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add to container
        toastContainer.appendChild(toastElement);
        
        // Add close event
        document.getElementById(`close-${toastId}`).addEventListener('click', () => {
            this.hide(toastId);
        });
        
        // Auto hide after duration
        if (duration > 0) {
            setTimeout(() => {
                this.hide(toastId);
            }, duration);
        }
        
        // Trigger enter animation
        setTimeout(() => {
            toastElement.classList.add('translate-x-0', 'opacity-100');
        }, 10);
        
        return toastId;
    },
    
    // Hide a toast notification
    hide: function(toastId) {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            toastElement.classList.remove('translate-x-0', 'opacity-100');
            toastElement.classList.add('translate-x-full', 'opacity-0');
            
            // Remove element after animation
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300);
        }
    },
    
    // Show success toast
    success: function(message, duration = 3000) {
        return this.show(message, 'success', duration);
    },
    
    // Show error toast
    error: function(message, duration = 5000) {
        return this.show(message, 'error', duration);
    },
    
    // Show warning toast
    warning: function(message, duration = 0) {
        return this.show(message, 'warning', duration);
    },
    
    // Show info toast
    info: function(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
};

// Load donation information
async function loadDonationInfo() {
    try {
        // Load donation info from the admin API endpoint
        const response = await AdminAPI.get('/donations');
        
        if (response.success) {
            // Update bank details
            const bankDetails = document.getElementById('bank-details');
            bankDetails.innerHTML = `
                <div class="space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.bank_details.bank_name}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="block text-sm font-medium text-gray-700">Account Name</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.bank_details.account_name}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="block text-sm font-medium text-gray-700">Account Number</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.bank_details.account_number}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="block text-sm font-medium text-gray-700">IFSC Code</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.bank_details.ifsc_code}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Branch</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.bank_details.branch}</p>
                    </div>
                </div>
            `;
            
            // Update UPI details
            const upiDetails = document.getElementById('upi-details');
            upiDetails.innerHTML = `
                <div class="space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <label class="block text-sm font-medium text-gray-700">UPI ID</label>
                        <p class="mt-1 text-sm text-gray-900">${response.data.upi_id}</p>
                    </div>
                    <div class="text-center py-4">
                        <img src="${response.data.qr_code}" alt="Donation QR Code" class="w-48 h-48 mx-auto">
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 text-center">${response.data.message}</p>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading donation info:', error);
        Toast.error('Error loading donation information: ' + error.message);
    }
}



// Modal functions
function openEditDonationModal() {
    // Load current donation details into the form
    loadDonationDetailsIntoForm();
    document.getElementById('edit-donation-modal').classList.remove('hidden');
}

function closeEditDonationModal() {
    document.getElementById('edit-donation-modal').classList.add('hidden');
    document.getElementById('edit-donation-form').reset();
}

// Load current donation details into the edit form
async function loadDonationDetailsIntoForm() {
    try {
        const response = await AdminAPI.get('/donations');
        
        if (response.success) {
            const data = response.data;
            
            // Populate bank details
            document.getElementById('bank-name').value = data.bank_details.bank_name;
            document.getElementById('account-name').value = data.bank_details.account_name;
            document.getElementById('account-number').value = data.bank_details.account_number;
            document.getElementById('ifsc-code').value = data.bank_details.ifsc_code;
            document.getElementById('branch').value = data.bank_details.branch;
            
            // Populate UPI details
            document.getElementById('upi-id').value = data.upi_id;
            document.getElementById('donation-message').value = data.message;
            
            // Show current QR code if exists
            const currentQrContainer = document.getElementById('current-qr-container');
            const currentQrImage = document.getElementById('current-qr-image');
            if (data.qr_code) {
                currentQrImage.src = data.qr_code;
                currentQrContainer.classList.remove('hidden');
            } else {
                currentQrContainer.classList.add('hidden');
            }
        }
    } catch (error) {
        Toast.error('Error loading donation details: ' + error.message);
    }
}

// Form submission - Edit Donation Details
document.getElementById('edit-donation-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const formData = new FormData();
        formData.append('_method', 'PUT'); // Laravel needs this for PUT requests
        
        // Append bank details
        formData.append('bank_name', document.getElementById('bank-name').value);
        formData.append('account_name', document.getElementById('account-name').value);
        formData.append('account_number', document.getElementById('account-number').value);
        formData.append('ifsc_code', document.getElementById('ifsc-code').value);
        formData.append('branch', document.getElementById('branch').value);
        
        // Append UPI details
        formData.append('upi_id', document.getElementById('upi-id').value);
        formData.append('message', document.getElementById('donation-message').value);
        
        // Append QR code if selected
        const qrCodeInput = document.getElementById('qr-code');
        if (qrCodeInput.files.length > 0) {
            formData.append('qr_code', qrCodeInput.files[0]);
        }
        
        const response = await AdminAPI.postWithFiles('/donations', formData);
        if (response.success) {
            Toast.success('Donation details updated successfully');
            closeEditDonationModal();
            loadDonationInfo(); // Reload the displayed details
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error updating donation details: ' + error.message);
    }
});

// Initialize when the DOM content is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadDonationInfo();
});
</script>
@endsection