@extends('layouts.app')

@section('title', 'Shree Hindutakht - Community Platform')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-orange-600 to-red-700 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-600/90 to-red-700/90"></div>
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="grid grid-cols-4 gap-8 transform -rotate-12">
                <div class="col-span-1 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="col-span-1 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="col-span-1 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="col-span-1 h-96 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
                    <span class="block">Connect with Your</span>
                    <span class="block mt-2 text-yellow-300">
                        Community
                    </span>
                </h1>
                <p class="mt-6 text-xl text-orange-100 max-w-2xl">
                    Shree Hindutakht is a modern platform for community engagement, event management, and spiritual growth. 
                    Join us to stay connected with fellow members and participate in meaningful activities.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-orange-700 font-bold rounded-lg shadow-lg hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition duration-300 transform hover:-translate-y-1">
                        Login
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-white/10 backdrop-blur-sm p-6">
                    <img src="{{ asset('images/about.jpg') }}" alt="Community Platform" class="w-full h-64 object-cover rounded-xl">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Why Join Shree Hindutakht?
            </h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Our platform offers a range of features designed to enhance your community experience
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Community Events</h3>
                <p class="text-gray-600">
                    Stay informed about upcoming events, ceremonies, and gatherings. RSVP to events that interest you.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Community Feed</h3>
                <p class="text-gray-600">
                    Share updates, photos, and thoughts with the community. Like and comment on posts from other members.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Support the Cause</h3>
                <p class="text-gray-600">
                    Contribute to our community through donations. View bank details and UPI information for easy giving.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Additional Information Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Privacy Policy</h3>
                <p class="text-gray-600 mb-6">
                    We are committed to protecting your privacy and personal information. Learn more about how we collect, use, and safeguard your data.
                </p>
                <a href="{{ route('privacy-policy') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    Read Privacy Policy
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Contact Us</h3>
                <p class="text-gray-600 mb-6">
                    Have questions or need assistance? Our team is here to help you with any inquiries about our organization.
                </p>
                <a href="mailto:info@nocollarmedia.com" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    Send Email
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-orange-500 to-red-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Ready to Join Our Community?
        </h2>
        <p class="text-xl text-orange-100 max-w-3xl mx-auto mb-10">
            Become a member today and start connecting with fellow community members.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-orange-700 font-bold rounded-lg shadow-lg hover:bg-gray-100 transition duration-300">
                Create Free Account
            </a>
            <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition duration-300">
                Login to Existing Account
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">Shree Hindutakht</h3>
                <p class="text-gray-300">A community platform for Hindu devotees to connect, share, and grow together in Dharma.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('team') }}" class="text-gray-300 hover:text-white transition-colors">Our Team</a></li>
                    <li><a href="{{ route('mission-vision') }}" class="text-gray-300 hover:text-white transition-colors">Mission & Vision</a></li>
                    <li><a href="{{ route('safety.standards') }}" class="text-gray-300 hover:text-white transition-colors">Safety Standards</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('privacy-policy') }}" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('safety.standards') }}" class="text-gray-300 hover:text-white transition-colors">Safety Standards</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Shree Hindutakht. All rights reserved.</p>
        </div>
    </div>
</footer>

@endsection