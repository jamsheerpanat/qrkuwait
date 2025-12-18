<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <form action="{{ route('admin.items.index') }}" method="GET" class="flex gap-2 w-full max-w-md">
                    <x-text-input name="search" placeholder="Search items..." class="w-full"
                        :value="request('search')" />
                    <x-primary-button>Search</x-primary-button>
                </form>
                <a href="{{ route('admin.items.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Add Item
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name (EN)</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        @if($item->image)
                                            <img src="{{ $item->image_url }}"
                                                class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold">{{ $item->getLocalizedName() }}</td>
                                    <td class="px-6 py-4">
                                        {{ $item->category->getLocalizedName() }}</td>
                                    <td class="px-6 py-4 font-mono text-indigo-600 font-bold">
                                        {{ number_format($item->price, 3) }} KD</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-[10px] font-bold uppercase tracking-wider">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-3">
                                        <a href="{{ route('admin.items.edit', $item) }}"
                                            class="text-indigo-600 hover:underline font-bold">Edit</a>
                                        <form action="{{ route('admin.items.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Delete this item?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:underline font-bold">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>