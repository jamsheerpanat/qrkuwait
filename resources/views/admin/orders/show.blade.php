<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order #') }}{{ $order->order_no }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank"
                    class="px-6 py-2 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Print
                    Ticket</a>
                <a href="{{ route('admin.orders.index') }}"
                    class="px-6 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">Back
                    to List</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Items -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b bg-gray-50/50">
                            <h3 class="text-xl font-black italic">Order Items</h3>
                        </div>
                        <div class="p-8">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        <th class="pb-4">Item</th>
                                        <th class="pb-4 text-center">Qty</th>
                                        <th class="pb-4 text-right">Price</th>
                                        <th class="pb-4 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="py-6">
                                                <div class="font-bold text-gray-900">{{ $item->item_name }}</div>
                                                @if($item->selected_variants)
                                                    <div class="text-xs text-indigo-600 font-bold mt-1">Variant:
                                                        {{ json_encode($item->selected_variants) }}</div>
                                                @endif
                                                @if($item->selected_modifiers)
                                                    <div class="text-xs text-slate-400 mt-1">Modifiers:
                                                        {{ json_encode($item->selected_modifiers) }}</div>
                                                @endif
                                            </td>
                                            <td class="py-6 text-center font-bold">{{ (float) $item->qty }}</td>
                                            <td class="py-6 text-right font-mono">{{ number_format($item->price, 3) }}</td>
                                            <td class="py-6 text-right font-black">{{ number_format($item->line_total, 3) }}
                                                KD</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-8 pt-8 border-t space-y-4">
                                <div class="flex justify-between font-bold text-gray-500">
                                    <span>Subtotal</span>
                                    <span>{{ number_format($order->subtotal, 3) }} KD</span>
                                </div>
                                @if($order->delivery_fee > 0)
                                    <div class="flex justify-between font-bold text-gray-500">
                                        <span>Delivery Fee</span>
                                        <span>{{ number_format($order->delivery_fee, 3) }} KD</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-2xl font-black italic text-gray-900">
                                    <span>Grand Total</span>
                                    <span>{{ number_format($order->total, 3) }} KD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b bg-gray-50/50">
                            <h3 class="text-xl font-black italic">Order Timeline</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            @forelse($order->statusLogs as $log)
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 flex-1 bg-indigo-100 mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-black uppercase tracking-widest text-indigo-600">
                                            {{ $log->to_status }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('M d, Y H:i') }}
                                            • {{ $log->user ? $log->user->name : 'System' }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <p class="font-bold">No status updates yet</p>
                                    <p class="text-xs mt-2">Order is in {{ $order->status }} status</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Status Update -->
                <div class="space-y-8">
                    <!-- Status Update -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-6">
                        <h3 class="text-lg font-bold">Update Status</h3>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <select name="status"
                                class="w-full rounded-xl border-gray-100 bg-gray-50 font-bold focus:ring-indigo-600">
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirm
                                    Order</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Move to
                                    Kitchen</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Mark as Ready
                                </option>
                                <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>Dispatch
                                    Order</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Complete
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancel
                                    Order</option>
                            </select>
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black italic shadow-lg shadow-indigo-100 hover:scale-[1.02] transition">Update
                                Status</button>
                        </form>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-6">
                        <h3 class="text-lg font-bold border-b pb-4">Customer Info</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Name</div>
                                <div class="font-bold text-gray-900 text-lg">{{ $order->customer_name }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mobile</div>
                                <div class="font-bold text-indigo-600 text-lg">
                                    <a href="https://wa.me/965{{ preg_replace('/[^0-9]/', '', $order->customer_mobile) }}"
                                        target="_blank">
                                        {{ $order->customer_mobile }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Method</div>
                                <div
                                    class="font-bold text-gray-700 bg-gray-100 inline-block px-3 py-1 rounded-full text-xs uppercase">
                                    {{ $order->delivery_type }}</div>
                            </div>
                            @if($order->address && is_array($order->address))
                                <div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Address</div>
                                    <div class="text-sm font-bold text-gray-700 leading-relaxed italic">
                                        {{ $order->address['area'] ?? '' }}{{ !empty($order->address['block']) ? ', Block ' . $order->address['block'] : '' }}{{ !empty($order->address['street']) ? ', Street ' . $order->address['street'] : '' }}{{ !empty($order->address['house']) ? ', House ' . $order->address['house'] : '' }}
                                        @if(!empty($order->address['extra']))
                                            <br><span class="text-gray-400 font-normal">Note:
                                                {{ $order->address['extra'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>