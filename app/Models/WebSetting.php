<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo',
        'whatsapp_number',
        'phone',
        'email',
        'store_address',
        'shipping_cost_per_kg',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_description',
        'about_image',
        'about_vision',
        'about_mission',
    ];

    public static function getSettings()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Toko Indah Keramik & Houseware Menggala',
                'whatsapp_number' => '+62 812-3456-7890',
                'phone' => '+62 812-3456-7890',
                'email' => 'info@tokokeramik.com',
                'store_address' => 'Jl. Pahlawan No. 123, Menggala',
                'shipping_cost_per_kg' => 15000,
                'hero_title' => 'Keindahan Estetika & Kualitas Eksklusif untuk Rumah Anda',
                'hero_subtitle' => 'Pusat koleksi hiasan keramik artistik, tea set elegan, vas mewah, dan perlengkapan rumah tangga berstandar ekspor dengan opsi pembayaran Tunai maupun Cicilan Kredit.',
                'about_title' => 'Tentang Toko Indah Keramik & Houseware Menggala',
                'about_description' => 'Toko Indah Keramik & Houseware Menggala adalah pusat galeri dan pengrajin perlengkapan keramik rumah tangga & hiasan artistik berkualitas ekspor. Berdiri sejak tahun 2018 di Lampung, kami berkomitmen menghadirkan sentuhan elegan dan kehangatan karya seni keramik ke dalam setiap sudut hunian Anda.',
                'about_image' => 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=1000&q=80',
                'about_vision' => 'Menjadi pusat keramik dan perlengkapan rumah tangga estetik terdepan di Indonesia yang terpercaya dalam kualitas, keindahan seni, dan kemudahan kepemilikan bagi semua kalangan.',
                'about_mission' => "1. Menghadirkan produk keramik berstandar mutu tinggi dengan material porselen tahan panas dan glasir ramah lingkungan.\n2. Memberikan fleksibilitas pembayaran terbaik melalui skema cicilan kredit tanpa beban bagi pelanggan setia.\n3. Menjamin keamanan pengiriman hingga 100% sampai ke tangan pelanggan di seluruh wilayah Nusantara.",
            ]
        );
    }
}
