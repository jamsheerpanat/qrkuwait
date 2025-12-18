<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
            {{ __('Manage Merchant:') }} {{ $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <form action="{{ route('super.tenants.update', $tenant->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="name" value="Store Name"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="name" name="name" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" :value="old('name', $tenant->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="slug" value="Store Slug"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="slug" name="slug" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-mono text-sm"
                                :value="old('slug', $tenant->slug)" required />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="type" value="Business Type"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="type" id="type"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="restaurant" {{ $tenant->type === 'restaurant' ? 'selected' : '' }}>
                                    Restaurant / Cafe</option>
                                <option value="grocery" {{ $tenant->type === 'grocery' ? 'selected' : '' }}>Grocery /
                                    Retail</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="status" id="status"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ $tenant->status === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ $tenant->status === 'inactive' ? 'selected' : '' }}>On Hold
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex gap-4">
                        <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">
                            SAVE CHANGES
                        </button>
                        <a href="{{ route('super.tenants.index') }}"
                            class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold flex items-center justify-center hover:bg-slate-200 transition">
                            BACK
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>