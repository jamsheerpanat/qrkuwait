<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $today = now()->startOfDay();

        $dailyStats = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', $today)
            ->selectRaw('count(*) as count, sum(total) as revenue')
            ->first();

        $topItems = \App\Models\OrderItem::whereHas('order', fn($q) => $q->where('tenant_id', $tenant->id))
            ->select('item_name', \DB::raw('sum(qty) as total_qty'))
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $peakHours = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('HOUR(created_at) as hour, count(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact('tenant', 'dailyStats', 'topItems', 'peakHours'));
    }

    public function export(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $startDate = $request->get('start_date', now()->subDays(7)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $orders = \App\Models\Order::where('tenant_id', $tenant->id)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('items')
            ->get();

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order No', 'Date', 'Customer', 'Type', 'Status', 'Total']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_no,
                    $order->created_at->toDateTimeString(),
                    $order->customer_name,
                    $order->delivery_type,
                    $order->status,
                    $order->total
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders_{$startDate}_{$endDate}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}
