<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (Admin, Owner, Pembeli)
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@tokokeramik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+62 812-1111-2222',
            'address' => 'Jl. Raya Keramik No. 88, Tulang Bawang, Lampung',
        ]);

        $owner = User::create([
            'name' => 'Bapak Owner',
            'email' => 'owner@tokokeramik.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '+62 812-3333-4444',
            'address' => 'Jl. Raya Keramik No. 88, Tulang Bawang, Lampung',
        ]);

        $customer1 = User::create([
            'name' => 'Rina Marlina',
            'email' => 'pembeli@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
            'phone' => '+62 813-5555-6666',
            'address' => 'Jl. Melati No. 12, Bandar Lampung',
        ]);

        $customer2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
            'phone' => '+62 815-7777-8888',
            'address' => 'Jl. Pahlawan No. 45, Tulang Bawang',
        ]);

        // 2. Create Categories
        $catPiring = Category::create([
            'name' => 'Piring & Mangkuk',
            'slug' => 'piring',
            'icon' => 'fa-solid fa-plate-wheat',
        ]);

        $catCangkir = Category::create([
            'name' => 'Cangkir & Set Teh',
            'slug' => 'cangkir',
            'icon' => 'fa-solid fa-mug-hot',
        ]);

        $catDekorasi = Category::create([
            'name' => 'Dekorasi',
            'slug' => 'dekorasi',
            'icon' => 'fa-solid fa-jar',
        ]);

        // 3. Create Sample Products
        $p1 = Product::create([
            'category_id' => $catPiring->id,
            'name' => 'Set Piring Keramik Jepang',
            'slug' => 'set-piring-keramik-jepang',
            'description' => 'Terbuat dari keramik tebal berkualitas tinggi dengan motif minimalis gaya Jepang. Sangat kuat, tahan panas, dan aman digunakan dalam microwave maupun pembersih piring.',
            'price' => 185000,
            'stock' => 0,
            'image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=500&q=80',
            'spec_1' => 'Tebal & Kokoh',
            'spec_2' => 'Tahan Panas Oven',
            'spec_3' => 'Anti Gores Glasir',
            'status' => 'out_of_stock',
        ]);

        $p2 = Product::create([
            'category_id' => $catCangkir->id,
            'name' => 'Set Cangkir Teh & Saucer',
            'slug' => 'set-cangkir-teh-saucer',
            'description' => '1 Set berisi 4 cangkir dan 4 piring kecil elegan. Finishing glasir mengkilap memberikan kesan mewah di meja tamu Anda.',
            'price' => 120000,
            'stock' => 15,
            'image' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=500&q=80',
            'spec_1' => '4 Piring & 4 Cangkir',
            'spec_2' => 'Anti Luntur',
            'spec_3' => 'Desain Klasik Mewah',
            'status' => 'ready',
        ]);

        $p3 = Product::create([
            'category_id' => $catPiring->id,
            'name' => 'Wadah Saji Makanan Tutup',
            'slug' => 'wadah-saji-makanan-tutup',
            'description' => 'Wadah saji berkapasitas besar lengkap dengan tutup rapat berbahan keramik tebal. Menjaga hidangan tetap hangat lebih lama di meja makan.',
            'price' => 150000,
            'stock' => 10,
            'image' => 'https://images.unsplash.com/photo-1590794056226-79ef3a8137e1?auto=format&fit=crop&w=500&q=80',
            'spec_1' => 'Kapasitas 1.5 Liter',
            'spec_2' => 'Tutup Kedap Rapat',
            'spec_3' => 'Mudah Dicuci',
            'status' => 'ready',
        ]);

        $p4 = Product::create([
            'category_id' => $catDekorasi->id,
            'name' => 'Vas Bunga Keramik Estetik',
            'slug' => 'vas-bunga-keramik-estetik',
            'description' => 'Vas bunga dengan bentuk artistik modern yang dirancang khusus untuk mempercantik sudut ruangan, meja kerja, maupun ruang tamu.',
            'price' => 95000,
            'stock' => 25,
            'image' => 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=500&q=80',
            'spec_1' => 'Tinggi 25 cm',
            'spec_2' => 'Keramik Premium',
            'spec_3' => 'Gaya Scandinavian',
            'status' => 'ready',
        ]);

        $p5 = Product::create([
            'category_id' => $catPiring->id,
            'name' => 'Mangkuk Sup Keramik Gagang',
            'slug' => 'mangkuk-sup-keramik-gagang',
            'description' => 'Mangkuk sup dengan desain pegangan ergonomis, sangat praktis untuk hidangan berkuah hangat seperti zuppa soup, soto, atau bakso.',
            'price' => 65000,
            'stock' => 30,
            'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=500&q=80',
            'spec_1' => 'Dilengkapi Gagang',
            'spec_2' => 'Aman Microwave',
            'spec_3' => 'Keramik Tebal Tahan Panas',
            'status' => 'ready',
        ]);

        $p6 = Product::create([
            'category_id' => $catCangkir->id,
            'name' => 'Mug Kopi Keramik Premium',
            'slug' => 'mug-kopi-keramik-premium',
            'description' => 'Mug kopi berkapasitas pas dengan dinding tebal untuk menjaga suhu kopi atau teh tetap hangat lebih lama saat Anda bekerja.',
            'price' => 45000,
            'stock' => 50,
            'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=500&q=80',
            'spec_1' => 'Kapasitas 350 ml',
            'spec_2' => 'Bahan Porselen',
            'spec_3' => 'Nyaman Digenggam',
            'status' => 'ready',
        ]);

        // 4. Log Initial Stock Movements (Barang Masuk)
        foreach ([$p2, $p3, $p4, $p5, $p6] as $prod) {
            StockMovement::create([
                'product_id' => $prod->id,
                'type' => 'in',
                'quantity' => $prod->stock + 5,
                'notes' => 'Pasokan Awal Toko (Stok Masuk)',
                'user_id' => $admin->id,
            ]);
        }

        // 5. Create Sample Orders (Cash & Credit for analytics demo)
        // Cash Order
        $orderCash = Order::create([
            'order_number' => 'RK-CASH001',
            'user_id' => $customer1->id,
            'payment_method' => 'cash',
            'credit_tenor_months' => null,
            'down_payment' => 0,
            'monthly_installment' => 0,
            'total_amount' => 240000,
            'status' => Order::STATUS_COMPLETED,
            'customer_name' => $customer1->name,
            'customer_phone' => $customer1->phone,
            'shipping_address' => $customer1->address,
            'notes' => 'Pengiriman cepat via Kurir',
        ]);

        OrderItem::create([
            'order_id' => $orderCash->id,
            'product_id' => $p2->id,
            'quantity' => 2,
            'price' => $p2->price,
            'subtotal' => 240000,
        ]);

        StockMovement::create([
            'product_id' => $p2->id,
            'type' => 'out',
            'quantity' => 2,
            'notes' => 'Penjualan Cash #' . $orderCash->order_number,
            'user_id' => $customer1->id,
        ]);

        // Credit Order (6 Months Credit)
        $orderCredit = Order::create([
            'order_number' => 'RK-KREDIT001',
            'user_id' => $customer2->id,
            'payment_method' => 'credit',
            'credit_tenor_months' => 6,
            'down_payment' => 90000, // 20% of 450,000
            'monthly_installment' => 60000, // 360,000 / 6
            'total_amount' => 450000,
            'status' => Order::STATUS_PENDING_DP,
            'customer_name' => $customer2->name,
            'customer_phone' => $customer2->phone,
            'shipping_address' => $customer2->address,
            'notes' => 'Pembayaran Kredit 6x Angsuran',
        ]);

        OrderItem::create([
            'order_id' => $orderCredit->id,
            'product_id' => $p3->id,
            'quantity' => 3,
            'price' => $p3->price,
            'subtotal' => 450000,
        ]);

        // Simulate admin approval for demo purposes
        $orderCredit->changeStatus(Order::STATUS_ADMIN_APPROVED, $admin, 'Disetujui admin, siap untuk DP Owner');

        StockMovement::create([
            'product_id' => $p3->id,
            'type' => 'out',
            'quantity' => 3,
            'notes' => 'Penjualan Kredit #' . $orderCredit->order_number,
            'user_id' => $customer2->id,
        ]);

        // Create 6 Installment Schedule Records
        for ($i = 1; $i <= 6; $i++) {
            $isPaid = ($i === 1); // 1st installment paid for demo
            Installment::create([
                'order_id' => $orderCredit->id,
                'installment_number' => $i,
                'amount' => 60000,
                'due_date' => Carbon::now()->addMonths($i)->format('Y-m-d'),
                'paid_at' => $isPaid ? Carbon::now()->subDays(5) : null,
                'status' => $isPaid ? 'paid' : 'unpaid',
            ]);
        }
    }
}
