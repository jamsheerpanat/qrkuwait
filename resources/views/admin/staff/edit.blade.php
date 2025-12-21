<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.staff.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight italic tracking-tighter">
                    {{ __('Edit Staff Member') }}
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ $member->name }} • Profile Settings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.staff.update', $member->id) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="name" value="Full Name" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3" />
                            <x-text-input id="name" name="name" type="text" :value="old('name', $member->name)" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-slate-900 transition" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="role" value="Assign Role" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3" />
                            <select name="role" id="role" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-slate-900 transition" required>
                                <option value="waiter" {{ $member->role === 'waiter' ? 'selected' : '' }}>Waiter (Dine-in POS)</option>
                                <option value="kitchen" {{ $member->role === 'kitchen' ? 'selected' : '' }}>Kitchen Staff (KDS)</option>
                                <option value="cashier" {{ $member->role === 'cashier' ? 'selected' : '' }}>Cashier (Full POS)</option>
                                <option value="tenant_admin" {{ $member->role === 'tenant_admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="email" value="Email Address" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3" />
                        <x-text-input id="email" name="email" type="email" :value="old('email', $member->email)" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-slate-900 transition" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="pt-8 border-t border-slate-50">
                        <h3 class="font-black italic text-slate-900 text-lg mb-2">Change Password</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-6">Leave blank to keep existing password</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label for="password" value="New Password" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3" />
                                <x-text-input id="password" name="password" type="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-slate-900 transition" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" value="Confirm New Password" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-slate-900 transition" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black italic text-xl shadow-2xl shadow-slate-200 transform active:scale-[0.98] transition">
                            Update Staff Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
