<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Installment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateOrderAction
{
    public function handle(array $data, $ktpPath = null, $paymentProofPath = null): Order
    {
        $cart = json_decode($data['cart'], true);
        $paymentMethod = $data['payment_method'];

        return DB::transaction(function () use ($data, $cart, $paymentMethod, $ktpPath, $paymentProofPath) {
            $totalAmount = 0;
            $totalWeight = 0;
            $orderItemsData = [];
            $settings = WebSetting::getSettings();

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }
                
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;
                $totalWeight += ($product->weight ?: 1000) * $item['quantity'];

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
                
                $product->decrement('stock', $item['quantity']);
            }
            
            $shippingCost = ceil($totalWeight / 1000) * $settings->shipping_cost_per_kg;
            $grandTotal = $totalAmount + $shippingCost;

            $downPayment = 0;
            $monthlyInstallment = 0;
            if ($paymentMethod === 'credit') {
                $tenor = (int) $data['credit_tenor_months'];
                $downPayment = $grandTotal * 0.20;
                $remainingForCredit = $grandTotal - $downPayment;
                $monthlyInstallment = ceil($remainingForCredit / $tenor);
            }

            $order = Order::create([
                'order_number'        => 'RK-' . strtoupper(Str::random(8)),
                'user_id'             => Auth::id(),
                'payment_method'      => $paymentMethod,
                'credit_tenor_months' => $paymentMethod === 'credit' ? $data['credit_tenor_months'] : null,
                'down_payment'        => $downPayment,
                'monthly_installment' => $monthlyInstallment,
                'total_amount'        => $grandTotal,
                'status'              => $paymentMethod === 'cash'
                                        ? ($paymentProofPath ? 'verifying_payment' : 'waiting_payment')
                                        : Order::STATUS_PENDING_DP,
                'payment_status'      => 'unpaid',
                'customer_name'       => $data['customer_name'],
                'customer_phone'      => $data['customer_phone'],
                'shipping_address'    => $data['shipping_address'],
                'notes'               => $data['notes'] ?? null,
                'ktp_path'            => $ktpPath, // Re-added
                'shipping_cost'       => $shippingCost,
                'total_weight'        => $totalWeight,
                'payment_proof_path'  => $paymentProofPath, // Re-added
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            if ($paymentMethod === 'credit') {
                $tenor = (int) $data['credit_tenor_months'];
                // Create DP "installment"
                Installment::create([
                    'order_id' => $order->id,
                    'installment_number' => 0,
                    'amount' => $downPayment,
                    'due_date' => Carbon::now()->addDays(2),
                    'status' => 'unpaid'
                ]);
                // Create monthly installments
                for ($i = 1; $i <= $tenor; $i++) {
                    Installment::create([
                        'order_id' => $order->id,
                        'installment_number' => $i,
                        'amount' => $monthlyInstallment,
                        'due_date' => Carbon::now()->addMonths($i),
                        'status' => 'unpaid'
                    ]);
                }
            }
            
            return $order;
        });
    }
}
