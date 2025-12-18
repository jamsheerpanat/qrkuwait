<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = \App\Models\Tenant::latest()->paginate(10);
        return view('super.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('super.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug|max:255|alpha_dash',
            'type' => 'required|in:restaurant,grocery',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant = \App\Models\Tenant::create(array_merge($data, [
            'default_language' => 'en',
            'timezone' => 'Asia/Kuwait',
            'currency' => 'KWD',
        ]));

        // Create Default Branch
        $tenant->branches()->create([
            'name' => 'Main Branch',
            'is_default' => true,
        ]);

        // Create Initial Admin User
        \App\Models\User::create([
            'name' => $data['name'] . ' Admin',
            'email' => "admin@{$data['slug']}.com",
            'password' => \Hash::make('password'),
            'role' => 'tenant_admin',
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('super.tenants.show', $tenant->id)->with('success', 'Tenant provisioned. You can now manage users and settings.');
    }

    public function show($id)
    {
        $tenant = \App\Models\Tenant::with(['branches', 'users'])->findOrFail($id);
        $ordersCount = \App\Models\Order::where('tenant_id', $tenant->id)->count();
        $recentOrders = \App\Models\Order::where('tenant_id', $tenant->id)->latest()->limit(5)->get();

        return view('super.tenants.show', compact('tenant', 'ordersCount', 'recentOrders'));
    }

    public function edit($id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        return view('super.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug,' . $tenant->id . '|max:255|alpha_dash',
            'type' => 'required|in:restaurant,grocery',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant->update($data);

        return redirect()->route('super.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy($id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        $tenant->delete();
        return redirect()->route('super.tenants.index')->with('success', 'Tenant deleted.');
    }
}
