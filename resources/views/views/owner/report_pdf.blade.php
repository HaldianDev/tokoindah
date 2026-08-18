<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan — {{ $settings->site_name }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; color: #333; font-size: 12px; margin: 0; padding: 10px; }
        .header { text-align: center; border-bottom: 2px solid #0F172A; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0 0 5px; font-size: 20px; color: #0F172A; }
        .header p { margin: 2px 0; color: #64748B; font-size: 11px; }
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 20px; gap: 10px; }
        .summary-card { flex: 1; padding: 10px 15px; border: 1px solid #CBD5E1; border-radius: 8px; background: #F8FAFC; text-align: center; }
        .summary-card h4 { margin: 0 0 5px; color: #64748B; font-size: 11px; text-transform: uppercase; }
        .summary-card p { margin: 0; font-size: 16px; font-weight: bold; color: #0F172A; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #CBD5E1; padding: 8px 10px; text-align: left; }
        th { background-color: #0F172A; color: #FFFFFF; font-size: 11px; }
        tr:nth-child(even) { background-color: #F8FAFC; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #DCFCE7; color: #166534; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }
        .footer-note { margin-top: 30px; text-align: right; font-size: 11px; color: #64748B; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background: #0284C7; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="background: #64748B; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-left: 8px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>{{ $settings->site_name }}</h1>
        <p><strong>LAPORAN REKAPITULASI PENJUALAN & TRANSAKSI</strong></p>
        <p>{{ $settings->store_address }} | No. Telp/WA: {{ $settings->whatsapp_number }}</p>
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="summary-box">
        <div class="summary-card">
            <h4>Total Omset Penjualan</h4>
            <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h4>Total Transaksi</h4>
            <p>{{ $totalOrders }} Pesanan</p>
        </div>
        <div class="summary-card">
            <h4>Sisa Stok Produk</h4>
            <p>{{ number_format($totalStock, 0, ',', '.') }} Unit</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Pesanan</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 12%;">Metode</th>
                <th style="width: 12%;">Ongkir</th>
                <th style="width: 14%;">Total Tagihan</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $order->order_number }}</strong></td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <strong>{{ $order->customer_name ?: ($order->user->name ?? '-') }}</strong><br>
                    <span style="color:#64748B; font-size:10px;">{{ $order->customer_phone ?: ($order->user->phone ?? '-') }}</span>
                </td>
                <td>
                    {{ strtoupper($order->payment_method) }}
                    @if($order->payment_method === 'credit')
                        <br><span style="font-size: 10px; color:#D97706;">Tenor {{ $order->credit_tenor_months }}x Bln</span>
                    @endif
                </td>
                <td>Rp {{ number_format($order->shipping_cost ?: 0, 0, ',', '.') }}</td>
                <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                <td>
                    @if($order->status === 'completed')
                        <span class="badge badge-success">Lunas</span>
                    @elseif($order->status === 'cancelled' || $order->status === 'rejected')
                        <span class="badge badge-danger">Batal</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($order->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #94A3B8;">Tidak ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        <p>Tulang Bawang, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>( Pimpinan / Owner )</strong></p>
    </div>

    <script>
        // Auto print prompt when loaded
        window.addEventListener('DOMContentLoaded', () => {
            // window.print();
        });
    </script>
</body>
</html>
