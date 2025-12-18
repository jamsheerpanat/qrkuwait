<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $query = \App\Models\Item::where('tenant_id', $tenant->id)->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                    ->orWhere('name->ar', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('sort_order')->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    public function create(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $categories = \App\Models\Category::where('tenant_id', $tenant->id)->get();
        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        \App\Models\Item::create([
            'tenant_id' => $tenant->id,
            'category_id' => $request->category_id,
            'name' => ['en' => $request->name_en, 'ar' => $request->name_ar],
            'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            'price' => $request->price,
            'sku' => $request->sku,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = \App\Models\Item::where('tenant_id', $tenant->id)->findOrFail($id);
        $categories = \App\Models\Category::where('tenant_id', $tenant->id)->get();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = \App\Models\Item::where('tenant_id', $tenant->id)->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('items', 'public');
        }

        $item->update([
            'category_id' => $request->category_id,
            'name' => ['en' => $request->name_en, 'ar' => $request->name_ar],
            'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            'price' => $request->price,
            'sku' => $request->sku,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = \App\Models\Item::where('tenant_id', $tenant->id)->findOrFail($id);

        if ($item->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
