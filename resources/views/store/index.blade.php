@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl space-y-10">

    <!-- CATALOG & SEARCH SECTION -->
    <div id="katalog" class="space-y-6">
        <!-- Section Header Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                        Katalog Produk Keramik & Rumah Tangga
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-1 pl-11">Pilih kategori atau cari nama barang yang Anda inginkan dengan mudah.</p>
            </div>

            <!-- SEARCH BAR -->
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="catalogSearch" onkeyup="filterProducts()" placeholder="Ketik nama produk untuk mencari..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 focus:outline-none transition-all shadow-inner">
                </div>
            </div>
        </div>

        <!-- CATEGORY TABS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('store.index') }}" class="category-btn {{ !request('category') || request('category') == 'all' ? 'active bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white shadow-md shadow-slate-900/20 border-slate-700/60' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300 shadow-sm' }} px-5 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all">
                Semua Produk ({{ $totalProducts }})
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('store.index', ['category' => $cat->slug]) }}" class="category-btn {{ request('category') == $cat->slug ? 'active bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white shadow-md shadow-slate-900/20 border-slate-700/60' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300 shadow-sm' }} px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <!-- PRODUCT GRID (ESTHETIC & MODERN) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productContainer">
            @forelse($products as $product)
            <div class="product-card bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-sky-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group"
                 data-category="{{ $product->category->slug ?? 'lainnya' }}"
                 data-name="{{ strtolower($product->name) }}">
                
                <div>
                    <!-- Product Image Showcase -->
                    <div class="relative h-52 bg-slate-100 overflow-hidden">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500">
                        
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/95 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Ready {{ $product->stock }} Unit
                                </span>
                            @else
                                <span class="bg-rose-500/95 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-md">
                                    Stok Habis
                                </span>
                            @endif
                        </div>

                        <!-- Installment & Weight Tag -->
                        <div class="absolute bottom-2.5 right-2.5 flex items-center gap-1.5">
                            <span class="bg-slate-900/80 backdrop-blur-md text-slate-200 text-[9px] font-bold px-2 py-0.5 rounded-md border border-white/10 shadow-sm">
                                ⚖️ {{ $product->weight ? ($product->weight >= 1000 ? ($product->weight/1000).' kg' : $product->weight.' g') : '1 kg' }}
                            </span>
                            @if($product->stock > 0)
                            <span class="bg-slate-900/80 backdrop-blur-md text-amber-300 text-[9px] font-bold px-2 py-0.5 rounded-md border border-white/10 shadow-sm">
                                DP 20%
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="p-5 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md uppercase">
                                {{ $product->category->name ?? 'Kategori' }}
                            </span>
                            <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                <i class="fa-solid fa-box text-[10px]"></i> Stok {{ $product->stock }}
                            </span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base line-clamp-1 group-hover:text-sky-600 transition-colors" title="{{ $product->name }}">
                            {{ $product->name }}
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $product->description ?: 'Peralatan keramik kualitas premium dengan desain artistik elegan.' }}
                        </p>

                        <!-- Price Section -->
                        <div class="pt-2 border-t border-slate-100 flex items-baseline justify-between">
                            <div>
                                <span class="text-[11px] text-slate-400 block font-medium">Harga Cash:</span>
                                <span class="text-lg font-black text-slate-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-amber-600 font-bold block">Cicilan mulai:</span>
                                <span class="text-xs font-black text-amber-700">
                                    Rp {{ number_format(ceil(($product->price * 0.8) / 12), 0, ',', '.') }}<span class="text-[9px] font-normal text-slate-400">/bln</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-5 pt-0 grid grid-cols-2 gap-2">
                    <button onclick="openProductDetail({{ json_encode($product) }})" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5 hover:shadow-sm">
                        <i class="fa-solid fa-circle-info text-[11px] text-sky-600"></i> Detail
                    </button>

                    @if($product->stock > 0)
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ addslashes($product->image) }}', {{ $product->stock }}, {{ $product->weight ?: 1000 }})" class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white text-xs font-bold py-2.5 px-3 rounded-xl shadow-md shadow-sky-600/20 transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5 active:translate-y-0">
                            <i class="fa-solid fa-cart-plus text-[11px]"></i> + Pesan
                        </button>
                    @else
                        <button disabled class="w-full bg-slate-100 text-slate-400 text-xs font-semibold py-2.5 px-3 rounded-xl cursor-not-allowed border border-slate-200">
                            Habis
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                <i class="fa-solid fa-box-open text-5xl mb-3 text-slate-300"></i>
                <p class="text-base font-bold text-slate-700">Belum ada produk dalam katalog saat ini.</p>
                <p class="text-xs text-slate-400 mt-1">Silakan kembali lagi nanti untuk koleksi terbaru.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>



@endsection


