<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="flex gap-4 mb-6 overflow-x-auto no-scrollbar pb-2">
                @php $currentStatus = request('status', 'all'); @endphp
                <a href="{{ route('admin.orders.index') }}"
                    class="px-6 py-3 rounded-xl font-bold transition {{ $currentStatus == 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">All
                    Orders</a>
                <a href="{{ route('admin.orders.index', ['status' => 'new']) }}"
                    class="px-6 py-3 rounded-xl font-bold transition {{ $currentStatus == 'new' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">New</a>
                <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}"
                    class="px-6 py-3 rounded-xl font-bold transition {{ $currentStatus == 'confirmed' ? 'bg-blue-500 text-white shadow-lg shadow-blue-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">Confirmed</a>
                <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}"
                    class="px-6 py-3 rounded-xl font-bold transition {{ $currentStatus == 'preparing' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-200' : 'bg-white text-gray-500 hover:bg-gray-50' }}">Preparing</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Order #
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Customer
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Type
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Items
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Total
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-6 font-mono text-indigo-600 font-bold">#{{ $order->order_no }}</td>
                                    <td class="px-6 py-6">
                                        <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->customer_mobile }}</div>
                                    </td>
                                    <td class="px-6 py-6 font-bold text-gray-500 uppercase text-[10px] tracking-widest">
                                        <span class="px-3 py-1 bg-gray-100 rounded-full">{{ $order->delivery_type }}</span>
                                    </td>
                                    <td class="px-6 py-6 font-bold text-gray-600">{{ $order->items_count }} items</td>
                                    <td class="px-6 py-6 font-black text-gray-900">{{ number_format($order->total, 3) }} KD
                                    </td>
                                    <td class="px-6 py-6">
                                        @php
                                            $statusColors = [
                                                'new' => 'bg-orange-100 text-orange-700',
                                                'confirmed' => 'bg-blue-100 text-blue-700',
                                                'preparing' => 'bg-yellow-100 text-yellow-700',
                                                'ready' => 'bg-green-100 text-green-700',
                                                'dispatched' => 'bg-indigo-100 text-indigo-700',
                                                'completed' => 'bg-emerald-100 text-emerald-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 {{ $statusColors[$order->status] }} rounded-full text-[10px] font-black uppercase tracking-widest">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>