<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $settings = \App\Models\TenantSetting::where('tenant_id', $tenant->id)
            ->pluck('value', 'key');

        return view('admin.settings.index', compact('tenant', 'settings'));
    }

    public function update(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        if ($request->has('generate_api_key')) {
            $tenant->update(['api_key' => \Illuminate\Support\Str::random(40)]);
            return back()->with('success', 'API Key generated successfully.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string',
            'settings' => 'nullable|array',
            'logo' => 'nullable|image|max:1024', // Added back validation for logo
            'cover' => 'nullable|image|max:2048', // Added back validation for cover
        ]);

        // Process settings checkboxes (ensure unchecked items are false)
        $currentSettings = $tenant->settings ?: [];
        $features = ['enable_kds', 'enable_packing', 'enable_delivery', 'enable_pickup', 'enable_variants', 'enable_modifiers', 'enable_whatsapp_notify'];
        $newSettings = $request->input('settings', []); // Get settings from request, default to empty array

        foreach ($features as $f) {
            $currentSettings[$f] = isset($newSettings[$f]) && $newSettings[$f] == '1';
        }

        // Handle File Uploads
        if ($request->hasFile('logo')) {
            $currentSettings['logo'] = $request->file('logo')->store('branding', 'public');
        }
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('branding', 'public');
        }

        foreach ($data as $key => $value) {
            \App\Models\TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
