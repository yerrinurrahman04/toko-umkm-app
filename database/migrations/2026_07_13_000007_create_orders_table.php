<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('shipping_cost', 15, 2)->default(0.00);
            $table->decimal('final_amount', 15, 2);
            $table->string('status')->default('pending');
            $table->text('shipping_address');
            $table->string('notes')->nullable();
            $table->timestamps();

            // Composite indexes for reporting and filtering optimization
            $table->index(['shop_id', 'status', 'created_at']);
            $table->index(['status', 'shop_id', 'final_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
