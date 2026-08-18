@extends('layouts.app')

@section('content')
<div class="space-y-16 pb-12">

    <!-- HERO SECTION -->
    <div class="container mx-auto px-4 pt-4 max-w-7xl">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#090E17] via-[#0F172A] to-[#172554] text-white shadow-2xl border border-slate-800/80 p-8 md:p-14">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid md:grid-cols-12 gap-8 items-center relative z-10">
                <div class="md:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-sky-300 text-xs font-bold tracking-wide shadow-sm">
                        <i class="fa-solid fa-sparkles text-amber-400"></i> {{ $settings->site_name }} Gallery & Craft
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight">
                        {{ $settings->hero_title ?: 'Keindahan Estetika & Kualitas Eksklusif untuk Rumah Anda' }}
                    </h1>

                    <p class="text-slate-300 text-sm md:text-base leading-relaxed max-w-xl font-normal">
                        {{ $settings->hero_subtitle ?: 'Pusat koleksi hiasan keramik artistik, tea set elegan, vas mewah, dan perlengkapan rumah tangga berstandar ekspor dengan opsi pembayaran Tunai maupun Cicilan Kredit.' }}
                    </p>

                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-semibold text-slate-300 pt-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                            <span>100% Keramik Asli & Bergaransi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                            <span>Cicilan Ringan Tenor s/d 12 Bulan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                            <span>Pengiriman Aman ke Seluruh Indonesia</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2.5 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm px-7 py-3.5 rounded-2xl shadow-lg shadow-sky-600/35 transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fa-solid fa-bag-shopping text-base"></i> Buka Katalog Belanja
                        </a>
                        <a href="{{ route('store.about') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/15 font-semibold text-sm px-6 py-3.5 rounded-2xl backdrop-blur-md transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fa-solid fa-circle-info text-sky-400"></i> Tentang Kami
                        </a>
                    </div>
                </div>

                <div class="md:col-span-5 relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 group bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=700&q=80" alt="Rumah Keramik" class="w-full h-80 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 p-3.5 rounded-2xl bg-slate-900/85 backdrop-blur-md border border-white/15 text-xs flex items-center justify-between shadow-xl">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold">
                                    <i class="fa-solid fa-shield-check"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white leading-snug">Garansi Ganti Baru 100%</p>
                                    <p class="text-[11px] text-slate-400">Klaim mudah jika rusak saat kirim</p>
                                </div>
                            </div>
                            <span class="text-sky-400 font-extrabold text-[11px] uppercase tracking-wider bg-sky-900/40 px-2 py-1 rounded-lg border border-sky-500/30">Official</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CATEGORY SHOWCASE -->
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="text-center max-w-2xl mx-auto space-y-2 mb-10">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Kategori Pilihan Koleksi</h2>
            <p class="text-sm text-slate-500">Temukan berbagai ragam hiasan keramik dan perlengkapan rumah tangga terbaik.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($categories as $cat)
            <a href="{{ route('store.index', ['category' => $cat->slug]) }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-lg hover:border-sky-300 transition-all text-center group flex flex-col items-center justify-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-all flex items-center justify-center text-2xl shadow-sm">
                    <i class="{{ $cat->icon ?: 'fa-solid fa-layer-group' }}"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-800 group-hover:text-sky-600 transition-colors">{{ $cat->name }}</h4>
                    <span class="text-[10px] text-slate-400 font-medium">{{ $cat->products()->count() }} Produk</span>
                </div>
            </a>
            @empty
            <div class="col-span-full py-8 text-center text-slate-400">Belum ada kategori.</div>
            @endforelse
        </div>
    </div>

    <!-- FEATURED PRODUCTS -->
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Produk Unggulan Terbaru</h2>
                <p class="text-sm text-slate-500 mt-1">Koleksi keramik paling diminati dengan jaminan mutu terbaik.</p>
            </div>
            <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-sky-600 hover:text-sky-700 transition-colors">
                Lihat Semua Katalog ({{ $totalProducts }}) <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-sky-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                <div>
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500">
                        <div class="absolute top-3 left-3">
                            <span class="bg-emerald-500/95 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                Ready {{ $product->stock }} Unit
                            </span>
                        </div>
                    </div>
                    <div class="p-5 space-y-2">
                        <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded uppercase">
                            {{ $product->category->name ?? 'Keramik' }}
                        </span>
                        <h3 class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-sky-600 transition-colors">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $product->description ?: 'Keramik artistik mutu ekspor tahan panas dan awet.' }}
                        </p>
                        <div class="pt-2 flex items-baseline justify-between border-t border-slate-100">
                            <span class="text-base font-black text-slate-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-amber-600">
                                DP 20%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <a href="{{ route('store.index') }}" class="w-full bg-slate-900 hover:bg-sky-600 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-cart-shopping text-[11px]"></i> Beli di Katalog
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-slate-400">Belum ada produk unggulan.</div>
            @endforelse
        </div>
    </div>

    <!-- STORY & ADVANTAGES SECTION -->
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="bg-slate-50 rounded-3xl p-8 md:p-12 border border-slate-200 grid md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-6 space-y-5">
                <div class="inline-flex items-center gap-2 text-xs font-bold text-sky-600 bg-sky-100 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-heart"></i> Cerita Kami
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                    Hadirkan Kehangatan & Sentuhan Seni di Rumah Anda
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Setiap karya keramik kami dibuat dengan ketelitian tinggi oleh pengrajin berpengalaman menggunakan tanah liat dan porselen pilihan. Dibalut dengan teknik glasir modern, koleksi kami tidak hanya indah dipandang tetapi juga tahan lama, aman untuk makanan (*food grade*), dan tahan panas.
                </p>
                <div class="pt-2">
                    <a href="{{ route('store.about') }}" class="inline-flex items-center gap-2 bg-white text-slate-800 border border-slate-300 hover:border-sky-500 hover:text-sky-600 font-bold text-xs px-5 py-3 rounded-xl shadow-sm transition-all">
                        Baca Selengkapnya Tentang Kami <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="md:col-span-6 grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <div class="text-3xl font-black text-sky-600 mb-1">5+</div>
                    <p class="text-xs font-bold text-slate-700">Tahun Pengalaman</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Sejak tahun 2018</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <div class="text-3xl font-black text-emerald-600 mb-1">1.200+</div>
                    <p class="text-xs font-bold text-slate-700">Pesanan Terkirim</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Ke seluruh Indonesia</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <div class="text-3xl font-black text-amber-600 mb-1">100%</div>
                    <p class="text-xs font-bold text-slate-700">Garansi Keaslian</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Standar Ekspor</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <div class="text-3xl font-black text-indigo-600 mb-1">4.9/5</div>
                    <p class="text-xs font-bold text-slate-700">Rating Pelanggan</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Ulasan Kepuasan</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
