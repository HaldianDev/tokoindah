@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">

    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-8 text-center">Keranjang & Checkout</h1>

    <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Cart Items -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Daftar Belanja Anda</h2>
                <div id="cartItemsContainer" class="space-y-4">
                    <!-- Cart items will be rendered here by JavaScript -->
                </div>
            </div>

            <!-- Right Column: Order Summary & Checkout -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <h2 class="text-xl font-bold text-slate-800">Ringkasan Pesanan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span id="cartSubtotalText" class="font-bold">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Berat Total</span>
                            <span id="cartWeightText">0 kg</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span>Ongkos Kirim</span>
                            <span id="cartShippingText" class="font-bold">Rp 0</span>
                        </div>
                        <div class="flex justify-between pt-2 text-lg font-extrabold text-slate-900">
                            <span>Total</span>
                            <span id="cartTotalText">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Payment & Customer Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <h2 class="text-xl font-bold text-slate-800">Detail Pembayaran & Pengiriman</h2>

                    @auth
                    <!-- Customer Info -->
                    <div class="space-y-3">
                        <div>
                            <label for="custName" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="custName" name="customer_name" required value="{{ Auth::user()->name ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        </div>
                        <div>
                            <label for="custPhone" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon/WhatsApp</label>
                            <input type="tel" id="custPhone" name="customer_phone" required value="{{ Auth::user()->phone ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        </div>
                        <div>
                            <label for="custAddress" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Pengiriman Lengkap</label>
                            <textarea id="custAddress" name="shipping_address" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ Auth::user()->address ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-3">
                        <div>
                            <label for="payMethod" class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                            <select id="payMethod" name="payment_method" onchange="togglePaymentFields()" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                                <option value="cash">Tunai (Transfer Bank)</option>
                                <option value="credit">Cicilan Kredit</option>
                            </select>
                        </div>

                        <!-- Credit Fields (Hidden by default) -->
                        <div id="tenorWrapper" class="hidden">
                            <label for="creditTenor" class="block text-xs font-semibold text-slate-700 mb-1">Tenor Cicilan</label>
                            <select id="creditTenor" name="credit_tenor_months" onchange="updateCartSummary()" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">12 Bulan</option>
                            </select>
                        </div>
                        <div id="dpWrapper" class="hidden space-y-2 text-sm pt-1">
                            <div class="flex justify-between">
                                <span>Uang Muka (DP 20%)</span>
                                <span id="cartDPText" class="font-bold">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Cicilan Bulanan</span>
                                <span id="cartMonthlyText" class="font-bold">Rp 0 x 3 Bln</span>
                            </div>
                        </div>
                        <div id="ktpWrapper" class="hidden">
                            <label for="ktpFile" class="block text-xs font-semibold text-slate-700 mb-1">Upload KTP (Untuk Cicilan)</label>
                            <input type="file" id="ktpFile" name="ktp_file" accept="image/*,application/pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                        </div>

                        <!-- Bank Transfer Details (For Cash) -->
                        <div id="bankTransferDetails" class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm space-y-2 mt-4">
                            <p class="font-bold text-blue-800 flex items-center gap-2">
                                <i class="fa-solid fa-building-columns"></i> Transfer Bank (BCA)
                            </p>
                            <p class="text-blue-700">
                                Mohon transfer ke rekening BCA kami:
                                <br><span class="font-extrabold text-lg text-blue-900">{{ $settings->bank_account_number ?? '08888888' }}</span>
                                <br>Atas Nama: <span class="font-semibold">{{ $settings->bank_account_name ?? 'PT. Keramik Sentosa' }}</span>
                            </p>
                            <p class="text-blue-600 text-xs">
                                Pesanan Anda akan diproses setelah bukti pembayaran kami verifikasi.
                            </p>
                        </div>

                        <!-- Payment Proof Upload (For Cash) -->
                        <div id="paymentProofUpload">
                            <label for="paymentProofFile" class="block text-xs font-semibold text-slate-700 mb-1">Upload Bukti Pembayaran (Untuk Tunai)</label>
                            <input type="file" id="paymentProofFile" name="payment_proof_file" accept="image/*,application/pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitOrder" class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold py-3 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Konfirmasi & Kirim Pesanan
                    </button>
                    @else
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-center space-y-2">
                        <p class="text-xs text-amber-800 font-semibold">Silakan masuk ke akun Anda terlebih dahulu untuk mengisi detail pengiriman.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                            <i class="fa-solid fa-right-to-bracket"></i> Login Pembeli
                        </a>
                    </div>
                    @endauth
            </div>
        </div>
    </form>
</div>

@endsection
