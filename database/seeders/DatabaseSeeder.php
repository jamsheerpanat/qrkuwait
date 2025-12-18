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
            'name' => 'Cheesecake Factory',
            'slug' => 'cheesecakefactory',
            'type' => 'restaurant',
            'status' => 'active',
            'default_language' => 'en',
            'timezone' => 'Asia/Kuwait',
        ]);

        // 3. Create Tenant Branch
        $tenant->branches()->create([
            'name' => 'Main Branch',
            'whatsapp_number' => '+96590000000',
            'address' => 'Grand Avenue, Kuwait City',
            'is_default' => true,
        ]);

        // 4. Create Tenant Admin
        \App\Models\User::create([
            'name' => 'Store Manager',
            'email' => 'manager@cheesecake.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'tenant_admin',
            'tenant_id' => $tenant->id,
        ]);
    }
}
