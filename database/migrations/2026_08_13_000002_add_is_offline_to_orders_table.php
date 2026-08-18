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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_offline')
                  ->default(false)
                  ->comment('True = transaksi dilakukan di kasir offline');
        });

        // Backfill existing offline orders
        DB::table('orders')
          ->where('shipping_address', 'like', 'Toko Offline%')
          ->update(['is_offline' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_offline');
        });
    }
};
