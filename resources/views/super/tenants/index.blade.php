<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('Merchant Management') }}
            </h2>
            <a href="{{ route('super.tenants.create') }}"
                class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition">
                Create Tenant
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
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
                            @foreach($tenants as $tenant)
                                <tr>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold">
                                                {{ substr($tenant->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-800">{{ $tenant->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 font-mono text-sm text-slate-500">{{ $tenant->slug }}</td>
                                    <td class="px-8 py-6 text-slate-500 uppercase text-xs font-bold tracking-wider">
                                        {{ $tenant->type }}
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="px-3 py-1 {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-lg text-[10px] font-bold uppercase tracking-widest">
                                            {{ $tenant->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('super.tenants.show', $tenant->id) }}"
                                                class="text-indigo-600 font-bold hover:underline">Manage</a>
                                            <a href="{{ route('super.tenants.edit', $tenant->id) }}"
                                                class="text-slate-400 font-bold hover:underline">Edit</a>
                                            <form action="{{ route('super.tenants.destroy', $tenant->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this tenant and all its data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 font-bold hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-8 border-t border-slate-50">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>