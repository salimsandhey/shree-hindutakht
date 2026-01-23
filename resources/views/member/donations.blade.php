@extends('layouts.member')

@section('title', 'Donation - Shree Hindutakht')

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
                <h1 class="text-lg font-semibold text-gray-800 animate-fade-in-down">Make a Donation</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 mobile-content">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden card animate-fade-in">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 animate-fade-in-down">Support Shree Hindutakht</h2>
                        <p class="text-gray-600 mt-2 animate-fade-in">Your contribution helps us serve the community better</p>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="mb-8 animate-fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Bank Transfer Details
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Bank Name</label>
                                    <p id="bank-name" class="text-gray-900">Loading...</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Account Name</label>
                                    <p id="account-name" class="text-gray-900">Loading...</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Account Number</label>
                                    <p id="account-number" class="text-gray-900">Loading...</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">IFSC Code</label>
                                    <p id="ifsc-code" class="text-gray-900">Loading...</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-600">Branch</label>
                                    <p id="branch" class="text-gray-900">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Details Section -->
                    <div class="mb-8 animate-fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            UPI Payment
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex flex-col items-center">
                                <div id="qr-code-container" class="mb-4 hidden">
                                    <img id="qr-code" src="" alt="Donation QR Code" class="w-48 h-48 object-contain">
                                </div>
                                <div class="text-center mb-4">
                                    <label class="text-sm font-medium text-gray-600">UPI ID</label>
                                    <p id="upi-id" class="text-lg font-semibold text-gray-900">Loading...</p>
                                </div>
                                <div class="text-center">
                                    <label class="text-sm font-medium text-gray-600">Message</label>
                                    <p id="message" class="text-gray-700">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 animate-fade-in">
                        <h4 class="font-medium text-blue-800 mb-2">Donation Instructions</h4>
                        <ul class="text-sm text-blue-700 list-disc pl-5 space-y-1">
                            <li>For bank transfer, use the details provided above</li>
                            <li>For UPI payment, scan the QR code or use the UPI ID</li>
                            <li>After making the payment, you can optionally share the details with us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch donation information from API
    fetch('/api/donation-info')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const donationData = data.data;
                
                // Populate bank details
                document.getElementById('bank-name').textContent = donationData.bank_details.bank_name || 'Not available';
                document.getElementById('account-name').textContent = donationData.bank_details.account_name || 'Not available';
                document.getElementById('account-number').textContent = donationData.bank_details.account_number || 'Not available';
                document.getElementById('ifsc-code').textContent = donationData.bank_details.ifsc_code || 'Not available';
                document.getElementById('branch').textContent = donationData.bank_details.branch || 'Not available';
                
                // Populate UPI details
                document.getElementById('upi-id').textContent = donationData.upi_id || 'Not available';
                document.getElementById('message').textContent = donationData.message || 'Not available';
                
                // Show QR code if available
                if (donationData.qr_code) {
                    document.getElementById('qr-code').src = donationData.qr_code;
                    document.getElementById('qr-code-container').classList.remove('hidden');
                }
            } else {
                // Handle case where no data is returned
                document.querySelectorAll('[id]').forEach(element => {
                    if (element.id !== 'bank-name' && element.id !== 'account-name' && 
                        element.id !== 'account-number' && element.id !== 'ifsc-code' && 
                        element.id !== 'branch' && element.id !== 'upi-id' && 
                        element.id !== 'message') return;
                        
                    element.textContent = 'Information not available';
                });
            }
        })
        .catch(error => {
            console.error('Error fetching donation information:', error);
            // Display error message in all fields
            document.querySelectorAll('[id]').forEach(element => {
                if (element.id !== 'bank-name' && element.id !== 'account-name' && 
                    element.id !== 'account-number' && element.id !== 'ifsc-code' && 
                    element.id !== 'branch' && element.id !== 'upi-id' && 
                    element.id !== 'message') return;
                    
                element.textContent = 'Error loading information';
            });
        });
});
</script>

@endsection