<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $today = now()->startOfDay();

        $stats = [
            'orders_today' => Order::where('tenant_id', $tenant->id)
                ->where('created_at', '>=', $today)
                ->count(),
            'revenue_today' => Order::where('tenant_id', $tenant->id)
                ->where('created_at', '>=', $today)
                ->sum('total'),
            'staff_count' => User::where('tenant_id', $tenant->id)->count(),
            'active_tables' => 12, // Placeholder
        ];

        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
