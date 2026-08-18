@extends('layouts.dashboard')

@section('page_title', 'Dashboard Owner')
@section('role_label', '👑 Owner Eksekutif')
@section('role_color', '#FCD34D')
@section('avatar_gradient', '#D97706, #FBBF24')

@section('sidebar_nav')
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

    <div class="sidebar-section-label">Pengaturan</div>

    <button class="sidebar-item" data-section="sec-owner-account-settings" data-title="Pengaturan Akun" data-breadcrumb="Pengaturan"
        onclick="switchSection('sec-owner-account-settings','Pengaturan Akun','Pengaturan')">
        <div class="sidebar-item-icon" style="background:rgba(217,119,6,0.12);color:#FCD34D;"><i class="fa-solid fa-user-cog"></i></div>
        Pengaturan Akun
    </button>



@endsection

@section('content')

{{-- ============================================ --}}
{{-- SECTION 1: RINGKASAN KEUANGAN                --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-keuangan">
    <div class="section-header">
        <div>
            <h1 class="section-title">Ringkasan Keuangan</h1>
            <p class="section-subtitle">Rekapitulasi omset, pendapatan terbayar, dan piutang kredit</p>
        </div>
        <div style="display:flex; gap:1rem; align-items:center;">
            <span class="badge badge-amber" style="padding:6px 14px;font-size:0.78rem;">
                <i class="fa-solid fa-star" style="font-size:8px;"></i> Mode Eksekutif
            </span>
            @include('owner.partials._export_buttons')
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Omset Sales</div>
                <div class="stat-card-value" style="font-size:1.4rem;">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
                <div style="margin-top:8px;display:flex;gap:12px;font-size:0.75rem;font-weight:600;">
                    <span style="color:#059669;">Cash: Rp {{ number_format($cashRevenue,0,',','.') }}</span>
                    <span style="color:#D97706;">Kredit: Rp {{ number_format($creditRevenue,0,',','.') }}</span>
                </div>
            </div>
            <div class="stat-card-icon" style="background:#F0FDF4;color:#059669;">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>

        <div class="stat-card" style="border-color:#A7F3D0;background:linear-gradient(135deg,#ECFDF5,#fff);">
            <div>
                <div class="stat-card-label" style="color:#059669;">Pendapatan Terbayar (Cash In)</div>
                <div class="stat-card-value" style="font-size:1.35rem;color:#059669;">Rp {{ number_format($totalReceivedIncome,0,',','.') }}</div>
                <div class="stat-card-sub" style="color:#059669;"><i class="fa-solid fa-circle-check"></i> Cash + DP + Angsuran Lunas</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(5,150,105,0.1);color:#059669;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="stat-card" style="border-color:#FDE68A;background:linear-gradient(135deg,#FFFBEB,#fff);">
            <div>
                <div class="stat-card-label" style="color:#D97706;">Piutang Angsuran Berjalan</div>
                <div class="stat-card-value" style="font-size:1.35rem;color:#D97706;">Rp {{ number_format($outstandingCredit,0,',','.') }}</div>
                <div class="stat-card-sub" style="color:#D97706;"><i class="fa-solid fa-clock-rotate-left"></i> Cicilan belum dibayar</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(217,119,6,0.1);color:#D97706;">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>
    </div>

    <!-- Summary stats row -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Produk</div>
                <div class="stat-card-value">{{ $totalProducts }}</div>
                <div class="stat-card-sub" style="color:#0284C7;"><i class="fa-solid fa-box"></i> Item aktif</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#2563EB;"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Pesanan Pending</div>
                <div class="stat-card-value" style="color:#D97706;">{{ $pendingOrders }}</div>
                <div class="stat-card-sub" style="color:#D97706;"><i class="fa-solid fa-clock"></i> Cicilan belum berjalan</div>
            </div>
            <div class="stat-card-icon" style="background:#FFFBEB;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Stok Habis</div>
                <div class="stat-card-value" style="color:#EF4444;">{{ $outOfStockCount }}</div>
                <div class="stat-card-sub" style="color:#EF4444;"><i class="fa-solid fa-triangle-exclamation"></i> Perlu restock</div>
            </div>
            <div class="stat-card-icon" style="background:#FEF2F2;color:#EF4444;"><i class="fa-solid fa-boxes-stacked"></i></div>
        </div>
    </div>

    <!-- Overview Chart -->
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
                <canvas id="ownerOverviewChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 2: PESANAN CASH                      --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-pesanan-cash">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-money-bill-wave" style="color:#059669;"></i> Pesanan Cash</h1>
            <p class="section-subtitle">Daftar pesanan tunai (otomatis selesai/completed saat transaksi)</p>
        </div>
        <div style="display:flex; gap:1rem; align-items:center;">
            <span class="badge badge-emerald" style="padding:6px 14px;font-size:0.78rem;">
                {{ $totalCashOrders }} Transaksi
            </span>
            @include('owner.partials._export_buttons')
        </div>
    </div>

    <!-- Cash Stats Mini -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card" style="border-color:#A7F3D0;background:linear-gradient(135deg,#ECFDF5,#fff);">
            <div>
                <div class="stat-card-label" style="color:#059669;">Total Pendapatan Cash</div>
                <div class="stat-card-value" style="font-size:1.3rem;color:#059669;">Rp {{ number_format($cashRevenue,0,',','.') }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-wallet"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Transaksi Cash</div>
                <div class="stat-card-value">{{ $totalCashOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#2563EB;"><i class="fa-solid fa-receipt"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Pesanan Selesai</div>
                <div class="stat-card-value" style="color:#059669;">{{ $cashOrders->where('status','completed')->count() }}</div>
            </div>
            <div class="stat-card-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-circle-check"></i></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-money-bill-wave"></i></div>
                Daftar Pesanan Cash ({{ $cashOrders->count() }} transaksi)
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
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">#{{ $ord->order_number }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td style="font-weight:700;color:#059669;">Rp {{ number_format($ord->total_amount,0,',','.') }}</td>
                        <td>
                            @if($ord->status === 'completed')
                                <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @elseif($ord->status === 'verifying_payment')
                                <span class="badge badge-amber" style="background:#FFFBEB;color:#D97706;border:1px solid #FCD34D;"><i class="fa-solid fa-user-shield"></i> Verifikasi Admin</span>
                            @elseif($ord->status === 'processing')
                                <span class="badge badge-indigo"><i class="fa-solid fa-gear"></i> Diproses</span>
                            @elseif($ord->status === 'waiting_payment')
                                <span class="badge badge-rose"><i class="fa-solid fa-clock"></i> Menunggu Pembayaran</span>
                            @else
                                <span class="badge badge-gray">{{ str_replace('_', ' ', Str::title($ord->status)) }}</span>
                            @endif
                        </td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ $ord->created_at->format('d M Y H:i') }}</td>
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
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 3: PESANAN CREDIT                    --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-pesanan-credit">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-credit-card" style="color:#D97706;"></i> Pesanan Credit</h1>
            <p class="section-subtitle">Daftar pesanan kredit. Status pesanan menyesuaikan pembayaran cicilan sesuai tenor.</p>
        </div>
        <div style="display:flex; gap:1rem; align-items:center;">
            <span class="badge badge-amber" style="padding:6px 14px;font-size:0.78rem;">
                {{ $totalCreditOrders }} Transaksi
            </span>
            @include('owner.partials._export_buttons')
        </div>
    </div>

    <!-- Credit Stats Mini -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card" style="border-color:#FDE68A;background:linear-gradient(135deg,#FFFBEB,#fff);">
            <div>
                <div class="stat-card-label" style="color:#D97706;">Total Nilai Kredit</div>
                <div class="stat-card-value" style="font-size:1.3rem;color:#D97706;">Rp {{ number_format($creditRevenue,0,',','.') }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-credit-card"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Transaksi Kredit</div>
                <div class="stat-card-value">{{ $totalCreditOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#2563EB;"><i class="fa-solid fa-receipt"></i></div>
        </div>
        <div class="stat-card" style="border-color:#FDE68A;">
            <div>
                <div class="stat-card-label" style="color:#D97706;">Piutang Outstanding</div>
                <div class="stat-card-value" style="font-size:1.2rem;color:#D97706;">Rp {{ number_format($outstandingCredit,0,',','.') }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#FFFBEB;color:#D97706;"><i class="fa-solid fa-credit-card"></i></div>
                Daftar Pesanan Kredit ({{ $creditOrders->count() }} transaksi)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pemesan</th>
                        <th>Total Nilai</th>
                        <th>DP (20%)</th>
                        <th>Cicilan/Bln</th>
                        <th>Tenor</th>
                        <th>KTP</th>
                        <th>Status Order</th>
                        <th>Progress Angsuran</th>
                        <th style="text-align:center;">Kelola Cicilan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($creditOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">#{{ $ord->order_number }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td style="font-weight:700;color:#0F172A;">Rp {{ number_format($ord->total_amount,0,',','.') }}</td>
                        <td style="font-weight:700;color:#059669;">Rp {{ number_format($ord->down_payment,0,',','.') }}</td>
                        <td style="font-weight:700;color:#D97706;">Rp {{ number_format($ord->monthly_installment,0,',','.') }}</td>
                        <td style="font-weight:700;color:#4F46E5;">{{ $ord->credit_tenor_months }}x Bln</td>
                        <td style="text-align:center;">
                            @if($ord->ktp_path)
                                <button onclick="openKtpModal('{{ route('file.serve', ['path' => base64_encode($ord->ktp_path)]) }}', '{{ $ord->customer_name }}')" class="btn-action btn-sm" style="background:#0EA5E9;color:#fff;padding:4px 10px;border-radius:6px;font-size:0.75rem;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                    <i class="fa-solid fa-id-card"></i> Lihat KTP
                                </button>
                            @else
                                <span style="color:#94A3B8;font-size:0.75rem;">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            @if($ord->status === 'completed')
                                <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @elseif($ord->status === 'approved')
                                <span class="badge badge-sky"><i class="fa-solid fa-spinner"></i> Angsuran Berjalan</span>
                            @elseif($ord->status === 'cancelled')
                                <span class="badge badge-rose">Cancelled</span>
                            @else
                                <span class="badge badge-amber"><i class="fa-solid fa-clock"></i> Pending DP / Persetujuan</span>
                            @endif
                        </td>
                        <td style="min-width:130px;">
                            @php
                                $paidCount = $ord->installments->where('status','paid')->count();
                                $totalInst = $ord->installments->count();
                                $pct = $totalInst > 0 ? round(($paidCount/$totalInst)*100) : 0;
                            @endphp
                            <div style="font-size:0.75rem;color:#64748B;margin-bottom:4px;">{{ $paidCount }}/{{ $totalInst }} Bln Lunas ({{ $pct }}%)</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $pct>=100?'#059669':'#F59E0B' }};"></div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <button onclick="openOwnerInstallmentsModal({{ json_encode($ord) }})"
                                class="btn-action btn-primary btn-sm" style="background:#4F46E5;">
                                <i class="fa-solid fa-list-check"></i> Kelola Cicilan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-credit-card" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada pesanan kredit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 4: ANALITIK & GRAFIK                 --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-analitik">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-chart-line" style="color:#4F46E5;"></i> Analitik & Grafik Penjualan</h1>
            <p class="section-subtitle">Analisis pendapatan mingguan, bulanan, dan tahunan untuk pengambilan keputusan</p>
        </div>
        <span class="badge badge-indigo" style="padding:6px 14px;font-size:0.78rem;">
            <i class="fa-solid fa-brain" style="font-size:8px;"></i> Decision Support
        </span>
    </div>

    <!-- Summary Cards for Analytics -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card" style="border-color:#C7D2FE;background:linear-gradient(135deg,#EEF2FF,#fff);">
            <div>
                <div class="stat-card-label" style="color:#4F46E5;">Total Pesanan</div>
                <div class="stat-card-value" style="color:#4F46E5;">{{ $totalCashOrders + $totalCreditOrders }}</div>
                <div style="margin-top:6px;display:flex;gap:10px;font-size:0.72rem;font-weight:600;">
                    <span style="color:#059669;"><i class="fa-solid fa-circle" style="font-size:6px;"></i> Cash: {{ $totalCashOrders }}</span>
                    <span style="color:#D97706;"><i class="fa-solid fa-circle" style="font-size:6px;"></i> Credit: {{ $totalCreditOrders }}</span>
                </div>
            </div>
            <div class="stat-card-icon" style="background:rgba(79,70,229,0.1);color:#4F46E5;"><i class="fa-solid fa-shopping-bag"></i></div>
        </div>
        <div class="stat-card" style="border-color:#A7F3D0;background:linear-gradient(135deg,#ECFDF5,#fff);">
            <div>
                <div class="stat-card-label" style="color:#059669;">Total Omset</div>
                <div class="stat-card-value" style="font-size:1.2rem;color:#059669;">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
            </div>
            <div class="stat-card-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-chart-pie"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Pesanan Selesai</div>
                <div class="stat-card-value" style="color:#059669;">{{ $completedOrders }}</div>
            </div>
            <div class="stat-card-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-flag-checkered"></i></div>
        </div>
    </div>

    <!-- Tab Switcher -->
    <div style="display:flex;gap:0;margin-bottom:0;background:#fff;border:1px solid #E2E8F0;border-bottom:none;border-radius:16px 16px 0 0;overflow:hidden;">
        <button onclick="switchAnalyticsTab('weekly')" id="tab-weekly" class="analytics-tab active"
            style="flex:1;padding:0.85rem 1rem;border:none;background:transparent;font-family:inherit;font-size:0.85rem;font-weight:700;cursor:pointer;transition:all 0.2s;border-bottom:3px solid transparent;color:#94A3B8;">
            <i class="fa-solid fa-calendar-week"></i> Mingguan
        </button>
        <button onclick="switchAnalyticsTab('monthly')" id="tab-monthly" class="analytics-tab"
            style="flex:1;padding:0.85rem 1rem;border:none;background:transparent;font-family:inherit;font-size:0.85rem;font-weight:700;cursor:pointer;transition:all 0.2s;border-bottom:3px solid transparent;color:#94A3B8;">
            <i class="fa-solid fa-calendar-days"></i> Bulanan
        </button>
        <button onclick="switchAnalyticsTab('yearly')" id="tab-yearly" class="analytics-tab"
            style="flex:1;padding:0.85rem 1rem;border:none;background:transparent;font-family:inherit;font-size:0.85rem;font-weight:700;cursor:pointer;transition:all 0.2s;border-bottom:3px solid transparent;color:#94A3B8;">
            <i class="fa-solid fa-calendar"></i> Tahunan
        </button>
    </div>

    <!-- Weekly Chart -->
    <div class="table-card analytics-panel active" id="panel-weekly" style="border-radius:0 0 16px 16px;">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-chart-area"></i></div>
                Pendapatan 7 Hari Terakhir (Cash vs Credit)
            </div>
            <span class="badge badge-blue">Mingguan</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="height:320px;position:relative;">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="table-card analytics-panel" id="panel-monthly" style="border-radius:0 0 16px 16px;display:none;">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-chart-bar"></i></div>
                Pendapatan 12 Bulan Terakhir (Cash vs Credit)
            </div>
            <span class="badge badge-amber">Bulanan</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="height:320px;position:relative;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Yearly Chart -->
    <div class="table-card analytics-panel" id="panel-yearly" style="border-radius:0 0 16px 16px;display:none;">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#E0E7FF;color:#4338CA;"><i class="fa-solid fa-chart-column"></i></div>
                Pendapatan 5 Tahun Terakhir (Cash vs Credit)
            </div>
            <span class="badge badge-indigo">Tahunan</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="height:320px;position:relative;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart: Cash vs Credit Composition -->
    <div class="table-card" style="margin-top:1.5rem;">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#F0FDF4;color:#059669;"><i class="fa-solid fa-chart-pie"></i></div>
                Komposisi Pendapatan: Cash vs Credit
            </div>
        </div>
        <div style="padding:1.5rem;display:flex;align-items:center;justify-content:center;gap:2rem;flex-wrap:wrap;">
            <div style="width:250px;height:250px;position:relative;">
                <canvas id="compositionDonut"></canvas>
            </div>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:14px;height:14px;border-radius:4px;background:#059669;"></div>
                    <div>
                        <div style="font-size:0.75rem;color:#94A3B8;font-weight:600;">Cash Revenue</div>
                        <div style="font-weight:800;color:#059669;font-size:1.1rem;">Rp {{ number_format($cashRevenue,0,',','.') }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:14px;height:14px;border-radius:4px;background:#D97706;"></div>
                    <div>
                        <div style="font-size:0.75rem;color:#94A3B8;font-weight:600;">Credit Revenue</div>
                        <div style="font-weight:800;color:#D97706;font-size:1.1rem;">Rp {{ number_format($creditRevenue,0,',','.') }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:14px;height:14px;border-radius:4px;background:#4F46E5;"></div>
                    <div>
                        <div style="font-size:0.75rem;color:#94A3B8;font-weight:600;">Outstanding Piutang</div>
                        <div style="font-weight:800;color:#4F46E5;font-size:1.1rem;">Rp {{ number_format($outstandingCredit,0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 5: PERGERAKAN BARANG                 --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-barang">
    <div class="section-header">
        <div>
            <h1 class="section-title">Pergerakan Barang</h1>
            <p class="section-subtitle">Jejak audit stok barang masuk dari pabrik dan barang keluar akibat penjualan</p>
        </div>
    </div>

    <!-- Stats Pergerakan -->
    <div class="grid-stats-2" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div class="stat-card-icon" style="background:#F0FDF4;color:#059669;width:56px;height:56px;">
                    <i class="fa-solid fa-box-archive" style="font-size:1.3rem;"></i>
                </div>
                <div>
                    <div class="stat-card-label">Total Barang Masuk (Stok In)</div>
                    <div class="stat-card-value" style="color:#059669;">{{ $totalBarangMasuk }} <span style="font-size:1rem;font-weight:600;color:#94A3B8;">Unit</span></div>
                    <div class="stat-card-sub" style="color:#64748B;">Pasokan stok yang telah diinput</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div class="stat-card-icon" style="background:#F0F9FF;color:#0284C7;width:56px;height:56px;">
                    <i class="fa-solid fa-truck-ramp-box" style="font-size:1.3rem;"></i>
                </div>
                <div>
                    <div class="stat-card-label">Total Barang Keluar (Stok Out)</div>
                    <div class="stat-card-value" style="color:#0284C7;">{{ $totalBarangKeluar }} <span style="font-size:1rem;font-weight:600;color:#94A3B8;">Unit</span></div>
                    <div class="stat-card-sub" style="color:#64748B;">Barang laku terjual ke pembeli</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#F0F9FF;color:#0284C7;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                Log Histori Pergerakan Barang
            </div>
            <form method="GET" action="{{ route('owner.dashboard') }}" style="margin:0;">
                <select name="type" onchange="this.form.submit()" class="form-control" style="width:auto;font-size:0.82rem;padding:6px 10px;">
                    <option value="">Semua Pergerakan</option>
                    <option value="in" {{ request('type')==='in'?'selected':'' }}>Barang Masuk (In)</option>
                    <option value="out" {{ request('type')==='out'?'selected':'' }}>Barang Keluar (Out)</option>
                </select>
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Produk</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Inputor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockMovements as $sm)
                    <tr>
                        <td style="font-family:monospace;font-size:0.78rem;color:#64748B;">{{ $sm->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight:700;color:#0F172A;">{{ $sm->product->name ?? '-' }}</td>
                        <td>
                            @if($sm->type === 'in')
                                <span class="badge badge-emerald"><i class="fa-solid fa-arrow-down"></i> Masuk</span>
                            @else
                                <span class="badge badge-sky"><i class="fa-solid fa-arrow-up"></i> Keluar</span>
                            @endif
                        </td>
                        <td style="font-weight:800;color:{{ $sm->type==='in'?'#059669':'#0284C7' }};">
                            {{ $sm->type==='in'?'+':'-' }}{{ $sm->quantity }} Unit
                        </td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ $sm->notes ?? '-' }}</td>
                        <td style="font-weight:600;color:#334155;">{{ $sm->user->name ?? 'Sistem' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;">Belum ada riwayat pergerakan stok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockMovements->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #E2E8F0;">
            {{ $stockMovements->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION 6: PIUTANG KREDIT                    --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-piutang">
    <div class="section-header">
        <div>
            <h1 class="section-title">Laporan Piutang Kredit</h1>
            <p class="section-subtitle">Rekap seluruh cicilan kredit pembeli yang masih berjalan</p>
        </div>
        @include('owner.partials._export_buttons')
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#FFFBEB;color:#D97706;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                Pesanan Kredit Aktif ({{ $creditOrders->count() }} transaksi)
            </div>
            <span class="stat-card-label" style="font-size:0.78rem;">
                Total Piutang: <strong style="color:#D97706;">Rp {{ number_format($outstandingCredit,0,',','.') }}</strong>
            </span>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pembeli</th>
                        <th>Total Nilai</th>
                        <th>DP (20%)</th>
                        <th>Cicilan/Bln</th>
                        <th>Tenor</th>
                        <th>Progress</th>
                        <th style="text-align:center;">Kelola Cicilan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($creditOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">#{{ $ord->order_number }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td style="font-weight:700;color:#0F172A;">Rp {{ number_format($ord->total_amount,0,',','.') }}</td>
                        <td style="font-weight:700;color:#059669;">Rp {{ number_format($ord->down_payment,0,',','.') }}</td>
                        <td style="font-weight:700;color:#D97706;">Rp {{ number_format($ord->monthly_installment,0,',','.') }}</td>
                        <td style="font-weight:700;color:#4F46E5;">{{ $ord->credit_tenor_months }}x Bln</td>
                        <td style="min-width:120px;">
                            @php
                                $paidCount = $ord->installments->where('status','paid')->count();
                                $totalInst = $ord->installments->count();
                                $pct = $totalInst > 0 ? round(($paidCount/$totalInst)*100) : 0;
                            @endphp
                            <div style="font-size:0.75rem;color:#64748B;margin-bottom:4px;">{{ $paidCount }}/{{ $totalInst }} ({{ $pct }}%)</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $pct>=100?'#059669':'#F59E0B' }};"></div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <button onclick="openOwnerInstallmentsModal({{ json_encode($ord) }})"
                                class="btn-action btn-primary btn-sm" style="background:#4F46E5;">
                                <i class="fa-solid fa-list-check"></i> Lihat & Kelola
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-credit-card" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada data kredit aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION: ANALISIS PENJUALAN OFFLINE (KASIR)  --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-offline-sales">
    <div class="section-header">
        <div>
            <h1 class="section-title">Analisis Penjualan Offline (Kasir Toko)</h1>
            <p class="section-subtitle">Rekapitulasi pendapatan dan histori transaksi kasir toko fisik offline.</p>
        </div>
        <div style="display:flex; gap:1rem; align-items:center;">
            <span class="badge badge-emerald" style="padding:6px 14px;font-size:0.78rem;">
                <i class="fa-solid fa-store"></i> Toko Offline
            </span>
            @include('owner.partials._export_buttons')
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid-stats-3" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Omset Kasir</div>
                <div class="stat-card-value" style="color:#059669;">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</div>
                <div class="stat-card-sub" style="color:#059669;"><i class="fa-solid fa-arrow-trend-up"></i> Pendapatan offline</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#059669;">
                <i class="fa-solid fa-cash-register"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Total Transaksi</div>
                <div class="stat-card-value">{{ $totalOfflineOrders }}</div>
                <div class="stat-card-sub" style="color:#0284C7;"><i class="fa-solid fa-receipt"></i> Struk tercetak</div>
            </div>
            <div class="stat-card-icon" style="background:#EFF6FF;color:#0284C7;">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-card-label">Rata-rata Transaksi</div>
                <div class="stat-card-value" style="color:#4F46E5;">
                    Rp {{ number_format($totalOfflineOrders > 0 ? $offlineRevenue / $totalOfflineOrders : 0, 0, ',', '.') }}
                </div>
                <div class="stat-card-sub" style="color:#4F46E5;"><i class="fa-solid fa-calculator"></i> Nilai per keranjang</div>
            </div>
            <div class="stat-card-icon" style="background:#EEF2FF;color:#4F46E5;">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
        </div>
    </div>

    <!-- Tabel Histori Kasir -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-history"></i></div>
                Daftar Transaksi Kasir Toko Offline ({{ $offlineOrders->count() }} transaksi)
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        <th>Operator Kasir (Admin)</th>
                        <th>Nama Pelanggan</th>
                        <th>Item Barang</th>
                        <th>Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offlineOrders as $ord)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:#0F172A;">#{{ $ord->order_number }}</td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ $ord->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight:600;color:#0F172A;">{{ $ord->user->name ?? 'Admin Toko' }}</td>
                        <td>
                            <span style="font-weight:700;color:#0F172A;display:block;">{{ $ord->customer_name }}</span>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ $ord->customer_phone }}</span>
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                @foreach($ord->items as $item)
                                <span style="font-size:0.8rem;color:#334155;">
                                    <i class="fa-solid fa-chevron-right" style="font-size:8px;color:#94A3B8;"></i>
                                    <strong>{{ $item->product->name ?? 'Produk Dihapus' }}</strong> (x{{ $item->quantity }})
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td style="font-weight:800;color:#059669;">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i class="fa-solid fa-cash-register" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Belum ada transaksi offline dari kasir.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ====== OWNER INSTALLMENT MODAL ====== --}}
<div class="modal-overlay" id="ownerInstallmentModal">
    <div class="modal-card" style="max-width:750px;">
        <button class="modal-close" onclick="closeOwnerInstallmentsModal()">&times;</button>
        <div style="border-bottom:1px solid #E2E8F0;padding-bottom:1rem;margin-bottom:1.25rem;">
            <h3 style="font-weight:800;font-size:1.05rem;color:#0F172A;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-credit-card" style="color:#F59E0B;"></i> Rincian Cicilan & Uang Muka (DP)
            </h3>
            <p id="ownerModalOrderTitle" style="font-size:0.8rem;color:#64748B;margin-top:4px;"></p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1rem;margin-bottom:1.25rem;font-size:0.82rem;">
            <div><div style="color:#94A3B8;">Total Nilai:</div><div id="ownerModalTotal" style="font-weight:800;color:#0F172A;font-size:0.95rem;"></div></div>
            <div><div style="color:#94A3B8;">DP (20%):</div><div id="ownerModalDP" style="font-weight:800;color:#059669;font-size:0.95rem;"></div></div>
            <div><div style="color:#94A3B8;">Tenor:</div><div id="ownerModalTenor" style="font-weight:700;color:#0F172A;"></div></div>
            <div><div style="color:#94A3B8;">Cicilan/Bln:</div><div id="ownerModalMonthly" style="font-weight:700;color:#D97706;"></div></div>
        </div>

        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Angsuran</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status Cicilan</th>
                        <th style="text-align:center;">Bukti</th>
                        <th style="text-align:center;">Ubah Status (Owner)</th>
                    </tr>
                </thead>
                <tbody id="ownerInstallmentTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION: PENGATURAN AKUN (Owner)             --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-owner-account-settings">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-user-cog" style="color:#FCD34D;"></i> Pengaturan Owner</h1>
            <p class="section-subtitle">Kelola informasi profil, kata sandi, dan foto profil Anda. Serta kelola akun Admin utama.</p>
        </div>
    </div>
    
    <div class="space-y-6">
        @if (session('success'))
            <div class="flash-alert flash-success">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="flash-alert flash-error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Owner's Profile Information -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-user"></i></div>
                    Informasi Profil Owner
                </div>
            </div>
            <div style="padding:1.5rem;">
                <div class="flex flex-wrap md:flex-nowrap gap-6">
                    <!-- Left Column: Profile Photo -->
                    <div class="w-full md:w-1/3 flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <label class="form-label mb-4 text-center">Foto Profil Saat Ini</label>
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg mb-4">
                        <form action="{{ route('owner.settings.update') }}" method="POST" enctype="multipart/form-data" class="w-full text-center">
                            @csrf
                            <input type="hidden" name="_action" value="update_profile_photo">
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 mb-2">
                            <small class="text-slate-500 text-xs mt-1 block">Pilih foto baru (Max 2MB)</small>
                            <button type="submit" class="btn-action btn-primary mt-4 w-full" style="background:#0EA5E9;color:#fff;padding:0.75rem 1rem;">
                                <i class="fa-solid fa-upload"></i> Unggah Foto Profil
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Profile Information -->
                    <div class="w-full md:w-2/3">
                        <form action="{{ route('owner.settings.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon/WhatsApp</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="3" required class="form-control">{{ old('address', Auth::user()->address) }}</textarea>
                            </div>
                            <button type="submit" class="btn-action btn-primary mt-4" style="background:#FCD34D;color:#fff;padding:0.75rem 2rem;">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Informasi Profil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner's Update Password -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-lock"></i></div>
                    Perbarui Kata Sandi Owner
                </div>
            </div>
            <div style="padding:1.5rem;">
                <form action="{{ route('owner.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                    </div>
                    <button type="submit" class="btn-action btn-success" style="padding:0.75rem 2rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>

        <!-- Owner's Profile Photo Upload (Now Empty) -->
        <div class="table-card" style="display: none;">
        </div>

        <!-- Admin Account Management -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;"><i class="fa-solid fa-user-shield"></i></div>
                    Manajemen Akun Admin
                </div>
            </div>
            <div style="padding:1.5rem;">
                <form action="{{ route('owner.settings.admin.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="admin_email" class="form-label">Email Admin</label>
                        <input type="email" id="admin_email" name="email" value="{{ old('email', $admin->email ?? '') }}" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="admin_password" class="form-label">Kata Sandi Baru Admin</label>
                        <input type="password" id="admin_password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="admin_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru Admin</label>
                        <input type="password" id="admin_password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn-action btn-danger" style="padding:0.75rem 2rem;">
                        <i class="fa-solid fa-user-lock"></i> Perbarui Akun Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ====== CHART.JS CONFIGS ======
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                usePointStyle: true,
                padding: 20,
                font: { size: 12, family: 'Plus Jakarta Sans', weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#0F172A',
            titleFont: { family: 'Plus Jakarta Sans', weight: '700' },
            bodyFont: { family: 'Plus Jakarta Sans' },
            padding: 12,
            cornerRadius: 10,
            callbacks: {
                label: function(ctx) {
                    return ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID');
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(156,163,175,0.12)' },
            ticks: {
                font: { size: 11, family: 'Plus Jakarta Sans' },
                callback: function(v) { return 'Rp ' + (v/1000000).toFixed(1) + 'jt'; }
            }
        },
        x: {
            grid: { display: false },
            ticks: { font: { size: 11, family: 'Plus Jakarta Sans' } }
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // ====== OVERVIEW CHART ======
    const overviewCtx = document.getElementById('ownerOverviewChart');
    if (overviewCtx) {
        new Chart(overviewCtx, {
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

    // ====== WEEKLY CHART ======
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: @json($weeklyLabels),
                datasets: [
                    {
                        label: 'Cash',
                        data: @json($weeklyCashData),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Credit',
                        data: @json($weeklyCreditData),
                        borderColor: '#D97706',
                        backgroundColor: 'rgba(217,119,6,0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#D97706',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }
                ]
            },
            options: { ...chartDefaults }
        });
    }

    // ====== MONTHLY CHART ======
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'Cash',
                        data: @json($monthlyCashData),
                        backgroundColor: 'rgba(5,150,105,0.7)',
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Credit',
                        data: @json($monthlyCreditData),
                        backgroundColor: 'rgba(217,119,6,0.7)',
                        borderColor: '#D97706',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: { ...chartDefaults }
        });
    }

    // ====== YEARLY CHART ======
    const yearlyCtx = document.getElementById('yearlyChart');
    if (yearlyCtx) {
        new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: @json($yearlyLabels),
                datasets: [
                    {
                        label: 'Cash',
                        data: @json($yearlyCashData),
                        backgroundColor: 'rgba(5,150,105,0.75)',
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 10,
                        borderSkipped: false,
                    },
                    {
                        label: 'Credit',
                        data: @json($yearlyCreditData),
                        backgroundColor: 'rgba(217,119,6,0.75)',
                        borderColor: '#D97706',
                        borderWidth: 2,
                        borderRadius: 10,
                        borderSkipped: false,
                    }
                ]
            },
            options: { ...chartDefaults }
        });
    }

    // ====== DONUT: COMPOSITION ======
    const donutCtx = document.getElementById('compositionDonut');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Cash Revenue', 'Credit Revenue', 'Outstanding Piutang'],
                datasets: [{
                    data: [{{ $cashRevenue }}, {{ $creditRevenue }}, {{ $outstandingCredit }}],
                    backgroundColor: ['#059669', '#D97706', '#4F46E5'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.label + ': Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});

// ====== TAB SWITCHER ======
function switchAnalyticsTab(tab) {
    document.querySelectorAll('.analytics-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.analytics-tab').forEach(t => {
        t.style.borderBottomColor = 'transparent';
        t.style.color = '#94A3B8';
        t.style.background = 'transparent';
    });

    const panel = document.getElementById('panel-' + tab);
    const tabBtn = document.getElementById('tab-' + tab);
    if (panel) panel.style.display = 'block';
    if (tabBtn) {
        tabBtn.style.borderBottomColor = '#4F46E5';
        tabBtn.style.color = '#4F46E5';
        tabBtn.style.background = '#EEF2FF';
    }
}

// Init first tab
document.addEventListener('DOMContentLoaded', () => switchAnalyticsTab('weekly'));

// ====== INSTALLMENT MODAL ======
function openOwnerInstallmentsModal(order) {
    document.getElementById('ownerModalOrderTitle').textContent =
        `Pesanan #${order.order_number} — ${order.customer_name} (${order.customer_phone})`;
    document.getElementById('ownerModalTotal').textContent = `Rp ${order.total_amount.toLocaleString('id-ID')}`;
    document.getElementById('ownerModalDP').textContent = `Rp ${order.down_payment.toLocaleString('id-ID')}`;
    document.getElementById('ownerModalTenor').textContent = `${order.credit_tenor_months} Bulan`;
    document.getElementById('ownerModalMonthly').textContent = `Rp ${order.monthly_installment.toLocaleString('id-ID')}/bln`;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const tbody = document.getElementById('ownerInstallmentTableBody');

    let html = '';

    if (order.installments && order.installments.length > 0) {
        order.installments.forEach(inst => {
            let badge = '';
            if (inst.status === 'paid') {
                badge = '<span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Lunas</span>';
            } else if (inst.payment_proof) {
                badge = '<span class="badge badge-amber" style="background:#FFFBEB;color:#D97706;border:1px solid #FCD34D;padding:4px 10px;"><i class="fa-solid fa-clock-rotate-left" style="animation:pulse 2s infinite;"></i> Menunggu Verifikasi</span>';
            } else if (inst.status === 'overdue') {
                badge = '<span class="badge badge-rose"><i class="fa-solid fa-triangle-exclamation"></i> Jatuh Tempo</span>';
            } else {
                badge = '<span class="badge badge-gray">Belum Bayar</span>';
            }

            const proof = inst.payment_proof
                ? `<a href="/storage/${inst.payment_proof}" target="_blank" class="btn-action btn-primary btn-sm" style="font-size:0.75rem;padding:3px 10px;text-decoration:none;"><i class="fa-solid fa-file-image"></i> Periksa Bukti</a>`
                : '<span style="color:#94A3B8;font-size:0.82rem;">Belum Upload</span>';

            const due = inst.installment_number === 0 
                ? 'Saat Transaksi' 
                : new Date(inst.due_date).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});

            const installmentName = inst.installment_number === 0
                ? '<i class="fa-solid fa-crown" style="color:#F59E0B;"></i> DP (20%)'
                : `Bulan Ke-${inst.installment_number}`;

            const rowBg = inst.installment_number === 0 
                ? 'background:#F0FDF4;' 
                : (inst.payment_proof && inst.status !== 'paid' ? 'background:#FFFBEB;' : '');

            const nominalColor = inst.installment_number === 0 ? '#059669' : '#D97706';

            html += `<tr style="${rowBg}">
                <td style="font-weight:700;color:#0F172A;">${installmentName}</td>
                <td style="font-weight:700;color:${nominalColor};">Rp ${inst.amount.toLocaleString('id-ID')}</td>
                <td style="font-size:0.82rem;color:#64748B;">${due}</td>
                <td>${badge}</td>
                <td style="text-align:center;">${proof}</td>
                <td style="text-align:center;">
                    <form method="POST" action="/owner/installment/status/${inst.id}" style="margin:0;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <select name="status" onchange="this.form.submit()" class="form-control" style="padding:4px 8px;font-size:0.75rem;width:auto;font-weight:700;${inst.payment_proof && inst.status !== 'paid' ? 'border-color:#F59E0B;background:#FFF;' : ''}">
                            <option value="unpaid" ${inst.status === 'unpaid' ? 'selected' : ''}>Belum Bayar</option>
                            <option value="paid" ${inst.status === 'paid' ? 'selected' : ''}>Setujui (Lunas)</option>
                            <option value="overdue" ${inst.status === 'overdue' ? 'selected' : ''}>Jatuh Tempo</option>
                        </select>
                    </form>
                </td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="6" style="text-align:center;padding:2rem;color:#94A3B8;">Tidak ada data cicilan.</td></tr>';
    }

    tbody.innerHTML = html;
    document.getElementById('ownerInstallmentModal').classList.add('active');
}

function closeOwnerInstallmentsModal() {
    document.getElementById('ownerInstallmentModal').classList.remove('active');
}

function openKtpModal(imageUrl, customerName) {
    document.getElementById('ktpModalImage').src = imageUrl;
    document.getElementById('ktpModalTitle').textContent = 'KTP - ' + customerName;
    document.getElementById('ktpModal').classList.add('active');
}

function closeKtpModal() {
    document.getElementById('ktpModal').classList.remove('active');
    document.getElementById('ktpModalImage').src = '';
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});
</script>

{{-- KTP Preview Modal --}}
<div id="ktpModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;max-width:550px;width:90%;max-height:90vh;overflow:hidden;box-shadow:0 25px 50px rgba(0,0,0,0.3);position:relative;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #E2E8F0;">
            <h3 id="ktpModalTitle" style="margin:0;font-size:1rem;font-weight:700;color:#0F172A;"><i class="fa-solid fa-id-card" style="margin-right:8px;color:#0EA5E9;"></i>KTP</h3>
            <button onclick="closeKtpModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:#94A3B8;padding:4px;">&times;</button>
        </div>
        <div style="padding:20px;text-align:center;overflow-y:auto;max-height:calc(90vh - 70px);">
            <img id="ktpModalImage" src="" alt="Foto KTP" style="max-width:100%;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);" />
        </div>
    </div>
</div>

<style>
#ktpModal.active { display:flex !important; animation: fadeIn 0.2s ease; }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
</style>
@endpush
