<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Core Statistics
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'inactive_tenants' => Tenant::where('status', 'inactive')->count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_revenue' => Order::sum('total'),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_today' => Order::whereDate('created_at', today())->sum('total'),
            'orders_this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'revenue_this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total'),
            'new_tenants_this_month' => Tenant::whereMonth('created_at', now()->month)->count(),
        ];

        // Yesterday comparison for trends
        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $yesterdayRevenue = Order::whereDate('created_at', today()->subDay())->sum('total');
        
        $stats['orders_trend'] = $yesterdayOrders > 0 
            ? round((($stats['orders_today'] - $yesterdayOrders) / $yesterdayOrders) * 100, 1) 
            : ($stats['orders_today'] > 0 ? 100 : 0);
        $stats['revenue_trend'] = $yesterdayRevenue > 0 
            ? round((($stats['revenue_today'] - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) 
            : ($stats['revenue_today'] > 0 ? 100 : 0);

        // Revenue Chart Data (Last 7 Days)
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenueChart[] = [
                'date' => $date->format('M d'),
                'revenue' => Order::whereDate('created_at', $date)->sum('total'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // Tenant Growth Chart (Last 6 Months)
        $tenantGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $tenantGrowth[] = [
                'month' => $month->format('M Y'),
                'count' => Tenant::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
            ];
        }

        // Top Performing Tenants (by revenue) - MySQL strict mode compatible
        $topTenants = Tenant::select('tenants.id', 'tenants.name', 'tenants.slug', 'tenants.type', 'tenants.status')
            ->selectRaw('COALESCE(SUM(orders.total), 0) as total_revenue')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->leftJoin('orders', 'tenants.id', '=', 'orders.tenant_id')
            ->groupBy('tenants.id', 'tenants.name', 'tenants.slug', 'tenants.type', 'tenants.status')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Recent Tenants
        $recentTenants = Tenant::latest()->limit(5)->get();

        // Recent Orders (Platform-wide)
        $recentOrders = Order::with('tenant')
            ->latest()
            ->limit(10)
            ->get();

        // Order Status Distribution
        $orderStatusDistribution = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Payment Method Distribution
        $paymentMethodDistribution = Order::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        // Tenant Type Distribution
        $tenantTypeDistribution = Tenant::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return view('super.dashboard', compact(
            'stats',
            'revenueChart',
            'tenantGrowth',
            'topTenants',
            'recentTenants',
            'recentOrders',
            'orderStatusDistribution',
            'paymentMethodDistribution',
            'tenantTypeDistribution'
        ));
    }
}
