<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CashierCheckoutAction
{
    public function handle(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($data['cart'] as $itemData) {
                $product = Product::findOrFail($itemData['id']);

                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi. (Stok: {$product->stock})");
                }

                $subtotal = $product->price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $itemsToCreate[] = [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }
            
            if ($data['amount_paid'] < $totalAmount) {
                throw new \Exception("Uang yang dibayarkan kurang dari total belanja.");
            }

            $changeAmount = $data['amount_paid'] - $totalAmount;

            $order = Order::create([
                'order_number'        => 'RK-POS-' . strtoupper(Str::random(6)),
                'user_id'             => Auth::id(),
                'payment_method'      => 'cash',
                'total_amount'        => $totalAmount,
                'status'              => 'completed',
                'payment_status'      => 'verified',
                'is_offline'          => true,
                'customer_name'       => $data['customer_name'] ?? 'Pelanggan Toko',
                'customer_phone'      => $data['customer_phone'] ?? '-',
                'shipping_address'    => 'Toko Offline',
            ]);

            foreach ($itemsToCreate as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);
                
                $product = $item['product'];
                $product->decrement('stock', $item['quantity']);
                
                StockMovement::create([
                    'product_id' => $product->id,
                    'type'       => 'out',
                    'quantity'   => $item['quantity'],
                    'notes'      => 'Penjualan Kasir Offline #' . $order->order_number,
                    'user_id'    => Auth::id(),
                ]);
            }
            
            $order->load('items.product');

            return [
                'order' => $order,
                'change' => $changeAmount,
            ];
        });
    }
}
