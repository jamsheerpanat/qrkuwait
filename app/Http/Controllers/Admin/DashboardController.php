<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $today = now()->startOfDay();

        // Core Statistics
        $stats = [
            'orders_today' => Order::where('tenant_id', $tenant->id)
                ->whereDate('created_at', today())
                ->count(),
            'revenue_today' => Order::where('tenant_id', $tenant->id)
                ->whereDate('created_at', today())
                ->sum('total'),
            'orders_this_week' => Order::where('tenant_id', $tenant->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'revenue_this_week' => Order::where('tenant_id', $tenant->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('total'),
            'orders_this_month' => Order::where('tenant_id', $tenant->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'revenue_this_month' => Order::where('tenant_id', $tenant->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
            'staff_count' => User::where('tenant_id', $tenant->id)->count(),
            'total_categories' => Category::where('tenant_id', $tenant->id)->count(),
            'total_items' => Item::where('tenant_id', $tenant->id)->count(),
            'avg_order_value' => Order::where('tenant_id', $tenant->id)->avg('total') ?? 0,
            'pending_orders' => Order::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('tenant_id', $tenant->id)->where('status', 'delivered')->count(),
        ];

        // Trend Calculations (vs yesterday)
        $yesterdayOrders = Order::where('tenant_id', $tenant->id)
            ->whereDate('created_at', today()->subDay())
            ->count();
        $yesterdayRevenue = Order::where('tenant_id', $tenant->id)
            ->whereDate('created_at', today()->subDay())
            ->sum('total');

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
                'date' => $date->format('D'),
                'full_date' => $date->format('M d'),
                'revenue' => Order::where('tenant_id', $tenant->id)
                    ->whereDate('created_at', $date)
                    ->sum('total'),
                'orders' => Order::where('tenant_id', $tenant->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        // Monthly Revenue Chart (Last 6 Months)
        $monthlyChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyChart[] = [
                'month' => $month->format('M'),
                'revenue' => Order::where('tenant_id', $tenant->id)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total'),
                'orders' => Order::where('tenant_id', $tenant->id)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
            ];
        }

        // Top Selling Items
        $topItems = OrderItem::select('item_name', DB::raw('SUM(qty) as total_sold'), DB::raw('SUM(qty * price) as total_revenue'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.tenant_id', $tenant->id)
            ->groupBy('item_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Peak Hours Analysis (Last 7 Days) - Database agnostic
        $driver = DB::connection()->getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? "CAST(strftime('%H', created_at) AS INTEGER)"
            : 'HOUR(created_at)';

        $peakHours = Order::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw("$hourExpression as hour"), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Fill missing hours with 0
        $fullPeakHours = [];
        for ($h = 0; $h < 24; $h++) {
            $fullPeakHours[$h] = $peakHours[$h] ?? 0;
        }

        // Order Status Distribution
        $orderStatusDistribution = Order::where('tenant_id', $tenant->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Payment Method Distribution
        $paymentMethodDistribution = Order::where('tenant_id', $tenant->id)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method')
            ->toArray();

        // Delivery Type Distribution
        $deliveryTypeDistribution = Order::where('tenant_id', $tenant->id)
            ->select('delivery_type', DB::raw('count(*) as count'))
            ->groupBy('delivery_type')
            ->pluck('count', 'delivery_type')
            ->toArray();

        // Category Performance - Using the JSON 'name' column
        $categoryPerformance = OrderItem::select('categories.id as category_id', 'categories.name as category_name', DB::raw('SUM(order_items.qty) as items_sold'), DB::raw('SUM(order_items.qty * order_items.price) as revenue'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where('orders.tenant_id', $tenant->id)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $name = is_array($item->category_name) ? $item->category_name : json_decode($item->category_name, true);
                $item->category_name = $name['en'] ?? ($name['ar'] ?? 'Unknown');
                return $item;
            });

        // Recent Orders
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->latest()
            ->limit(10)
            ->get();

        // Customer Metrics
        $totalCustomers = Order::where('tenant_id', $tenant->id)
            ->distinct('customer_mobile')
            ->count('customer_mobile');

        $repeatCustomers = Order::where('tenant_id', $tenant->id)
            ->select('customer_mobile')
            ->groupBy('customer_mobile')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $customerMetrics = [
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'repeat_rate' => $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'revenueChart',
            'monthlyChart',
            'topItems',
            'fullPeakHours',
            'orderStatusDistribution',
            'paymentMethodDistribution',
            'deliveryTypeDistribution',
            'categoryPerformance',
            'recentOrders',
            'customerMetrics'
        ));
    }
}
