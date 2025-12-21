<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'Tenant context not found.');
        }

        $staff = User::where('tenant_id', $tenant->id)
            ->where('role', '!=', 'super_admin')
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:waiter,kitchen,cashier,tenant_admin'],
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role' => $request->role,
            ]);

            return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Staff Creation Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Could not create staff member. This may be due to a missing role in the database. Please run migrations.');
        }
    }

    public function edit(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $member = User::where('tenant_id', $tenant->id)->findOrFail($id);

        return view('admin.staff.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $member = User::where('tenant_id', $tenant->id)->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$member->id],
            'role' => ['required', 'in:waiter,kitchen,cashier,tenant_admin'],
        ]);

        try {
            $member->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['confirmed', Rules\Password::defaults()],
                ]);
                $member->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Staff Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Could not update staff member. Please ensure migrations have been run.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant');
        $member = User::where('tenant_id', $tenant->id)->findOrFail($id);

        if ($member->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $member->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
