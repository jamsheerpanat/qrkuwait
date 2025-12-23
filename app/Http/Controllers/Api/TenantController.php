<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Get a list of all active tenants grouped by type.
     */
    public function index()
    {
        $tenants = Tenant::where('status', 'active')
            ->get()
            ->map(function ($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'slug' => $tenant->slug,
                    'type' => $tenant->type,
                    'logo' => $tenant->logo_url,
                    'cover' => $tenant->cover_url,
                    'currency' => $tenant->currency,
                    'url' => route('tenant.public', ['tenant_slug' => $tenant->slug]),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $tenants
        ]);
    }

    /**
     * Get tenants grouped by category (type).
     */
    public function grouped()
    {
        $tenants = Tenant::where('status', 'active')
            ->get()
            ->groupBy('type')
            ->map(function ($group) {
                return $group->map(function ($tenant) {
                    return [
                        'id' => $tenant->id,
                        'name' => $tenant->name,
                        'slug' => $tenant->slug,
                        'logo' => $tenant->logo_url,
                        'cover' => $tenant->cover_url,
                        'currency' => $tenant->currency,
                        'url' => route('tenant.public', ['tenant_slug' => $tenant->slug]),
                    ];
                });
            });

        return response()->json([
            'status' => 'success',
            'data' => $tenants
        ]);
    }
}
