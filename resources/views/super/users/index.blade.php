<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('Global User Management') }}
            </h2>
            <a href="{{ route('super.users.create') }}"
                class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition">
                Add User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <form action="{{ route('super.users.index') }}" method="GET" class="flex gap-4 items-center">
                    <div class="flex-1">
                        <select name="tenant_id" onchange="this.form.submit()"
                            class="w-full rounded-xl border-slate-100 bg-slate-50 font-bold focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Tenants (Global Users)</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">User
                                    Name</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Email
                                </th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Role
                                </th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Store /
                                    Tenant</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-slate-500">{{ $user->email }}</td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ str_replace('_', ' ', $user->role) }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($user->tenant)
                                            <a href="{{ route('super.tenants.show', $user->tenant_id) }}"
                                                class="text-indigo-600 font-bold hover:underline">{{ $user->tenant->name }}</a>
                                        @else
                                            <span class="text-slate-400 italic">Global / Super Admin</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('super.users.edit', $user->id) }}"
                                                class="text-indigo-600 font-bold hover:underline">Edit</a>
                                            <form action="{{ route('super.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 font-bold hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-8 border-t border-slate-50">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>