<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm font-medium text-gray-500 mb-1">Total Orders (Today)</div>
                    <div class="text-3xl font-bold">42</div>
                    <div class="mt-2 text-xs text-green-600 font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 10l7-7 7 7"></path>
                        </svg>
                        12% increase
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm font-medium text-gray-500 mb-1">Revenue</div>
                    <div class="text-3xl font-bold">156.400 KD</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm font-medium text-gray-500 mb-1">Active Tables</div>
                    <div class="text-3xl font-bold">8</div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Store Overview: {{ $currentTenant->name }}</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Primary Branch</div>
                                <div class="text-sm text-gray-500">Main Office, Kuwait City</div>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider">Active</span>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Staff Members</div>
                                <div class="text-sm text-gray-500">4 Employees logged in</div>
                            </div>
                        </div>
                        <a href="#" class="text-indigo-600 font-bold text-sm">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>