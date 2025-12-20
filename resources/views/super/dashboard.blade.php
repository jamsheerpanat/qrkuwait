<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight italic tracking-tighter">
                    {{ __('Command Center') }}
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">QR<span class="text-indigo-600">Kuwait</span> Platform Analytics</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    All Systems Online
                </span>
                <a href="{{ route('super.tenants.create') }}"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Tenant
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Primary Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-8 rounded-[2rem] text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Revenue</div>
                        <div class="text-4xl font-black italic tracking-tighter">{{ number_format($stats['total_revenue'], 3) }}</div>
                        <div class="text-sm font-bold text-white/80 mt-1">KWD</div>
                        <div class="mt-4 flex items-center gap-2 text-xs font-bold">
                            @if($stats['revenue_trend'] >= 0)
                                <span class="flex items-center gap-1 text-green-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    +{{ $stats['revenue_trend'] }}% today
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    {{ $stats['revenue_trend'] }}% today
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Total Tenants -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-blue-600 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Tenants</div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ $stats['total_tenants'] }}</div>
                        <div class="mt-4 flex items-center gap-4 text-xs font-bold">
                            <span class="flex items-center gap-1 text-green-500">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                {{ $stats['active_tenants'] }} Active
                            </span>
                            <span class="flex items-center gap-1 text-slate-400">
                                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                {{ $stats['inactive_tenants'] }} Inactive
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-amber-600 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Orders</div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">
                            {{ number_format($stats['total_orders']) }}</div>
                        <div class="mt-4 flex items-center gap-2 text-xs font-bold">
                            <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded-lg">
                                {{ $stats['orders_today'] }} today
                            </span>
                            @if($stats['orders_trend'] >= 0)
                                <span class="text-green-500">+{{ $stats['orders_trend'] }}%</span>
                            @else
                                <span class="text-red-500">{{ $stats['orders_trend'] }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Total Users -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-[4rem] flex items-center justify-center -mr-8 -mt-8 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-purple-600 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Platform Users</div>
                        <div class="text-4xl font-black text-slate-900 italic tracking-tighter">
                            {{ number_format($stats['total_users']) }}</div>
                        <div class="mt-4 text-xs font-bold text-slate-400">
                            Across all tenants
                        </div>
                    </div>
                </div>
                </div>
                
                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Revenue Chart -->
                    <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Revenue Overview</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Last 7 Days Performance</p>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-bold">
                                <span class="flex items-center gap-2">
                                    <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                                    Revenue
                                </span>
                                <span class="flex items-center gap-2">
                                    <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                                    Orders
                                </span>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                
                    <!-- Tenant Distribution -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Tenant Types</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Distribution</p>
                        </div>
                        <div class="h-48 flex items-center justify-center">
                            <canvas id="tenantTypeChart"></canvas>
                        </div>
                        <div class="mt-4 flex justify-center gap-6 text-xs font-bold">
                            @foreach($tenantTypeDistribution as $type => $count)
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-3 h-3 rounded-full {{ $type == 'restaurant' ? 'bg-indigo-500' : 'bg-emerald-500' }}"></span>
                                    {{ ucfirst($type) }}: {{ $count }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Secondary Stats & Tenant Growth -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Tenant Growth Chart -->
                    <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Tenant Growth</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">New Signups - Last 6 Months</p>
                        </div>
                        <div class="h-64">
                            <canvas id="tenantGrowthChart"></canvas>
                        </div>
                    </div>
                
                    <!-- Quick Stats -->
                    <div class="space-y-4">
                        <!-- Today's Revenue -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-[1.5rem] text-white">
                            <div class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Today's Revenue</div>
                            <div class="text-3xl font-black italic tracking-tighter">{{ number_format($stats['revenue_today'], 3) }}
                            </div>
                            <div class="text-sm font-bold text-white/80">KWD | {{ $stats['orders_today'] }} orders</div>
                        </div>
                
                        <!-- This Week -->
                        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">This Week</div>
                            <div class="text-3xl font-black text-slate-900 italic tracking-tighter">
                                {{ number_format($stats['revenue_this_week'], 3) }}</div>
                            <div class="text-sm font-bold text-slate-500">KWD | {{ $stats['orders_this_week'] }} orders</div>
                        </div>
                
                        <!-- New Tenants This Month -->
                        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">New Tenants (This Month)
                            </div>
                            <div class="text-3xl font-black text-slate-900 italic tracking-tighter">
                                {{ $stats['new_tenants_this_month'] }}</div>
                            <div class="text-sm font-bold text-green-500">Growing steadily</div>
                        </div>
                    </div>
                </div>
                
                <!-- Top Performing Tenants & Order Status -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Performing Tenants -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Top Performers</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">By Revenue</p>
                            </div>
                            <a href="{{ route('super.tenants.index') }}"
                                class="text-indigo-600 text-xs font-black uppercase tracking-widest hover:text-indigo-700">View All
                                →</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($topTenants as $index => $tenant)
                                <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-black text-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-slate-900">{{ $tenant->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $tenant->order_count }} orders</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-black text-slate-900">{{ number_format($tenant->total_revenue, 3) }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">KWD</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    No tenant data available yet
                                </div>
                            @endforelse
                        </div>
                    </div>
                
                    <!-- Order Status Distribution -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Order Status</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Distribution</p>
                        </div>
                        <div class="h-48 flex items-center justify-center mb-4">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'accepted' => 'bg-blue-100 text-blue-700',
                                    'preparing' => 'bg-purple-100 text-purple-700',
                                    'ready' => 'bg-cyan-100 text-cyan-700',
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            @foreach($orderStatusDistribution as $status => $count)
                                <div class="p-3 rounded-xl {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-700' }}">
                                    <div class="text-[10px] font-black uppercase tracking-widest opacity-70">{{ ucfirst($status) }}</div>
                                    <div class="text-2xl font-black">{{ number_format($count) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Recent Tenants & Recent Orders -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Tenants -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Recent Tenants</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Latest signups</p>
                            </div>
                            <a href="{{ route('super.tenants.index') }}"
                                class="text-indigo-600 text-xs font-black uppercase tracking-widest hover:text-indigo-700">View All
                                →</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach($recentTenants as $tenant)
                                <div class="p-6 flex items-center gap-4 hover:bg-slate-50 transition">
                                    <div
                                        class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-black text-xl">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-slate-900">{{ $tenant->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $tenant->slug }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-3 py-1 {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-lg text-[10px] font-black uppercase tracking-widest">
                                            {{ $tenant->status }}
                                        </span>
                                        <a href="{{ route('super.tenants.show', $tenant->id) }}"
                                            class="p-2 bg-slate-100 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                    <!-- Recent Orders (Platform-wide) -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50">
                            <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Live Orders Feed</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Across all tenants</p>
                        </div>
                        <div class="divide-y divide-slate-50 max-h-96 overflow-y-auto">
                            @forelse($recentOrders as $order)
                                <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-slate-900 truncate">#{{ $order->order_no }}</div>
                                        <div class="text-xs text-indigo-600 font-bold truncate">{{ $order->tenant->name ?? 'Unknown' }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-black text-slate-900">{{ number_format($order->total, 3) }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $order->created_at->diffForHumans() }}</div>
                                    </div>
                                    @php
                                        $statusBg = match ($order->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'accepted', 'ready', 'delivered' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-1 {{ $statusBg }} rounded-lg text-[9px] font-black uppercase">{{ $order->status }}</span>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400">
                                    No orders yet
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods Distribution -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Payment Methods</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Platform-wide distribution</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $paymentIcons = [
                                'cash' => '💵',
                                'knet' => '💳',
                                'card' => '💳',
                                'online' => '🌐',
                            ];
                            $paymentColors = [
                                'cash' => 'from-green-500 to-emerald-600',
                                'knet' => 'from-blue-500 to-indigo-600',
                                'card' => 'from-purple-500 to-pink-600',
                                'online' => 'from-amber-500 to-orange-600',
                            ];
                        @endphp
                        @foreach($paymentMethodDistribution as $method => $count)
                            <div
                                class="bg-gradient-to-br {{ $paymentColors[$method] ?? 'from-slate-500 to-slate-600' }} p-6 rounded-2xl text-white">
                                <div class="text-3xl mb-2">{{ $paymentIcons[$method] ?? '💰' }}</div>
                                <div class="text-white/70 text-[10px] font-black uppercase tracking-widest">{{ ucfirst($method) }}</div>
                                <div class="text-2xl font-black mt-1">{{ number_format($count) }}</div>
                                <div class="text-xs text-white/80">orders</div>
                            </div>
                        @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($revenueChart, 'date')),
                    datasets: [{
                        label: 'Revenue (KWD)',
                        data: @json(array_column($revenueChart, 'revenue')),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: 'Orders',
                        data: @json(array_column($revenueChart, 'orders')),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        yAxisID: 'y1',
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: { display: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Tenant Type Chart
            const tenantTypeCtx = document.getElementById('tenantTypeChart').getContext('2d');
            new Chart(tenantTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($tenantTypeDistribution)),
                    datasets: [{
                        data: @json(array_values($tenantTypeDistribution)),
                        backgroundColor: ['rgb(99, 102, 241)', 'rgb(16, 185, 129)', 'rgb(245, 158, 11)', 'rgb(239, 68, 68)'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '70%'
                }
            });

            // Tenant Growth Chart
            const growthCtx = document.getElementById('tenantGrowthChart').getContext('2d');
            new Chart(growthCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($tenantGrowth, 'month')),
                    datasets: [{
                        label: 'New Tenants',
                        data: @json(array_column($tenantGrowth, 'count')),
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($orderStatusDistribution)),
                    datasets: [{
                        data: @json(array_values($orderStatusDistribution)),
                        backgroundColor: [
                            'rgb(245, 158, 11)', // pending - amber
                            'rgb(59, 130, 246)', // accepted - blue
                            'rgb(168, 85, 247)', // preparing - purple
                            'rgb(6, 182, 212)',  // ready - cyan
                            'rgb(16, 185, 129)', // delivered - green
                            'rgb(239, 68, 68)'   // cancelled - red
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-app-layout>