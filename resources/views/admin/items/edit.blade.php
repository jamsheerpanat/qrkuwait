<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($item) ? __('Edit Item') : __('Create Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <form action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}"
                    method="POST" enctype="multipart/form-data">
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
                            <x-input-label for="price" :value="__('Price (KWD)')" />
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
                        <x-primary-button>{{ __('Save Item Catalog') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>