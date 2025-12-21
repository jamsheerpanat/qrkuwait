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
        // Support 'waiter' in role column by changing from enum to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('tenant_admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'tenant_admin', 'cashier', 'kitchen'])->default('tenant_admin')->change();
        });
    }
};
