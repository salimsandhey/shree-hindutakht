@extends('layouts.member')

@section('title', 'Events - Shree Hindutakht')

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
                <h1 class="text-lg font-semibold text-gray-800 animate-fade-in-down">Community Events</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 mobile-content">
        <div id="events-container">
            <!-- Events will be loaded here -->
            <div class="text-center py-4 animate-fade-in">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading events...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Function to load events
async function loadEvents() {
    console.log('Loading events...');
    try {
        const response = await fetch('/api/events', {
            headers: {
                'Authorization': 'Bearer ' + (localStorage.getItem('auth_token') || ''),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Response received:', response);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Data received:', data);
        
        if (data.success) {
            renderEvents(data.data);
        } else {
            document.getElementById('events-container').innerHTML = `
                <div class="card text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">No Events Found</h2>
                    <p class="text-gray-500">There are currently no upcoming events</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading events:', error);
        document.getElementById('events-container').innerHTML = `
            <div class="card text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Error Loading Events</h2>
                <p class="text-gray-500">There was an error loading events. Please try again later.</p>
                <p class="text-red-500 text-sm mt-2">Error: ${error.message}</p>
            </div>
        `;
    }
}

// Function to render events
function renderEvents(eventsData) {
    console.log('Rendering events:', eventsData);
    const container = document.getElementById('events-container');
    const events = eventsData.data || eventsData;
    
    if (!events || events.length === 0) {
        container.innerHTML = `
            <div class="card text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">No Events Found</h2>
                <p class="text-gray-500">There are currently no upcoming events</p>
            </div>
        `;
        return;
    }
    
    let eventsHtml = '';
    
    events.forEach(event => {
        // Format date
        const eventDate = new Date(event.event_date);
        const formattedDate = eventDate.toLocaleDateString() + ' ' + eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Create image HTML if event has featured image
        let imageHtml = '';
        if (event.featured_image_url) {
            imageHtml = `<img src="${event.featured_image_url}" class="w-full h-48 object-cover rounded-lg mt-2" alt="${event.title}">`;
        }
        
        // RSVP status button
        let rsvpButton = '';
        if (event.user_rsvp === 'going') {
            rsvpButton = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Going</span>';
        } else if (event.user_rsvp === 'interested') {
            rsvpButton = '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Interested</span>';
        } else {
            rsvpButton = `
                <div class="flex space-x-2 mt-2">
                    <button onclick="rsvpEvent(${event.id}, 'interested')" class="btn-secondary flex-1 text-sm py-1">Interested</button>
                    <button onclick="rsvpEvent(${event.id}, 'going')" class="btn-primary flex-1 text-sm py-1">Going</button>
                </div>
            `;
        }
        
        eventsHtml += `
            <div class="card mb-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-900">${event.title}</h3>
                        <p class="text-xs text-gray-500">${formattedDate}</p>
                    </div>
                    ${rsvpButton.includes('span') ? rsvpButton : ''}
                </div>
                <p class="text-gray-700 text-sm mb-2">${event.description}</p>
                <p class="text-gray-600 text-sm mb-2">📍 ${event.location}</p>
                ${imageHtml}
                ${rsvpButton.includes('button') ? rsvpButton : ''}
            </div>
        `;
    });
    
    container.innerHTML = eventsHtml;
}

// Function to RSVP to an event
async function rsvpEvent(eventId, response) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
        
        const res = await fetch(`/api/events/${eventId}/rsvp`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + (localStorage.getItem('auth_token') || ''),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                response: response
            })
        });
        
        const data = await res.json();
        
        if (data.success) {
            // Reload events to show updated RSVP status
            loadEvents();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error RSVPing to event:', error);
        alert('Error RSVPing to event. Please try again.');
    }
}

// Load events when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, calling loadEvents');
    loadEvents();
});

// Also try loading events after a short delay to ensure everything is ready
setTimeout(function() {
    if (document.getElementById('events-container').innerHTML.includes('Loading events')) {
        console.log('Fallback: Loading events after delay');
        loadEvents();
    }
}, 1000);
</script>
@endsection