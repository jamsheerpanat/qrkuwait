<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $currentTenant->name ?? 'QR Kuwait' }}</title>

    <meta name="theme-color" content="#0f172a">
    <!-- Fonts - Minimalist Selection -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        input:focus { outline: none; }
        
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased font-sans"
    x-data="{ cartCount: 0, isRtl: '{{ app()->getLocale() == 'ar' ? true : false }}' }" :dir="isRtl ? 'rtl' : 'ltr'"
    @cart-updated.window="cartCount = $event.detail.count">
    <!-- OCD Clean Top Bar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($currentTenant->logo_url)
                    <img src="{{ $currentTenant->logo_url }}" alt="{{ $currentTenant->name }}"
                        class="w-10 h-10 rounded-lg object-contain border border-slate-100">
                @else
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                        {{ substr($currentTenant->name ?? 'Q', 0, 1) }}
                    </div>
                @endif
                <h1 class="font-bold text-sm tracking-tight text-slate-900 uppercase">{{ $currentTenant->name ?? 'Store' }}</h1>
                </div>
            <div class="flex items-center gap-4">
                <a href="?lang={{ app()->getLocale() == 'en' ? 'ar' : 'en' }}"
                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
                    {{ app()->getLocale() == 'en' ? 'العربية' : 'EN' }}
                </a>
                <button @click="$dispatch('open-cart')" class="relative p-2 text-slate-900 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                    </svg>
                    <template x-if="cartCount > 0">
                        <span class="absolute top-1 right-1 w-2 h-2 bg-slate-900 rounded-full border border-white"></span>
                    </template>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-12 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8 pb-24 sm:pb-8">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/qrkuwait-logo.png') }}" alt="QRKuwait"
                    class="h-10 brightness-0 invert opacity-80">
            </div>
            <p class="text-xs text-slate-400 mb-2">Digital Ordering Solutions</p>
            <p class="text-[10px] text-slate-500">
                Powered by <a href="https://octonics.io" target="_blank" class="text-cyan-400 hover:text-cyan-300">Octonics
                    Innovations</a>
            </p>
        </div>
    </footer>

    <!-- Minimal Bottom Nav -->
    @unless(request()->routeIs('tenant.checkout') || request()->routeIs('tenant.checkout.success'))
        <nav
            class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-100 safe-area-bottom sm:hidden z-50">
            <div class="flex items-center justify-around h-16">
                <a href="{{ route('tenant.public', $currentTenant->slug) }}"
                    class="p-4 {{ request()->routeIs('tenant.public') ? 'text-slate-900' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </a>
                <button @click="$dispatch('open-search')" class="p-4 text-slate-400 hover:text-slate-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <button @click="$dispatch('open-cart')" class="p-4 text-slate-400 hover:text-slate-900 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                    </svg>
                    <template x-if="cartCount > 0">
                        <span class="absolute top-4 right-4 w-1.5 h-1.5 bg-slate-900 rounded-full"></span>
                    </template>
                </button>
                <a href="#" class="p-4 text-slate-400 hover:text-slate-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </a>
            </div>
        </nav>
    @endunless
</body>
</html>