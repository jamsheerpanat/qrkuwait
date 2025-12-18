<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $currentTenant->name ?? 'QR Kuwait' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f7ff',
                            100: '#ebf0ff',
                            200: '#d6e0ff',
                            300: '#b3c5ff',
                            400: '#85a0ff',
                            500: '#5c7bff',
                            600: '#3d56ff',
                            700: '#2e3fff',
                            800: '#2532d1',
                            900: '#242ea8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-900 antialiased pb-20">
    <!-- Top Bar -->
    <header class="sticky top-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand-200">
                        {{ substr($currentTenant->name ?? 'Q', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-slate-900 leading-tight">
                            {{ $currentTenant->name ?? 'Store Name' }}</h1>
                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Online Now
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-500 hover:text-brand-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <button class="relative p-2 text-slate-500 hover:text-brand-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                        </svg>
                        <span
                            class="absolute top-1 right-1 w-4 h-4 bg-brand-600 text-[10px] font-bold text-white rounded-full flex items-center justify-center">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Bottom Nav (Mobile Inspired) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 px-6 py-3 block sm:hidden z-50">
        <div class="flex justify-between items-center">
            <a href="#" class="flex flex-col items-center gap-1 text-brand-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                <span class="text-[10px] font-medium">Menu</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                </svg>
                <span class="text-[10px] font-medium">Cart</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-[10px] font-medium">Profile</span>
            </a>
        </div>
    </nav>
</body>

</html>