<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RumahKeramik Premium Dashboard Showcase</title>
    <!-- Tailwind Play CDN for Instant Gorgeous Compilation -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js and Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="dashboard" x-cloak>

    <!-- TOAST NOTIFICATION -->
    <div class="fixed top-5 right-5 z-[9999] transition-all duration-300 transform"
         x-show="toast.show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-20px] scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-[-20px] scale-95"
         class="max-w-md w-full bg-white shadow-2xl rounded-2xl border border-slate-100 p-4 flex items-center gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
             :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500'">
            <i class="fa-solid text-lg" :class="toast.type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-900" x-text="toast.type === 'success' ? 'Berhasil' : 'Pemberitahuan'"></p>
            <p class="text-xs text-slate-500" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600 transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- ROOT CONTAINER -->
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR (Collapsible, responsive) -->
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 text-slate-400 transition-all duration-300 ease-in-out border-r border-slate-800 lg:static"
               :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full lg:w-20 lg:translate-x-0'">
            
            <!-- Brand Logo Header -->
            <div class="flex items-center justify-between px-5 h-16 bg-slate-950 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-sky-500/20 shrink-0">
                        R
                    </div>
                    <span class="text-white font-black text-lg tracking-tight shrink-0 transition-opacity duration-200"
                          :class="!sidebarOpen && 'lg:opacity-0 lg:pointer-events-none'">
                        Rumah<span class="text-sky-400">Keramik</span>
                    </span>
                </div>
                <!-- Close sidebar mobile button -->
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Profile Info in Sidebar -->
            <div class="p-4 border-b border-slate-800 bg-slate-950/40 shrink-0 overflow-hidden" x-show="sidebarOpen">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr flex items-center justify-center font-bold text-white shadow-md"
                         :class="{
                            'from-emerald-500 to-teal-600': currentRole === 'buyer',
                            'from-purple-500 to-indigo-600': currentRole === 'admin',
                            'from-amber-500 to-amber-600': currentRole === 'owner'
                         }">
                        <span x-text="currentRole === 'buyer' ? 'B' : (currentRole === 'admin' ? 'A' : 'O')"></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white truncate" x-text="currentRole === 'buyer' ? 'Budi Santoso' : (currentRole === 'admin' ? 'Super Admin' : 'Haji Slamet (Owner)')"></p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold mt-1 text-white uppercase tracking-wider"
                              :class="{
                                 'bg-emerald-500/20 text-emerald-400': currentRole === 'buyer',
                                 'bg-purple-500/20 text-purple-400': currentRole === 'admin',
                                 'bg-amber-500/20 text-amber-400': currentRole === 'owner'
                              }"
                              x-text="currentRole">
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <!-- COMMON TITLE -->
                <div class="px-3 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest transition-all duration-300"
                     :class="!sidebarOpen && 'lg:scale-0 lg:opacity-0'">
                    Navigasi <span x-text="currentRole"></span>
                </div>

                <!-- BUYER MENUS -->
                <template x-if="currentRole === 'buyer'">
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <i class="fa-solid fa-receipt text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Daftar Pesanan</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white transition">
                            <i class="fa-solid fa-heart text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Wishlist</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white transition">
                            <i class="fa-solid fa-user-gear text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Profil & Alamat</span>
                        </a>
                    </div>
                </template>

                <!-- ADMIN MENUS -->
                <template x-if="currentRole === 'admin'">
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                            <i class="fa-solid fa-chart-pie text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Overview Admin</span>
                        </a>
                        <button @click="showAddProductModal = true" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white text-left transition">
                            <i class="fa-solid fa-plus-circle text-lg text-purple-400"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Tambah Produk</span>
                        </button>
                        <button @click="showAddStockModal = true" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white text-left transition">
                            <i class="fa-solid fa-boxes-packing text-lg text-emerald-400"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Input Stok Masuk</span>
                        </button>
                    </div>
                </template>

                <!-- OWNER MENUS -->
                <template x-if="currentRole === 'owner'">
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            <i class="fa-solid fa-wallet text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Finansial Owner</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white transition">
                            <i class="fa-solid fa-chart-line text-lg"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Analitik Penjualan</span>
                        </a>
                        <button @click="simulateExport('pdf')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-800 hover:text-white text-left transition">
                            <i class="fa-solid fa-file-pdf text-lg text-rose-400"></i>
                            <span :class="!sidebarOpen && 'lg:hidden'">Cetak Laporan PDF</span>
                        </button>
                    </div>
                </template>

                <!-- GENERAL PUBLIC CATALOG LINK -->
                <div class="pt-6">
                    <a href="{{ route('store.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-800 hover:text-slate-300 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span :class="!sidebarOpen && 'lg:hidden'">Kembali ke Toko</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0 text-center" x-show="sidebarOpen">
                <span class="text-[10px] text-slate-500">RumahKeramik v1.2</span>
            </div>
        </aside>

        <!-- MAIN LAYOUT CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- TOP HEADER WITH COLLAPSE TOGGLE, ROLE SWITCHER -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 shrink-0 shadow-sm relative z-30">
                <div class="flex items-center gap-4">
                    <!-- Collapse toggle button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <!-- Brand (small screen) -->
                    <span class="lg:hidden text-slate-900 font-extrabold text-lg">Rumah<span class="text-sky-500">Keramik</span></span>
                </div>

                <!-- TOPBAR INTERACTIVE ACTIONS & ROLE SWITCHER -->
                <div class="flex items-center gap-2 lg:gap-4">
                    
                    <!-- ROLE SWITCHER CONTAINER -->
                    <div class="relative bg-slate-100 p-1.5 rounded-2xl flex items-center gap-1">
                        <span class="hidden md:inline text-xs font-bold text-slate-500 px-2">Role:</span>
                        
                        <button @click="switchRole('buyer')"
                                class="px-3 py-1.5 rounded-xl text-xs font-extrabold tracking-wide transition duration-200"
                                :class="currentRole === 'buyer' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-600 hover:text-slate-950'">
                            <span class="md:hidden">🛍️</span>
                            <span class="hidden md:inline">🛍️ Pembeli</span>
                        </button>
                        
                        <button @click="switchRole('admin')"
                                class="px-3 py-1.5 rounded-xl text-xs font-extrabold tracking-wide transition duration-200"
                                :class="currentRole === 'admin' ? 'bg-purple-500 text-white shadow-md' : 'text-slate-600 hover:text-slate-950'">
                            <span class="md:hidden">⚡</span>
                            <span class="hidden md:inline">⚡ Admin</span>
                        </button>
                        
                        <button @click="switchRole('owner')"
                                class="px-3 py-1.5 rounded-xl text-xs font-extrabold tracking-wide transition duration-200"
                                :class="currentRole === 'owner' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:text-slate-950'">
                            <span class="md:hidden">👑</span>
                            <span class="hidden md:inline">👑 Owner</span>
                        </button>
                    </div>

                    <!-- PROFILE DROPDOWN (Decorative but beautiful) -->
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition cursor-pointer">
                        <i class="fa-regular fa-bell text-slate-600 text-lg"></i>
                    </div>
                </div>
            </header>

            <!-- CONTAINER FOR CONTENT SECTIONS -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">

                <!-- ============================================ -->
                <!-- 1. PEMBELI (BUYER) DASHBOARD                 -->
                <!-- ============================================ -->
                <div x-show="currentRole === 'buyer'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    <!-- WELCOME HEADER -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 lg:p-8 text-white shadow-xl shadow-emerald-950/10">
                        <div class="max-w-xl">
                            <span class="bg-white/20 text-white text-[10px] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-full">Dashboard Pembeli</span>
                            <h2 class="text-2xl lg:text-3xl font-black mt-3">Halo, Budi Santoso! 👋</h2>
                            <p class="text-sm text-emerald-100 mt-2">Selamat datang kembali! Periksa status pesanan keramik premium Anda dan tagihan angsuran berjalan secara real-time di bawah ini.</p>
                        </div>
                    </div>

                    <!-- SHOPPING SUMMARY CARDS -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Belanja</span>
                                <p class="text-2xl font-black text-slate-900 mt-1">Rp 27.200.000</p>
                                <span class="text-xs text-emerald-500 font-bold mt-2 inline-block"><i class="fa-solid fa-circle-check"></i> 4 Transaksi</span>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Cicilan Sisa (Tenor)</span>
                                <p class="text-2xl font-black text-amber-500 mt-1">Rp 12.500.000</p>
                                <span class="text-xs text-slate-500 font-bold mt-2 inline-block"><i class="fa-solid fa-clock"></i> Sisa 6 bulan berjalan</span>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Metode Pembayaran Utama</span>
                                <p class="text-2xl font-black text-sky-500 mt-1">Kredit/Cicilan</p>
                                <span class="text-xs text-slate-500 font-bold mt-2 inline-block"><i class="fa-solid fa-shield-halved"></i> Terverifikasi Keuangan</span>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                        </div>
                    </div>

                    <!-- ORDER STATUS TRACKER (PROGRESS BAR) -->
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pelacak Status Pesanan Terakhir</h3>
                                <p class="text-xs text-slate-400">Order #ORD001 — Keramik Granit Premium 60x60</p>
                            </div>
                            <span class="bg-indigo-50 text-indigo-600 text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">Dalam Pengiriman</span>
                        </div>

                        <!-- Progress Bar Nodes -->
                        <div class="relative py-4">
                            <!-- Horizontal track line -->
                            <div class="absolute top-[2.4rem] left-4 right-4 h-1 bg-slate-100 -translate-y-1/2 z-0"></div>
                            <!-- Filled track line -->
                            <div class="absolute top-[2.4rem] left-4 h-1 bg-gradient-to-r from-emerald-500 to-indigo-500 -translate-y-1/2 z-0 transition-all duration-500" style="width: 80%;"></div>

                            <!-- Step nodes -->
                            <div class="relative z-10 flex justify-between items-center text-center">
                                <!-- Step 1 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/20">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-800 mt-2">Dibuat</span>
                                    <span class="text-[9px] text-slate-400">16 Agst, 09:00</span>
                                </div>
                                <!-- Step 2 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/20">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-800 mt-2">Verifikasi</span>
                                    <span class="text-[9px] text-slate-400">16 Agst, 10:15</span>
                                </div>
                                <!-- Step 3 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/20">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-800 mt-2">Diproses</span>
                                    <span class="text-[9px] text-slate-400">16 Agst, 13:00</span>
                                </div>
                                <!-- Step 4 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-500/20 ring-4 ring-indigo-50">
                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-indigo-600 mt-2">Dikirim</span>
                                    <span class="text-[9px] text-slate-400">16 Agst, 15:45</span>
                                </div>
                                <!-- Step 5 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold border-2 border-slate-200">
                                        <i class="fa-solid fa-house-circle-check"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 mt-2">Selesai</span>
                                    <span class="text-[9px] text-slate-400">Estimasi Besok</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TRANSACTION HISTORY TABLE -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Riwayat Pembelian & Angsuran</h3>
                                <p class="text-xs text-slate-400">Daftar lengkap transaksi yang Anda lakukan secara offline maupun online.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                                    <i class="fa-solid fa-filter me-1.5"></i> Filter Tanggal
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Item Barang</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Metode Bayar</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="order in orders" :key="order.id">
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="py-4 px-6 font-bold text-slate-950" x-text="'#' + order.id"></td>
                                            <td class="py-4 px-6 text-slate-500 text-xs" x-text="order.date"></td>
                                            <td class="py-4 px-6">
                                                <p class="font-semibold text-slate-900 text-sm" x-text="order.items"></p>
                                                <span class="text-[10px] text-slate-400">RumahKeramik Premium</span>
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                                                      :class="order.method.includes('Cicilan') ? 'bg-amber-50 text-amber-600' : 'bg-sky-50 text-sky-600'">
                                                    <i class="fa-solid text-[10px]" :class="order.method.includes('Cicilan') ? 'fa-credit-card' : 'fa-money-bill-wave'"></i>
                                                    <span x-text="order.method"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 font-bold text-slate-900" x-text="'Rp ' + order.total.toLocaleString('id-ID')"></td>
                                            <td class="py-4 px-6">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                                                      :class="{
                                                         'bg-emerald-50 text-emerald-600': order.status === 'completed',
                                                         'bg-indigo-50 text-indigo-600': order.status === 'shipping',
                                                         'bg-purple-50 text-purple-600': order.status === 'processing',
                                                         'bg-amber-50 text-amber-600': order.status === 'waiting_payment'
                                                      }">
                                                    <span class="w-1.5 h-1.5 rounded-full" 
                                                          :class="{
                                                             'bg-emerald-500': order.status === 'completed',
                                                             'bg-indigo-500': order.status === 'shipping',
                                                             'bg-purple-500': order.status === 'processing',
                                                             'bg-amber-500': order.status === 'waiting_payment'
                                                          }"></span>
                                                    <span x-text="order.status === 'completed' ? 'Selesai' : (order.status === 'shipping' ? 'Dikirim' : (order.status === 'processing' ? 'Diproses' : 'Menunggu Bayar'))"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <button @click="showToast('Detail pesanan #' + order.id + ' akan dibuka!', 'success')"
                                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition">
                                                    Lihat Detail
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- 2. ADMIN DASHBOARD                           -->
                <!-- ============================================ -->
                <div x-show="currentRole === 'admin'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    <!-- ADMIN ACTIONS ROW -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Operasional & Stok</h2>
                            <p class="text-xs text-slate-400">Kelola katalog produk, input stok masuk, serta kelola verifikasi pesanan.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showAddProductModal = true" class="px-4 py-2 bg-purple-600 text-white font-extrabold rounded-xl hover:bg-purple-700 transition flex items-center gap-2 shadow-md shadow-purple-500/10">
                                <i class="fa-solid fa-plus-circle"></i> Tambah Produk Baru
                            </button>
                            <button @click="showAddStockModal = true" class="px-4 py-2 bg-emerald-600 text-white font-extrabold rounded-xl hover:bg-emerald-700 transition flex items-center gap-2 shadow-md shadow-emerald-500/10">
                                <i class="fa-solid fa-boxes-packing"></i> Input Stok Masuk
                            </button>
                        </div>
                    </div>

                    <!-- LOW STOCK ALERT PANEL (Elegant but prominent) -->
                    <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-2xl border border-rose-100 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4"
                         x-show="lowStockProducts.length > 0">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/10 shrink-0">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-rose-950">Peringatan: Stok Menipis!</h4>
                                <p class="text-xs text-rose-700 mt-1">Ada <span class="font-bold" x-text="lowStockProducts.length"></span> produk dengan persediaan kritis di bawah batas minimal (5 unit). Harap segera input stok masuk!</p>
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <button @click="showAddStockModal = true" class="px-3.5 py-1.5 bg-rose-600 text-white text-xs font-extrabold rounded-lg hover:bg-rose-700 transition">
                                Restock Sekarang
                            </button>
                        </div>
                    </div>

                    <!-- PRODUCTS & INVENTORY TABLE WITH REALTIME FILTER / SEARCH -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Katalog Stok Barang</h3>
                                <p class="text-xs text-slate-400">Total terdaftar <span x-text="products.length" class="font-bold"></span> item produk keramik.</p>
                            </div>
                            
                            <!-- Search & Category filter -->
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <!-- Search bar -->
                                <div class="relative w-full sm:w-64">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                    </span>
                                    <input type="text" 
                                           x-model="searchProductQuery"
                                           placeholder="Cari produk keramik..." 
                                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-50">
                                </div>
                                <!-- Category Pill Switcher -->
                                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl w-full sm:w-auto">
                                    <template x-for="cat in categories" :key="cat">
                                        <button @click="selectedCategory = cat"
                                                class="px-2.5 py-1.5 rounded-lg text-[11px] font-extrabold transition"
                                                :class="selectedCategory === cat ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                                x-text="cat">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Grid/Table list -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Jual</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Saat Ini</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status Stok</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi Cepat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="product in filteredProducts" :key="product.id">
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <img :src="product.image" alt="tile image" class="w-10 h-10 rounded-lg object-cover shrink-0 border border-slate-100">
                                                    <div>
                                                        <p class="font-bold text-slate-900 text-sm" x-text="product.name"></p>
                                                        <span class="text-[10px] text-slate-400" x-text="'SKU: RK-' + String(product.id).padStart(4, '0')"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700" x-text="product.category"></span>
                                            </td>
                                            <td class="py-4 px-6 font-bold text-slate-900" x-text="'Rp ' + product.price.toLocaleString('id-ID') + ' /m²'"></td>
                                            <td class="py-4 px-6 font-extrabold text-slate-900" x-text="product.stock + ' Dus'"></td>
                                            <td class="py-4 px-6 text-center">
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold"
                                                      :class="{
                                                         'bg-emerald-50 text-emerald-600': product.stock > 10,
                                                         'bg-amber-50 text-amber-600': product.stock > 4 && product.stock <= 10,
                                                         'bg-rose-50 text-rose-600': product.stock <= 4
                                                      }"
                                                      x-text="product.stock > 10 ? 'Stok Aman' : (product.stock > 4 ? 'Terbatas' : 'Kritis (Restock)')">
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button @click="stockToUpdate.productId = product.id; showAddStockModal = true"
                                                            class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-emerald-600 transition" 
                                                            title="Tambah Stok">
                                                        <i class="fa-solid fa-plus-circle"></i>
                                                    </button>
                                                    <button @click="showToast('Ubah detail produk ' + product.name, 'success')"
                                                            class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-indigo-600 transition"
                                                            title="Edit Detail">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ADMIN ORDERS TRACKER / APPROVAL QUEUE -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="text-lg font-black text-slate-900">Konfirmasi Status & Pengiriman Pesanan</h3>
                            <p class="text-xs text-slate-400">Verifikasi manual bukti pembayaran dan jalankan aksi update status kirim produk.</p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Item Barang</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Metode</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Sekarang</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi Konfirmasi Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="order in orders" :key="'admin-' + order.id">
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="py-4 px-6 font-bold text-slate-900" x-text="'#' + order.id"></td>
                                            <td class="py-4 px-6">
                                                <p class="font-semibold text-slate-900 text-xs" x-text="order.items"></p>
                                                <span class="text-[10px] text-slate-400" x-text="order.date"></span>
                                            </td>
                                            <td class="py-4 px-6 font-bold text-slate-900" x-text="'Rp ' + order.total.toLocaleString('id-ID')"></td>
                                            <td class="py-4 px-6">
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700" x-text="order.method"></span>
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                                                      :class="{
                                                         'bg-emerald-50 text-emerald-600': order.status === 'completed',
                                                         'bg-indigo-50 text-indigo-600': order.status === 'shipping',
                                                         'bg-purple-50 text-purple-600': order.status === 'processing',
                                                         'bg-amber-50 text-amber-600': order.status === 'waiting_payment'
                                                      }">
                                                    <span class="w-1.5 h-1.5 rounded-full" 
                                                          :class="{
                                                             'bg-emerald-500': order.status === 'completed',
                                                             'bg-indigo-500': order.status === 'shipping',
                                                             'bg-purple-500': order.status === 'processing',
                                                             'bg-amber-500': order.status === 'waiting_payment'
                                                          }"></span>
                                                    <span x-text="order.status === 'completed' ? 'Selesai' : (order.status === 'shipping' ? 'Dikirim' : (order.status === 'processing' ? 'Diproses' : 'Menunggu Bayar'))"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <!-- Wait state to processing -->
                                                    <button @click="updateOrderStatus(order.id, 'processing')"
                                                            class="px-2.5 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-lg transition"
                                                            :disabled="order.status !== 'waiting_payment'"
                                                            :class="order.status !== 'waiting_payment' && 'opacity-40 cursor-not-allowed'">
                                                        Approve
                                                    </button>
                                                    <!-- Processing to shipping -->
                                                    <button @click="updateOrderStatus(order.id, 'shipping')"
                                                            class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition"
                                                            :disabled="order.status !== 'processing'"
                                                            :class="order.status !== 'processing' && 'opacity-40 cursor-not-allowed'">
                                                        Ship
                                                    </button>
                                                    <!-- Shipping to completed -->
                                                    <button @click="updateOrderStatus(order.id, 'completed')"
                                                            class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg transition"
                                                            :disabled="order.status !== 'shipping'"
                                                            :class="order.status !== 'shipping' && 'opacity-40 cursor-not-allowed'">
                                                        Selesaikan
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- 3. OWNER DASHBOARD                           -->
                <!-- ============================================ -->
                <div x-show="currentRole === 'owner'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    <!-- EXECUTIVE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kinerja Finansial & Analitik Eksekutif</h2>
                            <p class="text-xs text-slate-400">Monitoring omset bulanan, realisasi margin laba bersih, serta ringkasan piutang kredit toko.</p>
                        </div>
                        
                        <!-- SIMULATED EXPORT CONTROLS -->
                        <div class="flex items-center gap-2">
                            <button @click="simulateExport('excel')" 
                                    :disabled="exporting"
                                    class="px-4 py-2 border border-slate-200 bg-white text-slate-700 font-extrabold text-xs rounded-xl hover:bg-slate-50 transition flex items-center gap-2 disabled:opacity-50">
                                <span x-show="exporting && exportType==='EXCEL'"><i class="fa-solid fa-spinner animate-spin"></i> Exporting...</span>
                                <span x-show="!exporting || exportType!=='EXCEL'"><i class="fa-solid fa-file-excel text-emerald-600"></i> Export Excel</span>
                            </button>
                            <button @click="simulateExport('pdf')" 
                                    :disabled="exporting"
                                    class="px-4 py-2 border border-slate-200 bg-white text-slate-700 font-extrabold text-xs rounded-xl hover:bg-slate-50 transition flex items-center gap-2 disabled:opacity-50">
                                <span x-show="exporting && exportType==='PDF'"><i class="fa-solid fa-spinner animate-spin"></i> Exporting...</span>
                                <span x-show="!exporting || exportType!=='PDF'"><i class="fa-solid fa-file-pdf text-rose-600"></i> Export PDF</span>
                            </button>
                        </div>
                    </div>

                    <!-- REVENUE & NET PROFIT CARDS -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Revenue -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between relative overflow-hidden">
                            <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 text-emerald-500/5 text-9xl font-black select-none pointer-events-none">Rp</div>
                            <div class="space-y-1">
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Revenue (Omset)</span>
                                <h3 class="text-2xl lg:text-3xl font-black text-slate-900">Rp 198.500.000</h3>
                                <p class="text-xs text-emerald-600 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> +12.4% dibanding bulan lalu</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-inner shrink-0">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                        </div>

                        <!-- Net Profit -->
                        <div class="bg-gradient-to-tr from-sky-50 to-indigo-50 p-6 rounded-3xl border border-sky-100 shadow-sm flex items-center justify-between relative overflow-hidden">
                            <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 text-indigo-500/5 text-9xl font-black select-none pointer-events-none">%</div>
                            <div class="space-y-1">
                                <span class="text-xs text-indigo-500 font-bold uppercase tracking-wider">Estimasi Net Profit (Bersih)</span>
                                <h3 class="text-2xl lg:text-3xl font-black text-indigo-950">Rp 48.750.000</h3>
                                <p class="text-xs text-indigo-600 font-semibold"><i class="fa-solid fa-chart-line"></i> Margin 24.5% laba bersih</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500 text-white flex items-center justify-center text-2xl shadow-lg shadow-indigo-500/20 shrink-0">
                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                            </div>
                        </div>

                        <!-- Outstanding credit -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between relative overflow-hidden">
                            <div class="space-y-1">
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Piutang Angsuran Aktif</span>
                                <h3 class="text-2xl lg:text-3xl font-black text-slate-900">Rp 75.320.000</h3>
                                <p class="text-xs text-amber-600 font-semibold"><i class="fa-solid fa-clock-rotate-left"></i> Tertunda pada 12 pembeli</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shrink-0">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <!-- CHART.JS AREA -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Line Chart Penjualan (2/3 width) -->
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm lg:col-span-2 space-y-4 flex flex-col justify-between h-[420px]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-black text-slate-900">Grafik Penjualan Bulanan (Cash vs Kredit)</h3>
                                    <p class="text-xs text-slate-400">Total volume penjualan terkonsolidasi dari platform offline dan online.</p>
                                </div>
                                <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-3 py-1.5 rounded-xl"><i class="fa-regular fa-calendar-check"></i> Periode 2026</span>
                            </div>
                            <!-- Canvas container -->
                            <div class="relative flex-1 min-h-[300px]">
                                <canvas id="salesChartCanvas"></canvas>
                            </div>
                        </div>

                        <!-- Top-Selling Products List (1/3 width) -->
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between h-[420px]">
                            <div>
                                <h3 class="text-base font-black text-slate-900">Produk Terlaris (Top-Selling)</h3>
                                <p class="text-xs text-slate-400 mb-4">Volume order m² terbanyak bulan ini.</p>
                                
                                <div class="space-y-4">
                                    <!-- item 1 -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                            <span class="truncate">Keramik Granit Premium 60x60</span>
                                            <span>450 m² (45%)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-sky-500 to-indigo-600 h-full rounded-full" style="width: 45%;"></div>
                                        </div>
                                    </div>
                                    <!-- item 2 -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                            <span class="truncate">Keramik Mozaik Dapur Pastel</span>
                                            <span>320 m² (32%)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-sky-500 to-indigo-600 h-full rounded-full" style="width: 32%;"></div>
                                        </div>
                                    </div>
                                    <!-- item 3 -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                            <span class="truncate">Keramik Polished Putih 80x80</span>
                                            <span>180 m² (18%)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-sky-500 to-indigo-600 h-full rounded-full" style="width: 18%;"></div>
                                        </div>
                                    </div>
                                    <!-- item 4 -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                            <span class="truncate">Semen Premium & Tile Grout</span>
                                            <span>50 unit (5%)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-sky-500 to-indigo-600 h-full rounded-full" style="width: 5%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button @click="showToast('Analisis produk lengkap sedang dimuat...', 'success')"
                                    class="w-full py-2.5 rounded-xl border border-slate-200 text-xs font-extrabold text-slate-700 hover:bg-slate-50 transition mt-4">
                                Lihat Analisis Produk Lengkap
                            </button>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODALS SECTION                              -->
    <!-- ============================================ -->

    <!-- MODAL 1: TAMBAH PRODUK BARU -->
    <div class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-show="showAddProductModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4"
             @click.away="showAddProductModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">Tambah Produk Keramik Baru</h3>
                <button @click="showAddProductModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <div class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Produk</label>
                    <input type="text" x-model="newProduct.name" placeholder="Contoh: Keramik Mozaik Hijau Tosca" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select x-model="newProduct.category" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                            <option value="Granit">Granit</option>
                            <option value="Mozaik">Mozaik</option>
                            <option value="Polished">Polished</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Stok Awal (Dus)</label>
                        <input type="number" x-model="newProduct.stock" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Harga Per m² (Rupiah)</label>
                    <input type="number" x-model="newProduct.price" placeholder="Contoh: 185000" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button @click="showAddProductModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                <button @click="addProduct" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 transition">Simpan Produk</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: INPUT STOK MASUK -->
    <div class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-show="showAddStockModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4"
             @click.away="showAddStockModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">Input Log Stok Masuk</h3>
                <button @click="showAddStockModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <div class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Produk</label>
                    <select x-model="stockToUpdate.productId" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                        <template x-for="p in products" :key="p.id">
                            <option :value="p.id" x-text="p.name + ' (Stok: ' + p.stock + ')'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Jumlah Dus Ditambahkan</label>
                    <input type="number" x-model="stockToUpdate.amount" placeholder="Contoh: 50" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button @click="showAddStockModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                <button @click="addStock" class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition">Tambah Stok</button>
            </div>
        </div>
    </div>

    <!-- JS GLOBAL LOGIC -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboard', () => ({
                currentRole: 'admin', // default dashboard role is admin
                sidebarOpen: true,
                
                // Buyer orders with tracking details
                orders: [
                    { id: 'ORD001', date: '16 Agu 2026', items: 'Keramik Granit Premium 60x60', total: 12500000, method: 'Cicilan 6x', status: 'shipping', progress: 80 },
                    { id: 'ORD002', date: '12 Agu 2026', items: 'Keramik Mozaik Dapur Pastel', total: 4200000, method: 'Cash', status: 'completed', progress: 100 },
                    { id: 'ORD003', date: '15 Agu 2026', items: 'Keramik Polished Putih 80x80', total: 8700000, method: 'Cicilan 12x', status: 'processing', progress: 50 },
                    { id: 'ORD004', date: '16 Agu 2026', items: 'Semen Premium Perekat Keramik', total: 1800000, method: 'Cash', status: 'waiting_payment', progress: 20 },
                ],
                
                // Admin products
                products: [
                    { id: 1, name: 'Keramik Granit Premium 60x60', category: 'Granit', stock: 5, price: 250000, image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=150&q=80' },
                    { id: 2, name: 'Keramik Mozaik Dapur Pastel', category: 'Mozaik', stock: 12, price: 180000, image: 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=150&q=80' },
                    { id: 3, name: 'Keramik Polished Putih 80x80', category: 'Polished', stock: 2, price: 340000, image: 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=150&q=80' },
                    { id: 4, name: 'Semen Premium Perekat Keramik', category: 'Aksesoris', stock: 45, price: 85000, image: 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?auto=format&fit=crop&w=150&q=80' },
                ],
                
                categories: ['Semua', 'Granit', 'Mozaik', 'Polished', 'Aksesoris'],
                selectedCategory: 'Semua',
                searchProductQuery: '',
                
                // Admin modals & form state
                showAddProductModal: false,
                showAddStockModal: false,
                newProduct: { name: '', category: 'Granit', stock: 0, price: 0 },
                stockToUpdate: { productId: 1, amount: null },
                
                // Owner state
                exporting: false,
                exportType: '',
                
                // Toast notification state
                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },
                
                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3500);
                },
                
                init() {
                    // Initialize the sales chart on startup
                    this.initChart();
                },
                
                // Chart.js Manager
                salesChart: null,
                initChart() {
                    this.$nextTick(() => {
                        const canvas = document.getElementById('salesChartCanvas');
                        if (!canvas) return;
                        
                        if (this.salesChart) {
                            this.salesChart.destroy();
                        }
                        
                        const ctx = canvas.getContext('2d');
                        this.salesChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                                datasets: [
                                    {
                                        label: 'Omset Cash (Rp)',
                                        data: [15000000, 22000000, 18000000, 28000000, 35000000, 29000000, 42000000, 48000000],
                                        borderColor: '#6366f1',
                                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 3,
                                        pointRadius: 4,
                                        pointBackgroundColor: '#6366f1'
                                    },
                                    {
                                        label: 'Omset Kredit (Rp)',
                                        data: [10000000, 15000000, 12000000, 20000000, 25000000, 21000000, 30000000, 38000000],
                                        borderColor: '#f59e0b',
                                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 3,
                                        pointRadius: 4,
                                        pointBackgroundColor: '#f59e0b'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            font: {
                                                family: 'Plus Jakarta Sans',
                                                weight: 'bold',
                                                size: 11
                                            },
                                            usePointStyle: true,
                                            padding: 15
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            font: { family: 'Plus Jakarta Sans', weight: 'semibold', size: 10 },
                                            callback: function(value) {
                                                return 'Rp ' + (value / 1000000) + ' Jt';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                },
                
                // Switch roles cleanly
                switchRole(role) {
                    this.currentRole = role;
                    if (role === 'owner') {
                        this.initChart();
                    }
                    this.showToast(`Berhasil beralih ke Mode: ${role.toUpperCase()}`, 'success');
                },
                
                // Admin Actions: Approve / Ship / Complete orders
                updateOrderStatus(orderId, nextStatus) {
                    const order = this.orders.find(o => o.id === orderId);
                    if (order) {
                        order.status = nextStatus;
                        if (nextStatus === 'completed') {
                            order.progress = 100;
                        } else if (nextStatus === 'shipping') {
                            order.progress = 80;
                        } else if (nextStatus === 'processing') {
                            order.progress = 50;
                        }
                        this.showToast(`Status Pesanan #${orderId} berhasil diupdate ke: ${nextStatus.toUpperCase()}`, 'success');
                    }
                },
                
                // Add product logic
                addProduct() {
                    if (!this.newProduct.name || this.newProduct.price <= 0) {
                        this.showToast('Lengkapi nama dan harga produk!', 'error');
                        return;
                    }
                    const newId = this.products.length + 1;
                    this.products.push({
                        id: newId,
                        name: this.newProduct.name,
                        category: this.newProduct.category,
                        stock: parseInt(this.newProduct.stock) || 0,
                        price: parseInt(this.newProduct.price),
                        image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=150&q=80'
                    });
                    this.showAddProductModal = false;
                    this.showToast('Produk baru berhasil ditambahkan ke katalog!', 'success');
                    this.newProduct = { name: '', category: 'Granit', stock: 0, price: 0 };
                },
                
                // Add stock logic
                addStock() {
                    const p = this.products.find(prod => prod.id == this.stockToUpdate.productId);
                    if (p && this.stockToUpdate.amount > 0) {
                        p.stock += parseInt(this.stockToUpdate.amount);
                        this.showToast(`Berhasil menambah +${this.stockToUpdate.amount} dus stok untuk ${p.name}!`, 'success');
                        this.showAddStockModal = false;
                        this.stockToUpdate = { productId: 1, amount: null };
                    } else {
                        this.showToast('Masukkan jumlah stok yang valid!', 'error');
                    }
                },
                
                // Owner simulate export
                simulateExport(format) {
                    this.exporting = true;
                    this.exportType = format.toUpperCase();
                    setTimeout(() => {
                        this.exporting = false;
                        this.showToast(`Unduhan Laporan ${this.exportType} berhasil disimulasikan!`, 'success');
                    }, 1800);
                },
                
                // Computed properties
                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesCat = this.selectedCategory === 'Semua' || p.category === this.selectedCategory;
                        const matchesSearch = p.name.toLowerCase().includes(this.searchProductQuery.toLowerCase());
                        return matchesCat && matchesSearch;
                    });
                },
                
                get lowStockProducts() {
                    return this.products.filter(p => p.stock <= 5);
                }
            }));
        });
    </script>
</body>
</html>