<x-app-layout>
        <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight italic tracking-tighter">
                    {{ __('Reports & Intelligence') }}
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ __('Business performance at a glance') }}</p>
                </div>
                </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-green-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="relative text-left">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Daily Revenue') }}</div>
                        <div class="text-3xl font-black text-slate-900 italic tracking-tighter">
                            {{ number_format($dailyStats->revenue ?? 0, 3) }}</div>
                        <div class="text-[9px] font-black text-slate-400 mt-1 uppercase">KWD</div>
                    </div>
                </div>
<!-- Orders Card -->
<div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
    <div
        class="absolute top-0 right-0 w-24 h-24 bg-brand-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
        <svg class="w-8 h-8 text-brand-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
        </svg>
    </div>
                    <div class="relative text-left">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Total Orders') }}</div>
                        <div class="text-3xl font-black text-slate-900 italic tracking-tighter">{{ $dailyStats->count ?? 0 }}</div>
                        <div class="text-[9px] font-black text-slate-400 mt-1 uppercase">{{ __('Transactions') }}</div>
                    </div>
                    </div>
                    
                    <!-- Average Ticket -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                            <svg class="w-8 h-8 text-purple-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="relative text-left">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Avg. Order Value') }}
                            </div>
                            <div class="text-3xl font-black text-slate-900 italic tracking-tighter">
                                {{ ($dailyStats->count ?? 0) > 0 ? number_format(($dailyStats->revenue ?? 0) / $dailyStats->count, 3) : '0.000' }}
                            </div>
                            <div class="text-[9px] font-black text-slate-400 mt-1 uppercase">KWD</div>
                        </div>
                    </div>
                    
                    <!-- Conversion / Performance (Visual Placeholder) -->
                    <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10 text-left">
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2">{{ __('Performance') }}</div>
                            <div class="text-3xl font-black text-white italic tracking-tighter">{{ __('STABLE') }}</div>
                            <div class="mt-4 flex items-center gap-2">
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-brand-500 h-full w-[65%]"></div>
                                </div>
                                <span class="text-[10px] font-black text-white ml-2">65%</span>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Items Heat Map -->
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col h-full">
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter">{{ __('Top Selling Products') }}</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">{{ __('Last 30 days performance') }}
                        </p>
                    </div>
                    <div class="space-y-4 flex-1">
                        @forelse($topItems as $item)
                            <div class="relative group">
                                <div
                                    class="flex items-center justify-between p-5 bg-slate-50 rounded-3xl border border-slate-100 transition hover:bg-white hover:shadow-xl group-hover:-translate-y-1">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center font-black text-slate-400 border border-slate-100">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="font-black text-slate-800 uppercase tracking-widest text-xs">{{ $item->item_name }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <div class="text-lg font-black italic text-brand-600">{{ (float) $item->total_qty }}</div>
                                        <div class="text-[8px] font-black text-slate-400 uppercase tracking-[0.1em]">{{ __('Units Sold') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <div class="font-black text-xs uppercase tracking-widest">{{ __('No data available') }}</div>
                            </div>
                        @endforelse
                    </div>
                
                    <!-- Peak Hours Mini Chart -->
                    <div class="mt-12 pt-10 border-t border-slate-50">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-900">{{ __('Peak Traffic hours') }}</h4>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ __('24H Format') }}</span>
                        </div>
                        <div class="flex items-end justify-between h-32 gap-1 px-2">
                            @php $maxOrders = $peakHours->max('count') ?: 1; @endphp
                            @foreach($peakHours as $peak)
                                <div class="flex-1 flex flex-col items-center group">
                                    <div class="w-full bg-slate-100 rounded-lg relative overflow-hidden h-full flex items-end">
                                        <div class="bg-brand-500 w-full transition-all duration-1000 group-hover:bg-brand-600"
                                            style="height: {{ ($peak->count / $maxOrders) * 100 }}%"></div>
                                    </div>
                                    <div class="mt-2 text-[8px] font-black text-slate-400">{{ $peak->hour }}:00</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Export & API Hub -->
                <div class="space-y-8 h-full flex flex-col">
                    <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex-1">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter">{{ __('Direct Data Export') }}</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">
                                {{ __('Download structured CSV files') }}</p>
                        </div>
                        <form action="{{ route('admin.reports.export') }}" class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">{{ __('Start Date') }}</label>
                                    <input type="date" name="start_date"
                                        class="w-full border-none bg-slate-50 rounded-2xl p-4 font-black text-sm text-slate-700 ring-1 ring-slate-100 focus:ring-brand-500 transition"
                                        value="{{ now()->subDays(7)->toDateString() }}">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">{{ __('End Date') }}</label>
                                    <input type="date" name="end_date"
                                        class="w-full border-none bg-slate-50 rounded-2xl p-4 font-black text-sm text-slate-700 ring-1 ring-slate-100 focus:ring-brand-500 transition"
                                        value="{{ now()->toDateString() }}">
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-slate-900 text-white py-5 rounded-3xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-slate-300 hover:bg-slate-800 hover:scale-[1.01] transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                {{ __('Generate CSV Report') }}
                            </button>
                        </form>

                        <div class="mt-12 pt-10 border-t border-slate-50">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                {{ __('POS Integration Hub') }}
                            </h4>
                            <div class="bg-slate-900 rounded-[2rem] p-8 space-y-6 relative overflow-hidden group">
                                <div class="relative z-10">
                                    <div class="text-[9px] font-black text-white/40 mb-2 uppercase tracking-widest">
                                        {{ __('Live JSON Endpoint') }}</div>
                                    <div
                                        class="font-mono text-[10px] text-brand-400 break-all bg-white/5 p-3 rounded-xl ring-1 ring-white/10 select-all">
                                        {{ url('/api/pos/orders') }}
                                    </div>
                                    <div class="mt-6 text-[9px] font-black text-white/40 mb-2 uppercase tracking-widest">
                                        {{ __('Authorization Key') }}</div>
                                    <div
                                        class="font-mono text-[10px] text-white bg-white/5 p-3 rounded-xl ring-1 ring-white/10 flex items-center justify-between">
                                        <span class="truncate">{{ $tenant->api_key ?: 'NO_API_KEY_FOUND' }}</span>
                                        <span
                                            class="text-[8px] font-black text-brand-500 uppercase tracking-widest ml-2">{{ __('Active') }}</span>
                                    </div>
                                </div>
                                <div class="absolute -right-8 -bottom-8 opacity-5 transition-transform group-hover:scale-110">
                                    <svg class="w-40 h-40 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-5 14H5v-9h10v9zm5 0h-4V9h4v9z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>