<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
            QR<span class="text-indigo-600">Kuwait</span>: {{ __('Super Admin Control Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Global Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-slate-900 p-6 rounded-[2rem] text-white">
                    <div class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Total Tenants</div>
                    <div class="text-4xl font-extrabold">{{ $stats['total_tenants'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Active
                        Stores</div>
                        <div class="text-4xl font-extrabold text-slate-900">{{ $stats['active_tenants'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Total Orders</div>
                    <div class="text-4xl font-extrabold text-slate-900">{{ $stats['total_orders'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-indigo-600">Global Users</div>
                    <div class="text-4xl font-extrabold text-slate-900">{{ $stats['total_users'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Recent Tenants</h3>
                        <p class="text-slate-500">Monitor and manage store activations</p>
                    </div>
                    <a href="{{ route('super.tenants.create') }}"
                        class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Add New Tenant
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Store
                                    Name</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Slug
                                </th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Type
                                </th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Status
                                </th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($recentTenants as $tenant)
                                <tr>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold">
                                                {{ substr($tenant->name, 0, 1) }}</div>
                                            <span class="font-bold text-slate-800">{{ $tenant->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 font-mono text-sm text-slate-500">{{ $tenant->slug }}</td>
                                    <td class="px-8 py-6 text-slate-500 uppercase text-xs font-bold tracking-wider">
                                        {{ $tenant->type }}</td>
                                        <td class="px-8 py-6">
                                            <span
                                            class="px-3 py-1 {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $tenant->status }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('super.tenants.show', $tenant->id) }}"
                                                class="text-indigo-600 font-bold hover:underline">Manage</a>
                                            <a href="{{ route('super.tenants.edit', $tenant->id) }}" class="text-slate-400 font-bold hover:underline">Edit</a>
                                            <form action="{{ route('super.tenants.destroy', $tenant->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this tenant and all its data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 font-bold hover:underline">Delete</button>
                                            </form>
                                        </div>
                                        </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>