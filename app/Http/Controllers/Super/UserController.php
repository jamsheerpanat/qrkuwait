<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::with('tenant');

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $users = $query->latest()->paginate(20);
        $tenants = \App\Models\Tenant::all();

        return view('super.users.index', compact('users', 'tenants'));
    }

    public function create(Request $request)
    {
        $tenants = \App\Models\Tenant::all();
        $selectedTenant = $request->get('tenant_id');
        return view('super.users.create', compact('tenants', 'selectedTenant'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,tenant_admin,kitchen,waiter,cashier',
            'tenant_id' => 'required_unless:role,super_admin|nullable|exists:tenants,id',
        ]);

        $data['password'] = \Hash::make($data['password']);
        \App\Models\User::create($data);

        return redirect()->route('super.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $tenants = \App\Models\Tenant::all();
        return view('super.users.edit', compact('user', 'tenants'));
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:super_admin,tenant_admin,kitchen,waiter,cashier',
            'tenant_id' => 'required_unless:role,super_admin|nullable|exists:tenants,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = \Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('super.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Cannot delete yourself.');
        }

        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('super.users.index')->with('success', 'User deleted.');
    }
}
