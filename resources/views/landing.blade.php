@extends('layouts.app-no-nav')

@section('title', 'Shree Hindutakht - Home')

@section('head')
<style>
    body {
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    .text-justify {
        text-align: justify;
    }
    .spiritual-gradient {
        background: linear-gradient(135deg, #b93a20 0%, #ea580c 100%);
    }
    .custom-shadow {
        box-shadow: 0 4px 20px -2px rgba(185, 58, 32, 0.15);
    }
    .custom-shadow-hover:hover {
        box-shadow: 0 10px 30px -5px rgba(185, 58, 32, 0.25);
    }
    .nav-link-custom {
        position: relative;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    .nav-link-custom::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -4px;
        left: 0;
        background-color: #b93a20;
        transition: width 0.3s ease;
    }
    .nav-link-custom:hover::after {
        width: 100%;
    }
    
    /* Horizontal scrollbar styling for the gallery */
    .scrollbar-thin::-webkit-scrollbar {
        height: 8px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #ea580c;
        border-radius: 4px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #b93a20;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col bg-white text-gray-800">



    <!-- Section 2: Main Navigation Header (White Background) -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            
            <!-- Logo Section using Hindu-Takth-Logo-1.png -->
            <a href="{{ route('landing') }}" class="flex items-center">
                <img src="{{ asset('images/hindu-takht-logo-full.png') }}" alt="Shree Hindu Takht Logo" class="h-10 sm:h-12 w-auto object-contain">
            </a>

            <!-- Desktop Menu (strictly keeping requested landing page section anchors) -->
            <nav class="hidden lg:flex items-center space-x-8 text-sm text-gray-700 font-semibold">
                <a href="#" class="nav-link-custom text-orange-600">HOME</a>
                <a href="#about-intro" class="nav-link-custom">ABOUT US</a>
                <a href="#main-work" class="nav-link-custom">OUR WORK</a>
                <a href="#gallery" class="nav-link-custom">GALLERY</a>
            </nav>

            <!-- Login / Register Buttons in Header -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition-colors">Register</a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 text-gray-700 hover:text-orange-600 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 py-3 px-4 space-y-3 font-semibold text-sm">
            <a href="#" class="block text-orange-600 py-1 border-b border-gray-50">HOME</a>
            <a href="#about-intro" class="block text-gray-700 hover:text-orange-600 py-1 border-b border-gray-50">ABOUT US</a>
            <a href="#main-work" class="block text-gray-700 hover:text-orange-600 py-1 border-b border-gray-50">OUR WORK</a>
            <a href="#gallery" class="block text-gray-700 hover:text-orange-600 py-1 border-b border-gray-50">GALLERY</a>
            <div class="pt-3 border-t border-gray-150 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="block text-center text-gray-700 hover:text-orange-600 py-2 font-semibold border border-gray-200 rounded-md">Login</a>
                <a href="{{ route('register') }}" class="block text-center bg-orange-600 hover:bg-orange-700 text-white py-2 font-semibold rounded-md">Register</a>
            </div>
        </div>
    </header>

    <!-- Section 3: Hero Banner (Full-Width Responsive Image) -->
    <section class="w-full bg-orange-600">
        <img src="{{ asset('images/banner-hindu-takht.jpg') }}" alt="Shree Hindu Takht Membership Campaign Banner" class="w-full h-auto object-cover block">
    </section>

    <!-- Section 4: Shri Hindutakht Introduction (Alternating Rows) -->
    <section id="about-intro" class="py-16 bg-white px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-20">

            <!-- Row 1: ~ Shri Hindutakht ~ -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                <!-- Left: Custom slanted side-by-side images aligned at the bottom -->
                <div class="lg:col-span-5 flex items-end gap-4 w-full">
                    <div class="w-[58%] rounded-xl overflow-hidden shadow-md transform hover:scale-102 transition-transform duration-300" style="clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%, 0 12%);">
                        <img src="{{ asset('images/hindu-takt-img.jpg') }}" alt="Certificate Presentation" class="w-full aspect-[3/4] object-cover object-top">
                    </div>
                    <div class="w-[38%] rounded-xl overflow-hidden shadow-md transform hover:scale-102 transition-transform duration-300">
                        <img src="{{ asset('images/hindu-takt-img-2.jpg') }}" alt="Gita Presentation" class="w-full aspect-[3/4] object-cover object-top">
                    </div>
                </div>
                <!-- Right: Text details -->
                <div class="lg:col-span-7 space-y-6">
                    <h2 class="text-3xl font-extrabold text-orange-600 tracking-wide mb-1 flex items-center gap-2">
                        ~ Shri Hindutakht ~
                    </h2>
                    <div class="text-gray-700 leading-relaxed text-justify space-y-4 text-[15px]">
                        <p>
                            <strong>BHARAT</strong> is the greatest land in the world. Sages and Saints took birth in this holy land. Through Penance, sages and saints composed the world\'s first scriptures on this land of India and guided the world.
                        </p>
                        <p>
                            Our Indian sages knew about the universe thousands of years ago. Our sages knew about science thousands of years ago.
                        </p>
                        <p>
                            India was known in the world as the <strong>"Golden Bird"</strong>. The kings and society here were very prosperous and glorious.
                        </p>
                        <p>
                            When there were no schools for studying in European countries, at that time, there were lakhs of Gurukuls and universities in India.
                        </p>
                        <p>
                            People from foreign countries used to come to India to acquire knowledge. Attracted by the prosperity and grandeur of India, foreign invaders attacked the Indian land. Foreign invaders looted many parts of undivided India and forcibly converted people to other religions.
                        </p>
                        <p>
                            Many countries surrendered to the foreign invaders, and along with the king, the people also changed their religion.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Row 2: Sanatan Hindu Heritage & Struggles -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                <!-- Left: Large image -->
                <div class="lg:col-span-5 rounded-xl overflow-hidden shadow-md transform hover:scale-102 transition-transform duration-300">
                    <img src="{{ asset('images/hindu-takht-new.jpg') }}" alt="Shri Parveen Kumar with Saints" class="w-full aspect-[3/4] object-cover object-top">
                </div>
                <!-- Right: Text details -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex flex-col items-start">
                        <h3 class="text-2xl font-extrabold text-red-700 tracking-wide mb-1">
                            Sanatan Hindu Struggle & Preservation
                        </h3>
                        <div class="h-1 w-20 bg-orange-500 rounded"></div>
                    </div>
                    <div class="text-gray-700 leading-relaxed text-justify space-y-4 text-[15px]">
                        <p>
                            Sanatan Hindu is an example for the world that fought against big invaders. The brave warriors of India fought bravely against the foreign invaders. This shows how much the great ancestors of Sanatma Hindus struggled to protect their religion. The Sanatam Hindus of India are the most powerful and greatest community in the world, which fought bravely against foreign invaders and invaders for thousands of years, but did not change its religion.
                        </p>
                        <p>
                            The biggest reason for this was the contribution of our scriptures. In India, the teachings of religious texts were taught through the Guru-disciple tradition. The Guru would speak, and the disciples would memorize it. In those times, writing was not practiced. After a long time, texts began to be written.
                        </p>
                        <p>
                            Foreign invaders used to kill our Sanatan scholars so that they could not teach and inspire the scriptures. Foreign invaders burnt the scriptures of Sanatan Dharma. Apart from this, they also ended the Gurukul tradition of Sanatan Dharma and implemented their own education system to make the character of the people of India dishonest.
                        </p>
                        <p>
                            Leftist writers were asked to write such a history for the people of India to read, which would create a feeling of inferiority among the Sanatan Hindus of India. Even after hundreds of years, Sanatan Hindus started believing the history of the Mughal Period and the British period to be true. Due to this, today's modern Hindu society is unaware of its ancient religious knowledge to a large extent.
                        </p>
                        <p>
                            From time to time, scholars of Sanatan Dharma started searching for ancient texts to give information about ancient history to the Hindu society.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Row 3: Founder Shri Parveen Kumar Ji -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                <!-- Left: Text details -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex flex-col items-start">
                        <h3 class="text-2xl font-extrabold text-red-700 tracking-wide mb-1">
                            Establishment & Leadership
                        </h3>
                        <div class="h-1 w-20 bg-orange-500 rounded"></div>
                    </div>
                    <div class="text-gray-700 leading-relaxed text-justify space-y-4 text-[15px]">
                        <p>
                            Sanatan scholars discovered and studied their ancient religious texts so that Hindus could know about their glorious history. A lot of work was done for this. The history that was hidden by leftist historians was presented before the world. Even today our ancient texts are studied in foreign countries. These are the ancient texts that foreign looters looted from India and took to their countries.
                        </p>
                        <p>
                            <strong>Shri Parveen Kumar Ji</strong>, the founder president of Shri Hindu Takht, created such an organisation for Hindus living in India and abroad to spread Sanatan Dharma and Sanatan traditions so that everyone can get the knowledge of our ancient sages and saints of Sanatan Dharma day and night, and told them his thoughts. Shri Hindu Takht was established with the blessings of great men and saints.
                        </p>
                        <p>
                            Mr. Parveen Kumar is a very big industrialist. The sanskars of his previous life inspired him to give a part of his earnings to serve the saints and Sanatan Dharma. Apart from being an industrialist, he is also a great social worker. He has a desire in his heart that Sanatan Hindus should get information about their ancient, glorious history. That is why he has started this work under the supervision of revered saints and Mahamandleshwars.
                        </p>
                        <p>
                            <strong>“Shri Hindu Takht”</strong> was established. Shri Hindu Takht is run by a trust under the guidance of saints and mahatmas.
                        </p>
                    </div>
                </div>
                <!-- Right: Large vertical image -->
                <div class="lg:col-span-5 rounded-xl overflow-hidden shadow-md transform hover:scale-102 transition-transform duration-300">
                    <img src="{{ asset('images/hindu-takt-img-4.jpg') }}" alt="Presenting temple model" class="w-full aspect-[3/4] object-cover object-top">
                </div>
            </div>

        </div>
    </section>

    <!-- Section 5: The Main Work Done (Two Columns) -->
    <section id="main-work" class="py-16 bg-gray-50 px-4 sm:px-6 lg:px-8 border-y border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-red-700 flex items-center justify-center gap-2">
                    <span class="text-orange-500 font-medium text-2xl">~</span> The Main Work Done By Shri Hindu Takht Us: <span class="text-orange-500 font-medium text-2xl">~</span>
                </h2>
                <div class="h-1 w-28 bg-orange-500 rounded mx-auto mt-2"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left: List of 10 points -->
                <div class="lg:col-span-7 space-y-4">
                    @php
                        $works = [
                            "Bringing ancient history to Sanatan society by scholars.",
                            "To provide all kinds of support to Hindus living in India and abroad through the organization.",
                            "To pressurize the government to declare cow as the national animal, to make the society aware about it and to get a strict law passed in the parliament to protect the cow species in all the states of India. And to get cow shelters constructed.",
                            "To train unemployed young Sanatan Hindu boys and girls through skill development for livelihood.",
                            "To make the society aware for \"Uniform Civil Code\" so that all the citizens of India get equal law and justice.",
                            "To exert pressure on the Indian Government to ensure that the money that comes to Indian temples through donations is used only for the service of Sanatan Hindus.",
                            "Getting the girls of poor Hindu families married.",
                            "Organising medical camps and maintaining traditions so that Sanatan children can get cultured education.",
                            "Making strict laws to prevent religious conversion.",
                            "To make the society aware to stop love jihad and land jihad."
                        ];
                    @endphp

                    @foreach($works as $index => $work)
                        <div class="flex items-start bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-500 transform hover:translate-x-1 transition-transform duration-200">
                            <span class="inline-flex items-center justify-center bg-orange-100 text-orange-700 font-extrabold rounded-full w-7 h-7 text-sm mr-3 flex-shrink-0 mt-0.5 shadow-inner">
                                {{ $index + 1 }}
                            </span>
                            <p class="text-gray-700 leading-relaxed text-sm font-medium">{{ $work }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Composite 2x2 Image Grid -->
                <div class="lg:col-span-5 rounded-xl overflow-hidden shadow-md bg-white p-2 border border-gray-150 transform hover:scale-102 transition-transform duration-300">
                    <img src="{{ asset('images/main-work-hindu-tkht (1).png') }}" alt="Main Work Done Grid" class="w-full h-auto object-cover rounded-lg">
                </div>

            </div>
        </div>
    </section>

    <!-- Section 6: Checkout Our Gallery (Full Width Scrollable) -->
    <section id="gallery" class="py-16 bg-white px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="flex flex-col items-start">
                <h2 class="text-3xl font-extrabold text-red-700 flex items-center gap-2">
                    <span class="text-orange-500 font-medium text-2xl">~</span> Checkout Our Gallery <span class="text-orange-500 font-medium text-2xl">~</span>
                </h2>
                <div class="h-1 w-24 bg-orange-500 rounded mt-2"></div>
            </div>

            <!-- Gallery Horizontal Scroll row container -->
            <div class="w-full">
                <div class="flex overflow-x-auto gap-6 pb-6 scrollbar-thin snap-x snap-mandatory">
                    @for($i = 1; $i <= 12; $i++)
                        <div class="flex-shrink-0 w-[280px] sm:w-[320px] md:w-[360px] snap-start transform hover:scale-[1.02] transition-transform duration-300">
                            <img src="{{ asset('images/gallery-' . $i . '.jpg') }}" alt="Gallery Image {{ $i }}" class="w-full h-auto rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 bg-white">
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Section 7: Footer (Aligned to Landing page sections & new logo) -->
    <footer id="footer" class="bg-gray-800 text-white py-12 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                
                <!-- Column 1: Shree Hindutakht (Logo & Description) -->
                <div class="space-y-4 md:col-span-2">
                    <div class="flex items-center space-x-2 bg-white/5 p-3 rounded-lg border border-white/10 w-fit">
                        <img src="{{ asset('images/hindu-takht-logo-full.png') }}" alt="Shree Hindu Takht Logo" class="h-16 w-auto object-contain">
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed max-w-md">
                        A community platform for Hindu devotees to connect, share, and grow together in Dharma.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-4 md:col-span-1">
                    <h3 class="text-lg font-bold text-orange-500 border-b border-orange-500/20 pb-2 mb-3 uppercase tracking-wider">
                        Quick Links
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-300 font-semibold">
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-1.5">&raquo; Home</a></li>
                        <li><a href="#about-intro" class="hover:text-white transition-colors flex items-center gap-1.5">&raquo; About Us</a></li>
                        <li><a href="#main-work" class="hover:text-white transition-colors flex items-center gap-1.5">&raquo; Our Work</a></li>
                        <li><a href="#gallery" class="hover:text-white transition-colors flex items-center gap-1.5">&raquo; Gallery</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright Row -->
            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400 text-xs font-semibold">
                <p>&copy; {{ date('Y') }} Shree Hindutakht. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

</div>

<!-- Floating Back to Top Button -->
<button id="back-to-top" class="fixed bottom-6 right-6 bg-white border-2 border-orange-500 text-orange-600 hover:bg-orange-500 hover:text-white rounded-full p-2.5 shadow-lg transition-all duration-300 transform hover:-translate-y-1 focus:outline-none z-50 flex items-center justify-center w-11 h-11 hidden">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

<!-- Interactive JS logic for Mobile Menu and Back to Top -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Back to Top Button Logic
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when a section link is clicked
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });
    });
</script>
@endsection