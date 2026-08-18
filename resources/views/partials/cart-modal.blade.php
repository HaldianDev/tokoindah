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
            <div id="rekeningInfo" class="hidden text-center pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-600">Mohon transfer ke rekening:</p>
                <p class="text-base font-black text-sky-700">0888888888888888</p>
            </div>
        </div>

        <!-- CHECKOUT FORM -->
        @auth
        <form id="checkoutForm" enctype="multipart/form-data" class="space-y-3 pt-1">
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

            <!-- Bukti Pembayaran (Visible if Cash) -->
            <div id="paymentProofWrapper" class="hidden" style="margin-top: 0.5rem;">
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Upload Bukti Pembayaran</label>
                <input type="file" id="paymentProofFile" name="payment_proof_file" accept="image/*" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none">
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
