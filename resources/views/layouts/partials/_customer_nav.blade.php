<div class="sidebar-section-label">Menu Utama</div>

<a href="{{ route('customer.orders') }}" class="sidebar-item active">
    <div class="sidebar-item-icon"><i class="fa-solid fa-receipt"></i></div>
    Daftar Pesanan
    @if(isset($orders) && $orders->total() > 0)
    <span class="sidebar-item-badge" style="background:#0284C7;">{{ $orders->total() }}</span>
    @endif
</a>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Belanja</div>

<a href="{{ route('store.index') }}" class="sidebar-item">
    <div class="sidebar-item-icon" style="background:rgba(56,189,248,0.12);color:#38BDF8;"><i class="fa-solid fa-bag-shopping"></i></div>
    Buka Katalog Belanja
</a>
