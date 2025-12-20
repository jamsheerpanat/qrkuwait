<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemVariant;
use App\Models\ItemModifier;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $query = Item::where('tenant_id', $tenant->id)->with('category');

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
        $categories = Category::where('tenant_id', $tenant->id)->get();
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

        Item::create([
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
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($id);
        $categories = Category::where('tenant_id', $tenant->id)->get();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($id);

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
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($id);

        if ($item->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }

    // ========== VARIANTS API ==========

    public function saveVariants(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($id);

        $variants = $request->input('variants', []);
        $savedVariants = [];

        // Get existing variant IDs
        $existingIds = $item->variants->pluck('id')->toArray();
        $submittedIds = array_filter(array_column($variants, 'id'));

        // Delete removed variants
        $toDelete = array_diff($existingIds, $submittedIds);
        ItemVariant::whereIn('id', $toDelete)->delete();

        // Update or create variants
        foreach ($variants as $variantData) {
            if (!empty($variantData['name_en']) || !empty($variantData['name_ar'])) {
                $variant = ItemVariant::updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    [
                        'item_id' => $item->id,
                        'name' => ['en' => $variantData['name_en'] ?? '', 'ar' => $variantData['name_ar'] ?? ''],
                        'price_diff' => $variantData['price'] ?? 0,
                    ]
                );
                $savedVariants[] = [
                    'id' => $variant->id,
                    'name_en' => $variant->name['en'] ?? '',
                    'name_ar' => $variant->name['ar'] ?? '',
                    'price' => $variant->price_diff,
                ];
            }
        }

        return response()->json(['success' => true, 'variants' => $savedVariants]);
    }

    public function deleteVariant(Request $request, string $itemId, string $variantId)
    {
        $tenant = $request->attributes->get('tenant');
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($itemId);
        $variant = ItemVariant::where('item_id', $item->id)->findOrFail($variantId);
        $variant->delete();

        return response()->json(['success' => true]);
    }

    // ========== ADD-ONS API ==========

    public function saveAddons(Request $request, string $id)
    {
        $tenant = $request->attributes->get('tenant');
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($id);

        $addons = $request->input('addons', []);
        $savedAddons = [];

        // Get existing addon IDs
        $existingIds = $item->modifiers->pluck('id')->toArray();
        $submittedIds = array_filter(array_column($addons, 'id'));

        // Delete removed addons
        $toDelete = array_diff($existingIds, $submittedIds);
        ItemModifier::whereIn('id', $toDelete)->delete();

        // Update or create addons
        foreach ($addons as $addonData) {
            if (!empty($addonData['name_en']) || !empty($addonData['name_ar'])) {
                $addon = ItemModifier::updateOrCreate(
                    ['id' => $addonData['id'] ?? null],
                    [
                        'item_id' => $item->id,
                        'name' => ['en' => $addonData['name_en'] ?? '', 'ar' => $addonData['name_ar'] ?? ''],
                        'price' => $addonData['price'] ?? 0,
                        'type' => 'multiple',
                    ]
                );
                $savedAddons[] = [
                    'id' => $addon->id,
                    'name_en' => $addon->name['en'] ?? '',
                    'name_ar' => $addon->name['ar'] ?? '',
                    'price' => $addon->price,
                ];
            }
        }

        return response()->json(['success' => true, 'addons' => $savedAddons]);
    }

    public function deleteAddon(Request $request, string $itemId, string $addonId)
    {
        $tenant = $request->attributes->get('tenant');
        $item = Item::where('tenant_id', $tenant->id)->findOrFail($itemId);
        $addon = ItemModifier::where('item_id', $item->id)->findOrFail($addonId);
        $addon->delete();

        return response()->json(['success' => true]);
    }
}
