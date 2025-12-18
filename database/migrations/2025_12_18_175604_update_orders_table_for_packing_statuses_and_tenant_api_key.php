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
        // Update status enum for orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
        });

        // Add API Key to tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('api_key')->nullable()->unique()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('api_key');
        });
    }
};
