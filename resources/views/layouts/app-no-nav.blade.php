<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f97316">
    
    <title>@yield('title', 'Shree Hindutakht')</title>
    
    <!-- Tailwind CSS with custom configuration -->
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifestPath = public_path('build/manifest.json');
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $version = file_exists($manifestPath) ? filemtime($manifestPath) : time();
            $appCss = asset('build/' . $manifest['resources/css/app.css']['file']) . "?v=" . $version;
            $appJs = asset('build/' . $manifest['resources/js/app.js']['file']) . "?v=" . $version;
        @endphp
        <link rel="stylesheet" href="{{ $appCss }}">
        <script type="module" src="{{ $appJs }}"></script>
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('logo3.png') }}">
    
    <!-- Custom Styles -->
    <style>
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #b93a20;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a02f1a;
        }
        
        /* Smooth scrolling for all elements */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading indicator */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #b93a20;
            transform-origin: 0%;
            transform: scaleX(0);
            z-index: 9999;
            transition: transform 0.3s ease;
        }
        
        /* Touch targets for mobile */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Prevent pull-to-refresh on mobile */
        body {
            overscroll-behavior-y: contain;
        }
        
        /* Fix for double scrollbars */
        body {
            overflow: hidden;
        }
    </style>
    
    @yield('head')
</head>
<body class="bg-gray-50 min-h-screen antialiased">
    <!-- Loading indicator -->
    <div class="loading-indicator" id="loadingIndicator"></div>
    
    <!-- Main Content -->
    <main class="pt-0 pb-0">
        @yield('content')
    </main>
    
    <!-- Scripts -->
    <script>
        // Loading indicator
        window.addEventListener('beforeunload', function() {
            document.getElementById('loadingIndicator').style.transform = 'scaleX(1)';
        });
        
        // Hide loading indicator when page is loaded
        window.addEventListener('load', function() {
            document.getElementById('loadingIndicator').style.transform = 'scaleX(0)';
        });
        
        // Service worker disabled as per requirements
    </script>
    
    @yield('scripts')
</body>
</html>