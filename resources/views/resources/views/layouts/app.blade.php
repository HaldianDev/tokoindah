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
        }

        .nav-menu {
            display: flex;
            gap: 1.6rem;
            list-style: none;
            align-items: center;
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
                        <span class="role-badge badge-{{ Auth::user()->role }}">
                            <i class="fa-solid fa-circle-user"></i> {{ ucfirst(Auth::user()->role) }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;" class="needs-confirmation" data-message="Anda yakin ingin keluar?">
                            @csrf
                            <button type="submit" class="btn-auth btn-login" style="cursor: pointer; border: none;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    @endguest

                    {{-- Sembunyikan Keranjang untuk Admin & Owner --}}
                    @if(!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isOwner()))
                        <button class="cart-btn" onclick="toggleCartDrawer()">
                            <i class="fa-solid fa-cart-shopping"></i> <span class="hidden sm:inline">Keranjang</span> <span class="cart-count" id="cartCount">0</span>
                        </button>
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

<script>
localStorage.removeItem('theme');
document.documentElement.classList.remove('dark');

document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('hamburgerMenu');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        const icon = hamburger.querySelector('i');
        if (navLinks.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-xmark');
        } else {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
    });

    // Swal Session Handler
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        })
    @endif
    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        })
    @endif

});
</script>
@stack('scripts')
</body>
</html>
