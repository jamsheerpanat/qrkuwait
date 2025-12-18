<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($category) ? __('Edit Category') : __('Create Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <form
                    action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($category)) @method('PUT') @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name_en" :value="__('Name (English)')" />
                            <x-text-input id="name_en" name="name_en" type="text" class="mt-1 block w-full"
                                :value="old('name_en', isset($category) ? json_decode($category->getRawOriginal('name'))->en : '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                        </div>

                        <div>
                            <x-input-label for="name_ar" :value="__('Name (Arabic)')" />
                            <x-text-input id="name_ar" name="name_ar" type="text" class="mt-1 block w-full"
                                :value="old('name_ar', isset($category) ? json_decode($category->getRawOriginal('name'))->ar : '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name_ar')" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full"
                                :value="old('sort_order', $category->sort_order ?? 0)" />
                        </div>

                        <div class="flex items-center gap-2 mt-8">
                            <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <x-input-label for="is_active" :value="__('Active')" />
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <x-primary-button>{{ __('Save Category') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>