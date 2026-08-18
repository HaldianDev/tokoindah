<div class="sidebar-section-label">Menu Utama</div>

<a href="{{ route('customer.orders') }}" class="sidebar-item {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
    <div class="sidebar-item-icon"><i class="fa-solid fa-receipt"></i></div>
    Daftar Pesanan
</a>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Belanja</div>

<a href="{{ route('store.index') }}" class="sidebar-item">
    <div class="sidebar-item-icon" style="background:rgba(56,189,248,0.12);color:#38BDF8;"><i class="fa-solid fa-bag-shopping"></i></div>
    Buka Katalog Belanja
</a>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Pengaturan</div>

<a href="{{ route('customer.settings') }}" class="sidebar-item {{ request()->routeIs('customer.settings') ? 'active' : '' }}">
    <div class="sidebar-item-icon" style="background:rgba(52,211,153,0.12);color:#34D399;"><i class="fa-solid fa-user-cog"></i></div>
    <span>Pengaturan Akun</span>
</a>

<div class="sidebar-divider"></div>
<form method="POST" action="{{ route('logout') }}" class="w-full">
    @csrf
    <button type="submit" class="sidebar-item">
        <div class="sidebar-item-icon" style="background:rgba(239,68,68,0.12);color:#EF4444;"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
        <span>Keluar</span>
    </button>
</form>
