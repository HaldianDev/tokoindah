<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement; // Re-added
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Policies\OrderPolicy;

use App\Http\Requests\StoreOrderRequest;
use App\Actions\Checkout\CreateOrderAction;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction)
    {
        $ktpPath = null;
        if ($request->hasFile('ktp_file')) {
            $ktpPath = $request->file('ktp_file')->store('ktp', 'private_uploads');
        }

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof_file')) {
            $paymentProofPath = $request->file('payment_proof_file')->store('payment_proofs', 'private_uploads');
        }

        try {
            $order = $createOrderAction->handle($request->validated(), $ktpPath, $paymentProofPath);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Anda telah berhasil dibuat!',
                'redirect' => route('order.show', $order),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $settings = WebSetting::getSettings();
        return view('order.show', compact('order', 'settings'));
    }
    
    public function payInstallment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:3072',
        ]);

        $installment = Installment::findOrFail($id);
        $this->authorize('update', $installment->order);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'private_uploads');
            $installment->update([
                'payment_proof' => $path,
                'status' => 'verifying',
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran angsuran berhasil diunggah! Menunggu verifikasi & persetujuan Owner.');
    }
}
