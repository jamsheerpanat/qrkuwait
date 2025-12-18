<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $currentTenant->name ?? 'QR Kuwait' }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4F46E5">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Tajawal:wght@400;500;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'Tajawal', 'sans-serif'],
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
        [x-cloak] { display: none !important; }
        
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        input:focus { ring: 2px; ring-color: #3d56ff; }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-900 antialiased pb-20 font-sans"
    x-data="{ cartCount: 0, isRtl: '{{ app()->getLocale() == 'ar' ? true : false }}' }" :dir="isRtl ? 'rtl' : 'ltr'"
    @cart-updated.window="cartCount = $event.detail.count">
    <!-- Top Bar -->
    <header class="sticky top-0 z-50 glass shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-100 p-1.5 overflow-hidden">
                        @if(isset($settings['logo']) && is_string($settings['logo']) && $settings['logo'])
                            <img src="{{ $currentTenant->getSettingUrl('logo') }}" class="w-full h-full object-contain">
                        @else
                            <div
                                class="w-full h-full bg-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($currentTenant->name ?? 'Q', 0, 1) }}
                            </div>
                        @endif
                        </div>
                    <div>
                        <h1 class="font-bold text-lg text-slate-900 leading-tight">
                            {{ $currentTenant->name ?? 'Store Name' }}</h1>
                        <p class="text-[10px] text-slate-500 flex items-center gap-1 uppercase tracking-widest font-bold">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                            {{ __('Open Now') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="?lang={{ app()->getLocale() == 'en' ? 'ar' : 'en' }}"
                        class="px-3 py-1.5 bg-white border border-slate-100 rounded-xl text-xs font-black uppercase hover:bg-slate-50 transition shadow-sm">
                        {{ app()->getLocale() == 'en' ? 'العربية' : 'English' }}
                    </a>
                    <button
                        class="relative w-12 h-12 flex items-center justify-center bg-brand-600 text-white rounded-2xl shadow-lg shadow-brand-100 hover:scale-105 transition"
                        @click="$dispatch('open-cart')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                        </svg>
                        <span x-show="cartCount > 0"
                            class="absolute -top-1 -right-1 w-5 h-5 bg-white text-brand-600 text-[10px] font-black rounded-full flex items-center justify-center shadow-md animate-bounce"
                            x-text="cartCount"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 min-h-screen">
        @yield('content')
    </main>

    <!-- Bottom Nav (Enhanced) -->
    <nav class="fixed bottom-6 left-6 right-6 glass rounded-3xl px-6 py-4 sm:hidden z-50 shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center relative z-10">
            <a href="#" class="flex flex-col items-center gap-1 text-brand-600">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                    <path
                        d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75v4.5a.75.75 0 01-.75.75H5.719c-1.035 0-1.875-.84-1.875-1.875V13.677c.031-.028.062-.056.091-.086L12 5.432z" />
                </svg>
                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Home') }}</span>
            </a>
            <button @click="$dispatch('open-search')" class="flex flex-col items-center gap-1 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Search') }}</span>
                </button>
                <button @click="$dispatch('open-cart')" class="flex flex-col items-center gap-1 text-slate-400 relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z">
                    </path>
                </svg>
                <span x-show="cartCount > 0"
                    class="absolute -top-1 -right-1 w-4 h-4 bg-brand-600 text-white text-[8px] rounded-full flex items-center justify-center font-bold"
                    x-text="cartCount"></span>
                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Order') }}</span>
                </button>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Track') }}</span>
            </a>
        </div>
    </nav>
</body>

</html>