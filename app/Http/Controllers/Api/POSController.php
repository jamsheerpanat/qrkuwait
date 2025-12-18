<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key required'], 401);
        }

        $tenant = \App\Models\Tenant::where('api_key', $apiKey)->first();
        if (!$tenant) {
            return response()->json(['error' => 'Invalid API Key'], 401);
        }

        $query = \App\Models\Order::where('tenant_id', $tenant->id)
            ->with(['items']);

        if ($request->has('since')) {
            $query->where('updated_at', '>', $request->since);
        }

        $orders = $query->orderBy('updated_at', 'desc')->limit(100)->get();

        return response()->json([
            'tenant' => $tenant->name,
            'orders' => $orders
        ]);
    }
}
