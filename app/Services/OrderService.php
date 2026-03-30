<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createFromCart(array $cart, array $data): Order
    {
        return DB::transaction(function () use ($cart, $data) {

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak cukup untuk {$product->name}");
                }
            }

            $total = 0;

            $order = Order::create([
                'invoice_number' => Order::generateInvoiceNumber(),
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $this->normalizePhone($data['phone']),
                'province_id' => $data['province_id'],
                'province_name' => $data['province_name'],
                'regency_id' => $data['regency_id'],
                'regency_name' => $data['regency_name'],
                'district_id' => $data['district_id'],
                'district_name' => $data['district_name'],
                'address' => $data['address'],
                'subtotal' => 0,
                'total_price' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'image' => $product->image,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $order->update([
                'subtotal' => $total,
                'total_price' => $total,
            ]);

            return $order->fresh(['items']);
        });
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return '+' . $phone;
    }
}