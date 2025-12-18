<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KDSController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        if (!($tenant->settings['enable_kds'] ?? true)) {
            return redirect()->route('admin.dashboard')->with('error', 'KDS is disabled.');
        }
        return view('admin.kds.index', compact('tenant'));
    }

    public function packing(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        if (!($tenant->settings['enable_packing'] ?? false)) {
            return redirect()->route('admin.dashboard')->with('error', 'Packing Screen is disabled.');
        }
        return view('admin.kds.packing', compact('tenant'));
    }

    public function feed(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $type = $request->get('type', 'kitchen'); // kitchen or packing

        $statuses = $type === 'packing'
            ? ['confirmed', 'preparing', 'picked', 'packed']
            : ['confirmed', 'preparing', 'ready'];

        $orders = \App\Models\Order::where('tenant_id', $tenant->id)
            ->whereIn('status', $statuses)
            ->with('items')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_no' => $order->order_no,
                    'status' => $order->status,
                    'type' => $order->delivery_type,
                    'elapsed' => $order->created_at->diffInMinutes(now()),
                    'notes' => $order->notes,
                    'items' => $order->items->map(fn($i) => [
                        'name' => $i->item_name,
                        'qty' => (float) $i->qty,
                        'notes' => $i->notes,
                        'variants' => $i->selected_variants,
                        'modifiers' => $i->selected_modifiers
                    ])
                ];
            });

        return response()->json($orders);
    }
}
