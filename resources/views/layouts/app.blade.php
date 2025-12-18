<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div>{{ $header }}</div>
                    @if(isset($currentTenant))
                        <div
                            class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-bold border border-indigo-100 italic">
                            QR<span class="text-indigo-400">Kuwait</span>: {{ $currentTenant->name }}
                        </div>
                    @endif
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-600 text-white px-6 py-4 rounded-2xl shadow-lg flex justify-between items-center font-bold">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false">&times;</button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-600 text-white px-6 py-4 rounded-2xl shadow-lg flex justify-between items-center font-bold">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false">&times;</button>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>

</html>