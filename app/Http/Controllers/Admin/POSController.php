<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Display the POS interface
     */
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        
        // Get categories with items (optimized query)
        $categories = Category::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'sort_order']);

        // Get all active items (optimized - only needed fields)
        $items = Item::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'category_id', 'name', 'price', 'image', 'sku', 'is_weighted', 'unit_label']);

        return view('admin.pos.index', compact('tenant', 'categories', 'items'));
    }

    /**
     * Get pending QR orders for POS display
     */
    public function pendingOrders(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        
        $orders = Order::where('tenant_id', $tenant->id)
            ->whereIn('status', ['new', 'confirmed'])
            ->with(['items:id,order_id,item_name,qty,price,line_total'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get([
                'id', 'order_no', 'customer_name', 'customer_mobile',
                'delivery_type', 'payment_method', 'subtotal', 'total',
                'status', 'source', 'created_at'
            ]);

        return response()->json($orders);
    }

    /**
     * Get items for POS (API endpoint for dynamic loading)
     */
    public function items(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        
        $query = Item::where('tenant_id', $tenant->id)
            ->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                    ->orWhere('name->ar', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('sort_order')->get([
            'id', 'category_id', 'name', 'price', 'image', 'sku', 'is_weighted', 'unit_label'
        ]);

        return response()->json($items);
    }

    /**
     * Create a walk-in order from POS
     */
    public function createOrder(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,knet,card',
            'notes' => 'nullable|string',
        ]);

        $items = [];
        $subtotal = 0;

        foreach ($request->items as $cartItem) {
            $item = Item::find($cartItem['id']);
            $lineTotal = $cartItem['price'] * $cartItem['qty'];
            $subtotal += $lineTotal;

            $items[] = [
                'item_id' => $item->id,
                'item_name' => $item->name['en'] ?? $item->name['ar'] ?? 'Item',
                'qty' => $cartItem['qty'],
                'price' => $cartItem['price'],
                'line_total' => $lineTotal,
                'notes' => $cartItem['notes'] ?? null,
            ];
        }

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => $request->customer_name ?: 'Walk-in Customer',
            'customer_mobile' => $request->customer_mobile ?: '',
            'delivery_type' => 'pickup',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'paid',
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'source' => 'pos',
            'notes' => $request->notes,
        ], $items, $tenant->id);

        // Auto-confirm POS orders
        $orderService->updateStatus($order, 'confirmed', auth()->id());

        return response()->json([
            'success' => true,
            'order' => $order->fresh(['items']),
            'message' => "Order #{$order->order_no} created successfully"
        ]);
    }

    /**
     * Accept/Confirm a QR order
     */
    public function acceptOrder(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        
        $order = Order::where('tenant_id', $tenant->id)
            ->where('id', $id)
            ->firstOrFail();

        $orderService = app(OrderService::class);
        $orderService->updateStatus($order, 'confirmed', auth()->id());

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_no} confirmed"
        ]);
    }

    /**
     * Update order status from POS
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,completed,cancelled'
        ]);

        $order = Order::where('tenant_id', $tenant->id)
            ->where('id', $id)
            ->firstOrFail();

        $orderService = app(OrderService::class);
        $orderService->updateStatus($order, $request->status, auth()->id());

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_no} updated to {$request->status}"
        ]);
    }

    /**
     * Print receipt for order
     */
    public function printReceipt(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        
        $order = Order::where('tenant_id', $tenant->id)
            ->with('items')
            ->findOrFail($id);

        return view('admin.pos.receipt', compact('tenant', 'order'));
    }

    /**
     * Get order count for badge updates
     */
    public function orderCount(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        
        $counts = [
            'new' => Order::where('tenant_id', $tenant->id)->where('status', 'new')->count(),
            'confirmed' => Order::where('tenant_id', $tenant->id)->where('status', 'confirmed')->count(),
            'preparing' => Order::where('tenant_id', $tenant->id)->where('status', 'preparing')->count(),
            'ready' => Order::where('tenant_id', $tenant->id)->where('status', 'ready')->count(),
        ];

        return response()->json($counts);
    }
}
