<div class="sidebar-section-label">Navigasi Utama</div>

<button class="sidebar-item" data-section="sec-overview" data-title="Overview Dashboard" data-breadcrumb="Overview"
    onclick="switchSection('sec-overview','Overview Dashboard','Overview')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-chart-pie"></i></div>
    Overview
</button>

<button class="sidebar-item" data-section="sec-products" data-title="Kelola Produk" data-breadcrumb="Kelola Produk"
    onclick="switchSection('sec-products','Kelola Produk','Kelola Produk')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
    Kelola Produk
    @if($outOfStockCount > 0)
        <span class="sidebar-item-badge">{{ $outOfStockCount }}</span>
    @endif
</button>

<button class="sidebar-item" data-section="sec-categories" data-title="Kelola Kategori" data-breadcrumb="Kelola Kategori"
    onclick="switchSection('sec-categories','Kelola Kategori','Kelola Kategori')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-tags"></i></div>
    Kelola Kategori
    <span class="sidebar-item-badge" style="background:#6366F1;">{{ $totalCategories }}</span>
</button>

<button class="sidebar-item" data-section="sec-orders-cash" data-title="Pesanan Cash" data-breadcrumb="Pesanan Cash"
    onclick="switchSection('sec-orders-cash','Pesanan Cash','Pesanan Cash')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
    Pesanan Cash
    @if($totalCashOrders > 0)
        <span class="sidebar-item-badge" style="background:#059669;">{{ $totalCashOrders }}</span>
    @endif
</button>

<button class="sidebar-item" data-section="sec-orders-credit" data-title="Pesanan Credit" data-breadcrumb="Pesanan Credit"
    onclick="switchSection('sec-orders-credit','Pesanan Credit','Pesanan Credit')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-credit-card"></i></div>
    Pesanan Credit
    @if($totalCreditOrders > 0)
        <span class="sidebar-item-badge" style="background:#D97706;">{{ $totalCreditOrders }}</span>
    @endif
</button>

<button class="sidebar-item" data-section="sec-cashier" data-title="Kasir Toko Offline" data-breadcrumb="Kasir"
    onclick="switchSection('sec-cashier','Kasir Toko Offline','Kasir')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-cash-register"></i></div>
    Kasir Offline
</button>

<button class="sidebar-item" data-section="sec-offline-orders" data-title="Riwayat Kasir" data-breadcrumb="Riwayat Kasir"
    onclick="switchSection('sec-offline-orders','Riwayat Kasir Offline','Riwayat Kasir')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-history"></i></div>
    Riwayat Kasir
    @if($totalOfflineOrders > 0)
        <span class="sidebar-item-badge" style="background:#059669;">{{ $totalOfflineOrders }}</span>
    @endif
</button>

<button class="sidebar-item" data-section="sec-stock-log" data-title="Log Stok Masuk" data-breadcrumb="Log Stok Masuk"
    onclick="switchSection('sec-stock-log','Log Stok Masuk','Log Stok Masuk')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
    Log Stok Masuk
</button>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Pengaturan</div>

<button class="sidebar-item" data-section="sec-settings" data-title="Pengaturan Website" data-breadcrumb="Pengaturan"
    onclick="switchSection('sec-settings','Pengaturan Website','Pengaturan')">
    <div class="sidebar-item-icon" style="background:rgba(99,102,241,0.12);color:#818CF8;"><i class="fa-solid fa-gear"></i></div>
    Pengaturan Website
</button>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Aksi Cepat</div>

<button class="sidebar-item" onclick="document.getElementById('addProductModal').classList.add('active')">
    <div class="sidebar-item-icon" style="background:rgba(99,102,241,0.12);color:#818CF8;"><i class="fa-solid fa-plus-circle"></i></div>
    Tambah Produk
</button>

<button class="sidebar-item" onclick="document.getElementById('addStockModal').classList.add('active')">
    <div class="sidebar-item-icon" style="background:rgba(16,185,129,0.12);color:#34D399;"><i class="fa-solid fa-boxes-packing"></i></div>
    Input Stok Masuk
</button>
