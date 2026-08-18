<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('status');
            $table->string('payment_rejection_reason')->nullable()->after('payment_proof_path');
        });

        // Add new statuses for cash payment verification
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'pending_dp', 'persetujuan', 'admin_approved', 'owner_pending_dp', 'angsuran_berjalan', 'approved', 'processing', 'completed', 'cancelled', 'rejected', 'waiting_payment', 'verifying_payment') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status ENUM to its previous state
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'pending_dp', 'persetujuan', 'admin_approved', 'owner_pending_dp', 'angsuran_berjalan', 'approved', 'processing', 'completed', 'cancelled', 'rejected') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_proof_path', 'payment_rejection_reason']);
        });
    }
};
