// ====== OVERVIEW CHART ======
document.addEventListener('DOMContentLoaded', function () {
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: JSON.parse(revenueCtx.dataset.labels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: JSON.parse(revenueCtx.dataset.data),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.3,
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

    const categorySalesCtx = document.getElementById('categorySalesChart');
    if (categorySalesCtx) {
        new Chart(categorySalesCtx, {
            type: 'doughnut',
            data: {
                labels: JSON.parse(categorySalesCtx.dataset.labels),
                datasets: [{
                    data: JSON.parse(categorySalesCtx.dataset.data),
                    backgroundColor: [
                        '#6366F1', '#F59E0B', '#10B981', '#EF4444', '#0EA5E9', '#EC4899', '#84CC16', '#7C3AED'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }

    const stockMovementCtx = document.getElementById('stockMovementChart');
    if (stockMovementCtx) {
        new Chart(stockMovementCtx, {
            type: 'bar',
            data: {
                labels: JSON.parse(stockMovementCtx.dataset.labels),
                datasets: [
                    {
                        label: 'Stok Masuk',
                        data: JSON.parse(stockMovementCtx.dataset.in),
                        backgroundColor: 'rgba(5, 150, 105, 0.7)',
                    },
                    {
                        label: 'Stok Keluar',
                        data: JSON.parse(stockMovementCtx.dataset.out),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    }
});

// Fungsi global untuk modal detail cicilan
window.viewInstallmentsModal = function(orderId, customerName, orderNumber) {
    const ordersData = window.ownerOrdersData || {};
    const ord = ordersData[orderId];
    if (!ord) return;

    document.getElementById('instModalTitle').textContent =
        `Pesanan #${orderNumber} — ${customerName} (${ord.customer_phone})`;

    let html = '';
    if (ord.installments && ord.installments.length > 0) {
        ord.installments.forEach(inst => {
            const statusBadge = inst.status === 'paid'
                ? '<span class="badge badge-emerald">Lunas</span>'
                : inst.status === 'overdue'
                    ? '<span class="badge badge-rose">Jatuh Tempo</span>'
                    : '<span class="badge badge-amber">Belum Bayar</span>';

            const paymentProofHtml = inst.payment_proof
                ? `<a href="${window.assetUrl + '/storage/' + inst.payment_proof}" target="_blank" class="text-blue-500 hover:underline">Lihat Bukti</a>`
                : '<span class="text-gray-500 italic">Belum ada bukti</span>';
            
            const actionButton = inst.status === 'unpaid' && inst.payment_proof
                ? `<form method="POST" action="/owner/installment/status/${inst.id}" onsubmit="return confirm('Verifikasi pembayaran ini?')" style="display:inline-block;margin-right:5px;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn-action btn-success btn-sm" style="font-size:0.7rem;padding:4px 8px;"><i class="fa-solid fa-check"></i> Verifikasi</button>
                    </form>
                    <form method="POST" action="/owner/installment/status/${inst.id}" onsubmit="return confirm('Tolak pembayaran ini?')" style="display:inline-block;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <input type="hidden" name="status" value="unpaid">
                        <button type="submit" class="btn-action btn-danger btn-sm" style="font-size:0.7rem;padding:4px 8px;"><i class="fa-solid fa-xmark"></i> Tolak</button>
                    </form>`
                : '';

            const dueDate = new Date(inst.due_date).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});

            html += `<tr>
                <td style="font-weight:700;">Angsuran Ke-${inst.installment_number}</td>
                <td style="font-weight:700;color:#0284C7;">Rp ${inst.amount.toLocaleString('id-ID')}</td>
                <td style="font-size:0.82rem;color:#64748B;">${dueDate}</td>
                <td>${statusBadge}</td>
                <td>${paymentProofHtml}</td>
                <td style="text-align:center;">${actionButton}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="6" style="text-align:center;color:#94A3B8;padding:1.5rem;">Tidak ada data cicilan.</td></tr>';
    }

    document.getElementById('instModalBody').innerHTML = html;
    document.getElementById('installmentModal').classList.add('active');
};