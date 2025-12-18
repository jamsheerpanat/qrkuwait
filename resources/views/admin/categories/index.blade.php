<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Add Category
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Sort</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name (EN)</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name (AR)</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($categories as $category)
                                <tr>
                                    <td class="px-6 py-4">{{ $category->sort_order }}</td>
                                    <td class="px-6 py-4 font-bold">
                                        {{ $category->getRawOriginal('name') ? json_decode($category->getRawOriginal('name'))->en : '' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $category->getRawOriginal('name') ? json_decode($category->getRawOriginal('name'))->ar : '' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-xs font-bold uppercase">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="text-indigo-600 hover:underline font-bold">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Delete this category?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:underline font-bold">Delete</button>
                                        </form>
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