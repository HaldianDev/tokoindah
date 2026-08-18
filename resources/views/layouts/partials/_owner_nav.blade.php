<div class="sidebar-section-label">Monitoring</div>

<button class="sidebar-item" data-section="sec-keuangan" data-title="Ringkasan Keuangan" data-breadcrumb="Keuangan"
    onclick="switchSection('sec-keuangan','Ringkasan Keuangan','Keuangan')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-wallet"></i></div>
    Ringkasan Keuangan
</button>

<button class="sidebar-item" data-section="sec-pesanan-cash" data-title="Pesanan Cash" data-breadcrumb="Pesanan Cash"
    onclick="switchSection('sec-pesanan-cash','Pesanan Cash','Pesanan Cash')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
    Pesanan Cash
    @if($totalCashOrders > 0)
        <span class="sidebar-item-badge" style="background:#059669;">{{ $totalCashOrders }}</span>
    @endif
</button>

<button class="sidebar-item" data-section="sec-pesanan-credit" data-title="Pesanan Credit" data-breadcrumb="Pesanan Credit"
    onclick="switchSection('sec-pesanan-credit','Pesanan Credit','Pesanan Credit')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-credit-card"></i></div>
    Pesanan Credit
    @if($totalCreditOrders > 0)
        <span class="sidebar-item-badge" style="background:#D97706;">{{ $totalCreditOrders }}</span>
    @endif
</button>

<div class="sidebar-divider"></div>
<div class="sidebar-section-label">Analitik</div>

<button class="sidebar-item" data-section="sec-analitik" data-title="Analitik & Grafik" data-breadcrumb="Analitik"
    onclick="switchSection('sec-analitik','Analitik & Grafik Penjualan','Analitik')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-chart-line"></i></div>
    Analitik & Grafik
</button>

<button class="sidebar-item" data-section="sec-barang" data-title="Pergerakan Barang" data-breadcrumb="Pergerakan Barang"
    onclick="switchSection('sec-barang','Pergerakan Barang','Pergerakan Barang')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
    Pergerakan Barang
</button>

<button class="sidebar-item" data-section="sec-offline-sales" data-title="Analisis Toko Offline" data-breadcrumb="Penjualan Offline"
    onclick="switchSection('sec-offline-sales','Analisis Penjualan Offline (Kasir)','Penjualan Offline')">
    <div class="sidebar-item-icon"><i class="fa-solid fa-cash-register"></i></div>
    Analisis Toko Offline
</button>

<div class="sidebar-divider"></div>
