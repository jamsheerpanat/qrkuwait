<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($item) ? __('Edit Item') : __('Create Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Main Item Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <form action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}"
                    method="POST" enctype="multipart/form-data" id="itemForm">
                    @csrf
                    @if(isset($item)) @method('PUT') @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Branding / Image -->
                        <div class="space-y-4">
                            <x-input-label for="image" :value="__('Item Image')" />
                            @if(isset($item) && $item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="w-32 h-32 rounded-2xl object-cover mb-2 border">
                            @endif
                            <input type="file" id="image" name="image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <!-- Category Selection -->
                        <div class="space-y-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->getRawOriginal('name') ? json_decode($cat->getRawOriginal('name'))->en : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- Basic Info -->
                        <div>
                            <x-input-label for="name_en" :value="__('Name (English)')" />
                            <x-text-input id="name_en" name="name_en" type="text" class="mt-1 block w-full"
                                :value="old('name_en', isset($item) ? $item->name['en'] : '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                        </div>

                        <div>
                            <x-input-label for="name_ar" :value="__('Name (Arabic)')" />
                            <x-text-input id="name_ar" name="name_ar" type="text" class="mt-1 block w-full"
                                :value="old('name_ar', isset($item) ? $item->name['ar'] : '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_ar')" />
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label for="description_en" :value="__('Description (English)')" />
                                <textarea id="description_en" name="description_en"
                                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    rows="3">{{ old('description_en', isset($item) ? $item->description['en'] : '') }}</textarea>
                            </div>
                            <div>
                                <x-input-label for="description_ar" :value="__('Description (Arabic)')" />
                                <textarea id="description_ar" name="description_ar"
                                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    rows="3">{{ old('description_ar', isset($item) ? $item->description['ar'] : '') }}</textarea>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Base Price (KWD)')" />
                            <x-text-input id="price" name="price" type="number" step="0.001" class="mt-1 block w-full"
                                :value="old('price', $item->price ?? '0.000')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="sku" :value="__('SKU (Optional)')" />
                            <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" :value="old('sku', $item->sku ?? '')" />
                        </div>

                        <div class="flex items-center gap-6 mt-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Active')" />
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_weighted" name="is_weighted" {{ old('is_weighted', $item->is_weighted ?? false) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_weighted" :value="__('Weighted Product')" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t flex justify-end">
                        <x-primary-button>{{ __('Save Item') }}</x-primary-button>
                    </div>
                </form>
            </div>
@if(isset($item))
    <!-- Variants Section (Size, Flavor, etc.) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8" x-data="variantsManager()">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ __('Variants (Sizes/Options)') }}</h3>
                <p class="text-sm text-gray-500">{{ __('Add size or flavor options. Customers must choose one.') }}</p>
            </div>
            <button type="button" @click="addVariant()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add Variant') }}
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(variant, index) in variants" :key="variant.id || index">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" x-model="variant.name_en" placeholder="Name (English)"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <input type="text" x-model="variant.name_ar" placeholder="Name (Arabic)" dir="rtl"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">+</span>
                            <input type="number" x-model="variant.price" step="0.001" placeholder="0.000"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <span class="text-sm text-gray-500">KWD</span>
                        </div>
                    </div>
                    <button type="button" @click="removeVariant(index)"
                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <div x-show="variants.length > 0" class="mt-4 flex justify-end">
            <button type="button" @click="saveVariants()"
                class="px-6 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition"
                :class="saving ? 'opacity-50 cursor-not-allowed' : ''">
                <span x-show="!saving">{{ __('Save Variants') }}</span>
                <span x-show="saving">{{ __('Saving...') }}</span>
            </button>
        </div>

        <div x-show="variants.length === 0" class="text-center py-8 text-gray-400">
            <p>{{ __('No variants added yet. Click "Add Variant" to create size or flavor options.') }}</p>
        </div>
    </div>

    <!-- Add-ons Section (Extras) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8" x-data="addonsManager()">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ __('Add-ons (Extras)') }}</h3>
                <p class="text-sm text-gray-500">{{ __('Optional extras customers can add to their order.') }}</p>
            </div>
            <button type="button" @click="addAddon()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-bold hover:bg-purple-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add Extra') }}
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(addon, index) in addons" :key="addon.id || index">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" x-model="addon.name_en" placeholder="Name (English)"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <input type="text" x-model="addon.name_ar" placeholder="Name (Arabic)" dir="rtl"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">+</span>
                            <input type="number" x-model="addon.price" step="0.001" placeholder="0.000"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <span class="text-sm text-gray-500">KWD</span>
                        </div>
                    </div>
                    <button type="button" @click="removeAddon(index)"
                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <div x-show="addons.length > 0" class="mt-4 flex justify-end">
            <button type="button" @click="saveAddons()"
                class="px-6 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition"
                :class="saving ? 'opacity-50 cursor-not-allowed' : ''">
                <span x-show="!saving">{{ __('Save Add-ons') }}</span>
                <span x-show="saving">{{ __('Saving...') }}</span>
            </button>
        </div>

        <div x-show="addons.length === 0" class="text-center py-8 text-gray-400">
            <p>{{ __('No add-ons yet. Click "Add Extra" to create optional extras like cheese, sauce, etc.') }}</p>
        </div>
                </div>
@endif
        </div>
    </div>

    @if(isset($item))
        <script>
            function variantsManager() {
                return {
                    variants: @json($item->variants->map(fn($v) => [
            'id' => $v->id,
            'name_en' => $v->name['en'] ?? '',
            'name_ar' => $v->name['ar'] ?? '',
            'price' => $v->price_diff,
        ])),
                    saving: false,

                    addVariant() {
                        this.variants.push({ id: null, name_en: '', name_ar: '', price: 0 });
                    },

                    removeVariant(index) {
                        const variant = this.variants[index];
                        if (variant.id) {
                            fetch(`/admin/items/{{ $item->id }}/variants/${variant.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                        }
                        this.variants.splice(index, 1);
                    },

                    async saveVariants() {
                        this.saving = true;
                        try {
                            const response = await fetch('/admin/items/{{ $item->id }}/variants', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ variants: this.variants })
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.variants = data.variants;
                                alert('Variants saved successfully!');
                            }
                        } catch (e) {
                            alert('Failed to save variants');
                        }
                        this.saving = false;
                    }
                }
            }

            function addonsManager() {
                return {
                    addons: @json($item->modifiers->map(fn($m) => [
            'id' => $m->id,
            'name_en' => $m->name['en'] ?? '',
            'name_ar' => $m->name['ar'] ?? '',
            'price' => $m->price ?? 0,
        ])),
                    saving: false,

                    addAddon() {
                        this.addons.push({ id: null, name_en: '', name_ar: '', price: 0 });
                    },

                    removeAddon(index) {
                        const addon = this.addons[index];
                        if (addon.id) {
                            fetch(`/admin/items/{{ $item->id }}/addons/${addon.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                        }
                        this.addons.splice(index, 1);
                    },

                    async saveAddons() {
                        this.saving = true;
                        try {
                            const response = await fetch('/admin/items/{{ $item->id }}/addons', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ addons: this.addons })
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.addons = data.addons;
                                alert('Add-ons saved successfully!');
                            }
                        } catch (e) {
                            alert('Failed to save add-ons');
                        }
                        this.saving = false;
                    }
                }
            }
        </script>
    @endif
</x-app-layout>