<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create an order from a cart, shipping address, and courier.
     *
     * @param array $cart
     * @param int $buyerId
     * @param string $shippingAddress
     * @param string $courier
     * @param string|null $notes
     * @param array|null $voucher
     * @return Order
     * @throws Exception
     */
    public function createOrder(array $cart, int $buyerId, string $shippingAddress, string $courier, ?string $notes = null, ?array $voucher = null): Order
    {
        // Decode courier details
        $courierParts = explode('|', $courier);
        $courierName = $courierParts[0];
        $shippingCost = floatval($courierParts[1]);
        $estimationTime = $courierParts[2] ?? '';

        return DB::transaction(function () use ($cart, $buyerId, $shippingAddress, $courierName, $shippingCost, $estimationTime, $notes, $voucher) {
            $subtotal = 0;
            $shopId = null;

            // 1. Validate stocks and calculate subtotal
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $shopId = $item['shop_id'];

                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                    if (!$variant || $variant->stock < $item['quantity']) {
                        throw new Exception("Stok untuk varian " . ($variant ? $variant->name : 'produk') . " tidak mencukupi.");
                    }
                } else {
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new Exception("Stok untuk produk " . ($product ? $product->name : 'produk') . " tidak mencukupi.");
                    }
                }
            }

            // 2. Calculate discounts
            $discountAmount = 0;
            $voucherId = null;

            if ($voucher) {
                $voucherId = $voucher['id'];
                if ($voucher['type'] === 'percent') {
                    $discountAmount = $subtotal * ($voucher['value'] / 100);
                } else {
                    $discountAmount = min($voucher['value'], $subtotal);
                }
            }

            $finalAmount = max(0, $subtotal - $discountAmount) + $shippingCost;
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 3. Create Order
            $order = Order::create([
                'buyer_id' => $buyerId,
                'shop_id' => $shopId,
                'voucher_id' => $voucherId,
                'order_number' => $orderNumber,
                'total_amount' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'notes' => $notes
            ]);

            // 4. Create Order Items and decrease stock
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount_amount' => 0.00,
                    'total' => $item['price'] * $item['quantity']
                ]);

                if (!empty($item['variant_id'])) {
                    ProductVariant::where('id', $item['variant_id'])->decrement('stock', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            // 5. Create Shipment
            Shipment::create([
                'order_id' => $order->id,
                'courier_name' => $courierName,
                'shipping_cost' => $shippingCost,
                'estimation_time' => $estimationTime,
                'status' => 'pending'
            ]);

            return $order;
        });
    }
}
