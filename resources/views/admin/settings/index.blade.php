<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Store Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Branding -->
                        <div class="space-y-8">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Branding</h3>
                                <div class="space-y-6">
                                    <div>
                                        <x-input-label for="logo" :value="__('Store Logo')" />
                                        @if(isset($settings['logo']))
                                            <img src="{{ asset('storage/' . $settings['logo']) }}"
                                                class="w-24 h-24 rounded-xl object-cover mb-4 border shadow-sm">
                                        @endif
                                        <input type="file" name="logo"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                    <div>
                                        <x-input-label for="cover" :value="__('Cover Banner')" />
                                        @if(isset($settings['cover']))
                                            <img src="{{ asset('storage/' . $settings['cover']) }}"
                                                class="w-full h-32 rounded-xl object-cover mb-4 border shadow-sm">
                                        @endif
                                        <input type="file" name="cover"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- General Settings -->
                        <div class="space-y-8">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Configuration</h3>
                                <div class="space-y-6">
                                    <div>
                                        <x-input-label for="currency" :value="__('Store Currency')" />
                                        <x-text-input name="currency" type="text" class="mt-1 block w-full"
                                            :value="$settings['currency'] ?? 'KWD'" />
                                    </div>
                                    <div>
                                        <x-input-label for="timezone" :value="__('Timezone')" />
                                        <select name="timezone"
                                            class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500">
                                            <option value="Asia/Kuwait" {{ ($settings['timezone'] ?? '') == 'Asia/Kuwait' ? 'selected' : '' }}>Asia/Kuwait</option>
                                            <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="business_hours" :value="__('Business Hours (JSON)')" />
                                        <textarea name="business_hours"
                                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm"
                                            rows="4">{{ $settings['business_hours'] ?? '{"open": "09:00", "close": "23:00"}' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Toggles -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-black italic">Operational Features</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $features = [
                                    'enable_kds' => 'Kitchen Display (KDS)',
                                    'enable_packing' => 'Grocery Packing Screen',
                                    'enable_delivery' => 'Delivery Support',
                                    'enable_pickup' => 'Pickup Support',
                                    'enable_variants' => 'Item Variants',
                                    'enable_modifiers' => 'Item Modifiers',
                                    'enable_whatsapp_notify' => 'WhatsApp Order Received Msg',
                                ];
                            @endphp
                            @foreach($features as $key => $label)
                                <label
                                    class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition">
                                    <input type="checkbox" name="settings[{{ $key }}]" value="1" {{ ($tenant->settings[$key] ?? true) ? 'checked' : '' }}
                                        class="w-6 h-6 rounded-lg text-brand-600 focus:ring-brand-600 border-slate-200">
                                    <span class="font-bold text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- API & POS -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-black italic">API & External POS</h3>
                        <div class="p-6 bg-slate-900 rounded-[2rem] text-white">
                            <p class="text-sm text-slate-400 mb-6 italic">Use this key to sync your orders with
                                third-party POS systems.</p>
                            <div class="flex items-center gap-4">
                                <input type="text" value="{{ $tenant->api_key ?: 'Not Generated Yet' }}" readonly
                                    class="flex-1 bg-slate-800 border-none rounded-xl font-mono text-sm px-4 py-3 text-brand-400">
                                <button type="submit" name="generate_api_key" value="1"
                                    class="bg-brand-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-brand-700 transition">Regenerate
                                    Key</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-slate-900 text-white px-12 py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">Save
                            Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>