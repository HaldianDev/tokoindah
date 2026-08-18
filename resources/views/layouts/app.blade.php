@php
    $appSettings = \App\Models\WebSetting::getSettings();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appSettings->site_name }} — Hiasan & Peralatan Keramik Rumah Tangga</title>
    
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/js/app-layout.js'])

    <style>
        :root {
            --primary: #0F172A;
            --primary-light: #1E293B;
            --accent: #0284C7;
            --accent-hover: #0369A1;
            --bg-body: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --radius: 16px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* Top Bar Info */
        .top-bar {
            background: linear-gradient(90deg, #090E17 0%, #0F172A 50%, #172554 100%);
            color: #94A3B8;
            font-size: 0.82rem;
            padding: 0.55rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .top-bar-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .top-bar span {
            color: #38BDF8;
            font-weight: 700;
        }

        /* Floating WA */
        .floating-wa {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: var(--transition);
            text-decoration: none;
        }

        .floating-wa:hover {
            transform: scale(1.1) rotate(10deg);
        }

        /* Header & Navbar */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            position: relative;
        }

        .logo {
            font-size: 1.35rem;
            font-weight: 900;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .logo span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.6rem;
            flex-grow: 1; /* Allow the container to grow */
        }

        .nav-menu {
            display: flex;
            gap: 1.6rem;
            list-style: none;
            align-items: center;
            margin: 0 auto; /* Center the menu within the nav-links container */
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.92rem;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: var(--accent);
            transition: var(--transition);
        }

        .nav-menu a:hover::after, .nav-menu a.active::after {
            width: 100%;
        }

        .nav-menu a:hover, .nav-menu a.active {
            color: var(--accent);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .hamburger-menu {
            display: none;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .cart-btn {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.65rem 1.3rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 9px;
            transition: var(--transition);
            font-size: 0.9rem;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.2);
        }

        .cart-btn:hover {
            background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
            border-color: rgba(56,189,248,0.4);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
            transform: translateY(-2px);
        }

        .cart-count {
            background: var(--accent);
            color: white;
            padding: 2px 7px;
            border-radius: 50%;
            font-size: 0.75rem;
        }

        .btn-auth {
            text-decoration: none;
            padding: 0.6rem 1.15rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-login {
            background: #F1F5F9;
            color: var(--primary);
            border: 1px solid var(--border-color);
        }

        .btn-login:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #FFFFFF;
        }

        .btn-register {
            background: var(--accent);
            color: white;
            border: none;
        }

        .btn-register:hover {
            background: var(--accent-hover);
        }

        .role-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-admin { background: #E0F2FE; color: #0369A1; }
        .badge-owner { background: #FEF3C7; color: #B45309; }
        .badge-pembeli { background: #DCFCE7; color: #15803D; }

        /* Flash Alert */
        .alert-box {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
        .alert-danger { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }

        /* Footer */
        footer {
            background: linear-gradient(180deg, #0F172A 0%, #080D1A 100%);
            color: #94A3B8;
            padding: 3.5rem 0 2.5rem;
            font-size: 0.9rem;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.06);
            position: relative;
        }

        footer span {
            color: #38BDF8;
            font-weight: 700;
        }

        /* Modal Overlay & Card styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 1rem;
        }
        .modal-overlay.active {
            display: flex !important;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-close {
            position: absolute;
            top: 1.25rem;
            right: 1.5rem;
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
        }
        .modal-close:hover {
            color: #ef4444;
        }
        
        /* Responsive Navbar */
        @media (max-width: 1024px) {
            .hamburger-menu {
                display: block;
                z-index: 1001;
            }
            .navbar {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 8px 16px rgba(0,0,0,0.1);
                border-top: 1px solid var(--border-color);
                flex-direction: column;
                padding: 1rem 0;
                gap: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            }
            .nav-links.active {
                max-height: 500px; /* Adjust as needed */
                padding: 1rem 0;
            }
            .nav-menu {
                flex-direction: column;
                width: 100%;
                gap: 0;
                margin-bottom: 1rem;
            }
            .nav-menu li {
                width: 100%;
                text-align: center;
            }
            .nav-menu a {
                padding: 0.8rem 1.25rem;
                display: block;
                width: 100%;
            }
             .nav-menu a::after {
                display: none;
            }
            .nav-actions {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
                padding: 0 1.25rem;
            }
            .nav-actions form {
                width: 100%;
            }
            .nav-actions .btn-auth, .nav-actions .cart-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-toast {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.9rem !important;
        }
    </style>
    <script>
        // Global variables for JavaScript
        window.SHIPPING_COST_PER_KG = {{ $appSettings->shipping_cost_per_kg ?: 15000 }};
        window.ORDER_STORE_ROUTE = "{{ route('checkout.process') }}";
    </script>
</head>
<body>

    <!-- Top Bar Info -->
    <div class="top-bar">
        <div class="container top-bar-flex">
            <div>📞 Kontak & Pemesanan: <span>{{ $appSettings->whatsapp_number }}</span></div>
            <div>🚚 Pengiriman Seluruh Wilayah Indonesia & Garansi Ganti Baru 100%</div>
        </div>
    </div>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appSettings->whatsapp_number) }}?text=Halo%20{{ urlencode($appSettings->site_name) }},%20saya%20tertarik%20dengan%20produk%20Anda." class="floating-wa" target="_blank" title="Chat WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- Navbar Header -->
    <header>
        <div class="container navbar">
            <a href="{{ route('home') }}" class="logo">
                @if($appSettings->logo)
                    <img src="{{ $appSettings->logo }}" alt="{{ $appSettings->site_name }}" style="height: 36px; object-fit: contain;">
                @else
                    <i class="fa-solid fa-house-chimney-window" style="color: var(--accent);"></i>
                @endif
                {{ $appSettings->site_name }}
            </a>
            
            <div class="nav-links" id="navLinks">
                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('store.index') }}" class="{{ request()->routeIs('store.index') ? 'active' : '' }}">Katalog</a></li>
                    <li><a href="{{ route('store.about') }}" class="{{ request()->routeIs('store.about') ? 'active' : '' }}">Tentang Kami</a></li>
                    
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fa-solid fa-user-shield"></i> Panel Admin</a></li>
                        @elseif(Auth::user()->isOwner())
                            <li><a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Dashboard Owner</a></li>
                        @else
                            <li><a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}"><i class="fa-solid fa-receipt"></i> Pesanan & Angsuran</a></li>
                        @endif
                    @endauth
                </ul>

                <div class="nav-actions">
                    @guest
                        <a href="{{ route('login') }}" class="btn-auth btn-login"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
                        <a href="{{ route('register') }}" class="btn-auth btn-register"><i class="fa-solid fa-user-plus"></i> Daftar</a>
                    @else
                        @php
                            $dashboardRoute = Auth::user()->isAdmin()
                                ? route('admin.dashboard')
                                : (Auth::user()->isOwner()
                                    ? route('owner.dashboard')
                                    : route('customer.orders'));
                            $settingsRoute = Auth::user()->isAdmin()
                                ? route('admin.settings')
                                : (Auth::user()->isOwner()
                                    ? route('owner.settings')
                                    : route('customer.settings'));
                        @endphp
                        <div class="relative" id="profile-dropdown-container">
                            <button id="profile-dropdown-button" class="flex items-center gap-2">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ $dashboardRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="{{ $settingsRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    {{-- Link to Cart for non-Admin & non-Owner --}}
                    @if(!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isOwner()))
                        <a href="{{ route('cart.index') }}" class="cart-btn">
                            <i class="fa-solid fa-cart-shopping"></i> <span class="hidden sm:inline">Keranjang</span> <span class="cart-count" id="cartCount">0</span>
                        </a>
                    @endif
                </div>
            </div>

            <button class="hamburger-menu" id="hamburgerMenu" aria-label="Toggle Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="grid md:grid-cols-3 gap-8 text-left mb-8 pb-8 border-b border-slate-800">
                <div class="space-y-3">
                    <h3 class="text-white font-extrabold text-lg">{{ $appSettings->site_name }}</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">{{ $appSettings->hero_subtitle ?: 'Pusat galeri dan pengrajin perlengkapan keramik rumah tangga & hiasan artistik berkualitas ekspor.' }}</p>
                </div>
                <div class="space-y-2 text-xs">
                    <h4 class="text-white font-bold text-sm">Navigasi Cepat</h4>
                    <ul class="space-y-1.5 text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-sky-400">Beranda Utama</a></li>
                        <li><a href="{{ route('store.index') }}" class="hover:text-sky-400">Katalog Produk & Belanja</a></li>
                        <li><a href="{{ route('store.about') }}" class="hover:text-sky-400">Tentang Galeri Kami</a></li>
                    </ul>
                </div>
                <div class="space-y-2 text-xs">
                    <h4 class="text-white font-bold text-sm">Alamat & Kontak</h4>
                    <p class="text-slate-400"><i class="fa-solid fa-location-dot text-rose-400 mr-1.5"></i> {{ $appSettings->store_address ?: 'Tulang Bawang, Lampung' }}</p>
                    <p class="text-slate-400"><i class="fa-brands fa-whatsapp text-emerald-400 mr-1.5"></i> WhatsApp: {{ $appSettings->whatsapp_number }}</p>
                    <p class="text-slate-400"><i class="fa-solid fa-envelope text-sky-400 mr-1.5"></i> Email: {{ $appSettings->email }}</p>
                </div>
            </div>
            <p class="text-center text-xs">&copy; {{ date('Y') }} <span>{{ $appSettings->site_name }}</span>. Pusat Peralatan Rumah Tangga & Hiasan Keramik Terlengkap.</p>
        </div>
    </footer>







    </footer>

    <!-- PRODUCT DETAIL MODAL -->
    <div class="modal-overlay" id="productDetailModalOverlay" onclick="closeProductDetail()">
        <div class="modal-card max-w-3xl" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeProductDetail()">&times;</button>
            <div class="grid md:grid-cols-12 gap-6">
                
                <!-- Left: Product Media & Specs -->
                <div class="md:col-span-5 space-y-4">
                    <div class="rounded-2xl overflow-hidden bg-slate-100 h-56 md:h-64 shadow-sm border border-slate-200">
                        <img id="modalProductImage" src="" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span id="modalProductCategory" class="text-[10px] font-bold tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded uppercase"></span>
                            <span id="modalProductWeight" class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded"></span>
                        </div>
                        <h3 id="modalProductName" class="text-lg font-bold text-slate-900 leading-tight"></h3>
                        <p id="modalProductDescription" class="text-xs text-slate-600 leading-relaxed"></p>

                        <div class="border-t border-slate-100 pt-3 space-y-1.5 text-xs">
                            <p class="font-bold text-slate-900">Spesifikasi & Keunggulan:</p>
                            <ul class="space-y-1 text-slate-600">
                                <li id="modalSpec1" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                                <li id="modalSpec2" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                                <li id="modalSpec3" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right: Payment Mode Choice (Cash vs Angsuran) -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                    <div class="space-y-4">
                        <div class="border-b border-slate-100 pb-2">
                            <h4 class="font-black text-slate-900 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-wallet text-sky-600"></i> Pilih Skema Pembayaran Produk
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Pilih bayar sekali lunas (Cash) atau cicilan kredit bulanan.</p>
                        </div>

                        <!-- TAB SELECTION: CASH VS ANGSURAN -->
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="tabModeCash" onclick="switchModalPaymentMode('cash')" class="p-3.5 rounded-2xl border-2 border-sky-600 bg-sky-50 text-left transition-all shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-sky-700">Bayar Tunai</span>
                                    <i class="fa-solid fa-circle-check text-sky-600 text-sm"></i>
                                </div>
                                <p class="text-sm font-black text-slate-900 mt-1" id="modalCashPriceText">Rp 0</p>
                                <span class="text-[10px] text-slate-500">Pembayaran sekali lunas</span>
                            </button>

                            <button type="button" id="tabModeCredit" onclick="switchModalPaymentMode('credit')" class="p-3.5 rounded-2xl border-2 border-slate-200 bg-white text-left hover:border-amber-400 transition-all">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-amber-700">Bayar Angsuran</span>
                                    <i class="fa-solid fa-credit-card text-amber-500 text-sm"></i>
                                </div>
                                <p class="text-sm font-black text-slate-900 mt-1" id="modalCreditDpText">DP 20% (Rp 0)</p>
                                <span class="text-[10px] text-slate-500">Cicilan ringan s/d 12 Bulan</span>
                            </button>
                        </div>

                        <!-- TENOR SELECTOR -->
                        <div id="modalTenorSection" class="hidden p-4 rounded-2xl bg-amber-50 border border-amber-200 space-y-3">
                            <label class="block text-xs font-bold text-amber-900">Pilih Tenor Angsuran Bulanan:</label>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" onclick="selectModalTenor(3, this)" class="modal-tenor-btn active bg-amber-600 text-white font-bold text-xs p-2.5 rounded-xl border border-amber-600 text-center shadow-sm">
                                    3 Bulan
                                </button>
                                <button type="button" onclick="selectModalTenor(6, this)" class="modal-tenor-btn bg-white text-slate-700 font-bold text-xs p-2.5 rounded-xl border border-amber-300 text-center">
                                    6 Bulan
                                </button>
                                <button type="button" onclick="selectModalTenor(12, this)" class="modal-tenor-btn bg-white text-slate-700 font-bold text-xs p-2.5 rounded-xl border border-amber-300 text-center">
                                    12 Bulan
                                </button>
                            </div>

                            <div class="pt-2 border-t border-amber-200 flex justify-between items-center text-xs">
                                <span class="text-amber-800 font-medium">Estimasi Cicilan per Bulan:</span>
                                <span class="font-black text-amber-700 text-sm" id="modalMonthlyInstallmentText">Rp 0 / bln</span>
                            </div>
                        </div>
                    </div>

                    <!-- ADD TO CART BUTTON -->
                    <div id="modalCartAction" class="pt-2">
                        <!-- Dynamic Button via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- CART DRAWER & CHECKOUT SLIDE-OVER -->
<div id="cartDrawerOverlay" onclick="toggleCartDrawer()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9998] hidden"></div>

<div id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-[9999] transition-transform duration-300 translate-x-full flex flex-col justify-between border-l border-slate-200">
    <!-- Header -->
    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                <i class="fa-solid fa-cart-shopping text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base leading-tight">Keranjang & Checkout</h3>
                <p class="text-[11px] text-slate-400">Periksa daftar belanjaan & ongkir kurir</p>
            </div>
        </div>
        <button onclick="toggleCartDrawer()" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">&times;</button>
    </div>

    <!-- Cart Items List -->
    <div class="p-5 flex-1 overflow-y-auto space-y-4" id="cartItemsContainer">
        <!-- Rendered via JS -->
    </div>

    <!-- Checkout Form Footer -->
    <div class="p-5 border-t border-slate-100 bg-slate-50 space-y-3.5 max-h-[60vh] overflow-y-auto">
        <!-- Calculation Summary Box -->
        <div class="space-y-1.5 bg-white p-3.5 rounded-xl border border-slate-200">
            <div class="flex justify-between text-xs text-slate-600">
                <span>Subtotal Barang:</span>
                <span id="cartSubtotalText" class="font-bold text-slate-900">Rp 0</span>
            </div>
            <div class="flex justify-between text-xs text-slate-600">
                <span>Total Berat Barang:</span>
                <span id="cartWeightText" class="font-bold text-indigo-600">0 kg</span>
            </div>
            <div class="flex justify-between text-xs text-slate-600">
                <span>Ongkos Kirim Kurir:</span>
                <span id="cartShippingText" class="font-bold text-slate-900">Rp 0</span>
            </div>
            <div id="downPaymentRow" class="flex justify-between text-xs text-amber-600 font-bold hidden">
                <span>Uang Muka (DP 20%):</span>
                <span id="cartDPText">Rp 0</span>
            </div>
            <div id="monthlyRow" class="flex justify-between text-xs text-sky-600 font-bold hidden">
                <span>Estimasi Cicilan / Bln:</span>
                <span id="cartMonthlyText">Rp 0</span>
            </div>
            <div class="flex justify-between text-base font-black text-slate-900 pt-2 border-t border-slate-100">
                <span>Total Tagihan:</span>
                <span id="cartTotalText" class="text-sky-600 font-black">Rp 0</span>
            </div>
        </div>

        <!-- CHECKOUT FORM -->
        @auth
        <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)" enctype="multipart/form-data" class="space-y-3 pt-1">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Pemesan</label>
                <input type="text" id="custName" required value="{{ Auth::user()->name }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" id="custPhone" required placeholder="0812xxxx" value="{{ Auth::user()->phone }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Metode Bayar</label>
                    <select id="payMethod" name="payment_method" onchange="togglePaymentFields()" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none font-medium">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="credit">Kredit (Cicilan)</option>
                    </select>
                </div>
            </div>

            <!-- Tenor Selection (Visible if Credit) -->
            <div id="tenorWrapper" class="hidden">
                <label class="block text-[11px] font-bold text-amber-600 mb-1">Pilih Tenor Angsuran</label>
                <select id="creditTenor" onchange="updateCartSummary()" class="w-full bg-white border border-amber-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-amber-500 focus:outline-none font-medium">
                    <option value="3">3 Bulan (DP 20%)</option>
                    <option value="6">6 Bulan (DP 20%)</option>
                    <option value="12">12 Bulan (DP 20%)</option>
                </select>
            </div>
            <div id="ktpWrapper" class="hidden" style="margin-top: 0.5rem;">
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Upload KTP (Foto)</label>
                <input type="file" id="ktpFile" name="ktp_file" accept="image/*" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Alamat Pengiriman Lengkap</label>
                <textarea id="custAddress" required rows="2" placeholder="Alamat lengkap jalan, nomor, kecamatan, kota..." class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">{{ Auth::user()->address }}</textarea>
            </div>

            <button type="submit" id="btnSubmitOrder" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0">
                <i class="fa-solid fa-paper-plane"></i> Konfirmasi & Kirim Pesanan
            </button>
        </form>
        @else
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-center space-y-2">
            <p class="text-xs text-amber-800 font-semibold">Silakan masuk ke akun Anda terlebih dahulu untuk memproses pesanan.</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                <i class="fa-solid fa-right-to-bracket"></i> Login Pembeli
            </a>
        </div>
        @endauth
    </div>
</div>

</body>
</html>
