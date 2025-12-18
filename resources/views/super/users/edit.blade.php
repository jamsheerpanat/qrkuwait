<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
            {{ __('Edit User Account:') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <form action="{{ route('super.users.update', $user->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="name" value="Full Name"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="name" name="name" type="text"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email Address"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="email" name="email" type="email"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="password" value="New Password (Leave blank to keep current)"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <x-text-input id="password" name="password" type="password"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" value="Access Role"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <select name="role" id="role"
                                class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="tenant_admin" {{ $user->role === 'tenant_admin' ? 'selected' : '' }}>Tenant
                                    admin (Manager)</option>
                                <option value="kitchen" {{ $user->role === 'kitchen' ? 'selected' : '' }}>Kitchen Staff
                                </option>
                                <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                                <option value="waiter" {{ $user->role === 'waiter' ? 'selected' : '' }}>Waiter</option>
                                <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>System
                                    Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="tenant_id" value="Assigned Merchant / Store"
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2" />
                        <select name="tenant_id" id="tenant_id"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- No Store (Global / Super Admin) --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $user->tenant_id == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tenant_id')" class="mt-2" />
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex gap-4">
                        <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black italic shadow-xl shadow-slate-200 hover:scale-[1.02] transition">
                            UPDATE USER ACCOUNT
                        </button>
                        <a href="{{ route('super.users.index') }}"
                            class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold flex items-center justify-center hover:bg-slate-200 transition">
                            BACK
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>