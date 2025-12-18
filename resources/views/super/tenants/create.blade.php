<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
            {{ __('Onboard New Merchant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <form action="{{ route('super.tenants.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="name" value="Store Name"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="name" name="name" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" required autofocus
                                placeholder="Cheesecake Factory" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="slug" value="Store Slug (URL)"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="slug" name="slug" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-mono text-sm" required
                                placeholder="cheesecakefactory" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="type" value="Business Type"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="type" id="type"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="restaurant">Restaurant / Cafe</option>
                                <option value="grocery">Grocery / Retail</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Initial Status"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="status" id="status"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active">Active</option>
                                <option value="inactive">On Hold</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50">
                        <button type="submit"
                            class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">
                            PROVISION MERCHANT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>