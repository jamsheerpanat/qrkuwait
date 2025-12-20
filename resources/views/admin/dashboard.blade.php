<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight italic tracking-tighter">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ $currentTenant->name }} • Analytics Hub</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.public', $currentTenant->slug) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Store
                </a>
                @if($stats['pending_orders'] > 0)
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center gap-2 bg-amber-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-200 animate-pulse">
                        <span class="w-2 h-2 bg-white rounded-full"></span>
                        {{ $stats['pending_orders'] }} Pending Orders
                    </a>
                @endif
            </div>
            </div>
            </x-slot>
            
            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
                    <!-- Top Stats Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Today's Revenue -->
                        <div
                            class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-[2rem] text-white relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-28 h-28 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform group-hover:scale-110">
                            </div>
                            <div class="relative z-10">
                                <div class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Today's Revenue
                                </div>
                                <div class="text-3xl font-black italic tracking-tighter">
                                    {{ number_format($stats['revenue_today'], 3) }}</div>
                                <div class="text-sm font-bold text-white/80">KWD</div>
                                <div class="mt-3 flex items-center gap-2 text-xs font-bold">
                                    @if($stats['revenue_trend'] >= 0)
                                        <span class="flex items-center gap-1 bg-white/20 px-2 py-1 rounded-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7">
                                                </path>
                                            </svg>
                                            +{{ $stats['revenue_trend'] }}%
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 bg-red-500/30 px-2 py-1 rounded-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            {{ $stats['revenue_trend'] }}%
                                        </span>
                                    @endif
                                    <span class="text-white/60">vs yesterday</span>
                                </div>
                            </div>
                        </div>
            
                        <!-- Today's Orders -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-20 h-20 bg-indigo-50 rounded-bl-[4rem] flex items-center justify-center -mr-6 -mt-6 transition-transform group-hover:scale-110">
                                <svg class="w-6 h-6 text-indigo-600 opacity-40" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Orders Today
                                </div>
                                <div class="text-3xl font-black text-slate-900 italic tracking-tighter">{{ $stats['orders_today'] }}
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs font-bold">
                                    @if($stats['orders_trend'] >= 0)
                                        <span class="text-green-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7">
                                                </path>
                                            </svg>
                                            +{{ $stats['orders_trend'] }}%
                                        </span>
                                    @else
                                        <span class="text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            {{ $stats['orders_trend'] }}%
                                        </span>
                                    @endif
                                    <span class="text-slate-400">from yesterday</span>
                                </div>
                            </div>
                        </div>
            
                        <!-- Average Order Value -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-20 h-20 bg-purple-50 rounded-bl-[4rem] flex items-center justify-center -mr-6 -mt-6 transition-transform group-hover:scale-110">
                                <svg class="w-6 h-6 text-purple-600 opacity-40" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Avg Order Value
                                </div>
                                <div class="text-3xl font-black text-slate-900 italic tracking-tighter">
                                    {{ number_format($stats['avg_order_value'], 3) }}</div>
                                <div class="text-sm font-bold text-slate-400">KWD / order</div>
                            </div>
                        </div>
            
                        <!-- Store Status -->
                        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-2xl relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                            </div>
                            <div class="relative z-10">
                                <div class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-1">Store Status</div>
                                <div class="text-2xl font-black text-white italic tracking-tighter">ONLINE</div>
                                <div
                                    class="mt-3 inline-flex items-center gap-2 bg-green-500 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                    Live Now
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Revenue Chart + Quick Stats -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Revenue Chart -->
                        <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Revenue Trend</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Last 7 Days</p>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-bold">
                                    <span class="flex items-center gap-2">
                                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                                        Revenue
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                                        Orders
                                    </span>
                                </div>
                            </div>
                            <div class="h-64">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
            
                        <!-- Weekly/Monthly Summary -->
                        <div class="space-y-4">
                            <!-- This Week -->
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-[1.5rem] text-white">
                                <div class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] mb-1">This Week</div>
                                <div class="text-2xl font-black italic tracking-tighter">
                                    {{ number_format($stats['revenue_this_week'], 3) }} <span
                                        class="text-sm font-bold text-white/70">KWD</span></div>
                                <div class="text-sm font-bold text-white/80 mt-1">{{ $stats['orders_this_week'] }} orders</div>
                            </div>
            
                            <!-- This Month -->
                            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                                <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">This Month</div>
                                <div class="text-2xl font-black text-slate-900 italic tracking-tighter">
                                    {{ number_format($stats['revenue_this_month'], 3) }} <span
                                        class="text-sm font-bold text-slate-400">KWD</span></div>
                                <div class="text-sm font-bold text-slate-500 mt-1">{{ $stats['orders_this_month'] }} orders</div>
                            </div>
            
                            <!-- Customer Stats -->
                            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                                <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Customers</div>
                                <div class="text-2xl font-black text-slate-900 italic tracking-tighter">
                                    {{ $customerMetrics['total_customers'] }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs font-bold text-green-500">{{ $customerMetrics['repeat_rate'] }}% repeat
                                        rate</span>
                                    <span class="text-xs text-slate-400">{{ $customerMetrics['repeat_customers'] }} returning</span>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Top Items + Peak Hours -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Top Selling Items -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Top Sellers</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Best performing items
                                    </p>
                                </div>
                                <a href="{{ route('admin.items.index') }}"
                                    class="text-indigo-600 text-xs font-black uppercase tracking-widest hover:text-indigo-700">View
                                    Menu →</a>
                            </div>
                            <div class="space-y-4">
                                @forelse($topItems as $index => $item)
                                    <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg shadow-amber-200">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-slate-900 truncate">{{ $item->item_name }}</div>
                                            <div class="text-xs text-slate-400">{{ $item->total_sold }} sold</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-black text-slate-900">{{ number_format($item->total_revenue, 3) }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">KWD</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        No sales data yet
                                    </div>
                                @endforelse
                            </div>
                        </div>
            
                        <!-- Peak Hours -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="mb-6">
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Peak Hours</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Order distribution (Last
                                    7 days)</p>
                            </div>
                            <div class="h-48">
                                <canvas id="peakHoursChart"></canvas>
                            </div>
                            @php
                                $maxHour = array_keys($fullPeakHours, max($fullPeakHours))[0] ?? 12;
                            @endphp
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-amber-600 font-bold uppercase">Busiest Hour</div>
                                        <div class="font-black text-slate-900">{{ $maxHour }}:00 - {{ $maxHour + 1 }}:00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Order Status + Payment Methods + Delivery Types -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Order Status Distribution -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="text-lg font-black text-slate-900 italic tracking-tighter mb-4">Order Status</h3>
                            <div class="h-40 flex items-center justify-center mb-4">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                            <div class="space-y-2">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-500',
                                        'accepted' => 'bg-blue-500',
                                        'preparing' => 'bg-purple-500',
                                        'ready' => 'bg-cyan-500',
                                        'delivered' => 'bg-green-500',
                                        'cancelled' => 'bg-red-500',
                                    ];
                                @endphp
                                @foreach($orderStatusDistribution as $status => $count)
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="flex items-center gap-2 font-bold text-slate-600">
                                            <span class="w-2 h-2 rounded-full {{ $statusColors[$status] ?? 'bg-slate-400' }}"></span>
                                            {{ ucfirst($status) }}
                                        </span>
                                        <span class="font-black text-slate-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            
                        <!-- Payment Methods -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="text-lg font-black text-slate-900 italic tracking-tighter mb-4">Payment Methods</h3>
                            <div class="space-y-3">
                                @php
                                    $paymentIcons = ['cash' => '💵', 'knet' => '💳', 'card' => '💳', 'online' => '🌐'];
                                    $paymentColors = ['cash' => 'from-green-400 to-emerald-500', 'knet' => 'from-blue-400 to-indigo-500', 'card' => 'from-purple-400 to-pink-500', 'online' => 'from-amber-400 to-orange-500'];
                                @endphp
                                @foreach($paymentMethodDistribution as $method => $data)
                                    <div
                                        class="p-4 bg-gradient-to-r {{ $paymentColors[$method] ?? 'from-slate-400 to-slate-500' }} rounded-xl text-white">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-2xl">{{ $paymentIcons[$method] ?? '💰' }}</span>
                                                <div>
                                                    <div class="font-black uppercase text-sm">{{ $method }}</div>
                                                    <div class="text-white/80 text-xs">{{ $data['count'] ?? 0 }} orders</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-black">{{ number_format($data['revenue'] ?? 0, 3) }}</div>
                                                <div class="text-white/80 text-[10px]">KWD</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            
                        <!-- Delivery Types -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="text-lg font-black text-slate-900 italic tracking-tighter mb-4">Order Types</h3>
                            @php
                                $totalDeliveryOrders = array_sum($deliveryTypeDistribution);
                                $deliveryIcons = ['delivery' => '🚗', 'pickup' => '🏃', 'dine_in' => '🍽️'];
                            @endphp
                            <div class="space-y-4">
                                @foreach($deliveryTypeDistribution as $type => $count)
                                    @php
                                        $percentage = $totalDeliveryOrders > 0 ? round(($count / $totalDeliveryOrders) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="flex items-center gap-2 text-sm font-bold text-slate-600">
                                                <span class="text-xl">{{ $deliveryIcons[$type] ?? '📦' }}</span>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </span>
                                            <span class="font-black text-slate-900">{{ $count }}</span>
                                        </div>
                                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            
                    <!-- Recent Orders Table -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Recent Orders</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Latest customer
                                    transactions</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}"
                                class="text-indigo-600 font-black text-xs uppercase tracking-widest hover:text-indigo-700 transition">
                                View All Orders →
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Order
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Customer</th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Type
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount
                                        </th>
                                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Time
                                        </th>
                                        <th class="px-8 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($recentOrders as $order)
                                        <tr class="hover:bg-slate-50 transition cursor-default group">
                                            <td class="px-8 py-5 font-black text-slate-900 italic">#{{ $order->order_no }}</td>
                                            <td class="px-8 py-5">
                                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold">{{ $order->customer_mobile }}</div>
                                            </td>
                                            <td class="px-8 py-5">
                                                <span
                                                    class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase">
                                                    {{ $order->delivery_type }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5">
                                                @php
                                                    $statusClasses = match ($order->status) {
                                                        'pending' => 'bg-amber-50 text-amber-600',
                                                        'accepted' => 'bg-blue-50 text-blue-600',
                                                        'preparing' => 'bg-purple-50 text-purple-600',
                                                        'ready' => 'bg-cyan-50 text-cyan-600',
                                                        'delivered' => 'bg-green-50 text-green-600',
                                                        'cancelled' => 'bg-red-50 text-red-600',
                                                        default => 'bg-slate-100 text-slate-500'
                                                    };
                                                @endphp
                                                <span
                                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusClasses }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 font-black text-slate-900 italic tracking-tighter">
                                                {{ number_format($order->total, 3) }} <span
                                                    class="text-[9px] not-italic text-slate-400 uppercase">KWD</span>
                                            </td>
                                            <td class="px-8 py-5 text-xs text-slate-400 font-bold">
                                                {{ $order->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-8 py-5 text-right">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="p-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-indigo-600 hover:text-white transition inline-block">
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
                                            <td colspan="7" class="px-8 py-16 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-4">
                                                    <div
                                                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="font-bold text-slate-400">No orders recorded yet</div>
                                                    <p class="text-sm text-slate-400">Orders will appear here when customers start
                                                        placing them</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
            
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Inventory Shortcuts -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="text-xl font-black text-slate-900 italic tracking-tighter mb-6">Quick Actions</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl hover:from-indigo-50 hover:to-indigo-100 transition border border-slate-100 group">
                                    <div
                                        class="w-12 h-12 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h10M4 18h16"></path>
                                        </svg>
                                    </div>
                                    <div class="font-black text-slate-900 text-sm">Categories</div>
                                    <div class="text-xs text-slate-400">{{ $stats['total_categories'] }} total</div>
                                </a>
                                <a href="{{ route('admin.items.index') }}"
                                    class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl hover:from-purple-50 hover:to-purple-100 transition border border-slate-100 group">
                                    <div
                                        class="w-12 h-12 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-purple-600 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div class="font-black text-slate-900 text-sm">Menu Items</div>
                                    <div class="text-xs text-slate-400">{{ $stats['total_items'] }} items</div>
                                </a>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl hover:from-amber-50 hover:to-amber-100 transition border border-slate-100 group">
                                    <div
                                        class="w-12 h-12 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-amber-600 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="font-black text-slate-900 text-sm">All Orders</div>
                                    <div class="text-xs text-slate-400">Manage orders</div>
                                </a>
                                <a href="{{ route('admin.qr.index') }}"
                                    class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl hover:from-green-50 hover:to-green-100 transition border border-slate-100 group">
                                    <div
                                        class="w-12 h-12 bg-white rounded-xl shadow-sm mb-4 flex items-center justify-center text-green-600 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="font-black text-slate-900 text-sm">QR Code</div>
                                    <div class="text-xs text-slate-400">Download & print</div>
                                </a>
                            </div>
                        </div>
            
                        <!-- Store Profile -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 italic tracking-tighter">Store Profile</h3>
                                <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
                                    Control your store settings, operating hours, and localized content from the central management
                                    panel.
                                </p>
                            </div>
                            <div class="mt-6 flex items-center gap-4">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl flex items-center justify-center text-white text-xl font-black">
                                    {{ substr($currentTenant->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-slate-900 uppercase tracking-widest text-sm">
                                        {{ $currentTenant->name }}</div>
                                    <div class="text-xs text-slate-400 font-bold">Primary Merchant Account</div>
                                </div>
                                <a href="{{ route('admin.settings.index') }}"
                                    class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition">
                                    Settings
                                </a>
                            </div>
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
                    labels: @json(array_column($revenueChart, 'full_date')),
                    datasets: [{
                        label: 'Revenue (KWD)',
                        data: @json(array_column($revenueChart, 'revenue')),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }, {
                        label: 'Orders',
                        data: @json(array_column($revenueChart, 'orders')),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        yAxisID: 'y1',
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { callback: function(value) { return value + ' KWD'; } }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Peak Hours Chart
            const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
            const peakData = @json(array_values($fullPeakHours));
            const peakLabels = Array.from({length: 24}, (_, i) => i + ':00');
            
            new Chart(peakCtx, {
                type: 'bar',
                data: {
                    labels: peakLabels,
                    datasets: [{
                        label: 'Orders',
                        data: peakData,
                        backgroundColor: peakData.map((v, i) => {
                            const max = Math.max(...peakData);
                            const intensity = max > 0 ? v / max : 0;
                            return `rgba(245, 158, 11, ${0.3 + intensity * 0.7})`;
                        }),
                        borderRadius: 4,
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
                            grid: { display: false },
                            ticks: {
                                callback: function(value, index) {
                                    return index % 3 === 0 ? peakLabels[index] : '';
                                }
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const statusData = @json($orderStatusDistribution);
            const statusColors = {
                'pending': 'rgb(245, 158, 11)',
                'accepted': 'rgb(59, 130, 246)',
                'preparing': 'rgb(168, 85, 247)',
                'ready': 'rgb(6, 182, 212)',
                'delivered': 'rgb(16, 185, 129)',
                'cancelled': 'rgb(239, 68, 68)'
            };
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: Object.keys(statusData).map(s => statusColors[s] || 'rgb(148, 163, 184)'),
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '65%'
                }
            });
        });
    </script>
</x-app-layout>