<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Item;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KFCSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create KFC Tenant
        $tenant = Tenant::updateOrCreate(
            ['slug' => 'kfc'],
            [
                'name' => 'KFC Kuwait',
                'type' => 'restaurant',
                'status' => 'active',
                'default_language' => 'en',
                'timezone' => 'Asia/Kuwait',
                'currency' => 'KWD'
            ]
        );

        $this->command->info('Setting up KFC (ID: ' . $tenant->id . ')');

        // Clear existing to avoid duplicates
        Item::where('tenant_id', $tenant->id)->delete();
        Category::where('tenant_id', $tenant->id)->delete();

        // 2. Create Branch
        $branch = Branch::updateOrCreate(
            ['tenant_id' => $tenant->id, 'is_default' => true],
            [
                'name' => 'Salmiya Main Branch',
                'whatsapp_number' => '+96599750046',
                'address' => 'Salmiya, Block 4, Amman St',
            ]
        );

        // 3. Create Settings
        $settings = [
            'currency' => 'KWD',
            'name' => 'KFC Kuwait',
            'timezone' => 'Asia/Kuwait',
            'enable_delivery' => '1',
            'enable_pickup' => '1',
            'enable_whatsapp_notify' => '1'
        ];

        foreach ($settings as $key => $value) {
            \App\Models\TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }

        // 4. Create Tenant Admin
        User::updateOrCreate(
            ['email' => 'kfc@admin.com'],
            [
                'name' => 'KFC Manager',
                'password' => Hash::make('password123'),
                'role' => 'tenant_admin',
                'tenant_id' => $tenant->id,
            ]
        );

        // 5. Categories
        $catBucket = Category::create([
            'tenant_id' => $tenant->id,
            'name' => ['en' => 'Buckets', 'ar' => 'براميل'],
            'sort_order' => 1,
            'is_active' => true
        ]);

        $catIndividual = Category::create([
            'tenant_id' => $tenant->id,
            'name' => ['en' => 'Individual Meals', 'ar' => 'وجبات فردية'],
            'sort_order' => 2,
            'is_active' => true
        ]);

        $catSides = Category::create([
            'tenant_id' => $tenant->id,
            'name' => ['en' => 'Sides & Desserts', 'ar' => 'أصناف جانبية وحلويات'],
            'sort_order' => 3,
            'is_active' => true
        ]);

        // 6. Items
        $items = [
            [
                'category_id' => $catBucket->id,
                'name' => ['en' => '9 Pcs Bucket', 'ar' => '9 قطع دجاج'],
                'description' => ['en' => '9 Pieces of Original or Spicy Chicken', 'ar' => '9 قطع دجاج بالخلطة السرية أو الحارة'],
                'price' => 5.500,
                'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?q=80&w=500',
            ],
            [
                'category_id' => $catBucket->id,
                'name' => ['en' => '15 Pcs Family Bucket', 'ar' => '15 قطع دجاج للعائلة'],
                'description' => ['en' => '15 Pieces Chicken + 1 Salad + 1 Fries + 1.25L Pepsi', 'ar' => '15 قطعة دجاج + 1 سلطة + 1 بطاطس + 1.25 لتر بيبسي'],
                'price' => 8.950,
                'image' => 'https://images.unsplash.com/photo-1562967916-eb82221dfb92?q=80&w=500',
            ],
            [
                'category_id' => $catIndividual->id,
                'name' => ['en' => 'Zinger Meal', 'ar' => 'وجبة زنجر'],
                'description' => ['en' => 'Zinger Sandwich + Fries + Drink', 'ar' => 'ساندوتش زنجر + بطاطس + مشروب'],
                'price' => 2.450,
                'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=500',
            ],
            [
                'category_id' => $catSides->id,
                'name' => ['en' => 'French Fries', 'ar' => 'بطاطس مقلية'],
                'description' => ['en' => 'Golden Crispy Fries', 'ar' => 'بطاطس ذهبية مقرمشة'],
                'price' => 0.750,
                'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?q=80&w=500',
            ],
            [
                'category_id' => $catSides->id,
                'name' => ['en' => 'coleslaw', 'ar' => 'كول سلو'],
                'description' => ['en' => 'Signature KFC Coleslaw', 'ar' => 'سلطة كول سلو المميزة'],
                'price' => 0.650,
                'image' => 'https://images.unsplash.com/photo-1512852939750-1305098529bf?q=80&w=500',
            ]
        ];

        foreach ($items as $index => $itemData) {
            Item::create(array_merge($itemData, [
                'tenant_id' => $tenant->id,
                'is_active' => true,
                'sort_order' => $index + 1
            ]));
        }

        // Add Logo and Cover to settings
        $branding = [
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/b/bf/KFC_logo.svg/1200px-KFC_logo.svg.png',
            'cover' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1200',
        ];

        foreach ($branding as $key => $value) {
            \App\Models\TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('KFC Restaurant Seeded Successfully with Branding!');
    }
}
