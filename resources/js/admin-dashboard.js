document.addEventListener('DOMContentLoaded', function () {
    // ====== OVERVIEW CHART ======
    const ctx = document.getElementById('adminStatsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Produk', 'Pesanan Pending', 'Stok Habis', 'Pesanan Cash', 'Pesanan Credit', 'Selesai'],
                datasets: [{
                    label: 'Jumlah',
                    data: JSON.parse(ctx.dataset.stats),
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
});

// Fungsi global karena mungkin dipanggil dari komponen lain
window.viewInstallmentsModal = function(orderId) {
    const ordersData = window.ordersData || {};
    const ord = ordersData[orderId];
    if (!ord) return;

    document.getElementById('instModalTitle').textContent =
        `Pesanan #${ord.order_number} — ${ord.customer_name} (${ord.customer_phone})`;

    let html = '';
    if (ord.installments && ord.installments.length > 0) {
        ord.installments.forEach(inst => {
            const statusBadge = inst.status === 'paid'
                ? '<span class="badge badge-emerald">Lunas</span>'
                : inst.status === 'overdue'
                    ? '<span class="badge badge-rose">Jatuh Tempo</span>'
                    : '<span class="badge badge-amber">Belum Bayar</span>';

            const due = new Date(inst.due_date).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});
            html += `<tr>
                <td style="font-weight:700;">Bulan Ke-${inst.installment_number}</td>
                <td style="font-weight:700;color:#D97706;">Rp ${inst.amount.toLocaleString('id-ID')}</td>
                <td style="font-size:0.82rem;color:#64748B;">${due}</td>
                <td>${statusBadge}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="4" style="text-align:center;color:#94A3B8;padding:1.5rem;">Tidak ada data cicilan.</td></tr>';
    }

    document.getElementById('instModalBody').innerHTML = html;
    document.getElementById('installmentModal').classList.add('active');
}

window.editCategory = function(id, name, icon) {
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryIcon').value = icon;
    document.getElementById('editCategoryForm').action = '/admin/category/' + id;
    document.getElementById('editCategoryModal').classList.add('active');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});

window.formatRupiahInput = function(el) {
    let val = el.value.replace(/\D/g, '');
    if (val === '') {
        el.value = '';
        return;
    }
    el.value = parseInt(val, 10).toLocaleString('id-ID');
}

window.parseRupiahValue = function(str) {
    if (!str) return 0;
    return parseInt(str.replace(/\./g, '').replace(/\D/g, ''), 10) || 0;
}

let cashierCart = [];
let lastReceiptOrder = null;
let lastReceiptCashPaid = 0;

window.addProductToCashier = function(productId, name, price, maxStock) {
    const existing = cashierCart.find(item => item.product_id === productId);
    if (existing) {
        if (existing.quantity >= maxStock) {
            Swal.fire('Stok Tidak Cukup', `Stok produk '${name}' tidak mencukupi untuk ditambah lagi.`, 'warning');
            return;
        }
        existing.quantity += 1;
    } else {
        cashierCart.push({
            product_id: productId,
            name: name,
            price: price,
            quantity: 1,
            max_stock: maxStock
        });
    }
    renderCashierCart();
}

function updateCashierQuantity(productId, newQty) {
    const item = cashierCart.find(item => item.product_id === productId);
    if (!item) return;

    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty < 1) {
        newQty = 1;
    }

    if (newQty > item.max_stock) {
        Swal.fire('Stok Terbatas', `Stok produk '${item.name}' tidak mencukupi (Stok maksimal: ${item.max_stock}).`, 'warning');
        newQty = item.max_stock;
    }

    item.quantity = newQty;
    renderCashierCart();
}

function removeCashierProduct(productId) {
    cashierCart = cashierCart.filter(item => item.product_id !== productId);
    renderCashierCart();
}

function renderCashierCart() {
    const cartContainer = document.getElementById('cashierCartItems');
    const cartCountBadge = document.getElementById('cashierCartCount');
    const totalLabel = document.getElementById('cashierTotalAmountLabel');

    if (cartContainer) {
        if (cashierCart.length === 0) {
            cartContainer.innerHTML = `<div style="text-align:center;padding:2rem;color:#94A3B8;font-style:italic;font-size:0.85rem;">Keranjang masih kosong.</div>`;
            cartCountBadge.textContent = '0 Item';
            totalLabel.textContent = 'Rp 0';
            calculateCashierChange();
            return;
        }

        let totalAmount = 0;
        let totalItemsCount = 0;
        let html = '';

        cashierCart.forEach(item => {
            const subtotal = item.price * item.quantity;
            totalAmount += subtotal;
            totalItemsCount += item.quantity;

            html += `
                <div style="display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;padding:0.75rem;border-radius:10px;border:1px solid #E2E8F0;gap:10px;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.82rem;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</div>
                        <div style="font-size:0.75rem;color:#64748B;">Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <input type="number" min="1" max="${item.max_stock}" value="${item.quantity}"
                            onchange="updateCashierQuantity(${item.product_id}, this.value)"
                            style="width:50px;text-align:center;padding:3px;font-size:0.8rem;border:1px solid #CBD5E1;border-radius:6px;font-weight:700;">
                        <button onclick="removeCashierProduct(${item.product_id})"
                            style="background:none;border:none;color:#EF4444;cursor:pointer;font-size:0.9rem;padding:4px;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    <div style="font-weight:800;color:#0284C7;font-size:0.82rem;min-width:80px;text-align:right;">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </div>
                </div>
            `;
        });

        cartContainer.innerHTML = html;
        cartCountBadge.textContent = `${totalItemsCount} Item`;
        totalLabel.textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
        calculateCashierChange();
    }
}

function calculateCashierChange() {
    const totalAmount = cashierCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const cashPaidInput = document.getElementById('cashierCashPaid');
    const changeLabel = document.getElementById('cashierChangeLabel');

    let cashPaid = parseRupiahValue(cashPaidInput.value);
    if (isNaN(cashPaid) || cashPaid <= 0) {
        changeLabel.textContent = 'Rp 0';
        changeLabel.style.color = '#34D399';
        return;
    }

    const change = cashPaid - totalAmount;
    changeLabel.textContent = `Rp ${change.toLocaleString('id-ID')}`;
    if (change < 0) {
        changeLabel.textContent = `Kurang Rp ${Math.abs(change).toLocaleString('id-ID')}`;
        changeLabel.style.color = '#EF4444';
    } else {
        changeLabel.style.color = '#34D399';
    }
}

function filterCashierProducts() {
    const searchVal = document.getElementById('cashierSearch').value.toLowerCase();
    const catVal = document.getElementById('cashierCategoryFilter').value;
    const cards = document.querySelectorAll('.cashier-product-card');

    cards.forEach(card => {
        const name = card.dataset.name;
        const cat = card.dataset.category;

        const matchesSearch = name.includes(searchVal);
        const matchesCat = catVal === '' || cat === catVal;

        if (matchesSearch && matchesCat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function checkoutCashier() {
    if (cashierCart.length === 0) {
        Swal.fire('Keranjang Kosong', 'Keranjang belanja kosong!', 'warning');
        return;
    }

    const totalAmount = cashierCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const cashPaidInput = document.getElementById('cashierCashPaid');
    const cashPaid = parseRupiahValue(cashPaidInput.value);

    if (isNaN(cashPaid) || cashPaid < totalAmount) {
        Swal.fire('Pembayaran Kurang', 'Nominal uang yang diterima kurang dari total belanja!', 'error');
        return;
    }

    const customerName = document.getElementById('cashierCustomerName').value || 'Pelanggan Umum';
    const customerPhone = document.getElementById('cashierCustomerPhone').value || '-';

    const data = {
        customer_name: customerName,
        customer_phone: customerPhone,
        amount_paid: cashPaid,
        cart: cashierCart,
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(window.adminRoutes.cashierCheckout, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            lastReceiptCashPaid = cashPaid;
            lastReceiptOrder = res.order;
            renderReceiptContent(res.order, cashPaid);
            document.getElementById('cashierReceiptModal').classList.add('active');
            
            cashierCart = [];
            document.getElementById('cashierCustomerName').value = '';
            document.getElementById('cashierCustomerPhone').value = '';
            document.getElementById('cashierCashPaid').value = '';
            renderCashierCart();
        } else {
            Swal.fire('Error', 'Error: ' + res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Terjadi kesalahan saat memproses checkout.', 'error');
    });
}

function renderReceiptContent(order, cashPaid) {
    const printArea = document.getElementById('print-receipt-area');
    const appSettings = window.appSettings || {};
    const dateStr = new Date(order.created_at).toLocaleDateString('id-ID', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });

    let itemsHtml = '';
    order.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        itemsHtml += `
            <div style="display:flex; justify-content:space-between; margin-bottom: 2px;">
                <span>${item.product.name} (x${item.quantity})</span>
                <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>
        `;
    });

    const change = cashPaid - order.total_amount;

    printArea.innerHTML = `
        <div style="text-align:center; margin-bottom:1rem;">
            <h3 style="font-size:1.1rem; font-weight:bold; margin:0;">${appSettings.site_name || 'RUMAH KERAMIK'}</h3>
            <p style="margin:2px 0 0 0; font-size:0.75rem;">${appSettings.store_address || ''}</p>
            <p style="margin:1px 0 0 0; font-size:0.75rem;">WA: ${appSettings.whatsapp_number || ''}</p>
        </div>
        <div style="border-top:1px dashed #000; border-bottom:1px dashed #000; padding:4px 0; margin-bottom:8px; font-size:0.78rem;">
            <div>No. Struk: ${order.order_number}</div>
            <div>Waktu    : ${dateStr}</div>
            <div>Kasir    : ${window.userName || 'Admin'}</div>
            <div>Pelanggan: ${order.customer_name} (${order.customer_phone})</div>
        </div>
        <div style="margin-bottom:8px; font-size:0.78rem;">
            ${itemsHtml}
        </div>
        <div style="border-top:1px dashed #000; padding-top:4px; font-size:0.78rem;">
            <div style="display:flex; justify-content:space-between; font-weight:bold;">
                <span>TOTAL BELANJA</span>
                <span>Rp ${order.total_amount.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>UANG BAYAR</span>
                <span>Rp ${cashPaid.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-weight:bold; color:#000;">
                <span>KEMBALIAN</span>
                <span>Rp ${change.toLocaleString('id-ID')}</span>
            </div>
        </div>
        <div style="text-align:center; margin-top:1.5rem; font-size:0.75rem;">
            * Terima Kasih Atas Kunjungan Anda *<br>
            Barang yang sudah dibeli tidak dapat ditukar
        </div>
    `;
}

function printCashierReceipt() {
    window.print();
}

function sendReceiptToWhatsApp() {
    if (!lastReceiptOrder) {
        Swal.fire('Data Tidak Ada', 'Tidak ada data struk untuk dikirim.', 'error');
        return;
    }
    const appSettings = window.appSettings || {};
    const order = lastReceiptOrder;
    const cashPaid = lastReceiptCashPaid;
    const change = cashPaid - order.total_amount;
    const phone = order.customer_phone && order.customer_phone !== '-' ? order.customer_phone.replace(/^0/, '62') : '';

    let items = '';
    order.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        items += `▪ ${item.product.name} (x${item.quantity}) = Rp ${subtotal.toLocaleString('id-ID')}\n`;
    });

    const text = `🧾 *STRUK BELANJA - ${appSettings.site_name}*\n` +
        `📍 ${appSettings.store_address}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        `No. Struk: *${order.order_number}*\n` +
        `Pelanggan: ${order.customer_name}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        items +
        `━━━━━━━━━━━━━━━━━━\n` +
        `*TOTAL: Rp ${order.total_amount.toLocaleString('id-ID')}*\n` +
        `Bayar: Rp ${cashPaid.toLocaleString('id-ID')}\n` +
        `Kembali: Rp ${change.toLocaleString('id-ID')}\n` +
        `━━━━━━━━━━━━━━━━━━\n` +
        `Terima kasih atas kunjungan Anda! 🙏`;

    const encodedText = encodeURIComponent(text);
    const waUrl = phone ? `https://wa.me/${phone}?text=${encodedText}` : `https://wa.me/?text=${encodedText}`;
    window.open(waUrl, '_blank');
}

function reprintOfflineOrder(order) {
    lastReceiptOrder = order;
    lastReceiptCashPaid = order.total_amount;
    renderReceiptContent(order, order.total_amount);
    document.getElementById('cashierReceiptModal').classList.add('active');
}

// Chained dropdown
document.getElementById('stockAddCategory').addEventListener('change', function() {
    const categoryId = this.value;
    const productSelect = document.getElementById('stockAddProduct');
    productSelect.innerHTML = '<option value="">Memuat produk...</option>';
    productSelect.disabled = true;

    if (!categoryId) {
        productSelect.innerHTML = '<option value="">-- Pilih Kategori Dulu --</option>';
        return;
    }

    fetch(`/admin/api/categories/${categoryId}/products`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(products => {
            productSelect.innerHTML = '<option value="">-- Pilih Produk --</option>';
            if (products.length === 0) {
                productSelect.innerHTML = '<option value="">-- Tidak ada produk di kategori ini --</option>';
            } else {
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.name} (Stok: ${product.stock})`;
                    productSelect.appendChild(option);
                });
            }
            productSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            productSelect.innerHTML = '<option value="">-- Gagal memuat produk --</option>';
        });
});
