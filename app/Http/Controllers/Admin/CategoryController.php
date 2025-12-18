<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $categories = \App\Models\Category::where('tenant_id', $tenant->id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'sort_order' => 'integer',
        ]);

        \App\Models\Category::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $category = \App\Models\Category::where('tenant_id', $tenant->id)->findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $category = \App\Models\Category::where('tenant_id', $tenant->id)->findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'sort_order' => 'integer',
        ]);

        $category->update([
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $category = \App\Models\Category::where('tenant_id', $tenant->id)->findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
