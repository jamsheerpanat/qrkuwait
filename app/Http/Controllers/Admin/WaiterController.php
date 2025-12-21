<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'Tenant context not found.');
        }

        $categories = Category::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $items = Item::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get active orders to show busy tables
        $activeOrders = Order::where('tenant_id', $tenant->id)
            ->whereIn('status', ['new', 'confirmed', 'preparing', 'ready'])
            ->where(function ($q) {
                $q->where('delivery_type', 'dine_in')
                    ->orWhereNotNull('table_number');
            })
            ->get(['id', 'table_number', 'status', 'total', 'order_no']);

        return view('admin.waiter.index', compact('tenant', 'categories', 'items', 'activeOrders'));
    }

    public function createOrder(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        $request->validate([
            'table_number' => 'required|string|max:10',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $items = [];
        $subtotal = 0;

        foreach ($request->items as $item) {
            $line_total = $item['price'] * $item['qty'];
            $subtotal += $line_total;
            
            $itemModel = Item::find($item['id']);
            $items[] = [
                'item_id' => $item['id'],
                'item_name' => $itemModel ? ($itemModel->name['en'] ?? 'Item') : 'Item #' . $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'line_total' => $line_total,
                'selected_variants' => $item['variant'] ?? null,
                'selected_modifiers' => $item['modifiers'] ?? null,
            ];
        }

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Table ' . $request->table_number,
            'customer_mobile' => null,
            'delivery_type' => 'dine_in',
            'table_number' => $request->table_number,
            'address' => null,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'payment_method' => 'pay_later',
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'source' => 'waiter',
            'notes' => $request->notes,
        ], $items, $tenant->id);

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $order->order_no . ' sent to kitchen!',
            'order' => [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'table_number' => $order->table_number,
                'total' => $order->total,
            ]
        ]);
    }

    public function tableOrders(Request $request, $table)
    {
        $tenant = $request->attributes->get('tenant');
        
        $orders = Order::where('tenant_id', $tenant->id)
            ->where('table_number', $table)
            ->whereIn('status', ['new', 'confirmed', 'preparing', 'ready'])
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function checkout(Request $request, $orderNo)
    {
        $tenant = $request->attributes->get('tenant');
        $order = Order::where('tenant_id', $tenant->id)
            ->where('order_no', $orderNo)
            ->firstOrFail();

        $orderService = app(OrderService::class);
        $orderService->updateStatus($order, 'completed', auth()->id());

        // Also mark as paid for dine-in checkout
        $order->update(['payment_status' => 'paid']);

        return response()->json(['success' => true, 'message' => 'Table checked out successfully!', 'order_id' => $order->id]);
    }
}
