<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify status ENUM to include all necessary statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'pending_dp', 'persetujuan', 'admin_approved', 'owner_pending_dp', 'angsuran_berjalan', 'approved', 'processing', 'completed', 'cancelled', 'rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'approved', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
