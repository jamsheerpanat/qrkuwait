<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight italic tracking-tighter">
                    {{ __('Admin Hub') }}
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ $currentTenant->name }}</p>
                </div>
                <a href="{{ route('tenant.public', $currentTenant->slug) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 px-6 py-3 rounded-2xl text-sm font-black text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    {{ __('View Storefront') }}
                </a>
                </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Today's Orders -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-brand-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-brand-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Orders Today') }}</div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ $stats['orders_today'] }}</div>
                        <div
                            class="mt-4 flex items-center gap-1 text-[10px] font-bold {{ $stats['orders_today'] > 0 ? 'text-green-500' : 'text-slate-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $stats['orders_today'] > 0 ? __('Receiving Traffic') : __('Waiting for orders') }}
                        </div>
                    </div>
                </div>
                
                <!-- Daily Revenue -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-green-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Revenue Today') }}
                        </div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">
                            {{ number_format($stats['revenue_today'], 3) }}</div>
                        <div class="text-xs font-black text-slate-400 mt-1 uppercase">KWD</div>
                    </div>
                </div>

                <!-- Active Staff -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-purple-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Team Online') }}</div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ $stats['staff_count'] }}</div>
                        <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-slate-400">
                            {{ __('Active personnel') }}
                        </div>
                    </div>
                    </div>
                    
                    <!-- Store Status -->
                    <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2">{{ __('Store Status') }}</div>
                            <div class="text-3xl font-black text-white italic tracking-tighter">{{ __('ONLINE') }}</div>
                            <div
                                class="mt-4 inline-flex items-center gap-2 bg-green-500 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                {{ __('Live Now') }}
                            </div>
                        </div>
                        <div class="absolute -right-4 -bottom-4 opacity-10">
                            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                            </svg>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Recent Activity Table -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">{{ __('Recent Orders') }}</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">{{ __('Latest customer transactions') }}</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}"
                            class="text-brand-600 font-black text-xs uppercase tracking-widest hover:text-brand-700 transition">
                            {{ __('View All Orders') }} →
                        </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            {{ __('Order ID') }}</th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            {{ __('Customer') }}</th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Status') }}
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Amount') }}
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Time') }}
                                        </th>
                                        <th class="px-8 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($recentOrders as $order)
                                        <tr class="hover:bg-slate-50 transition cursor-default group">
                                            <td class="px-8 py-6 font-black text-slate-900 italic">#{{ $order->order_no }}</td>
                                            <td class="px-8 py-6">
                                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold">{{ $order->customer_phone }}</div>
                                            </td>
                                            <td class="px-8 py-6">
                                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                                        @if($order->status == 'pending') bg-amber-50 text-amber-600 
                                                                        @elseif($order->status == 'accepted' || $order->status == 'ready' || $order->status == 'delivered') bg-green-50 text-green-600 
                                                                        @else bg-slate-100 text-slate-500 @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-6 font-black text-slate-900 italic tracking-tighter">
                                                {{ number_format($order->total, 3) }} <span
                                                    class="text-[9px] not-italic text-slate-400 uppercase">KWD</span></td>
                                            <td class="px-8 py-6 text-xs text-slate-400 font-bold uppercase">
                                                {{ $order->created_at->diffForHumans() }}</td>
                                            <td class="px-8 py-6 text-right">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="p-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-brand-600 hover:text-white transition inline-block">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-8 py-20 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-4">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="font-bold text-slate-400">{{ __('No orders recorded yet') }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6">
                    <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">{{ __('Inventory Shortcut') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.categories.index') }}"
                            class="p-6 bg-slate-50 rounded-3xl hover:bg-brand-50 transition border border-slate-50 group">
                            <div
                                class="w-10 h-10 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-brand-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 6h16M4 12h10M4 18h16"></path>
                                </svg>
                            </div>
                            <div class="font-black text-slate-900 text-sm uppercase tracking-widest">{{ __('Categories') }}</div>
                        </a>
                        <a href="{{ route('admin.items.index') }}"
                            class="p-6 bg-slate-50 rounded-3xl hover:bg-purple-50 transition border border-slate-50 group">
                            <div
                                class="w-10 h-10 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-purple-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="font-black text-slate-900 text-sm uppercase tracking-widest">{{ __('Menu Items') }}</div>
                        </a>
                    </div>
                </div>
<div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">{{ __('Store Profile') }}</h3>
                        <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
                            {{ __('Control your store settings, operating hours, and localized content from the central management panel.') }}
                        </p>
                        </div>
                    <div class="mt-8 flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white">
                            {{ substr($currentTenant->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-slate-900 uppercase tracking-widest text-xs">{{ $currentTenant->name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold">{{ __('Primary Merchant Account') }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="px-6 py-2 bg-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition">
                            {{ __('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>