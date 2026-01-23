@extends('layouts.app')

@section('title', 'Mission & Vision - Shree Hindutakht')

@section('content')
<!-- Mobile App-like Breadcrumbs -->
<div class="mobile-breadcrumbs md:hidden">
    <a href="/" class="breadcrumb-item">Home</a>
    <span class="breadcrumb-separator">/</span>
    <span class="breadcrumb-item active">Mission & Vision</span>
</div>

<div class="bg-gray-50 pt-16 mobile-content page-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 pt-4 sm:pt-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl animate-fade-in">Our Mission & Vision</h1>
            <p class="mt-3 text-xl text-gray-500 animate-fade-in">Guided by faith, unity, and service</p>
        </div>

        <!-- Mission Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-1/3 touch-target">
                        <!-- Mission photo placeholder -->
                        <img src="{{ asset('images/mission.jpg') }}" alt="Our Mission" class="w-full h-64 object-cover rounded-xl">
                    </div>
                    
                    <div class="md:w-2/3">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 animate-fade-in">Our Mission</h2>
                        <p class="text-orange-600 font-medium text-lg mb-4 animate-fade-in">"Reviving Roots, Empowering Communities, Building a United Future."</p>
                        
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-600 mb-4 animate-fade-in">
                                At Shri Hindu Takht, our mission is to reconnect the youth with the timeless values of Sanatan Dharma 
                                and foster a strong, united Hindu community. Under the esteemed leadership of Shri Parveen Kumar, 
                                we strive to promote selfless service, social welfare, and cultural preservation.
                            </p>
                            <p class="text-gray-600 mb-4 animate-fade-in">
                                Shri Parveen Kumar's lifelong dedication to Gau Seva, uplifting underprivileged families, and 
                                organizing weddings for girls from economically weaker backgrounds reflects his deep commitment 
                                to the welfare of society.
                            </p>
                            <p class="text-gray-600 animate-fade-in">
                                With unwavering passion and a heart full of compassion, he continues to lead us in our mission of 
                                nurturing a sense of pride in our Hindu identity while working toward a brighter, more harmonious 
                                future for all.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vision Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-2/3 order-1 md:order-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 animate-fade-in">Our Vision</h2>
                        <p class="text-orange-600 font-medium text-lg mb-4 animate-fade-in">"Empowering Faith, Uniting Communities, Building a Stronger Tomorrow."</p>
                        
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-600 mb-4 animate-fade-in">
                                Guided by the principles of faith, unity, and service, Hindu Takht envisions a society where cultural 
                                heritage, spirituality, and community welfare thrive together.
                            </p>
                            <p class="text-gray-600 mb-4 animate-fade-in">
                                Under the esteemed leadership of Parveen Ji, we aim to uphold the rich traditions of our values while 
                                creating a future where every individual is empowered, supported, and connected to their roots.
                            </p>
                            <p class="text-gray-600 animate-fade-in">
                                Our vision is to build a compassionate, just, and prosperous world for generations to come, where 
                                the timeless wisdom of Sanatan Dharma continues to guide humanity toward harmony and enlightenment.
                            </p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/3 order-2 md:order-2 touch-target">
                        <!-- Vision photo placeholder -->
                        <img src="{{ asset('images/vision.jpg') }}" alt="Our Vision" class="w-full h-64 object-cover rounded-xl">
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center animate-fade-in">Our Core Values</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Value 1 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Service (Seva)</h3>
                        <p class="text-gray-600">
                            Selfless service to the community and humanity without expectation of reward or recognition.
                        </p>
                    </div>
                    
                    <!-- Value 2 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Unity</h3>
                        <p class="text-gray-600">
                            Bringing together individuals from diverse backgrounds under the common umbrella of Sanatan Dharma.
                        </p>
                    </div>
                    
                    <!-- Value 3 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Integrity</h3>
                        <p class="text-gray-600">
                            Upholding the highest standards of honesty, transparency, and ethical conduct in all our endeavors.
                        </p>
                    </div>
                    
                    <!-- Value 4 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tradition</h3>
                        <p class="text-gray-600">
                            Preserving and promoting the rich cultural and spiritual heritage of Sanatan Dharma for future generations.
                        </p>
                    </div>
                    
                    <!-- Value 5 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Empowerment</h3>
                        <p class="text-gray-600">
                            Empowering individuals and communities to realize their full potential through education and opportunity.
                        </p>
                    </div>
                    
                    <!-- Value 6 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 card touch-target">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Compassion</h3>
                        <p class="text-gray-600">
                            Showing kindness, empathy, and understanding toward all beings as taught in our ancient scriptures.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 animate-fade-in">Additional Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="card touch-target nav-transition">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Privacy Policy</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">
                            We are committed to protecting your privacy. Learn more about how we collect, use, and protect your personal information.
                        </p>
                        <a href="{{ route('privacy-policy') }}" class="inline-flex items-center text-orange-600 font-medium hover:text-orange-700 transition-colors">
                            Read our Privacy Policy
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <div class="card touch-target nav-transition">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Contact Us</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">
                            Have questions or need assistance? Our team is here to help you with any inquiries about our organization.
                        </p>
                        <a href="mailto:info@nocollarmedia.com" class="inline-flex items-center text-orange-600 font-medium hover:text-orange-700 transition-colors">
                            Send us an email
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
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

<script>
// Add fade-in animations
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.animate-fade-in');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection