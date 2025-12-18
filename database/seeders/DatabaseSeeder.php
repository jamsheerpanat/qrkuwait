<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@qrkuwait.com',
            'password' => \Illuminate\Support\Facades\Hash::make('ChangeMe123!'),
            'role' => 'super_admin',
        ]);

        // 2. Create Sample Tenant
        $tenant = \App\Models\Tenant::create([
            'name' => 'The Cheesecake Factory',
            'slug' => 'cheesecake',
            'type' => 'restaurant',
            'status' => 'active',
            'default_language' => 'en',
            'timezone' => 'Asia/Kuwait',
        ]);

        // 3. Create Tenant Branch
        $branch = $tenant->branches()->create([
            'name' => 'Grand Avenue Branch',
            'whatsapp_number' => '+96590000000',
            'address' => 'The Avenues Mall, Kuwait City',
            'is_default' => true,
        ]);

        // 4. Create Tenant Admin
        \App\Models\User::create([
            'name' => 'Restaurant Manager',
            'email' => 'manager@cheesecake.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'tenant_admin',
            'tenant_id' => $tenant->id,
        ]);

        // --- Demo Catalog Data ---

        // Categories
        $catCakes = \App\Models\Category::create([
            'tenant_id' => $tenant->id,
            'name' => ['en' => 'Signature Cakes', 'ar' => 'حلويات التوقيع'],
            'sort_order' => 1
        ]);

        $catCoffee = \App\Models\Category::create([
            'tenant_id' => $tenant->id,
            'name' => ['en' => 'Specialty Coffee', 'ar' => 'قهوة مختصة'],
            'sort_order' => 2
        ]);

        // Items
        $cake1 = \App\Models\Item::create([
            'tenant_id' => $tenant->id,
            'category_id' => $catCakes->id,
            'name' => ['en' => 'Godiva Chocolate Cake', 'ar' => 'كيكة جوديفا بالشوكولاتة'],
            'description' => ['en' => 'Flourless Godiva Chocolate Cake topped with chocolate ganache.', 'ar' => 'كيكة الشوكولاتة الفاخرة مغطاة بالغاناش'],
            'price' => 3.750,
            'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=500',
        ]);

        // Variants for Cake
        $cake1->variants()->createMany([
            ['name' => ['en' => 'Slice', 'ar' => 'قطعة'], 'price_diff' => 0, 'is_default' => true],
            ['name' => ['en' => 'Whole Cake', 'ar' => 'كيكة كاملة'], 'price_diff' => 15.000, 'is_default' => false],
        ]);

        $coffee1 = \App\Models\Item::create([
            'tenant_id' => $tenant->id,
            'category_id' => $catCoffee->id,
            'name' => ['en' => 'Spanish Latte', 'ar' => 'سبانش لاتيه'],
            'price' => 2.250,
            'image' => 'https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=500',
        ]);

        // Modifiers for Coffee
        $modMilk = $coffee1->modifiers()->create([
            'name' => ['en' => 'Milk Options', 'ar' => 'خيارات الحليب'],
            'type' => 'single',
            'is_required' => false
        ]);
        $modMilk->options()->createMany([
            ['name' => ['en' => 'Oat Milk', 'ar' => 'حليب الشوفان'], 'price_diff' => 0.500],
            ['name' => ['en' => 'Almond Milk', 'ar' => 'حليب اللوز'], 'price_diff' => 0.450],
        ]);

        // --- Sample Orders (for KDS & Reports) ---
        $statuses = ['new', 'confirmed', 'preparing', 'ready', 'completed'];

        for ($i = 1; $i <= 8; $i++) {
            $order = \App\Models\Order::create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch->id,
                'order_no' => 'ORD-' . strtoupper(str()->random(5)),
                'customer_name' => 'Guest ' . $i,
                'customer_mobile' => '9000000' . $i,
                'delivery_type' => $i % 2 == 0 ? 'delivery' : 'pickup',
                'subtotal' => 5.000,
                'total' => 5.750,
                'status' => $statuses[array_rand($statuses)],
                'notes' => $i == 1 ? 'Please no sugar' : null,
                'created_at' => now()->subHours(rand(1, 24))
            ]);

            $order->items()->create([
                'item_id' => $cake1->id,
                'item_name' => $cake1->name['en'],
                'qty' => rand(1, 2),
                'price' => 3.750,
                'line_total' => 3.750,
                'selected_variants' => ['name' => 'Slice'],
                'notes' => $i == 2 ? 'Add extra forks' : null
            ]);
        }
    }
}
