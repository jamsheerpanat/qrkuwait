<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants', 'id', 'fk_categories_tenant_id')->onDelete('cascade')->index();
            $table->json('name'); // {en, ar}
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants', 'id', 'fk_items_tenant_id')->onDelete('cascade')->index();
            $table->foreignId('category_id')->constrained('categories', 'id', 'fk_items_category_id')->onDelete('cascade')->index();
            $table->json('name'); // {en, ar}
            $table->json('description')->nullable(); // {en, ar}
            $table->decimal('price', 10, 3)->default(0);
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_weighted')->default(false);
            $table->string('unit_label')->nullable(); // kg, pc, etc.
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Item Variants
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items', 'id', 'fk_item_variants_item_id')->onDelete('cascade')->index();
            $table->json('name'); // {en, ar}
            $table->decimal('price_diff', 10, 3)->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Item Modifiers
        Schema::create('item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items', 'id', 'fk_item_modifiers_item_id')->onDelete('cascade')->index();
            $table->json('name'); // {en, ar}
            $table->enum('type', ['single', 'multiple'])->default('single');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // Item Modifier Options
        Schema::create('item_modifier_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modifier_id')->constrained('item_modifiers', 'id', 'fk_item_mod_options_mod_id')->onDelete('cascade')->index();
            $table->json('name'); // {en, ar}
            $table->decimal('price_diff', 10, 3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_modifier_options');
        Schema::dropIfExists('item_modifiers');
        Schema::dropIfExists('item_variants');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
    }
};
