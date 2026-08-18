// resources/js/app.js
import 'bootstrap'; // Import Bootstrap's JavaScript

// Global variables will be set in layouts/app.blade.php
// const SHIPPING_COST_PER_KG;
// const ORDER_STORE_ROUTE;

let cart = [];
let activeModalProduct = null;
let selectedModalPaymentMode = 'cash';
let selectedModalTenor = 3;

// Helper functions for cart persistence
function saveCartToLocalStorage() {
    localStorage.setItem('shoppingCart', JSON.stringify(cart));
}

function loadCartFromLocalStorage() {
    const storedCart = localStorage.getItem('shoppingCart');
    if (storedCart) {
        cart = JSON.parse(storedCart);
    }
}

// Expose functions to global scope
window.openProductDetail = openProductDetail;
window.closeProductDetail = closeProductDetail;
window.switchModalPaymentMode = switchModalPaymentMode;
window.selectModalTenor = selectModalTenor;
window.recalculateModalInstallment = recalculateModalInstallment;
window.updateModalActionButton = updateModalActionButton;
window.addModalProductToCart = addModalProductToCart;
window.toggleCartDrawer = toggleCartDrawer;
window.addToCart = addToCart;
window.changeQuantity = changeQuantity;
window.renderCart = renderCart;
window.togglePaymentFields = togglePaymentFields;
window.updateCartSummary = updateCartSummary;
window.filterProducts = filterProducts; // for catalogSearch
window.handleCheckoutSubmit = handleCheckoutSubmit;
window.removeItemFromCart = removeItemFromCart;

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
    document.getElementById('productDetailModalOverlay').classList.add('active');
}

function closeProductDetail() {
    document.getElementById('productDetailModalOverlay').classList.remove('active');
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
    saveCartToLocalStorage();
    window.location.href = '/cart'; // Redirect to cart page
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
        saveCartToLocalStorage();
    }
}

function removeItemFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    renderCart();
    saveCartToLocalStorage();
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
            <button onclick="removeItemFromCart(${item.id})" class="ml-2 w-7 h-7 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-rose-600 bg-white rounded-xl border border-slate-200 shadow-sm flex-shrink-0" title="Hapus item">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    `).join('');

    updateCartSummary();
}

function togglePaymentFields() {
    const method = document.getElementById('payMethod').value;
    const tenorWrapper = document.getElementById('tenorWrapper');
    const dpWrapper = document.getElementById('dpWrapper');
    const ktpWrapper = document.getElementById('ktpWrapper');
    const ktpInput = document.getElementById('ktpFile');
    const bankTransferDetails = document.getElementById('bankTransferDetails');
    const paymentProofUpload = document.getElementById('paymentProofUpload');
    const paymentProofFile = document.getElementById('paymentProofFile');

    if (method === 'credit') {
        tenorWrapper.classList.remove('hidden');
        dpWrapper.classList.remove('hidden'); // Show DP/Monthly summary for credit
        ktpWrapper.classList.remove('hidden');
        if (ktpInput) ktpInput.required = true;
        if (paymentProofFile) paymentProofFile.required = false; // Not required for credit

        bankTransferDetails.classList.add('hidden'); // Hide bank details for credit
        paymentProofUpload.classList.add('hidden'); // Hide proof upload for credit
    } else { // method === 'cash'
        tenorWrapper.classList.add('hidden');
        dpWrapper.classList.add('hidden'); // Hide DP/Monthly summary for cash
        ktpWrapper.classList.add('hidden'); // Hide KTP for cash
        if (ktpInput) ktpInput.required = false; // Not required for cash

        bankTransferDetails.classList.remove('hidden'); // Show bank details for cash
        paymentProofUpload.classList.remove('hidden'); // Show proof upload for cash
        // Only make paymentProofFile required if there are items in the cart
        if (paymentProofFile) paymentProofFile.required = (cart.length > 0);
    }
    updateCartSummary();
}

function updateCartSummary() {
    const subtotal = cart.reduce((acc, i) => acc + (i.price * i.quantity), 0);
    const totalWeightGrams = cart.reduce((acc, i) => acc + (i.weight * i.quantity), 0);
    const weightInKg = Math.max(cart.length > 0 ? 1 : 0, Math.ceil(totalWeightGrams / 1000));
    const shippingCost = cart.length > 0 ? (weightInKg * window.SHIPPING_COST_PER_KG) : 0;
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

// SEARCH & FILTER (only relevant for catalog page, but kept global for now)
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
    formData.append('cart', JSON.stringify(cart.map(item => ({ product_id: item.id, quantity: item.quantity }))));
    const ktpInput = document.getElementById('ktpFile');
    if (ktpInput && ktpInput.files.length > 0) {
        formData.append('ktp_file', ktpInput.files[0]);
    }

    const payMethod = document.getElementById('payMethod').value;
    if (payMethod === 'cash') {
        const paymentProofFile = document.getElementById('paymentProofFile');
        if (paymentProofFile && paymentProofFile.files.length > 0) {
            formData.append('payment_proof_file', paymentProofFile.files[0]);
        }
    }

    fetch(window.ORDER_STORE_ROUTE, {
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
            saveCartToLocalStorage(); // Clear local storage cart too
            renderCart();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Pesanan Anda telah berhasil dibuat!',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal membuat pesanan: ' + (data.message || 'Periksa kembali data masukan Anda.'),
                showConfirmButton: true
            });
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
    loadCartFromLocalStorage();
    renderCart();
});