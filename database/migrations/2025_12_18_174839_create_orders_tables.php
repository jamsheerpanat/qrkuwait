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
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id', 'od_ord_tid')->references('id')->on('tenants')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id', 'od_ord_bid')->references('id')->on('branches')->onDelete('set null');
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
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'od_itm_oid')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id', 'od_itm_iid')->references('id')->on('items')->onDelete('set null');
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
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'od_log_oid')->references('id')->on('orders')->onDelete('cascade');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->unsignedBigInteger('changed_by_user_id')->nullable();
            $table->foreign('changed_by_user_id', 'od_log_uid')->references('id')->on('users')->onDelete('set null');
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
