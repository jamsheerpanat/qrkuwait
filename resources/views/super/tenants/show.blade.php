<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('Merchant Hub:') }} {{ $tenant->name }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('super.tenants.edit', $tenant->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition">
                    Edit Store
                </a>
                <a href="{{ url($tenant->slug) }}" target="_blank" class="bg-white border border-slate-200 text-slate-700 px-6 py-2 rounded-xl font-bold hover:bg-slate-50 transition">
                    Visit Storefront
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Merchant Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-slate-900 p-6 rounded-[2rem] text-white">
                    <div class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Total Orders</div>
                    <div class="text-4xl font-extrabold">{{ $ordersCount }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Active Staff</div>
                    <div class="text-4xl font-extrabold text-slate-900">{{ count($tenant->users) }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Branches</div>
                    <div class="text-4xl font-extrabold text-slate-900">{{ count($tenant->branches) }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Store Status</div>
                    <div class="text-4xl font-extrabold {{ $tenant->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                        {{ strtoupper($tenant->status) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- User Management for this Tenant -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-indigo-50/30">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Store Users</h3>
                            <p class="text-slate-500 text-sm">Manager and staff accounts</p>
                        </div>
                        <a href="{{ route('super.users.create', ['tenant_id' => $tenant->id]) }}" class="text-indigo-600 font-bold hover:underline flex items-center gap-1">
                            <span>+ Add Staff</span>
                        </a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($tenant->users as $user)
                        <div class="p-6 flex justify-between items-center group">
                            <div>
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[9px] font-bold uppercase">{{ str_replace('_', ' ', $user->role) }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('super.users.edit', $user->id) }}" class="text-indigo-600 text-sm font-bold">Edit</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity for this Tenant -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Recent Orders</h3>
                            <p class="text-slate-500 text-sm">Last 5 orders from this store</p>
                        </div>
                    </div>
                    @if($recentOrders->isEmpty())
                        <div class="p-12 text-center text-slate-400 italic">No orders recorded yet.</div>
                    @else
                        <div class="divide-y divide-slate-50">
                            @foreach($recentOrders as $order)
                            <div class="p-6 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-slate-800">#{{ $order->order_no }}</div>
                                    <div class="text-xs text-slate-500">{{ $order->customer_name }} • {{ number_format($order->total, 3) }} KWD</div>
                                </div>
                                <div>
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 text-center">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Store Configuration Hub</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 border border-slate-100 rounded-3xl hover:bg-slate-50 transition cursor-help">
                        <div class="font-bold text-slate-800">API Access</div>
                        <div class="text-xs text-slate-400 mt-1">Tenant Key: {{ $tenant->api_key ? substr($tenant->api_key, 0, 8) . '...' : 'Not Generated' }}</div>
                    </div>
                    <div class="p-6 border border-slate-100 rounded-3xl hover:bg-slate-50 transition cursor-help">
                        <div class="font-bold text-slate-800">Custom Domain</div>
                        <div class="text-xs text-slate-400 mt-1">Status: Internal Slug</div>
                    </div>
                    <div class="p-6 border border-slate-100 rounded-3xl hover:bg-slate-50 transition cursor-help">
                        <div class="font-bold text-slate-800">Billing Plan</div>
                        <div class="text-xs text-slate-400 mt-1">Tier: Enterprise / Unlimited</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
