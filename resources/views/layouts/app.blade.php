<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QRKuwait') }} - Digital Ordering Solutions</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/qrkuwait-logo.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div>{{ $header }}</div>
                    @if(isset($currentTenant))
                        <div
                            class="hidden md:flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-50 to-cyan-50 rounded-xl border border-indigo-100">
                            <img src="{{ asset('images/qrkuwait-logo.png') }}" alt="QRKuwait" class="h-6">
                            <span class="text-sm font-bold text-slate-600">{{ $currentTenant->name }}</span>
                        </div>
                    @endif
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg flex justify-between items-center font-bold">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </span>
                        <button @click="show = false" class="hover:bg-white/20 rounded-lg p-1">&times;</button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-600 text-white px-6 py-4 rounded-2xl shadow-lg flex justify-between items-center font-bold">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('error') }}
                        </span>
                        <button @click="show = false" class="hover:bg-white/20 rounded-lg p-1">&times;</button>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
<!-- Footer -->
<footer class="bg-white border-t border-gray-100 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/qrkuwait-logo.png') }}" alt="QRKuwait" class="h-8">
            </div>
            <div class="text-center md:text-right">
                <p class="text-xs text-slate-400 font-medium">
                    Powered by <a href="https://octonics.io" target="_blank"
                        class="font-bold text-slate-600 hover:text-indigo-600 transition">Octonics Innovations</a>
                </p>
                <p class="text-[10px] text-slate-300 mt-1">© {{ date('Y') }} QRKuwait. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
    </div>
</body>

</html>