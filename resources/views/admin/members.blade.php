@extends('admin.layouts.admin')

@section('title', 'Member Management - Shree Hindutakht Admin')

@section('content')
<div id="members-view" class="section-view with-fixed-header with-fixed-bottom-nav">
    <!-- Page Title -->
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">Member Management</h1>
        <p class="text-gray-600 text-sm mt-1">View, edit, and manage members</p>
    </div>
    
    <!-- Content -->
    <div class="p-4 space-y-4">
        <!-- Search and Actions -->
        <div class="card">
            <div class="flex space-x-2 mb-4">
                <input type="text" id="member-search" placeholder="Search members..." class="input-field flex-1">
                <button onclick="searchMembers()" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Members List -->
            <div id="members-list" class="space-y-3">
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading members...</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div id="members-pagination" class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                <button id="prev-page" class="btn-secondary px-4 py-2 text-sm hidden">Previous</button>
                <span id="page-info" class="text-sm text-gray-600"></span>
                <button id="next-page" class="btn-secondary px-4 py-2 text-sm hidden">Next</button>
            </div>
        </div>
    </div>
    
</div>

<!-- View Member Modal -->
<div id="view-member-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Member Details</h3>
                <button onclick="closeViewMemberModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div id="member-details-content">
                    <!-- Member details will be loaded here -->
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeViewMemberModal()" class="btn-secondary px-4 py-2 text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="edit-member-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Edit Member</h3>
                <button onclick="closeEditMemberModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="edit-member-form">
                    <input type="hidden" id="edit-member-id">
                    <div class="space-y-4">
                        <div>
                            <label for="edit-member-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="edit-member-name" class="input-field" required>
                        </div>
                        <div>
                            <label for="edit-member-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="edit-member-email" class="input-field" required>
                        </div>
                        <div>
                            <label for="edit-member-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="edit-member-phone" class="input-field">
                        </div>
                        <div>
                            <label for="edit-member-address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="edit-member-address" rows="2" class="input-field"></textarea>
                        </div>
                        <div>
                            <label for="edit-member-dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" id="edit-member-dob" class="input-field">
                        </div>
                        <div>
                            <label for="edit-member-gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select id="edit-member-gender" class="input-field">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-member-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit-member-status" class="input-field">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEditMemberModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm">
                            Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Confirm Action</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-700 mb-4" id="confirmation-message">Are you sure you want to proceed?</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmationModal()" class="btn-secondary px-4 py-2 text-sm">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmAction()" class="btn-primary px-4 py-2 text-sm" id="confirm-button">
                        Confirm
                    </button>
                </div>
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
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414z" clip-rule="evenodd" />
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

// Current page state
let currentMembersPage = 1;

// Load members
async function loadMembers(page = 1) {
    currentMembersPage = page;
    try {
        const response = await AdminAPI.get(`/members?page=${page}`);
        if (response.success) {
            renderMembers(response.data);
        } else {
            document.getElementById('members-list').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-500">Error loading members: ${response.message}</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('members-list').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-500">Error loading members: ${error.message}</p>
            </div>
        `;
    }
}

// Search members
async function searchMembers() {
    const searchTerm = document.getElementById('member-search').value;
    currentMembersPage = 1; // Reset to first page when searching
    try {
        const response = await AdminAPI.get(`/members?search=${encodeURIComponent(searchTerm)}&page=1`);
        if (response.success) {
            renderMembers(response.data);
        } else {
            document.getElementById('members-list').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-500">Error searching members: ${response.message}</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('members-list').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-500">Error searching members: ${error.message}</p>
            </div>
        `;
    }
}

// Render members list
function renderMembers(data) {
    const members = data.data;
    const listContainer = document.getElementById('members-list');
    
    if (members && members.length > 0) {
        listContainer.innerHTML = members.map(member => `
            <div class="border border-gray-200 rounded-lg p-4 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-900">${member.name}</h3>
                        <p class="text-sm text-gray-600">${member.email}</p>
                        <p class="text-xs text-gray-500 mt-1">ID: ${member.member_id}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full ${member.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${member.status}
                    </span>
                </div>
                <div class="flex space-x-2 mt-3">
                    <button onclick="viewMember(${member.id})" class="btn-secondary flex-1 text-sm py-1">View</button>
                    <button onclick="editMember(${member.id})" class="btn-secondary flex-1 text-sm py-1">Edit</button>
                    <button onclick="toggleMemberStatus(${member.id}, '${member.status}')" class="btn-secondary flex-1 text-sm py-1">
                        ${member.status === 'active' ? 'Deactivate' : 'Activate'}
                    </button>
                </div>
                <div class="mt-2">
                    <button onclick="deleteMember(${member.id})" class="w-full btn-secondary text-sm py-1 text-red-600">
                        Delete Member
                    </button>
                </div>
            </div>
        `).join('');
        
        updatePagination(data);
    } else {
        listContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.828 5.828a4 4 0 00-5.656 0l-1.414 1.414A6 6 0 0112 22a6 6 0 018.243-2.243l-1.414-1.414a4 4 0 00-5.657-5.657L9.828 5.828zM12 16a4 4 0 100-8 4 4 0 000 8z"></path>
                </svg>
                <p class="text-gray-500">No members found</p>
            </div>
        `;
    }
}

// Update pagination
function updatePagination(data) {
    const pageInfo = document.getElementById('page-info');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    if (data.total > 0) {
        pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
    } else {
        pageInfo.textContent = 'No members found';
    }
    
    // Show/hide pagination buttons
    if (data.prev_page_url) {
        prevBtn.classList.remove('hidden');
        prevBtn.onclick = () => loadMembers(data.current_page - 1);
    } else {
        prevBtn.classList.add('hidden');
    }
    
    if (data.next_page_url) {
        nextBtn.classList.remove('hidden');
        nextBtn.onclick = () => loadMembers(data.current_page + 1);
    } else {
        nextBtn.classList.add('hidden');
    }
}

// View member details
async function viewMember(memberId) {
    try {
        const response = await AdminAPI.get(`/members/${memberId}`);
        if (response.success) {
            const member = response.data;
            
            // Format date of birth
            let formattedDob = 'Not provided';
            if (member.date_of_birth) {
                const dob = new Date(member.date_of_birth);
                formattedDob = dob.toLocaleDateString();
            }
            
            // Format created date
            const createdDate = new Date(member.created_at);
            const formattedCreatedDate = createdDate.toLocaleDateString();
            
            // Create member details HTML
            const memberDetailsHtml = `
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                            <span class="text-gray-500 text-xl font-bold">${member.name.charAt(0)}</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">${member.name}</h3>
                            <p class="text-sm text-gray-600">${member.email}</p>
                            <p class="text-xs text-gray-500">ID: ${member.member_id}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-600">Phone</p>
                            <p class="font-medium">${member.phone || 'Not provided'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Gender</p>
                            <p class="font-medium">${member.gender || 'Not provided'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Date of Birth</p>
                            <p class="font-medium">${formattedDob}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <span class="px-2 py-1 text-xs rounded-full ${member.status === 'active' ? 'bg-green-100 text-green-800' : member.status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${member.status.charAt(0).toUpperCase() + member.status.slice(1)}
                            </span>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-600">Member Since</p>
                            <p class="font-medium">${formattedCreatedDate}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600">Address</p>
                        <p class="font-medium">${member.address || 'Not provided'}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <p class="text-2xl font-bold text-gray-900">${member.posts_count || 0}</p>
                            <p class="text-xs text-gray-600">Posts</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <p class="text-2xl font-bold text-gray-900">${member.event_rsvps_count || 0}</p>
                            <p class="text-xs text-gray-600">Events</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <p class="text-2xl font-bold text-gray-900">${member.post_likes_count || 0}</p>
                            <p class="text-xs text-gray-600">Likes</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('member-details-content').innerHTML = memberDetailsHtml;
            document.getElementById('view-member-modal').classList.remove('hidden');
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error loading member details: ' + error.message);
    }
}

// Close view member modal
function closeViewMemberModal() {
    document.getElementById('view-member-modal').classList.add('hidden');
}

// Edit member
async function editMember(memberId) {
    try {
        const response = await AdminAPI.get(`/members/${memberId}`);
        if (response.success) {
            const member = response.data;
            
            // Populate the edit form with member data
            document.getElementById('edit-member-id').value = member.id;
            document.getElementById('edit-member-name').value = member.name;
            document.getElementById('edit-member-email').value = member.email;
            document.getElementById('edit-member-phone').value = member.phone || '';
            document.getElementById('edit-member-address').value = member.address || '';
            
            // Format date for input field
            if (member.date_of_birth) {
                document.getElementById('edit-member-dob').value = member.date_of_birth;
            } else {
                document.getElementById('edit-member-dob').value = '';
            }
            
            document.getElementById('edit-member-gender').value = member.gender || '';
            document.getElementById('edit-member-status').value = member.status;
            
            // Show the edit modal
            document.getElementById('edit-member-modal').classList.remove('hidden');
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error loading member: ' + error.message);
    }
}

// Close edit member modal
function closeEditMemberModal() {
    document.getElementById('edit-member-modal').classList.add('hidden');
    document.getElementById('edit-member-form').reset();
}

// Form submission - Edit Member
document.getElementById('edit-member-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const memberId = document.getElementById('edit-member-id').value;
    const name = document.getElementById('edit-member-name').value;
    const email = document.getElementById('edit-member-email').value;
    const phone = document.getElementById('edit-member-phone').value;
    const address = document.getElementById('edit-member-address').value;
    const dateOfBirth = document.getElementById('edit-member-dob').value;
    const gender = document.getElementById('edit-member-gender').value;
    const status = document.getElementById('edit-member-status').value;
    
    if (!name || !email) {
        Toast.error('Please fill in all required fields');
        return;
    }
    
    try {
        const formData = {
            name: name,
            email: email,
            phone: phone,
            address: address,
            date_of_birth: dateOfBirth,
            gender: gender,
            status: status
        };
        
        const response = await AdminAPI.put(`/members/${memberId}`, formData);
        if (response.success) {
            Toast.success('Member updated successfully');
            closeEditMemberModal();
            loadMembers(currentMembersPage);
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error updating member: ' + error.message);
    }
});

// Confirmation modal functions
let confirmCallback = null;

function showConfirmationModal(message, callback) {
    const modal = document.getElementById('confirmation-modal');
    const messageElement = document.getElementById('confirmation-message');
    
    messageElement.textContent = message;
    confirmCallback = callback;
    
    modal.classList.remove('hidden');
}

function closeConfirmationModal() {
    const modal = document.getElementById('confirmation-modal');
    modal.classList.add('hidden');
    confirmCallback = null;
}

function confirmAction() {
    if (confirmCallback && typeof confirmCallback === 'function') {
        confirmCallback();
    }
    closeConfirmationModal();
}

// Toggle member status
async function toggleMemberStatus(memberId, currentStatus) {
    showConfirmationModal(
        `Are you sure you want to ${currentStatus === 'active' ? 'deactivate' : 'activate'} this member?`,
        async () => {
            try {
                const response = await AdminAPI.post(`/members/${memberId}/toggle-status`);
                if (response.success) {
                    Toast.success(`Member ${currentStatus === 'active' ? 'deactivated' : 'activated'} successfully`);
                    // Reload the current page of members
                    loadMembers(currentMembersPage);
                } else {
                    Toast.error('Error: ' + response.message);
                }
            } catch (error) {
                Toast.error('Error toggling member status: ' + error.message);
            }
        }
    );
}

// Delete member
async function deleteMember(memberId) {
    showConfirmationModal(
        'Are you sure you want to delete this member? This action cannot be undone.',
        async () => {
            try {
                const response = await AdminAPI.delete(`/members/${memberId}`);
                if (response.success) {
                    Toast.success('Member deleted successfully');
                    // Reload the current page of members
                    loadMembers(currentMembersPage);
                } else {
                    Toast.error('Error: ' + response.message);
                }
            } catch (error) {
                Toast.error('Error deleting member: ' + error.message);
            }
        }
    );
}

// Initialize when the DOM content is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add search listener
    const searchInput = document.getElementById('member-search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchMembers();
            }
        });
    }
    
    // Load members
    loadMembers();
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const viewModal = document.getElementById('view-member-modal');
    const editModal = document.getElementById('edit-member-modal');
    const confirmationModal = document.getElementById('confirmation-modal');
    
    if (viewModal && !viewModal.classList.contains('hidden') && event.target === viewModal) {
        closeViewMemberModal();
    }
    
    if (editModal && !editModal.classList.contains('hidden') && event.target === editModal) {
        closeEditMemberModal();
    }
    
    if (confirmationModal && !confirmationModal.classList.contains('hidden') && event.target === confirmationModal) {
        closeConfirmationModal();
    }
});
</script>
@endsection