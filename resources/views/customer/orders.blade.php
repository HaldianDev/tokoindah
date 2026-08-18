@php
    $appSettings = \App\Models\WebSetting::getSettings();
@endphp
@extends('layouts.dashboard')

@section('page_title', 'Riwayat Pesanan Saya')
@section('role_label', '🛍️ Area Pembeli')
@section('role_color', '#34D399')
@section('avatar_gradient', '#059669, #34D399')

@section('sidebar_nav')
    @include('partials.customer-sidebar')
@endsection

@section('topbar_actions')
    <a href="{{ route('store.index') }}" class="btn-action btn-primary" style="font-size:0.8rem;padding:0.5rem 1rem;text-decoration:none;">
        <i class="fa-solid fa-cart-plus"></i> Belanja Lagi
    </a>
@endsection

@section('content')
<div class="dash-section active" id="sec-semua">
    
    <div class="section-header" style="margin-bottom: 1.5rem;">
        <div>
            <h1 class="section-title">Riwayat Pesanan & Tagihan Cicilan</h1>
            <p class="section-subtitle">Daftar transaksi belanja Anda di {{ $appSettings->site_name }} dengan rincian status, pengiriman, dan angsuran.</p>
        </div>
    </div>

    @if($orders->isEmpty())
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:5rem 2rem;text-align:center;">
            <i class="fa-solid fa-box-open" style="font-size:4rem;color:#CBD5E1;display:block;margin-bottom:1rem;"></i>
            <h3 style="font-size:1.2rem;font-weight:700;color:#0F172A;margin-bottom:8px;">Belum Ada Riwayat Pesanan</h3>
            <p style="color:#64748B;margin-bottom:1.5rem;font-size:0.85rem;">Anda belum melakukan transaksi di {{ $appSettings->site_name }}.</p>
            <a href="{{ route('store.index') }}" class="btn-action btn-primary" style="text-decoration:none;">
                <i class="fa-solid fa-bag-shopping"></i> Jelajahi Katalog Produk
            </a>
        </div>
    @else
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background: rgba(2,132,199,0.1); color: #0284C7;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 800; color: #0F172A;">Tabel Transaksi Anda</h3>
                        <p style="font-size: 0.75rem; color: #64748B;">Klik tombol "Lihat Detail" untuk memeriksa rincian produk, ongkir, dan jadwal pembayaran angsuran.</p>
                    </div>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Item Barang</th>
                            <th>Berat & Ongkir</th>
                            <th>Metode Bayar</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong style="color: #0284C7;">#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; color: #475569; font-weight: 500;">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0F172A;">{{ $order->items->count() }} Produk</span>
                                <div style="font-size: 0.72rem; color: #64748B;">{{ $order->items->first()->product->name ?? '' }} {{ $order->items->count() > 1 ? '+'.($order->items->count()-1).' lainnya' : '' }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.78rem; font-weight: 600; color: #475569;">
                                    ⚖️ {{ $order->total_weight ? ($order->total_weight >= 1000 ? ($order->total_weight/1000).' kg' : $order->total_weight.' g') : '1 kg' }}
                                </div>
                                <div style="font-size: 0.72rem; color: #64748B;">
                                    Ongkir: Rp {{ number_format($order->shipping_cost ?: 0, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                @if($order->payment_method === 'credit')
                                    <span class="badge badge-amber"><i class="fa-solid fa-credit-card"></i> Cicilan {{ $order->credit_tenor_months }}x Bln</span>
                                @else
                                    <span class="badge badge-blue"><i class="fa-solid fa-money-bill-wave"></i> Tunai (Cash)</span>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size: 0.95rem; color: #0F172A;">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </strong>
                                @if($order->payment_method === 'credit')
                                    <div style="font-size: 0.7rem; color: #D97706; font-weight: 700;">DP: Rp {{ number_format($order->down_payment, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai / Lunas</span>
                                @elseif($order->status === 'approved')
                                    <span class="badge badge-sky"><i class="fa-solid fa-spinner"></i> Angsuran Berjalan</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge badge-indigo"><i class="fa-solid fa-gear"></i> Diproses</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-rose"><i class="fa-solid fa-xmark"></i> Dibatalkan</span>
                                @elseif($order->status === 'waiting_payment')
                                    <span class="badge badge-amber"><i class="fa-solid fa-clock"></i> Menunggu Pembayaran</span>
                                @elseif($order->status === 'verifying_payment')
                                    <span class="badge badge-indigo" style="background:#E0E7FF;color:#4338CA;"><i class="fa-solid fa-user-shield"></i> Verifikasi Bukti</span>
                                @else
                                    <span class="badge badge-amber"><i class="fa-solid fa-clock"></i> {{ str_replace('_', ' ', Str::title($order->status)) }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($order->payment_method === 'cash')
                                    @if($order->payment_status === 'unpaid' && $order->status === 'waiting_payment')
                                        <form action="{{ route('order.upload_cash_proof', $order->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-center gap-2">
                                            @csrf
                                            <input type="file" name="payment_proof" required class="form-control" style="font-size: 0.68rem; max-width: 120px; padding: 0.2rem 0.4rem;">
                                            <button type="submit" class="btn-action btn-primary btn-sm" style="font-size: 0.7rem; padding: 0.4rem 0.7rem;">
                                                <i class="fa-solid fa-upload"></i> Upload
                                            </button>
                                        </form>
                                    @elseif($order->payment_status === 'pending_verification')
                                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="btn-action btn-outline btn-sm">
                                            <i class="fa-solid fa-eye"></i> Lihat Bukti
                                        </a>
                                    @else
                                        <button type="button" onclick="openOrderDetail({{ json_encode($order) }})" class="btn-action btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.4rem 0.8rem;">
                                            <i class="fa-solid fa-eye text-sky-600"></i> Lihat Detail
                                        </button>
                                    @endif
                                @else
                                    <button type="button" onclick="openOrderDetail({{ json_encode($order) }})" class="btn-action btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.4rem 0.8rem;">
                                        <i class="fa-solid fa-eye text-sky-600"></i> Lihat Detail
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div style="padding: 1.25rem; border-top: 1px solid #E2E8F0;">
                {{ $orders->links() }}
            </div>
        </div>
    @endif

</div>

<!-- ORDER DETAIL MODAL -->
<div class="modal-overlay" id="orderDetailModal">
    <div class="modal-card max-w-2xl" style="max-width: 700px;">
        <button class="modal-close" onclick="closeOrderDetail()">&times;</button>
        
        <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 0.75rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A;" id="modalOrderNumber">Detail Pesanan</h3>
                    <p style="font-size: 0.75rem; color: #64748B;" id="modalOrderDate"></p>
                </div>
                <div id="modalOrderStatusBadge"></div>
            </div>
        </div>

        <div class="space-y" style="gap: 1.25rem;">
            
            <!-- Items List -->
            <div>
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.6rem;">Daftar Produk Belanja:</h4>
                <div id="modalItemsList" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
            </div>

            <!-- Shipping & Summary Box -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1rem; font-size: 0.8rem; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem;" class="responsive-grid-2-col">
                <div>
                    <strong style="color: #334155; display: block; margin-bottom: 4px;">Alamat Penerima:</strong>
                    <p id="modalCustomerName" style="color: #475569; margin: 0; font-weight: 600;"></p>
                    <p id="modalCustomerPhone" style="color: #64748B; margin: 0;"></p>
                    <p id="modalCustomerAddress" style="color: #64748B; margin-top: 4px; line-height: 1.4;"></p>
                </div>
                <div style="text-align: right;" class="space-y-1">
                    <div style="display: flex; justify-content: space-between; color: #64748B;">
                        <span>Total Berat:</span>
                        <strong id="modalTotalWeight" style="color: #0F172A;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #64748B;">
                        <span>Ongkir Kurir:</span>
                        <strong id="modalShippingCost" style="color: #0F172A;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #0F172A; font-weight: 800; font-size: 1rem; padding-top: 6px; border-top: 1px solid #CBD5E1;">
                        <span>Total Tagihan:</span>
                        <span id="modalGrandTotal" style="color: #0284C7;">-</span>
                    </div>
                </div>
            </div>

            <!-- Installment Schedule if Credit -->
            <div id="modalInstallmentWrapper" style="display: none;">
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.6rem;">Jadwal Angsuran & Pembayaran:</h4>
                <div style="overflow-x: auto;">
                    <table class="dash-table" style="font-size: 0.75rem;">
                        <thead>
                            <tr>
                                <th>Angsuran</th>
                                <th>Nominal</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="modalInstallmentTableBody"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION: PENGATURAN AKUN (Customer)          --}}
{{-- ============================================ --}}
<div class="dash-section" id="sec-customer-account-settings">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-user-cog" style="color:#34D399;"></i> Pengaturan Akun</h1>
            <p class="section-subtitle">Kelola informasi profil, kata sandi, dan keamanan akun Anda.</p>
        </div>
    </div>
    
    <div class="row">


        <!-- Main Content Area (Tab Panes) -->
        <div class="col-md-9">
            <div class="tab-content" id="settingsTabsContent">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="flash-alert flash-success mb-4">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-alert flash-error mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Profile Information Tab Pane -->
                <div class="tab-pane fade show active" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="table-card">
                        <div class="table-card-header">
                            <div class="table-card-title">
                                <div class="table-card-title-icon" style="background:rgba(52,211,153,0.1);color:#34D399;"><i class="fa-solid fa-user"></i></div>
                                Informasi Profil
                            </div>
                        </div>
                        <div style="padding:1.5rem;">
                            <div class="flex flex-wrap md:flex-nowrap gap-6">
                                <!-- Left Column: Profile Photo -->
                                <div class="w-full md:w-1/3 flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <label class="form-label mb-4 text-center">Foto Profil Saat Ini</label>
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg mb-4">
                                    <form action="{{ route('customer.settings.update') }}" method="POST" enctype="multipart/form-data" class="w-full text-center">
                                        @csrf
                                        <input type="hidden" name="_action" value="update_profile_photo">
                                        <input type="file" id="profile_photo_customer" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500
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
                                    <form action="{{ route('customer.settings.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_action" value="update_profile_info">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Nomor Telepon/WhatsApp</label>
                                            <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="form-label">Alamat Pengiriman Lengkap</label>
                                            <textarea id="address" name="address" rows="3" required class="form-control">{{ old('address', Auth::user()->address) }}</textarea>
                                        </div>
                                        <button type="submit" class="btn-action btn-primary mt-4" style="background:#34D399;color:#fff;padding:0.75rem 2rem;">
                                            <i class="fa-solid fa-floppy-disk"></i> Simpan Informasi Profil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Tab Pane -->
                <div class="tab-pane fade" id="password-pane" role="tabpanel" aria-labelledby="password-tab">
                    <div class="table-card">
                        <div class="table-card-header">
                            <div class="table-card-title">
                                <div class="table-card-title-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-lock"></i></div>
                                Perbarui Kata Sandi
                            </div>
                        </div>
                        <div style="padding:1.5rem;">
                            <form action="{{ route('customer.settings.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_action" value="update_password">
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
                </div>

                <!-- Profile Photo Tab Pane -->
                <div class="tab-pane fade" id="photo-pane" role="tabpanel" aria-labelledby="photo-tab">
                    
                </div>

                <!-- 2FA Tab Pane -->
                <div class="tab-pane fade" id="two-factor-auth-pane" role="tabpanel" aria-labelledby="2fa-tab">
                    <div class="table-card">
                        <div class="table-card-header">
                            <div class="table-card-title">
                                <div class="table-card-title-icon" style="background:rgba(12, 12, 12, 0.1);color:#0F172A;"><i class="fa-solid fa-shield-alt"></i></div>
                                Otentikasi Dua Faktor (2FA)
                            </div>
                        </div>
                        <div style="padding:1.5rem;">
                            <p class="text-slate-600 mb-4">Tambahkan lapisan keamanan ekstra ke akun Anda menggunakan otentikasi dua faktor. Setelah diaktifkan, Anda akan diminta token OTP dari aplikasi autentikator Anda saat login.</p>

                            @if (session('status') == 'two-factor-authentication-enabled')
                                <div class="flash-alert flash-success mb-4">
                                    <i class="fa-solid fa-check-circle"></i> Otentikasi dua faktor telah diaktifkan.
                                </div>
                            @endif

                            @if (session('status') == 'two-factor-authentication-disabled')
                                <div class="flash-alert flash-error mb-4">
                                    <i class="fa-solid fa-xmark-circle"></i> Otentikasi dua faktor telah dinonaktifkan.
                                </div>
                            @endif

                            @if (! Auth::user()->two_factor_secret)
                                <!-- Enable 2FA -->
                                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                    @csrf
                                    <button type="submit" class="btn-action btn-primary" style="background:#059669;color:#fff;padding:0.75rem 2rem;">
                                        <i class="fa-solid fa-toggle-on"></i> Aktifkan 2FA
                                    </button>
                                </form>
                            @else
                                <!-- Disable 2FA -->
                                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-danger mb-4" style="padding:0.75rem 2rem;">
                                        <i class="fa-solid fa-toggle-off"></i> Nonaktifkan 2FA
                                    </button>
                                </form>

                                @if (Auth::user()->two_factor_secret && ! Auth::user()->two_factor_confirmed_at)
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                                        <p class="text-amber-800 font-bold mb-3">Selesaikan Aktivasi 2FA</p>
                                        <p class="text-amber-700 text-sm mb-3">Harap pindai kode QR ini menggunakan aplikasi autentikator pilihan Anda seperti Google Authenticator, atau masukkan kunci pengaturan. Kemudian masukkan kode OTP dari aplikasi untuk mengonfirmasi aktivasi.</p>
                                        <div class="flex justify-center my-3">
                                            {!! Auth::user()->twoFactorQrCodeSvg() !!}
                                        </div>
                                        <p class="text-center small text-amber-700 mb-3">Kunci Pengaturan: <code class="font-bold text-amber-900">{{ decrypt(Auth::user()->two_factor_secret) }}</code></p>
                                        
                                        <form method="POST" action="{{ url('user/confirmed-two-factor-authentication') }}">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="code" class="form-label text-amber-800">Kode Otentikasi</label>
                                                <input type="text" name="code" id="code" class="form-control" inputmode="numeric" autofocus autocomplete="one-time-code" required>
                                            </div>
                                            <button type="submit" class="btn-action btn-primary" style="background:#D97706;color:#fff;padding:0.75rem 2rem;">
                                                <i class="fa-solid fa-check-circle"></i> Konfirmasi & Selesaikan
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                @if (Auth::user()->two_factor_secret && Auth::user()->two_factor_confirmed_at)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                        <p class="text-green-800 font-bold mb-3">2FA Aktif!</p>
                                        <p class="text-green-700 text-sm">Otentikasi dua faktor saat ini aktif dan akun Anda lebih aman.</p>
                                    </div>
                                @endif


                                @if (Auth::user()->two_factor_recovery_codes && Auth::user()->two_factor_confirmed_at)
                                    <div class="table-card">
                                        <div class="table-card-header">
                                            <div class="table-card-title">
                                                <div class="table-card-title-icon" style="background:rgba(100, 116, 139, 0.1);color:#64748B;"><i class="fa-solid fa-key"></i></div>
                                                Kode Pemulihan
                                            </div>
                                        </div>
                                        <div style="padding:1.5rem;">
                                            <p class="text-slate-600 mb-3">Simpan kode pemulihan ini di tempat yang aman. Kode ini dapat digunakan untuk mengakses akun Anda jika Anda kehilangan akses ke perangkat autentikator Anda.</p>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-4">
                                                <ul class="list-disc list-inside text-slate-700 font-mono text-sm">
                                                    @foreach (json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true) as $code)
                                                        <li>{{ $code }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}">
                                                @csrf
                                                <button type="submit" class="btn-action btn-outline" style="color:#64748B;border-color:#CBD5E1;padding:0.75rem 2rem;">
                                                    <i class="fa-solid fa-sync-alt"></i> Buat Kode Pemulihan Baru
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
    .custom-pills .nav-link {
        color: #495057;
        font-weight: 500;
        padding: 1rem 1.5rem;
        border-radius: 0;
        border-right: 3px solid transparent;
        transition: all 0.2s ease-in-out;
    }
    .custom-pills .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    .custom-pills .nav-link.active {
        background-color: #e9f5ff;
        color: #0d6efd;
        border-color: #0d6efd;
        font-weight: 600;
    }
    .custom-pills .nav-item:first-child .nav-link {
        border-top-left-radius: .5rem;
        border-top-right-radius: .5rem;
    }
    .custom-pills .nav-item:last-child .nav-link {
        border-bottom-left-radius: .5rem;
        border-bottom-right-radius: .5rem;
    }
    </style>
    @endsection


@push('scripts')
<script>
    function openOrderDetail(order) {
        document.getElementById('modalOrderNumber').innerText = '#' + order.order_number;
        document.getElementById('modalOrderDate').innerText = 'Dipesan pada ' + new Date(order.created_at).toLocaleString('id-ID');
        
        // Status badge
        let badgeHtml = '';
        if (order.status === 'completed') {
            badgeHtml = '<span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai / Lunas</span>';
        } else if (order.status === 'approved') {
            badgeHtml = '<span class="badge badge-sky"><i class="fa-solid fa-spinner"></i> Angsuran Berjalan</span>';
        } else {
            badgeHtml = '<span class="badge badge-amber"><i class="fa-solid fa-clock"></i> ' + order.status + '</span>';
        }
        document.getElementById('modalOrderStatusBadge').innerHTML = badgeHtml;

        // Customer info
        document.getElementById('modalCustomerName').innerText = order.customer_name;
        document.getElementById('modalCustomerPhone').innerText = order.customer_phone;
        document.getElementById('modalCustomerAddress').innerText = order.shipping_address;

        const weight = order.total_weight || 1000;
        document.getElementById('modalTotalWeight').innerText = (weight >= 1000 ? (weight/1000).toFixed(1) + ' kg' : weight + ' g');
        document.getElementById('modalShippingCost').innerText = 'Rp ' + (order.shipping_cost || 0).toLocaleString('id-ID');
        document.getElementById('modalGrandTotal').innerText = 'Rp ' + order.total_amount.toLocaleString('id-ID');

        // Items
        const itemsContainer = document.getElementById('modalItemsList');
        itemsContainer.innerHTML = (order.items || []).map(item => `
            <div style="display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; padding: 0.6rem 0.85rem; border-radius: 8px; border: 1px solid #E2E8F0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="${item.product.image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;" alt="${item.product.name}">
                    <div>
                        <div style="font-weight: 700; font-size: 0.8rem; color: #0F172A;">${item.product.name}</div>
                        <div style="font-size: 0.72rem; color: #64748B;">${item.quantity} unit x Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                </div>
                <strong style="color: #0284C7; font-size: 0.85rem;">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</strong>
            </div>
        `).join('');

        // Installments
        const instWrapper = document.getElementById('modalInstallmentWrapper');
        const instBody = document.getElementById('modalInstallmentTableBody');

        if (order.payment_method === 'credit' && order.installments && order.installments.length > 0) {
            instWrapper.style.display = 'block';
            instBody.innerHTML = order.installments.map(inst => `
                <tr style="${inst.installment_number === 0 ? 'background: #F0FDF4;' : ''}">
                    <td><strong>${inst.installment_number === 0 ? 'DP (20%)' : 'Bulan Ke-' + inst.installment_number}</strong></td>
                    <td style="font-weight: 700; color: #0284C7;">Rp ${inst.amount.toLocaleString('id-ID')}</td>
                    <td>${inst.due_date}</td>
                    <td>
                        <span class="badge ${inst.status === 'paid' ? 'badge-emerald' : 'badge-amber'}">
                            ${inst.status === 'paid' ? 'Lunas' : (inst.payment_proof ? 'Menunggu Konfirmasi' : 'Belum Bayar')}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        ${inst.status === 'paid' 
                            ? '<span style="color:#059669; font-weight:700; font-size:0.75rem;">✓ Terverifikasi</span>' 
                            : (inst.payment_proof 
                                ? `<a href="/storage/${inst.payment_proof}" target="_blank" style="color:#0284C7; font-size:0.75rem; text-decoration:underline;">Lihat Bukti</a>`
                                : `<form method="POST" action="/pay-installment/${inst.id}" enctype="multipart/form-data" style="display:flex; gap:4px; align-items:center; justify-content:center;">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="file" name="payment_proof" required style="font-size:0.68rem; width:110px;">
                                    <button type="submit" class="btn-action btn-primary btn-sm" style="font-size:0.68rem; padding:0.25rem 0.5rem;">Upload</button>
                                   </form>`
                              )
                        }
                    </td>
                </tr>
            `).join('');
        } else {
            instWrapper.style.display = 'none';
        }

        document.getElementById('orderDetailModal').classList.add('active');
    }

    function closeOrderDetail() {
        document.getElementById('orderDetailModal').classList.remove('active');
    }
</script>
@endpush
