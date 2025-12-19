<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request, $tenant_slug)
    {
        $tenant = $request->attributes->get('tenant');
        return view('tenant.checkout', compact('tenant'));
    }

    public function store(Request $request, $tenant_slug)
    {
        $tenant = $request->attributes->get('tenant');

        // Honeypot check
        if ($request->filled('verify_token')) {
            return response()->json(['message' => 'Spam detected'], 422);
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => ['required', 'string', 'regex:/^(\+?965)?[0-9]{8}$/'],
            'delivery_type' => 'required|in:pickup,delivery',
            'area' => 'required_if:delivery_type,delivery',
            'block' => 'required_if:delivery_type,delivery',
            'house' => 'required_if:delivery_type,delivery',
            'payment_method' => 'required|in:cash,knet',
            'cart_data' => 'required|json',
        ]);

        $cart = json_decode($request->cart_data, true);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $items = [];
        $subtotal = 0;
        foreach ($cart as $item) {
            $line_total = $item['price'] * ($item['qty'] ?? 1);
            $subtotal += $line_total;
            $items[] = [
                'item_id' => $item['id'] ?? null,
                'item_name' => $item['name'] ?? 'Unknown Item',
                'qty' => $item['qty'] ?? 1,
                'price' => $item['price'],
                'line_total' => $line_total,
                'selected_variants' => $item['variants'] ?? null,
                'selected_modifiers' => $item['modifiers'] ?? null,
            ];
        }

        $orderService = app(\App\Services\OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'delivery_type' => $request->delivery_type,
            'address' => $request->delivery_type === 'delivery' ? [
                'area' => $request->area,
                'block' => $request->block,
                'street' => $request->street,
                'house' => $request->house,
                'building' => $request->building,
                'landmark' => $request->landmark,
                'paci' => $request->paci,
                'extra' => $request->extra,
            ] : null,
            'subtotal' => $subtotal,
            'total' => $subtotal, // Delivery fee logic later
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ], $items, $tenant->id);

        return redirect()->route('tenant.checkout.success', [$tenant->slug, $order->order_no]);
    }

    public function success(Request $request, $tenant_slug, $order_no)
    {
        $tenant = $request->attributes->get('tenant');
        $order = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('order_no', $order_no)
            ->with('items')
            ->firstOrFail();

        $formatter = app(\App\Services\WhatsAppFormatter::class);
        $waUrl = $formatter->getWhatsAppUrl($order, $tenant->default_language);

        return view('tenant.success', compact('tenant', 'order', 'waUrl'));
    }
}

    public function uploadPayment(Request $request, $tenant_slug, $order_no)
    {
        $tenant = $request->attributes->get('tenant');
        
        $request->validate([
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $order = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('order_no', $order_no)
            ->firstOrFail();

        if ($request->hasFile('payment_screenshot')) {
            // Delete old screenshot if exists
            if ($order->payment_screenshot && \Storage::disk('public')->exists($order->payment_screenshot)) {
                \Storage::disk('public')->delete($order->payment_screenshot);
            }

            // Store new screenshot
            $path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
            $order->payment_screenshot = $path;
            $order->payment_status = 'submitted';
            $order->save();

            return back()->with('success', 'Payment screenshot uploaded successfully!');
        }

        return back()->withErrors(['payment_screenshot' => 'Failed to upload screenshot.']);
    }
}
