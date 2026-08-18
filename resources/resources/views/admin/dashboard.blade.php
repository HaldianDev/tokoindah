@extends('layouts.dashboard')

@section('page_title', 'Panel Admin')
@section('role_label', '⚡ Administrator')
@section('role_color', '#F472B6')
@section('avatar_gradient', '#7C3AED, #A855F7')

@section('sidebar_nav')
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
@endsection



@section('content')

{{-- ============================================ --}}
{{-- SECTION 1: OVERVIEW                          --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-overview">
    <div class="section-header">
        <div>
            <h1 class="section-title">Overview Dashboard Admin</h1>
            <p class="section-subtitle">Ringkasan real-time sistem toko RumahKeramik</p>
        </div>
        <span class="badge badge-emerald" style="padding:6px 14px;font-size:0.78rem;">
            <i class="fa-solid fa-circle" style="font-size:8px;animation:pulse 2s infinite;"></i> Live
        </span>
    </div>

    <!-- Stats Grid -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Produk</div>
                <div class="stat-card-value">{{ $totalProducts }}</div>
                <div class="stat-card-sub" style="color:#0284C7;"><i class="fa-solid fa-arrow-trend-up"></i> Katalog aktif</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#2563EB;">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Pesanan Pending</div>
                <div class="stat-card-value" style="color:#D97706;">{{ $pendingOrders }}</div>
                <div class="stat-card-sub" style="color:#D97706;"><i class="fa-solid fa-clock-rotate-left"></i> Perlu tindakan owner</div>
            </div>
            <div class="stat-card-icon" style="background:#FFFBEB;color:#D97706;">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Stok Habis</div>
                <div class="stat-card-value" style="color:#EF4444;">{{ $outOfStockCount }}</div>
                <div class="stat-card-sub" style="color:#EF4444;"><i class="fa-solid fa-triangle-exclamation"></i> Perlu restock</div>
            </div>
            <div class="stat-card-icon" style="background:#FEF2F2;color:#EF4444;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
    </div>

    <!-- Order Summary Row -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card" style="border-color:#A7F3D0;background:linear-gradient(135deg,#ECFDF5,#fff);">
            <div>
                <div class="stat-card-label" style="color:#059669;">Pesanan Cash</div>
                <div class="stat-card-value" style="color:#059669;">{{ $totalCashOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-card" style="border-color:#FDE68A;background:linear-gradient(135deg,#FFFBEB,#fff);">
            <div>
                <div class="stat-card-label" style="color:#D97706;">Pesanan Credit</div>
                <div class="stat-card-value" style="color:#D97706;">{{ $totalCreditOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-credit-card"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Selesai</div>
                <div class="stat-card-value" style="color:#059669;">{{ $completedOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-circle-check"></i></div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-chart-column"></i></div>
                Statistik Ringkasan Sistem
            </div>
            <span class="badge badge-gray">Real-time Data</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="height:280px;position:relative;">
                <canvas id="adminStatsChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 2: KELOLA PRODUK                     --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-products">
    <div class="section-header">
        <div>
            <h1 class="section-title">Kelola Produk & Stok</h1>
            <p class="section-subtitle">Daftar seluruh produk yang terdaftar di etalase toko</p>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
            <button onclick="document.getElementById('addStockModal').classList.add('active')" class="btn-action btn-success">
                <i class="fa-solid fa-boxes-packing"></i> Input Stok
            </button>
            <button onclick="document.getElementById('addProductModal').classList.add('active')" class="btn-action btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </button>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-boxes-stacked"></i></div>
                Daftar Produk ({{ $products->total() }} item)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Berat</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>
                            <img src="{{ $p->image }}" style="width:44px;height:44px;object-fit:cover;border-radius:10px;border:1px solid #E2E8F0;" alt="{{ $p->name }}">
                        </td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;">{{ $p->name }}</span>
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $p->category->name ?? '-' }}</span>
                        </td>
                        <td style="font-weight:700;color:#0284C7;">Rp {{ number_format($p->price,0,',','.') }}</td>
                        <td>
                            <span style="font-weight:800;color:{{ $p->stock <= 0 ? '#EF4444' : '#059669' }};">
                                {{ $p->stock }} Unit
                            </span>
                        </td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ number_format($p->weight ?? 0) }}g</td>
                        <td>
                            @if($p->stock > 0)
                                <span class="badge badge-emerald">Ready</span>
                            @else
                                <span class="badge badge-rose">Habis</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:inline-flex;gap:6px;align-items:center;">
                                <a href="{{ route('admin.product.edit', $p->id) }}" class="btn-action btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.product.destroy', $p->id) }}" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-danger btn-sm btn-icon" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                            Belum ada produk terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION: KELOLA KATEGORI                     --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-categories">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-tags" style="color:#6366F1;"></i> Kelola Kategori</h1>
            <p class="section-subtitle">Buat dan kelola kategori produk untuk pengelompokan yang rapi di etalase toko</p>
        </div>
        <button onclick="document.getElementById('addCategoryModal').classList.add('active')" class="btn-action btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#EEF2FF;color:#6366F1;"><i class="fa-solid fa-tags"></i></div>
                Daftar Kategori ({{ $categoriesList->total() }} kategori)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Jumlah Produk</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoriesList as $cat)
                    <tr>
                        <td><i class="{{ $cat->icon ?? 'fa-solid fa-layer-group' }}" style="font-size:1.1rem;color:#6366F1;"></i></td>
                        <td style="font-weight:700;color:#0F172A;">{{ $cat->name }}</td>
                        <td style="font-family:monospace;font-size:0.8rem;color:#64748B;">{{ $cat->slug }}</td>
                        <td>
                            <span class="badge badge-indigo">{{ $cat->products_count }} produk</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:inline-flex;gap:6px;align-items:center;">
                                <button onclick="editCategory({{ $cat->id }}, '{{ e($cat->name) }}', '{{ e($cat->icon) }}')" class="btn-action btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.category.destroy', $cat->id) }}" onsubmit="return confirm('Yakin hapus kategori ini? Kategori hanya bisa dihapus jika tidak memiliki produk.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-danger btn-sm btn-icon" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-tags" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                            Belum ada kategori. Buat kategori terlebih dahulu sebelum menambah produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categoriesList->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $categoriesList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 3: PESANAN CASH                      --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-orders-cash">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-money-bill-wave" style="color:#059669;"></i> Pesanan Cash</h1>
            <p class="section-subtitle">Daftar pesanan cash (otomatis selesai saat pembayaran tunai)</p>
        </div>
        <span class="badge badge-emerald" style="padding:6px 14px;font-size:0.78rem;">
            {{ $totalCashOrders }} Transaksi
        </span>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-money-bill-wave"></i></div>
                Daftar Pesanan Cash ({{ $cashOrders->total() }} transaksi)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashOrders as $ord)
                    <tr style="{{ $ord->status === 'verifying_payment' ? 'background: #FFFBEB;' : '' }}">
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">{{ $ord->order_number }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td style="font-weight:700;color:#059669;">Rp {{ number_format($ord->total_amount,0,',','.') }}</td>
                        <td>
                            @if($ord->status === 'completed')
                                <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @elseif($ord->status === 'verifying_payment')
                                <span class="badge badge-amber"><i class="fa-solid fa-user-shield"></i> Verifikasi Bukti</span>
                            @elseif($ord->status === 'processing')
                                <span class="badge badge-indigo"><i class="fa-solid fa-gear"></i> Diproses</span>
                            @elseif($ord->status === 'waiting_payment')
                                <span class="badge badge-rose"><i class="fa-solid fa-clock"></i> Menunggu Pembayaran</span>
                            @else
                                <span class="badge badge-gray">{{ str_replace('_', ' ', Str::title($ord->status)) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($ord->status === 'verifying_payment')
                                <div style="display:flex; gap:8px; justify-content:center; align-items:center;">
                                    <a href="{{ asset('storage/' . $ord->payment_proof_path) }}" target="_blank" class="btn-action btn-outline btn-sm">
                                        <i class="fa-solid fa-eye"></i> Lihat Bukti
                                    </a>
                                    <form action="{{ route('admin.order.approve_payment', $ord) }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini?')">
                                        @csrf
                                        <button type="submit" class="btn-action btn-success btn-sm"><i class="fa-solid fa-check"></i> Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.order.reject_payment', $ord) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini?')">
                                        @csrf
                                        <button type="submit" class="btn-action btn-danger btn-sm"><i class="fa-solid fa-xmark"></i> Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span style="color:#94A3B8; font-size:0.8rem; font-style:italic;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-receipt" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada pesanan cash.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cashOrders->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $cashOrders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 4: PESANAN CREDIT                    --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-orders-credit">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-credit-card" style="color:#D97706;"></i> Pesanan Credit</h1>
            <p class="section-subtitle">Daftar pesanan kredit (status dikelola langsung oleh Owner)</p>
        </div>
        <span class="badge badge-amber" style="padding:6px 14px;font-size:0.78rem;">
            {{ $totalCreditOrders }} Transaksi
        </span>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#FFFBEB;color:#D97706;"><i class="fa-solid fa-credit-card"></i></div>
                Daftar Pesanan Kredit ({{ $creditOrders->total() }} transaksi)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pemesan</th>
                        <th>Total / Angsuran</th>
                        <th>Tenor</th>
                        <th>Status</th>
                        <th style="text-align:center;">Detail Cicilan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($creditOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">{{ $ord->order_number }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">Rp {{ number_format($ord->total_amount,0,',','.') }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">Cicilan: Rp {{ number_format($ord->monthly_installment,0,',','.') }}/bln</span>
                        </td>
                        <td>
                            <span class="badge badge-amber"><i class="fa-solid fa-credit-card"></i> {{ $ord->credit_tenor_months }}x</span>
                        </td>
                        <td>
                            @if($ord->status === 'completed') <span class="badge badge-emerald">Completed</span>
                            @elseif($ord->status === 'approved') <span class="badge badge-sky">Approved</span>
                            @elseif($ord->status === 'processing') <span class="badge badge-indigo">Processing</span>
                            @elseif($ord->status === 'cancelled') <span class="badge badge-rose">Cancelled</span>
                            @else <span class="badge badge-amber">Pending</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($ord->installments->isNotEmpty())
                                <button onclick="viewInstallmentsModal({{ $ord->id }})"
                                    class="btn-action btn-outline btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                    Cicilan ({{ $ord->installments->where('status','paid')->count() }}/{{ $ord->installments->count() }})
                                </button>
                            @else
                                <span style="font-size:0.78rem;color:#94A3B8;font-style:italic;">Tidak ada cicilan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-credit-card" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada pesanan kredit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($creditOrders->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $creditOrders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 5: KASIR OFFLINE                     --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-cashier">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-cash-register" style="color:#0284C7;"></i> Kasir Toko Offline</h1>
            <p class="section-subtitle">Transaksi penjualan langsung di toko offline RumahKeramik.</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:3fr 2fr;gap:1.5rem;align-items:start;" class="cashier-grid">
        <!-- Kolom Kiri: Katalog Produk Kasir -->
        <div class="table-card" style="padding:1.25rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap;">
                <input type="text" id="cashierSearch" placeholder="🔍 Cari produk..." class="form-control" onkeyup="filterCashierProducts()" style="max-width:300px;flex:1;">
                <select id="cashierCategoryFilter" onchange="filterCashierProducts()" class="form-control" style="max-width:200px;">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="cashierProductGrid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:1rem;max-height:600px;overflow-y:auto;padding-right:4px;">
                @forelse($cashierProducts as $prod)
                <div class="cashier-product-card" data-name="{{ strtolower($prod->name) }}" data-category="{{ $prod->category->name ?? '' }}"
                    style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;overflow:hidden;padding:0.75rem;display:flex;flex-direction:column;gap:6px;transition:all 0.2s;">
                    <img src="{{ $prod->image }}" style="width:100%;height:110px;object-fit:cover;border-radius:8px;border:1px solid #E2E8F0;" alt="{{ $prod->name }}">
                    <div style="display:flex;flex-direction:column;flex:1;">
                        <span style="font-size:0.68rem;font-weight:700;color:#0284C7;text-transform:uppercase;">{{ $prod->category->name ?? 'Umum' }}</span>
                        <h4 style="font-size:0.85rem;font-weight:800;color:#0F172A;margin:2px 0;line-height:1.2;min-height:32px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $prod->name }}</h4>
                        <div style="font-weight:800;color:#059669;font-size:0.9rem;margin-top:auto;">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
                        <div style="font-size:0.75rem;color:#64748B;margin-top:2px;">Stok: <strong>{{ $prod->stock }} unit</strong></div>
                    </div>
                    <button onclick="addProductToCashier({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->price }}, {{ $prod->stock }})"
                        class="btn-action btn-primary btn-sm" style="width:100%;justify-content:center;margin-top:6px;padding:6px 0;">
                        <i class="fa-solid fa-cart-plus"></i> Tambah
                    </button>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#94A3B8;">
                    <i class="fa-solid fa-box-open" style="font-size:2rem;"></i>
                    <p style="margin-top:8px;">Tidak ada produk siap jual.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang & Kalkulator -->
        <div class="table-card" style="padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;">
            <div style="font-weight:800;font-size:1rem;color:#0F172A;border-bottom:1px solid #F1F5F9;padding-bottom:0.75rem;display:flex;align-items:center;justify-content:space-between;">
                <span>🛒 Keranjang Belanja</span>
                <span id="cashierCartCount" class="badge badge-emerald" style="border-radius:50px;padding:4px 10px;">0 Item</span>
            </div>

            <!-- List Cart Items -->
            <div id="cashierCartItems" style="max-height:260px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;padding-right:4px;min-height:100px;">
                <div style="text-align:center;padding:2rem;color:#94A3B8;font-style:italic;font-size:0.85rem;">
                    Keranjang masih kosong.
                </div>
            </div>

            <!-- Detail Pelanggan -->
            <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:8px;">
                <div style="font-weight:700;font-size:0.8rem;color:#334155;margin-bottom:2px;"><i class="fa-solid fa-user-tag"></i> Data Pembeli (Opsional)</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <div>
                        <input type="text" id="cashierCustomerName" placeholder="Nama Pelanggan" class="form-control" style="font-size:0.8rem;padding:6px 10px;">
                    </div>
                    <div>
                        <input type="text" id="cashierCustomerPhone" placeholder="No. HP (08xxx)" class="form-control" style="font-size:0.8rem;padding:6px 10px;">
                    </div>
                </div>
            </div>

            <!-- Panel Kalkulator Pembayaran -->
            <div style="background:#0F172A;color:#fff;border-radius:14px;padding:1.25rem;display:flex;flex-direction:column;gap:10px;box-shadow:0 8px 20px rgba(15,23,42,0.15);">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:0.85rem;color:#94A3B8;">TOTAL BELANJA</span>
                    <span id="cashierTotalAmountLabel" style="font-size:1.4rem;font-weight:900;color:#38BDF8;">Rp 0</span>
                </div>
                <div style="height:1px;background:rgba(255,255,255,0.1);margin:4px 0;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                    <span style="font-size:0.82rem;color:#94A3B8;white-space:nowrap;">UANG DITERIMA</span>
                    <input type="text" id="cashierCashPaid" placeholder="0" oninput="formatRupiahInput(this); calculateCashierChange();" class="form-control"
                        style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;text-align:right;font-size:1rem;font-weight:800;max-width:180px;padding:5px 10px;">
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:0.82rem;color:#94A3B8;">KEMBALIAN</span>
                    <span id="cashierChangeLabel" style="font-size:1.15rem;font-weight:800;color:#34D399;">Rp 0</span>
                </div>
            </div>

            <button onclick="checkoutCashier()" class="btn-action btn-success" style="width:100%;justify-content:center;padding:0.75rem 0;font-size:0.95rem;border-radius:12px;font-weight:800;box-shadow:0 4px 12px rgba(5,150,105,0.25);">
                <i class="fa-solid fa-print"></i> Proses Transaksi & Cetak Struk
            </button>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 6: RIWAYAT KASIR OFFLINE             --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-offline-orders">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-history" style="color:#059669;"></i> Riwayat Kasir Offline</h1>
            <p class="section-subtitle">Daftar transaksi penjualan langsung di kasir toko offline.</p>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#E6F4EA;color:#059669;"><i class="fa-solid fa-receipt"></i></div>
                Daftar Transaksi Offline ({{ $offlineOrders->total() }} transaksi)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Struk</th>
                        <th>Waktu</th>
                        <th>Pelanggan</th>
                        <th>Daftar Belanja</th>
                        <th>Total Pembayaran</th>
                        <th style="text-align:center;">Cetak Ulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offlineOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">#{{ $ord->order_number }}</td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ $ord->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                @foreach($ord->items as $item)
                                <span style="font-size:0.8rem;color:#334155;">
                                    <i class="fa-solid fa-chevron-right" style="font-size:8px;color:#94A3B8;"></i>
                                    {{ $item->product->name ?? 'Produk Dihapus' }} (x{{ $item->quantity }})
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td style="font-weight:800;color:#059669;">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                        <td style="text-align:center;">
                            <button onclick="reprintOfflineOrder(@json($ord))"
                                class="btn-action btn-outline btn-sm btn-icon" title="Cetak Ulang Struk">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-file-invoice" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada transaksi kasir offline toko yang tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($offlineOrders->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $offlineOrders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ====== KASIR RECEIPT MODAL ====== --}}
<div class="modal-overlay" id="cashierReceiptModal" style="z-index: 11000;">
    <div class="modal-card" style="max-width:380px; font-family:'Courier New', Courier, monospace; color:#000; padding:1.5rem; background:#fff; border-radius:8px;">
        <button class="modal-close no-print" onclick="document.getElementById('cashierReceiptModal').classList.remove('active')">&times;</button>
        <div id="print-receipt-area" style="width:100%; font-size: 0.85rem; line-height: 1.3;">
            <!-- Rendered dynamically by Javascript -->
        </div>
        <div style="margin-top:1.5rem; display:flex; flex-direction:column; gap:10px;" class="no-print">
            <div style="display:flex; gap:10px;">
                <button onclick="printCashierReceipt()" class="btn-action btn-success" style="flex:1; justify-content:center;">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </button>
                <button onclick="sendReceiptToWhatsApp()" class="btn-action btn-primary" style="flex:1; justify-content:center; background:#25D366;">
                    <i class="fa-brands fa-whatsapp"></i> Kirim WA
                </button>
            </div>
            <button onclick="document.getElementById('cashierReceiptModal').classList.remove('active')" class="btn-action btn-outline" style="width:100%; justify-content:center;">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 7: LOG STOK MASUK                    --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-stock-log">
    <div class="section-header">
        <div>
            <h1 class="section-title">Log Stok Masuk</h1>
            <p class="section-subtitle">Riwayat penambahan stok barang dari pasokan pabrik</p>
        </div>
        <button onclick="document.getElementById('addStockModal').classList.add('active')" class="btn-action btn-success">
            <i class="fa-solid fa-plus"></i> Input Stok Baru
        </button>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                Histori Stok Masuk
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Inputor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockMovements->where('type','in') as $sm)
                    <tr>
                        <td style="font-family:monospace;font-size:0.78rem;color:#64748B;">{{ $sm->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight:700;color:#0F172A;">{{ $sm->product->name ?? '-' }}</td>
                        <td>
                            <span style="font-weight:800;color:#059669;">+{{ $sm->quantity }} Unit</span>
                        </td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ $sm->notes ?? '-' }}</td>
                        <td style="font-weight:600;color:#334155;">{{ $sm->user->name ?? 'Sistem' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-box-archive" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada log stok masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockMovements->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $stockMovements->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 8: PENGATURAN WEBSITE                --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-settings">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-gear" style="color:#6366F1;"></i> Pengaturan Website</h1>
            <p class="section-subtitle">Kelola informasi toko, logo, konten beranda, halaman tentang kami, dan tarif ongkir</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- Informasi Toko --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(99,102,241,0.1);color:#6366F1;"><i class="fa-solid fa-store"></i></div>
                    Informasi Toko
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Nama Toko / Brand <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="site_name" class="form-control" required value="{{ old('site_name', $settings->site_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="whatsapp_number" class="form-control" required value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="6281234567890">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Alamat Toko</label>
                    <textarea name="store_address" rows="2" class="form-control">{{ old('store_address', $settings->store_address) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarif Ongkir per Kg (Rp) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="shipping_cost_per_kg" class="form-control" required min="0" value="{{ old('shipping_cost_per_kg', $settings->shipping_cost_per_kg) }}">
                    <small style="color:#64748B;font-size:0.72rem;">Tarif dasar per kilogram untuk kalkulasi ongkos kirim kurir.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Logo Toko</label>
                    <input type="file" name="logo_file" accept="image/*" class="form-control" style="padding:0.4rem;">
                    @if($settings->logo)
                    <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                        <img src="{{ $settings->logo }}" style="height:36px;border-radius:6px;" alt="Logo saat ini">
                        <span style="font-size:0.72rem;color:#64748B;">Logo saat ini</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Hero Beranda --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-home"></i></div>
                    Konten Beranda (Hero Section)
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Judul Hero</label>
                    <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $settings->hero_title) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Subtitle Hero</label>
                    <textarea name="hero_subtitle" rows="2" class="form-control">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                </div>
            </div>
        </div>

        {{-- About Us --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-info-circle"></i></div>
                    Konten Tentang Kami
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Judul About</label>
                    <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $settings->about_title) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Deskripsi About</label>
                    <textarea name="about_description" rows="3" class="form-control">{{ old('about_description', $settings->about_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Visi</label>
                    <textarea name="about_vision" rows="2" class="form-control">{{ old('about_vision', $settings->about_vision) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Misi</label>
                    <textarea name="about_mission" rows="2" class="form-control">{{ old('about_mission', $settings->about_mission) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar About</label>
                    <input type="file" name="about_file" accept="image/*" class="form-control" style="padding:0.4rem;">
                    @if($settings->about_image)
                    <div style="margin-top:6px;">
                        <img src="{{ $settings->about_image }}" style="height:60px;border-radius:8px;" alt="About Image">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:1rem;">
            <button type="submit" class="btn-action btn-primary" style="padding:0.75rem 2rem;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>

{{-- Modals --}}
@include('admin.partials._add_product_modal')
@include('admin.partials._add_stock_modal')

{{-- Add Category Modal --}}
<div class="modal-overlay" id="addCategoryModal">
    <div class="modal-card" style="max-width:480px;">
        <button class="modal-close" onclick="document.getElementById('addCategoryModal').classList.remove('active')">&times;</button>
        <div style="border-bottom:1px solid #E2E8F0;padding-bottom:0.8rem;margin-bottom:1.2rem;">
            <h3 style="font-weight:800;color:#0F172A;font-size:1.1rem;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-tags" style="color:#6366F1;"></i> Tambah Kategori Baru
            </h3>
        </div>
        <form method="POST" action="{{ route('admin.category.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700;font-size:0.82rem;display:block;margin-bottom:4px;color:#0F172A;">Nama Kategori <span style="color:#EF4444;">*</span></label>
                <input type="text" name="name" required class="form-control" style="width:100%;padding:0.65rem;" placeholder="Contoh: Piring, Vas Bunga, Keramik Lantai">
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-weight:700;font-size:0.82rem;display:block;margin-bottom:4px;color:#0F172A;">Icon (Font Awesome Class)</label>
                <input type="text" name="icon" class="form-control" style="width:100%;padding:0.65rem;" placeholder="fa-solid fa-layer-group" value="fa-solid fa-layer-group">
                <small style="color:#64748B;font-size:0.72rem;">Contoh: fa-solid fa-mug-hot, fa-solid fa-bowl-rice</small>
            </div>
            <button type="submit" class="btn-action btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">
                <i class="fa-solid fa-plus"></i> Simpan Kategori
            </button>
        </form>
    </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal-overlay" id="editCategoryModal">
    <div class="modal-card" style="max-width:480px;">
        <button class="modal-close" onclick="document.getElementById('editCategoryModal').classList.remove('active')">&times;</button>
        <div style="border-bottom:1px solid #E2E8F0;padding-bottom:0.8rem;margin-bottom:1.2rem;">
            <h3 style="font-weight:800;color:#0F172A;font-size:1.1rem;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-pen" style="color:#D97706;"></i> Edit Kategori
            </h3>
        </div>
        <form method="POST" id="editCategoryForm">
            @csrf @method('PUT')
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700;font-size:0.82rem;display:block;margin-bottom:4px;color:#0F172A;">Nama Kategori <span style="color:#EF4444;">*</span></label>
                <input type="text" name="name" id="editCategoryName" required class="form-control" style="width:100%;padding:0.65rem;">
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-weight:700;font-size:0.82rem;display:block;margin-bottom:4px;color:#0F172A;">Icon (Font Awesome Class)</label>
                <input type="text" name="icon" id="editCategoryIcon" class="form-control" style="width:100%;padding:0.65rem;">
            </div>
            <button type="submit" class="btn-action btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">
                <i class="fa-solid fa-floppy-disk"></i> Update Kategori
            </button>
        </form>
    </div>
</div>

{{-- Installment Modal (Admin) --}}
<div class="modal-overlay" id="installmentModal">
    <div class="modal-card" style="max-width:640px;">
        <button class="modal-close" onclick="document.getElementById('installmentModal').classList.remove('active')">&times;</button>
        <div style="border-bottom:1px solid #E2E8F0;padding-bottom:1rem;margin-bottom:1.25rem;">
            <h3 style="font-weight:800;font-size:1.05rem;color:#0F172A;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-credit-card" style="color:#F59E0B;"></i> Detail Cicilan Kredit
            </h3>
            <p id="instModalTitle" style="font-size:0.8rem;color:#64748B;margin-top:4px;"></p>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Angsuran</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="instModalBody"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ====== OVERVIEW CHART ======
    const ctx = document.getElementById('adminStatsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Produk', 'Pesanan Pending', 'Stok Habis', 'Pesanan Cash', 'Pesanan Credit', 'Selesai'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $totalProducts }}, {{ $pendingOrders }}, {{ $outOfStockCount }}, {{ $totalCashOrders }}, {{ $totalCreditOrders }}, {{ $completedOrders }}],
                    backgroundColor: [
                        'rgba(99,102,241,0.75)', 'rgba(245,158,11,0.75)', 'rgba(244,63,94,0.75)',
                        'rgba(5,150,105,0.75)', 'rgba(217,119,6,0.75)', 'rgba(34,197,94,0.75)'
                    ],
                    borderColor: [
                        'rgb(99,102,241)', 'rgb(245,158,11)', 'rgb(244,63,94)',
                        'rgb(5,150,105)', 'rgb(217,119,6)', 'rgb(34,197,94)'
                    ],
                    borderWidth: 2,
                    borderRadius: 10,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(156,163,175,0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});

// ====== INSTALLMENT MODAL ======
const ordersData = @json($recentOrders->keyBy('id'));

function viewInstallmentsModal(orderId) {
    const ord = ordersData[orderId];
    if (!ord) return;

    document.getElementById('instModalTitle').textContent =
        `Pesanan #${ord.order_number} — ${ord.customer_name} (${ord.customer_phone})`;

    let html = '';
    if (ord.installments && ord.installments.length > 0) {
        ord.installments.forEach(inst => {
            const statusBadge = inst.status === 'paid'
                ? '<span class="badge badge-emerald">Lunas</span>'
                : inst.status === 'overdue'
                    ? '<span class="badge badge-rose">Jatuh Tempo</span>'
                    : '<span class="badge badge-amber">Belum Bayar</span>';

            const due = new Date(inst.due_date).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});
            html += `<tr>
                <td style="font-weight:700;">Bulan Ke-${inst.installment_number}</td>
                <td style="font-weight:700;color:#D97706;">Rp ${inst.amount.toLocaleString('id-ID')}</td>
                <td style="font-size:0.82rem;color:#64748B;">${due}</td>
                <td>${statusBadge}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="4" style="text-align:center;color:#94A3B8;padding:1.5rem;">Tidak ada data cicilan.</td></tr>';
    }

    document.getElementById('instModalBody').innerHTML = html;
    document.getElementById('installmentModal').classList.add('active');
}

// ====== EDIT CATEGORY MODAL ======
function editCategory(id, name, icon) {
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryIcon').value = icon;
    document.getElementById('editCategoryForm').action = '/admin/category/' + id;
    document.getElementById('editCategoryModal').classList.add('active');
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});

// ====== FORMAT RUPIAH INPUT (with dots) ======
function formatRupiahInput(el) {
    let val = el.value.replace(/\D/g, '');
    if (val === '') {
        el.value = '';
        return;
    }
    el.value = parseInt(val, 10).toLocaleString('id-ID');
}

function parseRupiahValue(str) {
    if (!str) return 0;
    return parseInt(str.replace(/\./g, '').replace(/\D/g, ''), 10) || 0;
}

// ====== CASHIER OFFLINE JAVASCRIPT ======
let cashierCart = [];
let lastReceiptOrder = null;
let lastReceiptCashPaid = 0;

function addProductToCashier(productId, name, price, maxStock) {
    const existing = cashierCart.find(item => item.product_id === productId);
    if (existing) {
        if (existing.quantity >= maxStock) {
            alert(`Stok produk '${name}' tidak mencukupi untuk ditambah lagi.`);
            return;
        }
        existing.quantity += 1;
    } else {
        cashierCart.push({
            product_id: productId,
            name: name,
            price: price,
            quantity: 1,
            max_stock: maxStock
        });
    }
    renderCashierCart();
}

function updateCashierQuantity(productId, newQty) {
    const item = cashierCart.find(item => item.product_id === productId);
    if (!item) return;

    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty < 1) {
        newQty = 1;
    }

    if (newQty > item.max_stock) {
        alert(`Stok produk '${item.name}' tidak mencukupi (Stok maksimal: ${item.max_stock}).`);
        newQty = item.max_stock;
    }

    item.quantity = newQty;
    renderCashierCart();
}

function removeCashierProduct(productId) {
    cashierCart = cashierCart.filter(item => item.product_id !== productId);
    renderCashierCart();
}

function renderCashierCart() {
    const cartContainer = document.getElementById('cashierCartItems');
    const cartCountBadge = document.getElementById('cashierCartCount');
    const totalLabel = document.getElementById('cashierTotalAmountLabel');

    if (cashierCart.length === 0) {
        cartContainer.innerHTML = `
            <div style="text-align:center;padding:2rem;color:#94A3B8;font-style:italic;font-size:0.85rem;">
                Keranjang masih kosong.
            </div>
        `;
        cartCountBadge.textContent = '0 Item';
        totalLabel.textContent = 'Rp 0';
        calculateCashierChange();
        return;
    }

    let totalAmount = 0;
    let totalItemsCount = 0;
    let html = '';

    cashierCart.forEach(item => {
        const subtotal = item.price * item.quantity;
        totalAmount += subtotal;
        totalItemsCount += item.quantity;

        html += `
            <div style="display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;padding:0.75rem;border-radius:10px;border:1px solid #E2E8F0;gap:10px;">
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:0.82rem;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</div>
                    <div style="font-size:0.75rem;color:#64748B;">Rp ${item.price.toLocaleString('id-ID')}</div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <input type="number" min="1" max="${item.max_stock}" value="${item.quantity}"
                        onchange="updateCashierQuantity(${item.product_id}, this.value)"
                        style="width:50px;text-align:center;padding:3px;font-size:0.8rem;border:1px solid #CBD5E1;border-radius:6px;font-weight:700;">
                    <button onclick="removeCashierProduct(${item.product_id})"
                        style="background:none;border:none;color:#EF4444;cursor:pointer;font-size:0.9rem;padding:4px;">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
                <div style="font-weight:800;color:#0284C7;font-size:0.82rem;min-width:80px;text-align:right;">
                    Rp ${subtotal.toLocaleString('id-ID')}
                </div>
            </div>
        `;
    });

    cartContainer.innerHTML = html;
    cartCountBadge.textContent = `${totalItemsCount} Item`;
    totalLabel.textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
    calculateCashierChange();
}

function calculateCashierChange() {
    const totalAmount = cashierCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const cashPaidInput = document.getElementById('cashierCashPaid');
    const changeLabel = document.getElementById('cashierChangeLabel');

    let cashPaid = parseRupiahValue(cashPaidInput.value);
    if (isNaN(cashPaid) || cashPaid <= 0) {
        changeLabel.textContent = 'Rp 0';
        changeLabel.style.color = '#34D399';
        return;
    }

    const change = cashPaid - totalAmount;
    changeLabel.textContent = `Rp ${change.toLocaleString('id-ID')}`;
    if (change < 0) {
        changeLabel.textContent = `Kurang Rp ${Math.abs(change).toLocaleString('id-ID')}`;
        changeLabel.style.color = '#EF4444';
    } else {
        changeLabel.style.color = '#34D399';
    }
}

function filterCashierProducts() {
    const searchVal = document.getElementById('cashierSearch').value.toLowerCase();
    const catVal = document.getElementById('cashierCategoryFilter').value;
    const cards = document.querySelectorAll('.cashier-product-card');

    cards.forEach(card => {
        const name = card.dataset.name;
        const cat = card.dataset.category;

        const matchesSearch = name.includes(searchVal);
        const matchesCat = catVal === '' || cat === catVal;

        if (matchesSearch && matchesCat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function checkoutCashier() {
    if (cashierCart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }

    const totalAmount = cashierCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const cashPaidInput = document.getElementById('cashierCashPaid');
    const cashPaid = parseRupiahValue(cashPaidInput.value);

    if (isNaN(cashPaid) || cashPaid < totalAmount) {
        alert('Nominal uang yang diterima kurang dari total belanja!');
        return;
    }

    const customerName = document.getElementById('cashierCustomerName').value || 'Pelanggan Umum';
    const customerPhone = document.getElementById('cashierCustomerPhone').value || '-';

    const data = {
        customer_name: customerName,
        customer_phone: customerPhone,
        cash_received: cashPaid,
        cart_items: cashierCart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity
        }))
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("admin.cashier.checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            lastReceiptCashPaid = cashPaid;
            lastReceiptOrder = res.order;
            renderReceiptContent(res.order, cashPaid);
            document.getElementById('cashierReceiptModal').classList.add('active');
            
            cashierCart = [];
            document.getElementById('cashierCustomerName').value = '';
            document.getElementById('cashierCustomerPhone').value = '';
            document.getElementById('cashierCashPaid').value = '';
            renderCashierCart();
        } else {
            alert('Error: ' + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat memproses checkout.');
    });
}

function renderReceiptContent(order, cashPaid) {
    const printArea = document.getElementById('print-receipt-area');
    const dateStr = new Date(order.created_at).toLocaleDateString('id-ID', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });

    let itemsHtml = '';
    let receiptTextItems = '';
    order.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        itemsHtml += `
            <div style="display:flex; justify-content:space-between; margin-bottom: 2px;">
                <span>${item.product.name} (x${item.quantity})</span>
                <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>
        `;
        receiptTextItems += `${item.product.name} x${item.quantity} = Rp ${subtotal.toLocaleString('id-ID')}\n`;
    });

    const change = cashPaid - order.total_amount;

    @php
        $storeName = $settings->site_name ?? 'RUMAH KERAMIK';
        $storeAddr = $settings->store_address ?? 'Jl. Raya Keramik No. 88, Lampung';
        $storeWA   = $settings->whatsapp_number ?? '6281234567890';
    @endphp

    printArea.innerHTML = `
        <div style="text-align:center; margin-bottom:1rem;">
            <h3 style="font-size:1.1rem; font-weight:bold; margin:0;">{{ $storeName }}</h3>
            <p style="margin:2px 0 0 0; font-size:0.75rem;">{{ $storeAddr }}</p>
            <p style="margin:1px 0 0 0; font-size:0.75rem;">WA: {{ $storeWA }}</p>
        </div>
        <div style="border-top:1px dashed #000; border-bottom:1px dashed #000; padding:4px 0; margin-bottom:8px; font-size:0.78rem;">
            <div>No. Struk: ${order.order_number}</div>
            <div>Waktu    : ${dateStr}</div>
            <div>Kasir    : {{ Auth::user()->name }}</div>
            <div>Pelanggan: ${order.customer_name} (${order.customer_phone})</div>
        </div>
        <div style="margin-bottom:8px; font-size:0.78rem;">
            ${itemsHtml}
        </div>
        <div style="border-top:1px dashed #000; padding-top:4px; font-size:0.78rem;">
            <div style="display:flex; justify-content:space-between; font-weight:bold;">
                <span>TOTAL BELANJA</span>
                <span>Rp ${order.total_amount.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>UANG BAYAR</span>
                <span>Rp ${cashPaid.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-weight:bold; color:#000;">
                <span>KEMBALIAN</span>
                <span>Rp ${change.toLocaleString('id-ID')}</span>
            </div>
        </div>
        <div style="text-align:center; margin-top:1.5rem; font-size:0.75rem;">
            * Terima Kasih Atas Kunjungan Anda *<br>
            Barang yang sudah dibeli tidak dapat ditukar
        </div>
    `;
}

function printCashierReceipt() {
    window.print();
}

function sendReceiptToWhatsApp() {
    if (!lastReceiptOrder) {
        alert('Tidak ada data struk untuk dikirim.');
        return;
    }

    const order = lastReceiptOrder;
    const cashPaid = lastReceiptCashPaid;
    const change = cashPaid - order.total_amount;
    const phone = order.customer_phone && order.customer_phone !== '-' ? order.customer_phone.replace(/^0/, '62') : '';

    let items = '';
    order.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        items += `▪ ${item.product.name} (x${item.quantity}) = Rp ${subtotal.toLocaleString('id-ID')}\n`;
    });

    const text = `🧾 *STRUK BELANJA - {{ $storeName }}*\n` +
        `📍 {{ $storeAddr }}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        `No. Struk: *${order.order_number}*\n` +
        `Pelanggan: ${order.customer_name}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        items +
        `━━━━━━━━━━━━━━━━━━\n` +
        `*TOTAL: Rp ${order.total_amount.toLocaleString('id-ID')}*\n` +
        `Bayar: Rp ${cashPaid.toLocaleString('id-ID')}\n` +
        `Kembali: Rp ${change.toLocaleString('id-ID')}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        `Terima kasih atas kunjungan Anda! 🙏`;

    const encodedText = encodeURIComponent(text);
    const waUrl = phone ? `https://wa.me/${phone}?text=${encodedText}` : `https://wa.me/?text=${encodedText}`;
    window.open(waUrl, '_blank');
}

function reprintOfflineOrder(order) {
    lastReceiptOrder = order;
    lastReceiptCashPaid = order.total_amount;
    renderReceiptContent(order, order.total_amount);
    document.getElementById('cashierReceiptModal').classList.add('active');
}

// Pulse animation
const style = document.createElement('style');
style.textContent = `
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
@media print {
    body * {
        visibility: hidden !important;
    }
    #cashierReceiptModal,
    #cashierReceiptModal .modal-card,
    #print-receipt-area, #print-receipt-area * {
        visibility: visible !important;
    }
    #print-receipt-area {
        position: fixed;
        left: 0;
        top: 0;
        width: 100% !important;
        margin: 0 !important;
        padding: 20px !important;
        background: #fff !important;
        z-index: 99999;
    }
    .no-print {
        display: none !important;
    }
    .modal-overlay {
        background: none !important;
        backdrop-filter: none !important;
        position: static !important;
        display: block !important;
        box-shadow: none !important;
    }
    .modal-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        max-width: 100% !important;
        background: #fff !important;
    }
    .modal-close {
        display: none !important;
    }
}
`;
document.head.appendChild(style);

// ====== CHAINED DROPDOWN FOR ADD STOCK MODAL ======
document.getElementById('stockAddCategory').addEventListener('change', function() {
    const categoryId = this.value;
    const productSelect = document.getElementById('stockAddProduct');
    productSelect.innerHTML = '<option value="">Memuat produk...</option>';
    productSelect.disabled = true;

    if (!categoryId) {
        productSelect.innerHTML = '<option value="">-- Pilih Kategori Dulu --</option>';
        return;
    }

    fetch(`/admin/api/categories/${categoryId}/products`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(products => {
            productSelect.innerHTML = '<option value="">-- Pilih Produk --</option>';
            if (products.length === 0) {
                productSelect.innerHTML = '<option value="">-- Tidak ada produk di kategori ini --</option>';
            } else {
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.name} (Stok: ${product.stock})`;
                    productSelect.appendChild(option);
                });
            }
            productSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            productSelect.innerHTML = '<option value="">-- Gagal memuat produk --</option>';
        });
});
</script>
@endpush