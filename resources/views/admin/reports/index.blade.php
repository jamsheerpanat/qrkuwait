<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operations & Reporting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Today's Revenue
                    </div>
                    <div class="text-3xl font-black italic">{{ number_format($dailyStats->revenue ?? 0, 3) }} KD</div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Today's Orders
                    </div>
                    <div class="text-3xl font-black italic">{{ $dailyStats->count ?? 0 }}</div>
                </div>
                <!-- ... other stats ... -->
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Items -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black italic mb-6">Top Selling Items</h3>
                    <div class="space-y-4">
                        @foreach($topItems as $item)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                                <span class="font-bold">{{ $item->item_name }}</span>
                                <span
                                    class="bg-brand-100 text-brand-600 px-3 py-1 rounded-lg font-black text-sm">{{ (float) $item->total_qty }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Export Module -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black italic mb-6">Export Orders</h3>
                    <form action="{{ route('admin.reports.export') }}" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">From</label>
                                <input type="date" name="start_date"
                                    class="w-full rounded-xl border-slate-100 bg-slate-50 font-bold"
                                    value="{{ now()->subDays(7)->toDateString() }}">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">To</label>
                                <input type="date" name="end_date"
                                    class="w-full rounded-xl border-slate-100 bg-slate-50 font-bold"
                                    value="{{ now()->toDateString() }}">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">DOWNLOAD
                            CSV</button>
                    </form>

                    <div class="mt-8 pt-8 border-t">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">POS Integration</h4>
                        <div class="bg-slate-50 p-4 rounded-2xl">
                            <div class="text-[10px] font-bold text-slate-400 mb-1">JSON API Endpoint</div>
                            <div class="font-mono text-xs break-all text-indigo-600 select-all">
                                {{ url('/api/pos/orders') }}</div>
                            <div class="mt-2 text-[10px] font-bold text-slate-400 mb-1">Merchant API Key</div>
                            <div
                                class="font-mono text-xs text-slate-900 bg-white p-2 rounded border border-slate-100 shadow-inner select-all">
                                {{ $tenant->api_key ?: 'No API Key Generated' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>