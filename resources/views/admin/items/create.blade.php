<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Branding / Image -->
                        <div class="space-y-4">
                            <x-input-label for="image" :value="__('Item Image')" />
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
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                :value="old('name_en')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                        </div>

                        <div>
                            <x-input-label for="name_ar" :value="__('Name (Arabic)')" />
                            <x-text-input id="name_ar" name="name_ar" type="text" class="mt-1 block w-full"
                                :value="old('name_ar')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_ar')" />
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label for="description_en" :value="__('Description (English)')" />
                                <textarea id="description_en" name="description_en"
                                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    rows="3">{{ old('description_en') }}</textarea>
                            </div>
                            <div>
                                <x-input-label for="description_ar" :value="__('Description (Arabic)')" />
                                <textarea id="description_ar" name="description_ar"
                                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    rows="3">{{ old('description_ar') }}</textarea>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Base Price (KWD)')" />
                            <x-text-input id="price" name="price" type="number" step="0.001" class="mt-1 block w-full"
                                :value="old('price', '0.000')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="sku" :value="__('SKU (Optional)')" />
                            <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" :value="old('sku')" />
                        </div>

                        <div class="flex items-center gap-6 mt-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Active')" />
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_weighted" name="is_weighted" {{ old('is_weighted') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_weighted" :value="__('Weighted Product')" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t flex justify-end">
                        <x-primary-button>{{ __('Create Item') }}</x-primary-button>
                    </div>
                </form>
            </div>
<!-- Note about Variants -->
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-amber-800">{{ __('Variants & Add-ons') }}</h3>
            <p class="text-sm text-amber-700 mt-1">
                {{ __('After creating the item, you can add size/flavor variants and optional add-ons (extras) by editing the item.') }}
            </p>
        </div>
    </div>
</div>
        </div>
    </div>
</x-app-layout>