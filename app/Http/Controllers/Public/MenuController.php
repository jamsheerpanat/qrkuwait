<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        $categories = \App\Models\Category::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->with([
                'items' => function ($query) {
                    $query->where('is_active', true)
                        ->with(['variants', 'modifiers.options'])
                        ->orderBy('sort_order');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $settings = \App\Models\TenantSetting::where('tenant_id', $tenant->id)
            ->pluck('value', 'key');

        return view('tenant.landing', compact('tenant', 'categories', 'settings'));
    }
}
