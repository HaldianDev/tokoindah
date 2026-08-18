<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['cash', 'credit'])->default('cash');
            $table->integer('credit_tenor_months')->nullable(); // e.g. 3, 6, 12
            $table->decimal('down_payment', 12, 2)->default(0); // DP
            $table->decimal('monthly_installment', 12, 2)->default(0); // Angsuran per bulan
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
