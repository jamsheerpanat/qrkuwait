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
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id', 'ct_cat_tid')->references('id')->on('tenants')->onDelete('cascade');
            $table->json('name'); // {en, ar}
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id', 'ct_itm_tid')->references('id')->on('tenants')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id', 'ct_itm_cid')->references('id')->on('categories')->onDelete('cascade');
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
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id', 'ct_var_iid')->references('id')->on('items')->onDelete('cascade');
            $table->json('name'); // {en, ar}
            $table->decimal('price_diff', 10, 3)->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Item Modifiers
        Schema::create('item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id', 'ct_mod_iid')->references('id')->on('items')->onDelete('cascade');
            $table->json('name'); // {en, ar}
            $table->enum('type', ['single', 'multiple'])->default('single');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // Item Modifier Options
        Schema::create('item_modifier_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modifier_id');
            $table->foreign('modifier_id', 'ct_mop_mid')->references('id')->on('item_modifiers')->onDelete('cascade');
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
