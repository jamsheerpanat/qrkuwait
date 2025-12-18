<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderManagerController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $orders = \App\Models\Order::where('tenant_id', $tenant->id)
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $order = \App\Models\Order::where('tenant_id', $tenant->id)
            ->with(['items', 'statusLogs.user'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $order = \App\Models\Order::where('tenant_id', $tenant->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,dispatched,completed,cancelled',
        ]);

        $orderService = app(\App\Services\OrderService::class);
        $orderService->updateStatus($order, $request->status, auth()->id());

        return redirect()->back()->with('success', 'Order status updated to ' . $request->status);
    }

    public function print(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $order = \App\Models\Order::where('tenant_id', $tenant->id)
            ->with('items')
            ->findOrFail($id);

        return view('admin.orders.print', compact('order', 'tenant'));
    }
}
