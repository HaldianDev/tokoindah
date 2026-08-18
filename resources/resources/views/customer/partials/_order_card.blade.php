{{--
    Partial: _order_card.blade.php
    Used in: customer/orders.blade.php
    Variables: $order
--}}
<div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.75rem;box-shadow:0 2px 12px rgba(0,0,0,0.03);">

    {{-- Order Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #F1F5F9;padding-bottom:1rem;margin-bottom:1.25rem;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;border-radius:12px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;color:#2563EB;font-size:1rem;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div style="font-weight:800;font-size:1rem;color:#0F172A;">#{{ $order->order_number }}</div>
                <div style="font-size:0.78rem;color:#94A3B8;">{{ $order->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            @if($order->payment_method === 'credit')
                <span class="badge badge-amber"><i class="fa-solid fa-credit-card"></i> Angsuran ({{ $order->credit_tenor_months }}x Bulan)</span>
            @else
                <span class="badge badge-blue"><i class="fa-solid fa-money-bill-wave"></i> Cash / Lunas</span>
            @endif

            @if($order->status === 'completed')
                <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Selesai / Lunas</span>
            @elseif($order->status === 'approved')
                <span class="badge badge-sky"><i class="fa-solid fa-spinner"></i> Angsuran Berjalan</span>
            @elseif($order->status === 'processing')
                <span class="badge badge-indigo"><i class="fa-solid fa-gear"></i> Diproses</span>
            @elseif($order->status === 'cancelled')
                <span class="badge badge-rose"><i class="fa-solid fa-xmark"></i> Dibatalkan</span>
            @else
                <span class="badge badge-amber"><i class="fa-solid fa-clock"></i> Menunggu Verifikasi DP</span>
            @endif
        </div>
    </div>

    {{-- Order Items --}}
    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.25rem;">
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;padding:0.8rem 1.1rem;border-radius:10px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <img src="{{ $item->product->image }}"
                    style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #E2E8F0;"
                    alt="{{ $item->product->name }}">
                <div>
                    <div style="font-weight:700;font-size:0.9rem;color:#0F172A;">{{ $item->product->name }}</div>
                    <div style="font-size:0.8rem;color:#64748B;">{{ $item->quantity }} x Rp {{ number_format($item->price,0,',','.') }}</div>
                </div>
            </div>
            <div style="font-weight:800;color:#0284C7;">Rp {{ number_format($item->subtotal,0,',','.') }}</div>
        </div>
        @endforeach
    </div>

    {{-- Order Summary --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1.1rem;margin-bottom:{{ ($order->payment_method==='credit' && $order->installments->isNotEmpty()) ? '1.25rem' : '0' }};">
        <div>
            <div style="font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:6px;">Detail Penerima:</div>
            <div style="font-size:0.82rem;color:#64748B;"><strong>Nama:</strong> {{ $order->customer_name }} ({{ $order->customer_phone }})</div>
            <div style="font-size:0.82rem;color:#64748B;margin-top:2px;"><strong>Alamat:</strong> {{ $order->shipping_address }}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:0.8rem;color:#64748B;">Total Belanja:</div>
            <div style="font-size:1.3rem;font-weight:800;color:#0284C7;">Rp {{ number_format($order->total_amount,0,',','.') }}</div>
            @if($order->payment_method === 'credit')
                <div style="font-size:0.8rem;color:#64748B;margin-top:4px;">
                    DP (20%): <strong>Rp {{ number_format($order->down_payment,0,',','.') }}</strong> |
                    Cicilan: <strong style="color:#D97706;">Rp {{ number_format($order->monthly_installment,0,',','.') }}/bln</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Credit Installment Table --}}
    @if($order->payment_method === 'credit' && $order->installments->isNotEmpty())
    <div>
        <div style="display:flex;align-items:center;gap:8px;font-size:0.9rem;font-weight:800;color:#0F172A;margin-bottom:10px;">
            <i class="fa-solid fa-calendar-days" style="color:#0284C7;"></i> Jadwal Angsuran
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table" style="border:1px solid #E2E8F0;border-radius:10px;overflow:hidden;">
                <thead>
                    <tr>
                        <th style="background:#0F172A;color:#fff;">Angsuran</th>
                        <th style="background:#0F172A;color:#fff;">Nominal</th>
                        <th style="background:#0F172A;color:#fff;">Jatuh Tempo</th>
                        <th style="background:#0F172A;color:#fff;">Status</th>
                        <th style="background:#0F172A;color:#fff;text-align:center;">Aksi Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->installments as $inst)
                    <tr style="{{ $inst->installment_number === 0 ? 'background:#F0FDF4;' : '' }}">
                        @if($inst->installment_number === 0)
                            <td style="font-weight:800;color:#065F46;">
                                <i class="fa-solid fa-crown" style="color:#F59E0B;"></i> DP (20%)
                            </td>
                        @else
                            <td style="font-weight:700;color:#0F172A;">Bulan Ke-{{ $inst->installment_number }}</td>
                        @endif
                        <td style="font-weight:700;color:{{ $inst->installment_number === 0 ? '#059669' : '#D97706' }};">Rp {{ number_format($inst->amount,0,',','.') }}</td>
                        <td style="font-size:0.82rem;color:#64748B;">{{ \Carbon\Carbon::parse($inst->due_date)->format('d M Y') }}</td>
                        <td>
                            @if($inst->status === 'paid')
                                <span class="badge badge-emerald"><i class="fa-solid fa-circle-check"></i> Lunas</span>
                            @elseif($inst->payment_proof)
                                <span class="badge badge-amber"><i class="fa-solid fa-clock"></i> Menunggu Konfirmasi</span>
                            @elseif($inst->status === 'overdue')
                                <span class="badge badge-rose">Jatuh Tempo</span>
                            @else
                                <span class="badge badge-gray">Belum Bayar</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($inst->status === 'paid')
                                <span style="color:#059669;font-weight:700;font-size:0.82rem;">
                                    <i class="fa-solid fa-circle-check"></i> Dikonfirmasi Owner
                                </span>
                            @elseif($inst->payment_proof)
                                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                                    <span style="color:#D97706;font-weight:700;font-size:0.78rem;">
                                        <i class="fa-solid fa-hourglass-half"></i> Bukti Diunggah
                                    </span>
                                    <a href="{{ asset('storage/' . $inst->payment_proof) }}" target="_blank" style="font-size:0.72rem;color:#0284C7;text-decoration:underline;">
                                        Lihat Bukti Saya
                                    </a>
                                </div>
                            @else
                                <form method="POST" action="{{ route('installment.pay', $inst->id) }}" enctype="multipart/form-data"
                                    style="display:flex;gap:6px;align-items:center;justify-content:center;">
                                    @csrf
                                    <input type="file" name="payment_proof" required
                                        style="font-size:0.72rem;width:130px;color:#64748B;">
                                    <button type="submit" class="btn-action btn-primary btn-sm">
                                        <i class="fa-solid fa-upload"></i> Upload Bukti
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
