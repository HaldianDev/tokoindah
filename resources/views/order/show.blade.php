@extends('layouts.dashboard')

@section('page_title', 'Detail Pesanan #' . $order->order_number)
@section('role_label', '🛍️ Area Pembeli')
@section('role_color', '#34D399')
@section('avatar_gradient', '#059669, #34D399')

@section('sidebar_nav')
    @include('partials.customer-sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/80 p-6 md:p-8">
        
        <div class="border-b border-slate-200 pb-6 mb-6">
            <a href="{{ route('customer.orders') }}" class="text-sm text-sky-600 hover:underline mb-4 inline-block"><i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Pesanan</a>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Detail Pesanan</h1>
            <p class="text-sm text-slate-500 mt-1">Terima kasih! Pesanan Anda telah kami terima.</p>
        </div>

        {{-- Order Status & Number --}}
        <div class="flex flex-wrap justify-between items-center gap-4 bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase">Nomor Pesanan</span>
                <span class="block text-lg font-bold text-slate-800 tracking-wider">#{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase text-right">Status</span>
                <span class="block text-base font-bold capitalize {{ $order->status_class_customer['text'] }}">{{ $order->status_name_customer }}</span>
            </div>
        </div>

        {{-- Payment Instructions --}}
        @if($order->payment_method == 'cash' && $order->status == 'waiting_payment')
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm space-y-4 mb-6">
                <h3 class="font-bold text-blue-800 text-base flex items-center gap-3">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    Instruksi Pembayaran
                </h3>
                <p class="text-blue-700">
                    Mohon segera selesaikan pembayaran Anda dengan mentransfer jumlah total ke rekening bank kami di bawah ini:
                </p>
                <div class="bg-white/70 rounded-lg p-4 border border-blue-200 text-center">
                    <img src="https://i.imgur.com/v8S7T4d.png" alt="BCA Logo" class="h-6 mx-auto mb-2">
                    <span class="block text-xs text-blue-900/80">Nomor Rekening</span>
                    <span class="block text-2xl font-extrabold text-blue-900 tracking-wider">{{ $settings->bank_account_number ?? '08888888' }}</span>
                    <span class="block text-sm font-semibold text-blue-900/90 mt-1">a/n {{ $settings->bank_account_name ?? 'PT. Keramik Sentosa' }}</span>
                </div>
                <div class="flex justify-between items-center bg-white/70 rounded-lg p-4 border border-blue-200">
                    <div>
                        <span class="block text-xs text-blue-900/80">Jumlah Total</span>
                        <span class="block text-xl font-extrabold text-blue-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <button onclick="copyToClipboard('{{ $order->total_amount }}')" class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold rounded-lg transition">
                        <i class="fa-solid fa-copy"></i> Salin
                    </button>
                </div>
                <p class="text-blue-600 text-xs text-center pt-2">
                    Pesanan Anda akan diproses setelah bukti pembayaran kami verifikasi.
                </p>
            </div>

            {{-- Payment Proof Upload Form --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-3">Upload Bukti Pembayaran</h3>
                <form action="{{ route('order.upload_proof', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <input type="file" name="payment_proof" required class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-emerald-50 file:text-emerald-700
                            hover:file:bg-emerald-100"/>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-5 rounded-xl shadow-sm transition-colors text-sm">
                            <i class="fa-solid fa-upload"></i> Kirim
                        </button>
                    </div>
                     @error('payment_proof')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        @endif

         @if($order->payment_method == 'credit' && $order->status == 'pending' && !$order->ktp_path)
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-3">Upload KTP (Untuk Cicilan)</h3>
                 <form action="{{ route('order.upload_ktp', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <input type="file" name="ktp_file" required class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-sky-50 file:text-sky-700
                            hover:file:bg-sky-100"/>
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-5 rounded-xl shadow-sm transition-colors text-sm">
                            <i class="fa-solid fa-upload"></i> Kirim
                        </button>
                    </div>
                     @error('ktp_file')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        @endif

        {{-- Order Summary --}}
        <div class="border-t border-slate-200 pt-6 mt-6">
             <h3 class="text-base font-bold text-slate-800 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-4">
                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $item->product->name ?? '' }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                        <div>
                            <span class="font-bold text-slate-800">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                            <span class="block text-xs text-slate-500">x {{ $item->quantity }}</span>
                        </div>
                    </div>
                    <div class="font-semibold text-slate-700">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            <div class="border-t border-slate-100 mt-4 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Subtotal</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Ongkos Kirim</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-slate-900">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Details --}}
        <div class="border-t border-slate-200 pt-6 mt-6">
             <h3 class="text-base font-bold text-slate-800 mb-4">Detail Pengiriman</h3>
             <div class="text-sm text-slate-600 leading-relaxed">
                 <p class="font-semibold text-slate-800">{{ $order->customer_name }}</p>
                 <p>{{ $order->customer_phone }}</p>
                 <p>{{ $order->shipping_address }}</p>
             </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text.replace(/[^0-9]/g, '')).then(function() {
        alert('Jumlah total berhasil disalin!');
    }, function(err) {
        alert('Gagal menyalin jumlah total.');
    });
}
</script>
@endpush
@endsection

