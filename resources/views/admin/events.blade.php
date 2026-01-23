@extends('admin.layouts.admin')

@section('title', 'Event Management - Shree Hindutakht Admin')

@section('content')
<div id="events-view" class="section-view with-fixed-header with-fixed-bottom-nav">
    <!-- Page Title -->
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">Event Management</h1>
        <div class="flex justify-between items-center mt-2">
            <p class="text-gray-600 text-sm">Create and manage events</p>
            <button onclick="openCreateEventModal()" class="btn-primary px-3 py-1 text-sm">
                Create Event
            </button>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-4 space-y-4">
        <!-- Events List -->
        <div id="events-list" class="space-y-4">
            <div class="text-center py-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading events...</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div id="events-pagination" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
            <button id="prev-events-page" class="btn-secondary px-4 py-2 text-sm hidden">Previous</button>
            <span id="events-page-info" class="text-sm text-gray-600"></span>
            <button id="next-events-page" class="btn-secondary px-4 py-2 text-sm hidden">Next</button>
        </div>
    </div>
    
</div>

<!-- Create Event Modal -->
<div id="create-event-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Create New Event</h3>
                <button onclick="closeCreateEventModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="create-event-form">
                    <div class="space-y-4">
                        <div>
                            <label for="event-title" class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
                            <input type="text" id="event-title" class="input-field" required>
                        </div>
                        <div>
                            <label for="event-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="event-description" rows="3" class="input-field" required></textarea>
                        </div>
                        <div>
                            <label for="event-date" class="block text-sm font-medium text-gray-700 mb-1">Event Date & Time</label>
                            <input type="datetime-local" id="event-date" class="input-field" required>
                        </div>
                        <div>
                            <label for="registration-deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline (Optional)</label>
                            <input type="datetime-local" id="registration-deadline" class="input-field">
                        </div>
                        <div>
                            <label for="event-location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="event-location" class="input-field" required>
                        </div>
                        <div>
                            <label for="max-participants" class="block text-sm font-medium text-gray-700 mb-1">Max Participants (Optional)</label>
                            <input type="number" id="max-participants" class="input-field" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <input type="checkbox" id="is-featured" class="mr-2">
                                Featured Event
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image (Optional)</label>
                            <div class="mt-1 flex justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="event-image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-orange-600">
                                            <span>Upload image</span>
                                            <input id="event-image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeCreateEventModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div id="edit-event-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Edit Event</h3>
                <button onclick="closeEditEventModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="edit-event-form">
                    <input type="hidden" id="edit-event-id">
                    <div class="space-y-4">
                        <div>
                            <label for="edit-event-title" class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
                            <input type="text" id="edit-event-title" class="input-field" required>
                        </div>
                        <div>
                            <label for="edit-event-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="edit-event-description" rows="3" class="input-field" required></textarea>
                        </div>
                        <div>
                            <label for="edit-event-date" class="block text-sm font-medium text-gray-700 mb-1">Event Date & Time</label>
                            <input type="datetime-local" id="edit-event-date" class="input-field" required>
                        </div>
                        <div>
                            <label for="edit-registration-deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline (Optional)</label>
                            <input type="datetime-local" id="edit-registration-deadline" class="input-field">
                        </div>
                        <div>
                            <label for="edit-event-location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="edit-event-location" class="input-field" required>
                        </div>
                        <div>
                            <label for="edit-max-participants" class="block text-sm font-medium text-gray-700 mb-1">Max Participants (Optional)</label>
                            <input type="number" id="edit-max-participants" class="input-field" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <input type="checkbox" id="edit-is-featured" class="mr-2">
                                Featured Event
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image (Optional)</label>
                            <div class="mt-1 flex justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="edit-event-image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-orange-600">
                                            <span>Upload image</span>
                                            <input id="edit-event-image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            <div id="current-image-container" class="mt-2 hidden">
                                <p class="text-sm text-gray-600 mb-1">Current Image:</p>
                                <img id="current-event-image" src="" alt="Current event image" class="w-32 h-32 object-cover rounded">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEditEventModal()" class="btn-secondary px-4 py-2 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 text-sm">
                            Update Event
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

// Current page state
let currentEventsPage = 1;
let currentEditingEvent = null;

// Load events
async function loadEvents(page = 1) {
    currentEventsPage = page;
    try {
        const response = await AdminAPI.get(`/events?page=${page}`);
        if (response.success) {
            renderEvents(response.data);
        } else {
            document.getElementById('events-list').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-500">Error loading events: ${response.message}</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('events-list').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-500">Error loading events: ${error.message}</p>
            </div>
        `;
    }
}

// Render events list
function renderEvents(data) {
    const events = data.data;
    const listContainer = document.getElementById('events-list');
    
    if (events && events.length > 0) {
        listContainer.innerHTML = events.map(event => {
            // Format dates
            const eventDate = new Date(event.event_date);
            const formattedEventDate = eventDate.toLocaleDateString() + ' ' + eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            let formattedRegistrationDeadline = 'None';
            if (event.registration_deadline) {
                const registrationDeadline = new Date(event.registration_deadline);
                formattedRegistrationDeadline = registrationDeadline.toLocaleDateString() + ' ' + registrationDeadline.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            // Create image HTML if event has featured image
            let imageHtml = '';
            if (event.featured_image_url) {
                imageHtml = `<img src="${event.featured_image_url}" class="w-full h-48 object-cover rounded-lg mt-2" alt="Event image">`;
            }
            
            // Create featured badge
            const featuredBadge = event.is_featured ? '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 mr-2">Featured</span>' : '';
            
            // Create status badge with appropriate color
            let statusBadge = '';
            switch(event.status) {
                case 'upcoming':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Upcoming</span>';
                    break;
                case 'ongoing':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Ongoing</span>';
                    break;
                case 'completed':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Completed</span>';
                    break;
                case 'cancelled':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>';
                    break;
                default:
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unknown</span>';
            }
            
            return `
                <div class="card">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">${event.title}</h3>
                            <div class="flex items-center mt-1">
                                ${featuredBadge}
                                ${statusBadge}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">${formattedEventDate}</p>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm mb-2">${event.description.substring(0, 100)}${event.description.length > 100 ? '...' : ''}</p>
                    <p class="text-gray-600 text-sm mb-1">📍 ${event.location}</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-2">
                        <div>
                            <span class="font-medium">Max Participants:</span> ${event.max_participants || 'Unlimited'}
                        </div>
                        <div>
                            <span class="font-medium">Registration Deadline:</span> ${formattedRegistrationDeadline}
                        </div>
                        <div>
                            <span class="font-medium">RSVPs:</span> ${event.interested_count} interested, ${event.going_count} going
                        </div>
                    </div>
                    ${imageHtml}
                    <div class="flex space-x-2 mt-3">
                        <button onclick="viewEvent(${event.id})" class="btn-secondary flex-1 text-sm py-1">View</button>
                        <button onclick="editEvent(${event.id})" class="btn-secondary flex-1 text-sm py-1">Edit</button>
                        <button onclick="deleteEvent(${event.id})" class="btn-secondary flex-1 text-sm py-1 text-red-600">Delete</button>
                    </div>
                </div>
            `;
        }).join('');
        
        updateEventsPagination(data);
    } else {
        listContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500">No events found</p>
            </div>
        `;
    }
}

// Update pagination
function updateEventsPagination(data) {
    const pageInfo = document.getElementById('events-page-info');
    const prevBtn = document.getElementById('prev-events-page');
    const nextBtn = document.getElementById('next-events-page');
    
    if (data.total > 0) {
        pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
    } else {
        pageInfo.textContent = 'No events found';
    }
    
    // Show/hide pagination buttons
    if (data.prev_page_url) {
        prevBtn.classList.remove('hidden');
        prevBtn.onclick = () => loadEvents(data.current_page - 1);
    } else {
        prevBtn.classList.add('hidden');
    }
    
    if (data.next_page_url) {
        nextBtn.classList.remove('hidden');
        nextBtn.onclick = () => loadEvents(data.current_page + 1);
    } else {
        nextBtn.classList.add('hidden');
    }
}

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

// Delete event
async function deleteEvent(eventId) {
    showConfirmationModal(
        'Are you sure you want to delete this event? This action cannot be undone.',
        async () => {
            try {
                const response = await AdminAPI.delete(`/events/${eventId}`);
                if (response.success) {
                    Toast.success('Event deleted successfully');
                    // Reload the current page of events
                    loadEvents(currentEventsPage);
                } else {
                    Toast.error('Error: ' + response.message);
                }
            } catch (error) {
                Toast.error('Error deleting event: ' + error.message);
            }
        }
    );
}

// View event
async function viewEvent(eventId) {
    try {
        const response = await AdminAPI.get(`/events/${eventId}`);
        if (response.success) {
            const event = response.data;
            
            // Format dates
            const eventDate = new Date(event.event_date);
            const formattedEventDate = eventDate.toLocaleDateString() + ' ' + eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            let formattedRegistrationDeadline = 'None';
            if (event.registration_deadline) {
                const registrationDeadline = new Date(event.registration_deadline);
                formattedRegistrationDeadline = registrationDeadline.toLocaleDateString() + ' ' + registrationDeadline.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            // Create image HTML if event has featured image
            let imageHtml = '';
            if (event.featured_image_url) {
                imageHtml = `<img src="${event.featured_image_url}" class="w-full h-64 object-cover rounded-lg mt-2" alt="Event image">`;
            }
            
            // Create featured badge
            const featuredBadge = event.is_featured ? '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Featured</span>' : '';
            
            // Create status badge with appropriate color
            let statusBadge = '';
            switch(event.status) {
                case 'upcoming':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Upcoming</span>';
                    break;
                case 'ongoing':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Ongoing</span>';
                    break;
                case 'completed':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Completed</span>';
                    break;
                case 'cancelled':
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>';
                    break;
                default:
                    statusBadge = '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unknown</span>';
            }
            
            // Create a modal to show event details
            const viewModal = document.createElement('div');
            viewModal.id = 'view-event-modal';
            viewModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50';
            viewModal.innerHTML = `
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">${event.title}</h3>
                            <button onclick="closeViewEventModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    ${featuredBadge}
                                    ${statusBadge}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Event Date & Time</p>
                                    <p class="font-medium">${formattedEventDate}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Registration Deadline</p>
                                    <p class="font-medium">${formattedRegistrationDeadline}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Location</p>
                                    <p class="font-medium">${event.location}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Max Participants</p>
                                        <p class="font-medium">${event.max_participants || 'Unlimited'}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Interested</p>
                                        <p class="font-medium">${event.interested_count}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Going</p>
                                        <p class="font-medium">${event.going_count}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Description</p>
                                    <p class="font-medium">${event.description}</p>
                                </div>
                                ${imageHtml}
                                <div class="flex justify-end space-x-3 mt-6">
                                    <button onclick="closeViewEventModal()" class="btn-secondary px-4 py-2 text-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(viewModal);
            viewModal.classList.remove('hidden');
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error viewing event: ' + error.message);
    }
}

// Close view event modal
function closeViewEventModal() {
    const modal = document.getElementById('view-event-modal');
    if (modal) {
        modal.remove();
    }
}

// Edit event
async function editEvent(eventId) {
    try {
        const response = await AdminAPI.get(`/events/${eventId}`);
        if (response.success) {
            const event = response.data;
            currentEditingEvent = event;
            
            // Populate the edit form with event data
            document.getElementById('edit-event-id').value = event.id;
            document.getElementById('edit-event-title').value = event.title;
            document.getElementById('edit-event-description').value = event.description;
            
            // Format dates for datetime-local inputs
            const eventDate = new Date(event.event_date);
            const formattedEventDate = eventDate.toISOString().slice(0, 16);
            document.getElementById('edit-event-date').value = formattedEventDate;
            
            if (event.registration_deadline) {
                const registrationDeadline = new Date(event.registration_deadline);
                const formattedRegistrationDeadline = registrationDeadline.toISOString().slice(0, 16);
                document.getElementById('edit-registration-deadline').value = formattedRegistrationDeadline;
            } else {
                document.getElementById('edit-registration-deadline').value = '';
            }
            
            document.getElementById('edit-event-location').value = event.location;
            document.getElementById('edit-max-participants').value = event.max_participants || '';
            document.getElementById('edit-is-featured').checked = event.is_featured;
            
            // Show current image if exists
            const currentImageContainer = document.getElementById('current-image-container');
            const currentEventImage = document.getElementById('current-event-image');
            if (event.featured_image_url) {
                currentEventImage.src = event.featured_image_url;
                currentImageContainer.classList.remove('hidden');
            } else {
                currentImageContainer.classList.add('hidden');
            }
            
            // Show the edit modal
            document.getElementById('edit-event-modal').classList.remove('hidden');
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error loading event: ' + error.message);
    }
}

// Close edit event modal
function closeEditEventModal() {
    document.getElementById('edit-event-modal').classList.add('hidden');
    document.getElementById('edit-event-form').reset();
    currentEditingEvent = null;
}

// Modal functions
function openCreateEventModal() {
    document.getElementById('create-event-modal').classList.remove('hidden');
}

function closeCreateEventModal() {
    document.getElementById('create-event-modal').classList.add('hidden');
    document.getElementById('create-event-form').reset();
}

// Form submission - Create Event
document.getElementById('create-event-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const title = document.getElementById('event-title').value;
    const description = document.getElementById('event-description').value;
    const eventDateTime = document.getElementById('event-date').value;
    const registrationDeadline = document.getElementById('registration-deadline').value;
    const location = document.getElementById('event-location').value;
    const maxParticipants = document.getElementById('max-participants').value;
    const isFeatured = document.getElementById('is-featured').checked;
    const imageInput = document.getElementById('event-image');
    
    if (!title || !description || !eventDateTime || !location) {
        Toast.error('Please fill in all required fields');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('event_date', eventDateTime);
        formData.append('location', location);
        formData.append('status', 'upcoming'); // Default status
        
        if (registrationDeadline) {
            formData.append('registration_deadline', registrationDeadline);
        }
        
        if (maxParticipants) {
            formData.append('max_participants', maxParticipants);
        }
        
        if (isFeatured) {
            formData.append('is_featured', '1');
        }
        
        if (imageInput.files.length > 0) {
            formData.append('featured_image', imageInput.files[0]);
        }
        
        const response = await AdminAPI.postWithFiles('/events', formData);
        if (response.success) {
            Toast.success('Event created successfully');
            closeCreateEventModal();
            loadEvents(currentEventsPage);
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error creating event: ' + error.message);
    }
});

// Form submission - Edit Event
document.getElementById('edit-event-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const eventId = document.getElementById('edit-event-id').value;
    const title = document.getElementById('edit-event-title').value;
    const description = document.getElementById('edit-event-description').value;
    const eventDateTime = document.getElementById('edit-event-date').value;
    const registrationDeadline = document.getElementById('edit-registration-deadline').value;
    const location = document.getElementById('edit-event-location').value;
    const maxParticipants = document.getElementById('edit-max-participants').value;
    const isFeatured = document.getElementById('edit-is-featured').checked;
    const imageInput = document.getElementById('edit-event-image');
    
    if (!title || !description || !eventDateTime || !location) {
        Toast.error('Please fill in all required fields');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('_method', 'PUT'); // Laravel needs this for PUT requests
        formData.append('title', title);
        formData.append('description', description);
        formData.append('event_date', eventDateTime);
        formData.append('location', location);
        
        if (registrationDeadline) {
            formData.append('registration_deadline', registrationDeadline);
        }
        
        if (maxParticipants) {
            formData.append('max_participants', maxParticipants);
        }
        
        if (isFeatured) {
            formData.append('is_featured', '1');
        } else {
            formData.append('is_featured', '0');
        }
        
        // Only append image if a new one is selected
        if (imageInput.files.length > 0) {
            formData.append('featured_image', imageInput.files[0]);
        }
        
        const response = await AdminAPI.postWithFiles(`/events/${eventId}`, formData);
        if (response.success) {
            Toast.success('Event updated successfully');
            closeEditEventModal();
            loadEvents(currentEventsPage);
        } else {
            Toast.error('Error: ' + response.message);
        }
    } catch (error) {
        Toast.error('Error updating event: ' + error.message);
    }
});

// Initialize when the DOM content is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const createModal = document.getElementById('create-event-modal');
    const editModal = document.getElementById('edit-event-modal');
    const viewModal = document.getElementById('view-event-modal');
    const confirmationModal = document.getElementById('confirmation-modal');
    
    if (createModal && !createModal.classList.contains('hidden') && event.target === createModal) {
        closeCreateEventModal();
    }
    
    if (editModal && !editModal.classList.contains('hidden') && event.target === editModal) {
        closeEditEventModal();
    }
    
    if (viewModal && !viewModal.classList.contains('hidden') && event.target === viewModal) {
        closeViewEventModal();
    }
    
    if (confirmationModal && !confirmationModal.classList.contains('hidden') && event.target === confirmationModal) {
        closeConfirmationModal();
    }
});
</script>
@endsection