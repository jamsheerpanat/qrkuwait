<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
            {{ __('Add New System User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <form action="{{ route('super.users.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="name" value="Full Name"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="name" name="name" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email Address"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="email" name="email" type="email"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="password" value="Initial Password"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="password" name="password" type="password"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" value="Access Role"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="role" id="role" x-data
                                x-on:change="showTenant = ($el.value !== 'super_admin')"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="tenant_admin">Tenant admin (Manager)</option>
                                <option value="kitchen">Kitchen Staff</option>
                                <option value="cashier">Cashier</option>
                                <option value="waiter">Waiter</option>
                                <option value="super_admin">System Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>

                    <div id="tenant_container">
                        <x-input-label for="tenant_id" value="Assign to Merchant / Store"
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                        <select name="tenant_id" id="tenant_id"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- No Store Assigned --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $selectedTenant == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tenant_id')" class="mt-2" />
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex gap-4">
                        <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">
                            CREATE SYSTEM ACCOUNT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>