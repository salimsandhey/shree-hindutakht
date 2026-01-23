@extends('layouts.app')

@section('title', 'Our Team - Shree Hindutakht')

@section('content')
<!-- Mobile App-like Breadcrumbs -->
<div class="mobile-breadcrumbs md:hidden">
    <a href="/" class="breadcrumb-item">Home</a>
    <span class="breadcrumb-separator">/</span>
    <span class="breadcrumb-item active">Team</span>
</div>

<div class="bg-gray-50 pt-16 mobile-content page-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 pt-4 sm:pt-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl animate-fade-in">Our Leadership Team</h1>
            <p class="mt-3 text-xl text-gray-500 animate-fade-in">Meet the dedicated individuals leading our mission</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 animate-fade-in">"One Vision, One Voice Uniting Hindus with Purpose and Pride"</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto animate-fade-in">
                        Shri Parveen Kumar is a dedicated leader committed to reviving Hindu values while addressing the challenges 
                        faced by the community. As the National President and Chief Sewadar of Shri Hindu Takht, he works tirelessly 
                        to build a society where tradition and progress go hand in hand.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-8 mb-12">
                    <div class="flex-shrink-0 touch-target">
                        <!-- Leadership photo placeholder -->
                        <img src="{{ asset('images/parveen_sir.jpg') }}" alt="Shri Parveen Kumar" class="w-64 h-64 object-cover rounded-full mx-auto">
                    </div>
                    
                    <div class="flex-grow">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2 animate-fade-in">Shri Parveen Kumar</h3>
                        <p class="text-orange-600 font-medium text-lg mb-4 animate-fade-in">National President & Chief Sewadar</p>
                        
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-600 mb-4 animate-fade-in">
                                His focus is on guiding youth, promoting ethical living, and organizing social initiatives that benefit 
                                the underprivileged. Shri Parveen Kumar's leadership brings together Hindus for a common cause, 
                                inspiring them to lead with purpose and serve with humility.
                            </p>
                            <p class="text-gray-600 animate-fade-in">
                                Under his esteemed leadership, Shri Hindu Takht continues to work towards creating a compassionate, 
                                just, and prosperous world for generations to come, where cultural heritage, spirituality, and 
                                community welfare thrive together.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- <div class="border-t border-gray-200 pt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 text-center animate-fade-in">Core Team Members</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-gray-50 rounded-lg p-6 text-center card touch-target">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-32 mx-auto flex items-center justify-center mb-4">
                                <span class="text-gray-500 text-sm">Team Member</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Team Member Name</h4>
                            <p class="text-orange-600 font-medium">Position</p>
                            <p class="text-gray-600 mt-2 text-sm">Brief description of role and contributions to the organization.</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-6 text-center card touch-target">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-32 mx-auto flex items-center justify-center mb-4">
                                <span class="text-gray-500 text-sm">Team Member</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Team Member Name</h4>
                            <p class="text-orange-600 font-medium">Position</p>
                            <p class="text-gray-600 mt-2 text-sm">Brief description of role and contributions to the organization.</p>
                        </div>
                        
                        
                        <div class="bg-gray-50 rounded-lg p-6 text-center card touch-target">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-32 mx-auto flex items-center justify-center mb-4">
                                <span class="text-gray-500 text-sm">Team Member</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Team Member Name</h4>
                            <p class="text-orange-600 font-medium">Position</p>
                            <p class="text-gray-600 mt-2 text-sm">Brief description of role and contributions to the organization.</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

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

        <div class="bg-white rounded-xl shadow-md overflow-hidden card">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 animate-fade-in">Join Our Team</h2>
                <p class="text-gray-600 mb-6 animate-fade-in">
                    We are always looking for passionate individuals who share our vision of cultural revival, social service, 
                    and spiritual unity. Whether you have skills in technology, event management, content creation, or community 
                    outreach, there's a place for you at Shri Hindu Takht.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in">
                    <a href="{{ route('register') }}" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-orange-700 transition-all duration-300 text-center mobile-button touch-target nav-transition">
                        Volunteer With Us
                    </a>
                    <a href="#" class="border border-orange-600 text-orange-600 px-6 py-3 rounded-lg font-medium hover:bg-orange-50 transition-all duration-300 text-center mobile-button touch-target nav-transition">
                        Contact For Opportunities
                    </a>
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