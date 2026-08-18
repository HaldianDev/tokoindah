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

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed h-8">
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

<!-- PRODUCT SPECIFICATION & PAYMENT SELECTION MODAL -->
<div class="modal-overlay" id="productDetailModal">
    <div class="modal-card max-w-3xl">
        <button class="modal-close" onclick="closeProductDetail()">&times;</button>
        <div class="grid md:grid-cols-12 gap-6">
            
            <!-- Left: Product Media & Specs -->
            <div class="md:col-span-5 space-y-4">
                <div class="rounded-2xl overflow-hidden bg-slate-100 h-56 md:h-64 shadow-sm border border-slate-200">
                    <img id="modalProductImage" src="" alt="" class="w-full h-full object-cover">
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span id="modalProductCategory" class="text-[10px] font-bold tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded uppercase"></span>
                        <span id="modalProductWeight" class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded"></span>
                    </div>
                    <h3 id="modalProductName" class="text-lg font-bold text-slate-900 leading-tight"></h3>
                    <p id="modalProductDescription" class="text-xs text-slate-600 leading-relaxed"></p>

                    <div class="border-t border-slate-100 pt-3 space-y-1.5 text-xs">
                        <p class="font-bold text-slate-900">Spesifikasi & Keunggulan:</p>
                        <ul class="space-y-1 text-slate-600">
                            <li id="modalSpec1" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                            <li id="modalSpec2" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                            <li id="modalSpec3" class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span></span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right: Payment Mode Choice (Cash vs Angsuran) -->
            <div class="md:col-span-7 flex flex-col justify-between space-y-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h4 class="font-black text-slate-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-sky-600"></i> Pilih Skema Pembayaran Produk
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pilih bayar sekali lunas (Cash) atau cicilan kredit bulanan.</p>
                    </div>

                    <!-- TAB SELECTION: CASH VS ANGSURAN -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="tabModeCash" onclick="switchModalPaymentMode('cash')" class="p-3.5 rounded-2xl border-2 border-sky-600 bg-sky-50 text-left transition-all shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-sky-700">Bayar Tunai</span>
                                <i class="fa-solid fa-circle-check text-sky-600 text-sm"></i>
                            </div>
                            <p class="text-sm font-black text-slate-900 mt-1" id="modalCashPriceText">Rp 0</p>
                            <span class="text-[10px] text-slate-500">Pembayaran sekali lunas</span>
                        </button>

                        <button type="button" id="tabModeCredit" onclick="switchModalPaymentMode('credit')" class="p-3.5 rounded-2xl border-2 border-slate-200 bg-white text-left hover:border-amber-400 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-amber-700">Bayar Angsuran</span>
                                <i class="fa-solid fa-credit-card text-amber-500 text-sm"></i>
                            </div>
                            <p class="text-sm font-black text-slate-900 mt-1" id="modalCreditDpText">DP 20% (Rp 0)</p>
                            <span class="text-[10px] text-slate-500">Cicilan ringan s/d 12 Bulan</span>
                        </button>
                    </div>

                    <!-- TENOR SELECTOR -->
                    <div id="modalTenorSection" class="hidden p-4 rounded-2xl bg-amber-50 border border-amber-200 space-y-3">
                        <label class="block text-xs font-bold text-amber-900">Pilih Tenor Angsuran Bulanan:</label>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="selectModalTenor(3, this)" class="modal-tenor-btn active bg-amber-600 text-white font-bold text-xs p-2.5 rounded-xl border border-amber-600 text-center shadow-sm">
                                3 Bulan
                            </button>
                            <button type="button" onclick="selectModalTenor(6, this)" class="modal-tenor-btn bg-white text-slate-700 font-bold text-xs p-2.5 rounded-xl border border-amber-300 text-center">
                                6 Bulan
                            </button>
                            <button type="button" onclick="selectModalTenor(12, this)" class="modal-tenor-btn bg-white text-slate-700 font-bold text-xs p-2.5 rounded-xl border border-amber-300 text-center">
                                12 Bulan
                            </button>
                        </div>

                        <div class="pt-2 border-t border-amber-200 flex justify-between items-center text-xs">
                            <span class="text-amber-800 font-medium">Estimasi Cicilan per Bulan:</span>
                            <span class="font-black text-amber-700 text-sm" id="modalMonthlyInstallmentText">Rp 0 / bln</span>
                        </div>
                    </div>
                </div>

                <!-- ADD TO CART BUTTON -->
                <div id="modalCartAction" class="pt-2">
                    <!-- Dynamic Button via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CART DRAWER & CHECKOUT SLIDE-OVER -->
<div id="cartDrawerOverlay" onclick="toggleCartDrawer()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9998] hidden"></div>

<div id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-[9999] transition-transform duration-300 translate-x-full flex flex-col justify-between border-l border-slate-200">
    <!-- Header -->
    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                <i class="fa-solid fa-cart-shopping text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base leading-tight">Keranjang & Checkout</h3>
                <p class="text-[11px] text-slate-400">Periksa daftar belanjaan & ongkir kurir</p>
            </div>
        </div>
        <button onclick="toggleCartDrawer()" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">&times;</button>
    </div>

    <!-- Cart Items List -->
    <div class="p-5 flex-1 overflow-y-auto space-y-4" id="cartItemsContainer">
        <!-- Rendered via JS -->
    </div>

    <!-- Checkout Form Footer -->
    <div class="p-5 border-t border-slate-100 bg-slate-50 space-y-3.5 max-h-[60vh] overflow-y-auto">
        <!-- Calculation Summary Box -->
        <div class="space-y-1.5 bg-white p-3.5 rounded-xl border border-slate-200">
            <div class="flex justify-between text-xs text-slate-600">
                <span>Subtotal Barang:</span>
                <span id="cartSubtotalText" class="font-bold text-slate-900">Rp 0</span>
            </div>
            <div class="flex justify-between text-xs text-slate-600">
                <span>Total Berat Barang:</span>
                <span id="cartWeightText" class="font-bold text-indigo-600">0 kg</span>
            </div>
            <div class="flex justify-between text-xs text-slate-600">
                <span>Ongkos Kirim Kurir:</span>
                <span id="cartShippingText" class="font-bold text-slate-900">Rp 0</span>
            </div>
            <div id="downPaymentRow" class="flex justify-between text-xs text-amber-600 font-bold hidden">
                <span>Uang Muka (DP 20%):</span>
                <span id="cartDPText">Rp 0</span>
            </div>
            <div id="monthlyRow" class="flex justify-between text-xs text-sky-600 font-bold hidden">
                <span>Estimasi Cicilan / Bln:</span>
                <span id="cartMonthlyText">Rp 0</span>
            </div>
            <div class="flex justify-between text-base font-black text-slate-900 pt-2 border-t border-slate-100">
                <span>Total Tagihan:</span>
                <span id="cartTotalText" class="text-sky-600 font-black">Rp 0</span>
            </div>
        </div>

        <!-- CHECKOUT FORM -->
        @auth
        <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)" enctype="multipart/form-data" class="space-y-3 pt-1">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Pemesan</label>
                <input type="text" id="custName" required value="{{ Auth::user()->name }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" id="custPhone" required placeholder="0812xxxx" value="{{ Auth::user()->phone }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Metode Bayar</label>
                    <select id="payMethod" name="payment_method" onchange="togglePaymentFields()" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none font-medium">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="credit">Kredit (Cicilan)</option>
                    </select>
                </div>
            </div>

            <!-- Tenor Selection (Visible if Credit) -->
            <div id="tenorWrapper" class="hidden">
                <label class="block text-[11px] font-bold text-amber-600 mb-1">Pilih Tenor Angsuran</label>
                <select id="creditTenor" onchange="updateCartSummary()" class="w-full bg-white border border-amber-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-amber-500 focus:outline-none font-medium">
                    <option value="3">3 Bulan (DP 20%)</option>
                    <option value="6">6 Bulan (DP 20%)</option>
                    <option value="12">12 Bulan (DP 20%)</option>
                </select>
            </div>
            <div id="ktpWrapper" class="hidden" style="margin-top: 0.5rem;">
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Upload KTP (Foto)</label>
                <input type="file" id="ktpFile" name="ktp_file" accept="image/*" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Alamat Pengiriman Lengkap</label>
                <textarea id="custAddress" required rows="2" placeholder="Alamat lengkap jalan, nomor, kecamatan, kota..." class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">{{ Auth::user()->address }}</textarea>
            </div>

            <button type="submit" id="btnSubmitOrder" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0">
                <i class="fa-solid fa-paper-plane"></i> Konfirmasi & Kirim Pesanan
            </button>
        </form>
        @else
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-center space-y-2">
            <p class="text-xs text-amber-800 font-semibold">Silakan masuk ke akun Anda terlebih dahulu untuk memproses pesanan.</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                <i class="fa-solid fa-right-to-bracket"></i> Login Pembeli
            </a>
        </div>
        @endauth
    </div>
</div>

@endsection

@push('scripts')
<script>
    const SHIPPING_COST_PER_KG = {{ $settings->shipping_cost_per_kg ?: 15000 }};
    let cart = [];
    let activeModalProduct = null;
    let selectedModalPaymentMode = 'cash';
    let selectedModalTenor = 3;

    // MODAL PAYMENT SELECTION
    function openProductDetail(product) {
        activeModalProduct = product;
        selectedModalPaymentMode = 'cash';
        selectedModalTenor = 3;

        document.getElementById('modalProductImage').src = product.image;
        document.getElementById('modalProductCategory').innerText = product.category ? product.category.name : 'Kategori';
        
        const weightGrams = product.weight || 1000;
        document.getElementById('modalProductWeight').innerText = '⚖️ ' + (weightGrams >= 1000 ? (weightGrams/1000) + ' kg' : weightGrams + ' g');
        
        document.getElementById('modalProductName').innerText = product.name;
        document.getElementById('modalProductDescription').innerText = product.description || 'Peralatan keramik kualitas premium dengan desain elegan.';

        document.querySelector('#modalSpec1 span').innerText = product.spec_1 || 'Keramik Mutu Tinggi & Glasir Halus';
        document.querySelector('#modalSpec2 span').innerText = product.spec_2 || 'Tahan Panas & Higienis Digunakan';
        document.querySelector('#modalSpec3 span').innerText = product.spec_3 || 'Garansi Pengiriman Aman & Anti Pecah';

        document.getElementById('modalCashPriceText').innerText = `Rp ${product.price.toLocaleString('id-ID')}`;

        const dp = product.price * 0.20;
        document.getElementById('modalCreditDpText').innerText = `DP 20% (Rp ${dp.toLocaleString('id-ID')})`;

        switchModalPaymentMode('cash');
        document.getElementById('productDetailModal').classList.add('active');
    }

    function closeProductDetail() {
        document.getElementById('productDetailModal').classList.remove('active');
    }

    function switchModalPaymentMode(mode) {
        selectedModalPaymentMode = mode;
        const tabCash = document.getElementById('tabModeCash');
        const tabCredit = document.getElementById('tabModeCredit');
        const tenorSection = document.getElementById('modalTenorSection');

        if (mode === 'cash') {
            tabCash.className = 'p-3.5 rounded-2xl border-2 border-sky-600 bg-sky-50 text-left transition-all shadow-sm';
            tabCredit.className = 'p-3.5 rounded-2xl border-2 border-slate-200 bg-white text-left hover:border-amber-400 transition-all';
            tenorSection.classList.add('hidden');
        } else {
            tabCredit.className = 'p-3.5 rounded-2xl border-2 border-amber-500 bg-amber-50 text-left transition-all shadow-sm';
            tabCash.className = 'p-3.5 rounded-2xl border-2 border-slate-200 bg-white text-left hover:border-sky-400 transition-all';
            tenorSection.classList.remove('hidden');
            recalculateModalInstallment();
        }

        updateModalActionButton();
    }

    function selectModalTenor(months, btn) {
        selectedModalTenor = months;
        document.querySelectorAll('.modal-tenor-btn').forEach(b => {
            b.className = 'modal-tenor-btn bg-white text-slate-700 font-bold text-xs p-2.5 rounded-xl border border-amber-300 text-center';
        });

        btn.className = 'modal-tenor-btn active bg-amber-600 text-white font-bold text-xs p-2.5 rounded-xl border border-amber-600 text-center shadow-sm';
        recalculateModalInstallment();
    }

    function recalculateModalInstallment() {
        if (!activeModalProduct) return;
        const total = activeModalProduct.price;
        const dp = total * 0.20;
        const remaining = total - dp;
        const monthly = Math.ceil(remaining / selectedModalTenor);

        document.getElementById('modalMonthlyInstallmentText').innerText = `Rp ${monthly.toLocaleString('id-ID')} / bulan`;
    }

    function updateModalActionButton() {
        const actionDiv = document.getElementById('modalCartAction');
        if (!activeModalProduct) return;

        if (activeModalProduct.stock > 0) {
            actionDiv.innerHTML = `
                <button type="button" onclick="addModalProductToCart()" class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs py-3.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-shopping"></i> Masukkan ke Keranjang (${selectedModalPaymentMode === 'cash' ? 'Bayar Tunai' : 'Cicilan ' + selectedModalTenor + ' Bln'})
                </button>
            `;
        } else {
            actionDiv.innerHTML = `
                <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold text-xs py-3.5 rounded-xl cursor-not-allowed border border-slate-200">
                    Stok Produk Habis
                </button>
            `;
        }
    }

    function addModalProductToCart() {
        if (!activeModalProduct) return;
        addToCart(activeModalProduct.id, activeModalProduct.name, activeModalProduct.price, activeModalProduct.image, activeModalProduct.stock, activeModalProduct.weight || 1000);
        closeProductDetail();
        toggleCartDrawer();
    }

    // CART MANAGEMENT
    function toggleCartDrawer() {
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartDrawerOverlay');
        
        if (drawer.classList.contains('translate-x-full')) {
            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            drawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    function addToCart(id, name, price, image, stock, weight = 1000) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            if (existing.quantity + 1 > stock) {
                alert(`Maaf, stok maksimal produk ini adalah ${stock} unit.`);
                return;
            }
            existing.quantity += 1;
        } else {
            cart.push({ id, name, price, image, quantity: 1, stock, weight: weight || 1000 });
        }
        renderCart();
    }

    function changeQuantity(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            if (delta > 0 && item.quantity + 1 > item.stock) {
                alert(`Maaf, stok maksimal produk ini adalah ${item.stock} unit.`);
                return;
            }
            item.quantity += delta;
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
            renderCart();
        }
    }

    function renderCart() {
        const countBadge = document.getElementById('cartCount');
        const container = document.getElementById('cartItemsContainer');
        
        const totalItems = cart.reduce((acc, i) => acc + i.quantity, 0);
        if (countBadge) countBadge.innerText = totalItems;

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-solid fa-cart-arrow-down text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-bold text-slate-700">Keranjang Masih Kosong</p>
                    <p class="text-xs text-slate-400 mt-1">Pilih produk di katalog untuk mulai berbelanja.</p>
                </div>
            `;
            updateCartSummary();
            return;
        }

        container.innerHTML = cart.map(item => `
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-200">
                <img src="${item.image}" alt="${item.name}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-xs text-slate-900 truncate">${item.name}</h4>
                    <p class="text-[11px] text-sky-600 font-extrabold mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p>
                    <span class="text-[10px] text-slate-400">⚖️ ${(item.weight * item.quantity >= 1000 ? ((item.weight * item.quantity)/1000) + ' kg' : (item.weight * item.quantity) + ' g')}</span>
                </div>
                <div class="flex items-center gap-1.5 bg-white px-2 py-1 rounded-xl border border-slate-200 shadow-sm">
                    <button onclick="changeQuantity(${item.id}, -1)" class="w-5 h-5 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-rose-600">-</button>
                    <span class="text-xs font-bold text-slate-900 px-1">${item.quantity}</span>
                    <button onclick="changeQuantity(${item.id}, 1)" class="w-5 h-5 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-sky-600">+</button>
                </div>
            </div>
        `).join('');

        updateCartSummary();
    }

    function togglePaymentFields() {
        const method = document.getElementById('payMethod').value;
        const tenorWrapper = document.getElementById('tenorWrapper');
        const ktpWrapper = document.getElementById('ktpWrapper');
        const ktpInput = document.getElementById('ktpFile');
        const dpRow = document.getElementById('downPaymentRow');
        const monthlyRow = document.getElementById('monthlyRow');

        if (method === 'credit') {
            tenorWrapper.classList.remove('hidden');
            ktpWrapper.classList.remove('hidden');
            dpRow.classList.remove('hidden');
            monthlyRow.classList.remove('hidden');
            if (ktpInput) ktpInput.required = true;
        } else {
            tenorWrapper.classList.add('hidden');
            ktpWrapper.classList.add('hidden');
            dpRow.classList.add('hidden');
            monthlyRow.classList.add('hidden');
            if (ktpInput) ktpInput.required = false;
        }
        updateCartSummary();
    }

    function updateCartSummary() {
        const subtotal = cart.reduce((acc, i) => acc + (i.price * i.quantity), 0);
        const totalWeightGrams = cart.reduce((acc, i) => acc + (i.weight * i.quantity), 0);
        const weightInKg = Math.max(cart.length > 0 ? 1 : 0, Math.ceil(totalWeightGrams / 1000));
        const shippingCost = cart.length > 0 ? (weightInKg * SHIPPING_COST_PER_KG) : 0;
        const grandTotal = subtotal + shippingCost;

        const method = document.getElementById('payMethod') ? document.getElementById('payMethod').value : 'cash';
        
        document.getElementById('cartSubtotalText').innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('cartWeightText').innerText = `${(totalWeightGrams/1000).toFixed(1)} kg (${weightInKg} kg dihitung)`;
        document.getElementById('cartShippingText').innerText = `Rp ${shippingCost.toLocaleString('id-ID')}`;

        if (method === 'credit') {
            const tenor = parseInt(document.getElementById('creditTenor').value || 3);
            const dp = grandTotal * 0.20;
            const remaining = grandTotal - dp;
            const monthly = Math.ceil(remaining / tenor);

            document.getElementById('cartDPText').innerText = `Rp ${dp.toLocaleString('id-ID')}`;
            document.getElementById('cartMonthlyText').innerText = `Rp ${monthly.toLocaleString('id-ID')} x ${tenor} Bln`;
            document.getElementById('cartTotalText').innerText = `Rp ${dp.toLocaleString('id-ID')} (DP Saja)`;
        } else {
            document.getElementById('cartTotalText').innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
        }
    }

    // SEARCH & FILTER
    function filterProducts() {
        const query = document.getElementById('catalogSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        
        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            if (name.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // CHECKOUT AJAX SUBMIT
    function handleCheckoutSubmit(e) {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Keranjang belanja Anda masih kosong!');
            return;
        }

        const btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Memproses Pesanan...';

        const formData = new FormData();
        formData.append('customer_name', document.getElementById('custName').value);
        formData.append('customer_phone', document.getElementById('custPhone').value);
        formData.append('shipping_address', document.getElementById('custAddress').value);
        formData.append('payment_method', document.getElementById('payMethod').value);
        if (document.getElementById('payMethod').value === 'credit') {
            formData.append('credit_tenor_months', document.getElementById('creditTenor').value);
        }
        formData.append('cart_items', JSON.stringify(cart.map(item => ({ product_id: item.id, quantity: item.quantity }))));
        const ktpInput = document.getElementById('ktpFile');
        if (ktpInput && ktpInput.files.length > 0) {
            formData.append('ktp_file', ktpInput.files[0]);
        }

        fetch("{{ route('order.store') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                cart = [];
                renderCart();
                alert(data.message || 'Pesanan Anda berhasil dibuat!');
                window.location.href = data.redirect;
            } else {
                alert('Gagal membuat pesanan: ' + (data.message || 'Periksa kembali data masukan Anda.'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Konfirmasi & Kirim Pesanan';
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan koneksi saat mengirim pesanan.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Konfirmasi & Kirim Pesanan';
        });
    }

    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
    });
</script>
@endpush
