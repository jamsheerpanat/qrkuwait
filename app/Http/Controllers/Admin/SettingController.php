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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string',
            'settings' => 'nullable|array',
            'logo' => 'nullable|image|max:1024',
            'cover' => 'nullable|image|max:2048',
        ]);

        // 1. Update core Tenant model fields
        $tenant->update([
            'name' => $validated['name'],
            'currency' => $validated['currency'],
            'timezone' => $validated['timezone'],
        ]);

        // 2. Prepare settings to save in TenantSetting table
        $settingsToSave = [
            'currency' => $validated['currency'],
            'timezone' => $validated['timezone'],
            'name' => $validated['name'],
        ];

        // Process feature flags (checkboxes)
        $features = ['enable_kds', 'enable_packing', 'enable_delivery', 'enable_pickup', 'enable_variants', 'enable_modifiers', 'enable_whatsapp_notify'];
        $inputSettings = $request->input('settings', []);
        foreach ($features as $f) {
            $settingsToSave[$f] = isset($inputSettings[$f]) && $inputSettings[$f] == '1' ? '1' : '0';
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $settingsToSave['logo'] = $request->file('logo')->store('branding', 'public');
        }

        // Handle Cover Upload
        if ($request->hasFile('cover')) {
            $settingsToSave['cover'] = $request->file('cover')->store('branding', 'public');
        }

        // 3. Persist all settings to TenantSetting table
        foreach ($settingsToSave as $key => $value) {
            \App\Models\TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
