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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_no')->index();
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup');
            $table->json('address')->nullable();
            $table->decimal('subtotal', 10, 3)->default(0);
            $table->decimal('delivery_fee', 10, 3)->default(0);
            $table->decimal('tax', 10, 3)->default(0);
            $table->decimal('total', 10, 3)->default(0);
            $table->enum('status', ['new', 'confirmed', 'preparing', 'ready', 'dispatched', 'completed', 'cancelled'])->default('new');
            $table->string('source')->default('qr');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique order_no per tenant
            $table->unique(['tenant_id', 'order_no']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name'); // Snapshot
            $table->decimal('qty', 10, 3)->default(1);
            $table->string('unit_label')->nullable();
            $table->decimal('price', 10, 3);
            $table->decimal('line_total', 10, 3);
            $table->text('notes')->nullable();
            $table->json('selected_variants')->nullable();
            $table->json('selected_modifiers')->nullable();
            $table->timestamps();
        });

        Schema::create('order_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->index();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_logs');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
