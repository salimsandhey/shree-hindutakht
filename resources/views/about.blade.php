@extends('layouts.app')

@section('title', 'About Us - Shree Hindutakht')

@section('content')
<!-- Mobile App-like Breadcrumbs -->
<div class="mobile-breadcrumbs md:hidden">
    <a href="/" class="breadcrumb-item">Home</a>
    <span class="breadcrumb-separator">/</span>
    <span class="breadcrumb-item active">About</span>
</div>

<div class="bg-gray-50 pt-16 mobile-content page-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 pt-4 sm:pt-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl animate-fade-in">About Shree Hindutakht</h1>
            <p class="mt-3 text-xl text-gray-500 animate-fade-in">Learn more about our mission, vision, and values</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <div class="prose prose-lg max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 animate-fade-in">Our Story</h2>
                    <p class="text-gray-600 mb-6 animate-fade-in">
                        Shree Hindutakht is a dedicated platform for community engagement, cultural preservation, and spiritual growth. 
                        We are committed to connecting individuals with their roots while fostering a sense of unity and purpose 
                        within the Hindu community.
                    </p>
                    
                    <!-- Image placeholder for our story -->
                    <div class="bg-gray-100 rounded-lg p-4 mb-8 touch-target">
                        <img src="{{ asset('images/about.jpg') }}" alt="Our Story" class="w-full h-64 object-cover rounded-xl">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="card touch-target nav-transition">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Our Mission</h3>
                            <p class="text-gray-600 animate-fade-in">
                                "Reviving Roots, Empowering Communities, Building a United Future." At Shri Hindu Takht, our mission 
                                is to reconnect the youth with the timeless values of Sanatan Dharma and foster a strong, united Hindu 
                                community. Under the esteemed leadership of Shri Parveen Kumar, we strive to promote selfless service, 
                                social welfare, and cultural preservation.
                            </p>
                        </div>
                        
                        <div class="card touch-target nav-transition">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Our Vision</h3>
                            <p class="text-gray-600 animate-fade-in">
                                "Empowering Faith, Uniting Communities, Building a Stronger Tomorrow." Guided by the principles of faith, 
                                unity, and service, Hindu Takht envisions a society where cultural heritage, spirituality, and community 
                                welfare thrive together. Under the esteemed leadership of Parveen Ji, we aim to uphold the rich traditions 
                                of our values while creating a future where every individual is empowered, supported, and connected to their roots.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 animate-fade-in">Leadership</h2>
                
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-shrink-0 touch-target">
                        <!-- Profile photo placeholder -->
                        <img src="{{ asset('images/parveen_sir.jpg') }}" alt="Shri Parveen Kumar" class="w-48 h-48 object-cover rounded-full">
                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-bold text-gray-900">Shri Parveen Kumar</h3>
                            <p class="text-orange-600 font-medium">National President & Chief Sewadar</p>
                        </div>
                    </div>
                    
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">"Serving Dharma, Uplifting Society The Journey of a True Sanatani Leader"</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">
                            Shri Parveen Kumar was born into a devout Hindu family and has been deeply connected to Sanatan values 
                            since his early school days. From a young age, he dedicated himself to selfless service and became a true 
                            sevadar of the Sanatan tradition. His thoughts, principles, and working style are deeply inspired by the 
                            teachings of Sanatan Dharma.
                        </p>
                        <p class="text-gray-600 mb-4 animate-fade-in">
                            A committed social worker and spiritual guide, Shri Parveen Kumar has always focused on connecting today's 
                            youth with the roots of Sanatan culture. His life's mission includes helping underprivileged families, 
                            promoting Gau Seva (cow protection and care), organizing the weddings of girls from economically weaker 
                            backgrounds, and fostering a sense of pride in Hindu identity and values.
                        </p>
                        <p class="text-gray-600 animate-fade-in">
                            As the National President and Chief Sewadar of Shri Hindu Takht, he continues to lead with unwavering 
                            dedication, compassion, and a deep sense of responsibility towards society. His work reflects not only 
                            his leadership but also his heartfelt commitment to the upliftment and unity of the Hindu community.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 card">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 animate-fade-in">Additional Information</h2>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Privacy Policy</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">Learn about how we protect your personal information and respect your privacy.</p>
                        <a href="{{ route('privacy-policy') }}" class="inline-flex items-center text-orange-600 font-medium hover:text-orange-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Read Privacy Policy
                        </a>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Safety Standards</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">Our commitment to child safety and protection against exploitation.</p>
                        <a href="{{ route('safety.standards') }}" class="inline-flex items-center text-orange-600 font-medium hover:text-orange-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            View Safety Standards
                        </a>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 animate-fade-in">Contact Us</h3>
                        <p class="text-gray-600 mb-4 animate-fade-in">Have questions or need assistance? Reach out to our team.</p>
                        <a href="mailto:info@nocollarmedia.com" class="inline-flex items-center text-orange-600 font-medium hover:text-orange-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Us
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