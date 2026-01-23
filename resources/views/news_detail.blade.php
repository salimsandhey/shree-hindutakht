@extends('layouts.app')

@section('title', $news->title . ' - News - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    <!-- Sticky Header (Adjusts based on login status) -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 sticky {{ auth()->check() ? 'top-[70px]' : 'top-0' }} z-40 transition-all duration-300">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <a href="{{ route('news.index') }}" class="flex items-center text-gray-600 hover:text-primary transition-colors group">
                    <div class="p-1.5 rounded-full group-hover:bg-gray-100 transition-colors mr-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-sm">Back to News</span>
                </a>
                <div class="flex items-center space-x-2">
                    <!-- Share Button (Optional Placeholder) -->
                    <button class="p-2 text-gray-500 hover:text-primary rounded-full hover:bg-gray-100 transition-colors" title="Share">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Hero Image / Slider Section -->
            @if($news->media_urls && count($news->media_urls) > 0)
                <div class="relative group bg-gray-100 aspect-video">
                    
                    <!-- Scroll Container -->
                    <div id="news-slider" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none h-full w-full touch-pan-x" style="scroll-behavior: smooth;">
                        @foreach($news->media_urls as $index => $mediaUrl)
                            <div class="w-full flex-none snap-center h-full relative" data-index="{{ $index }}">
                                <img src="{{ $mediaUrl }}" 
                                     alt="{{ $news->title }}" 
                                     class="w-full h-full object-contain" 
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                     onerror="this.src='{{ asset('placeholder.jpg') }}'; this.onerror=null;">
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Controls (Only if > 1 image) -->
                    @if(count($news->media_urls) > 1)
                        <!-- Arrows -->
                        <button onclick="moveSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hidden md:block z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button onclick="moveSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hidden md:block z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <!-- Counter Pill -->
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md text-white px-3 py-1 rounded-full text-xs font-medium z-10">
                            <span id="slider-counter">1</span> / {{ count($news->media_urls) }}
                        </div>

                        <!-- Dots -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                            @foreach($news->media_urls as $index => $url)
                                <button onclick="goToSlide({{ $index }})" 
                                        class="slider-dot w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80' }}"
                                        aria-label="Go to slide {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Featured Badge -->
                    @if($news->featured)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                Featured
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Article Body -->
            <div class="p-6 md:p-8">
                <!-- Meta Info -->
                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-6 border-b border-gray-100 pb-6">
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-1.5 rounded-full mr-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">{{ $news->author_name ?? 'Admin' }}</span>
                    </div>
                    <div class="h-1 w-1 rounded-full bg-gray-300"></div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <time datetime="{{ $news->published_at }}">{{ \Carbon\Carbon::parse($news->published_at)->format('M d, Y') }}</time>
                    </div>
                    @if($news->views_count > 0)
                        <div class="h-1 w-1 rounded-full bg-gray-300"></div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>{{ $news->views_count }} views</span>
                        </div>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $news->title }}
                </h1>

                <!-- Content -->
                <div class="prose prose-lg prose-orange max-w-none text-gray-600 leading-relaxed">
                    {!! $news->content !!}
                </div>
            </div>
        </article>

        <!-- Related News -->
        @if(isset($relatedNews) && count($relatedNews) > 0)
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">More News</h2>
                    <a href="{{ route('news.index') }}" class="text-sm text-primary font-medium hover:text-orange-700">View All</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedNews as $related)
                        <a href="{{ route('news.show', $related->id) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full">
                            <div class="aspect-[16/9] bg-gray-100 relative overflow-hidden">
                                @if($related->media_urls && count($related->media_urls) > 0)
                                    <img src="{{ $related->media_urls[0] }}" 
                                         alt="{{ $related->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         onerror="this.src='{{ asset('placeholder.jpg') }}'; this.onerror=null;">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-gray-500 text-sm line-clamp-2 mb-4 flex-1">
                                    {{ Str::limit(strip_tags($related->content), 100) }}
                                </p>
                                <div class="text-xs text-gray-400 flex items-center mt-auto">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($related->published_at)->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>
</div>

<style>
    /* Custom Scrollbar hide */
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('news-slider');
        if (!slider) return;

        const dots = document.querySelectorAll('.slider-dot');
        const counter = document.getElementById('slider-counter');

        // Scroll listener to update UI
        slider.addEventListener('scroll', () => {
            const index = Math.round(slider.scrollLeft / slider.clientWidth);
            
            // Update dots
            dots.forEach((dot, i) => {
                if(i === index) {
                    dot.classList.remove('bg-white/50', 'w-2');
                    dot.classList.add('bg-white', 'w-6');
                } else {
                    dot.classList.remove('bg-white', 'w-6');
                    dot.classList.add('bg-white/50', 'w-2');
                }
            });

            // Update counter
            if(counter) counter.textContent = index + 1;
        });
    });

    function moveSlide(direction) {
        const slider = document.getElementById('news-slider');
        if(!slider) return;
        const width = slider.clientWidth;
        slider.scrollBy({ left: direction * width, behavior: 'smooth' });
    }

    function goToSlide(index) {
        const slider = document.getElementById('news-slider');
        if(!slider) return;
        const width = slider.clientWidth;
        slider.scrollTo({ left: index * width, behavior: 'smooth' });
    }
</script>
@endsection