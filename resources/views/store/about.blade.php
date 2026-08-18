@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl space-y-12">

    <!-- PAGE HEADER -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="inline-flex items-center gap-2 text-xs font-bold text-sky-600 bg-sky-50 px-3.5 py-1.5 rounded-full border border-sky-200">
            <i class="fa-solid fa-house-chimney-window"></i> Mengenal Kami Lebih Dekat
        </span>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
            {{ $settings->about_title ?: 'Tentang ' . $settings->site_name }}
        </h1>
        <p class="text-slate-500 text-sm md:text-base leading-relaxed">
            Pusat galeri keramik artistik dan peralatan rumah tangga terpercaya dengan kualitas terbaik dan pelayanan sepenuh hati.
        </p>
    </div>

    <!-- MAIN ABOUT HERO -->
    <div class="grid md:grid-cols-12 gap-8 items-center bg-white rounded-3xl p-6 md:p-10 border border-slate-200 shadow-sm">
        <div class="md:col-span-6 rounded-2xl overflow-hidden shadow-lg border border-slate-100">
            <img src="{{ $settings->about_image ?: 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=1000&q=80' }}" alt="Galeri {{ $settings->site_name }}" class="w-full h-80 md:h-96 object-cover">
        </div>
        <div class="md:col-span-6 space-y-5">
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                Karya Keramik Berkualitas untuk Hunian Idaman
            </h3>
            <div class="text-slate-600 text-sm leading-relaxed space-y-3">
                {!! nl2br(e($settings->about_description ?: $settings->site_name . ' adalah pusat galeri dan pengrajin perlengkapan keramik rumah tangga & hiasan artistik berkualitas ekspor. Berdiri sejak tahun 2018 di Tulang Bawang, Lampung, kami berkomitmen menghadirkan sentuhan elegan dan kehangatan karya seni keramik ke dalam setiap sudut hunian Anda.')) !!}
            </div>

            <div class="pt-2 flex flex-wrap gap-4 text-xs font-semibold text-slate-700">
                <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                    <i class="fa-solid fa-location-dot text-rose-500"></i>
                    <span>{{ $settings->store_address ?: 'Tulang Bawang, Lampung' }}</span>
                </div>
                <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                    <i class="fa-brands fa-whatsapp text-emerald-500 text-sm"></i>
                    <span>{{ $settings->whatsapp_number }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- VISION & MISSION -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Visi -->
        <div class="bg-gradient-to-br from-sky-50 to-white rounded-3xl p-8 border border-sky-100 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white flex items-center justify-center text-xl shadow-md">
                <i class="fa-solid fa-bullseye"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Visi Kami</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                {!! nl2br(e($settings->about_vision ?: 'Menjadi pusat keramik dan perlengkapan rumah tangga estetik terdepan di Indonesia yang terpercaya dalam kualitas, keindahan seni, dan kemudahan kepemilikan bagi semua kalangan.')) !!}
            </p>
        </div>

        <!-- Misi -->
        <div class="bg-gradient-to-br from-emerald-50 to-white rounded-3xl p-8 border border-emerald-100 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-md">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Misi Kami</h3>
            <div class="text-sm text-slate-600 leading-relaxed space-y-2">
                {!! nl2br(e($settings->about_mission ?: "1. Menghadirkan produk keramik berstandar mutu tinggi dengan material porselen tahan panas.\n2. Memberikan fleksibilitas pembayaran melalui skema cicilan kredit tanpa beban.\n3. Menjamin keamanan pengiriman hingga 100% sampai ke tangan pelanggan.")) !!}
            </div>
        </div>
    </div>

    <!-- CONTACT CTA -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-8 md:p-12 text-center space-y-6 shadow-xl border border-slate-800">
        <h2 class="text-2xl md:text-3xl font-black tracking-tight">Ingin Konsultasi atau Kunjungan Galeri?</h2>
        <p class="text-slate-300 text-sm max-w-xl mx-auto leading-relaxed">
            Tim kami siap membantu memilihkan produk keramik yang sesuai dengan tema ruangan, kebutuhan acara, maupun pesanan khusus grosir & hampers.
        </p>
        <div class="flex justify-center gap-4 pt-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}?text=Halo%20{{ urlencode($settings->site_name) }},%20saya%20ingin%20berkonsultasi." target="_blank" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm px-6 py-3.5 rounded-2xl shadow-lg transition-all">
                <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi WhatsApp ({{ $settings->whatsapp_number }})
            </a>
            <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm px-6 py-3.5 rounded-2xl transition-all">
                <i class="fa-solid fa-store"></i> Lihat Katalog Produk
            </a>
        </div>
    </div>

</div>
@endsection
