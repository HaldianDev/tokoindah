<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Toko Indah Keramik & Houseware Menggala');
            $table->string('logo')->nullable();
            $table->string('whatsapp_number')->default('+62 812-3456-7890');
            $table->string('phone')->default('+62 812-3456-7890');
            $table->string('email')->default('info@tokokeramik.com');
            $table->text('store_address')->nullable();
            $table->unsignedInteger('shipping_cost_per_kg')->default(15000);

            // Hero section
            $table->string('hero_title')->default('Keindahan Estetika & Kualitas Eksklusif untuk Rumah Anda');
            $table->text('hero_subtitle')->nullable();

            // About us section
            $table->string('about_title')->default('Tentang Toko Indah Keramik & Houseware Menggala');
            $table->text('about_description')->nullable();
            $table->string('about_image')->nullable();
            $table->text('about_vision')->nullable();
            $table->text('about_mission')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_settings');
    }
};
