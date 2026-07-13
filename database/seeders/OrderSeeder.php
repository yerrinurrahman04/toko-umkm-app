<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['pending', 'paid', 'processed', 'shipping', 'completed', 'cancelled', 'returned'];
        
        // Setup dates (last 30 days)
        $ordersCount = 105; // Create 105 orders to satisfy the "minimum 100" requirement
        $days = 30;

        // Fetch products by shop to ensure orders don't mix products from different shops
        $productsByShop = [];
        for ($s = 1; $s <= 3; $s++) {
            $productsByShop[$s] = DB::table('products')->where('shop_id', $s)->get();
        }

        // Fetch variants mapping
        $variants = DB::table('product_variants')->get()->groupBy('product_id');

        $orderId = 1;
        $orderItemId = 1;
        $paymentId = 1;
        $shipmentId = 1;
        $reviewId = 1;

        $ordersData = [];
        $orderItemsData = [];
        $paymentsData = [];
        $shipmentsData = [];
        $reviewsData = [];

        // Daily summaries tracker: [shop_id][date] => [sales => X, orders => Y]
        $dailySummaries = [];

        for ($i = 1; $i <= $ordersCount; $i++) {
            $buyerId = rand(5, 14); // 10 buyers: ID 5 to 14
            $shopId = rand(1, 3); // 3 shops: ID 1 to 3
            
            // Distribute status
            if ($i <= 15) {
                $status = 'pending';
            } elseif ($i <= 30) {
                $status = 'paid';
            } elseif ($i <= 45) {
                $status = 'processed';
            } elseif ($i <= 60) {
                $status = 'shipping';
            } elseif ($i <= 92) {
                $status = 'completed';
            } elseif ($i <= 98) {
                $status = 'cancelled';
            } else {
                $status = 'returned';
            }

            // Assign date
            $orderDate = now()->subDays(rand(1, $days))->subHours(rand(1, 23))->subMinutes(rand(1, 59));

            // Select 1 to 2 random products from this shop
            $shopProducts = $productsByShop[$shopId];
            if ($shopProducts->isEmpty()) {
                continue;
            }
            $numItems = rand(1, 2);
            $selectedProducts = $shopProducts->random(min($numItems, $shopProducts->count()));

            $totalAmount = 0.00;
            $itemsTemp = [];

            foreach ($selectedProducts as $prod) {
                $qty = rand(1, 2);
                $price = $prod->price;
                $variantId = null;

                // Check if product has variants
                if (isset($variants[$prod->id])) {
                    $prodVariants = $variants[$prod->id];
                    $selectedVariant = $prodVariants->random();
                    $variantId = $selectedVariant->id;
                    $price = $selectedVariant->price;
                }

                $discountProduct = ($price * ($prod->discount_percentage / 100)) * $qty;
                $subtotal = ($price * $qty) - $discountProduct;
                $totalAmount += $subtotal;

                $itemsTemp[] = [
                    'product_id' => $prod->id,
                    'variant_id' => $variantId,
                    'price' => $price,
                    'quantity' => $qty,
                    'discount_amount' => $discountProduct,
                    'total' => $subtotal,
                ];
            }

            // Voucher application
            $voucherId = null;
            $discountVoucher = 0.00;
            // 30% chance of voucher
            if (rand(1, 10) <= 3 && $totalAmount >= 50000) {
                $vouchers = [
                    ['id' => 1, 'min_spend' => 50000, 'type' => 'percent', 'value' => 10],
                    ['id' => 2, 'min_spend' => 100000, 'type' => 'fixed', 'value' => 15000],
                ];
                $voucher = $vouchers[rand(0, 1)];
                if ($totalAmount >= $voucher['min_spend']) {
                    $voucherId = $voucher['id'];
                    if ($voucher['type'] === 'percent') {
                        $discountVoucher = $totalAmount * ($voucher['value'] / 100);
                    } else {
                        $discountVoucher = $voucher['value'];
                    }
                }
            }

            $shippingCost = 15000.00; // Fixed flat shipping cost
            $finalAmount = ($totalAmount - $discountVoucher) + $shippingCost;

            $ordersData[] = [
                'id' => $orderId,
                'buyer_id' => $buyerId,
                'shop_id' => $shopId,
                'voucher_id' => $voucherId,
                'order_number' => 'ORD-' . $orderDate->format('Ymd') . '-' . sprintf('%04d', $i),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountVoucher,
                'shipping_cost' => $shippingCost,
                'final_amount' => $finalAmount,
                'status' => $status,
                'shipping_address' => 'Jl. Jenderal Sudirman No. ' . rand(1, 150) . ', Jakarta',
                'notes' => rand(0, 1) ? 'Tolong dikirim cepat ya.' : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate->addDays(rand(1, 2)),
            ];

            // Add items
            foreach ($itemsTemp as $item) {
                $orderItemsData[] = [
                    'id' => $orderItemId,
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount_amount' => $item['discount_amount'],
                    'total' => $item['total'],
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];

                // If completed, add review (50% chance)
                if ($status === 'completed' && rand(1, 2) === 1) {
                    $comments = [
                        'Sangat puas dengan barangnya!',
                        'Sesuai deskripsi, mantap betul.',
                        'Pengiriman cepat, packing sangat rapi.',
                        'Kualitas produk sangat baik untuk harga segini.',
                        'Pelayanan penjual luar biasa ramah.',
                        'Barang sampai dengan aman tanpa cacat.'
                    ];
                    $reviewsData[] = [
                        'id' => $reviewId++,
                        'product_id' => $item['product_id'],
                        'buyer_id' => $buyerId,
                        'order_item_id' => $orderItemId,
                        'rating' => rand(4, 5),
                        'comment' => $comments[rand(0, 5)],
                        'is_moderated' => rand(1, 10) <= 8, // 80% auto approved
                        'created_at' => $orderDate->addDays(3),
                        'updated_at' => $orderDate->addDays(3),
                    ];
                }

                $orderItemId++;
            }

            // Payments (for anything except pending and cancelled)
            if ($status !== 'pending' && $status !== 'cancelled') {
                $paymentsData[] = [
                    'id' => $paymentId++,
                    'order_id' => $orderId,
                    'amount' => $finalAmount,
                    'payment_method' => 'Transfer Bank Manual',
                    'receipt_image' => 'receipts/mock_receipt.jpg',
                    'status' => 'approved',
                    'notes' => 'Pembayaran terverifikasi otomatis seeder.',
                    'verified_at' => $orderDate->addMinutes(rand(30, 240)),
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            // Shipments (for shipping and completed)
            if ($status === 'shipping' || $status === 'completed') {
                $shipmentsData[] = [
                    'id' => $shipmentId++,
                    'order_id' => $orderId,
                    'courier_name' => rand(0, 1) ? 'J&T Express' : 'JNE Reguler',
                    'tracking_number' => 'JN' . rand(100000000, 999999999) . 'ID',
                    'status' => ($status === 'completed') ? 'delivered' : 'shipped',
                    'shipping_cost' => $shippingCost,
                    'estimation_time' => '2-3 hari',
                    'shipped_at' => $orderDate->addHours(rand(12, 24)),
                    'delivered_at' => ($status === 'completed') ? $orderDate->addDays(rand(2, 4)) : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            // Aggregate daily summaries for completed orders
            if ($status === 'completed') {
                $dateString = $orderDate->toDateString();
                if (!isset($dailySummaries[$shopId][$dateString])) {
                    $dailySummaries[$shopId][$dateString] = ['sales' => 0.00, 'orders' => 0];
                }
                $dailySummaries[$shopId][$dateString]['sales'] += $finalAmount;
                $dailySummaries[$shopId][$dateString]['orders'] += 1;
            }

            $orderId++;
        }

        // Insert order collections in chunks
        DB::table('orders')->insert($ordersData);
        DB::table('order_items')->insert($orderItemsData);
        if (!empty($paymentsData)) {
            DB::table('payments')->insert($paymentsData);
        }
        if (!empty($shipmentsData)) {
            DB::table('shipments')->insert($shipmentsData);
        }
        if (!empty($reviewsData)) {
            DB::table('reviews')->insert($reviewsData);
        }

        // Insert Daily summaries
        $summariesData = [];
        foreach ($dailySummaries as $sId => $dates) {
            foreach ($dates as $date => $metrics) {
                $summariesData[] = [
                    'shop_id' => $sId,
                    'date' => $date,
                    'total_sales' => $metrics['sales'],
                    'total_orders' => $metrics['orders'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($summariesData)) {
            DB::table('daily_sales_summaries')->insert($summariesData);
        }
    }
}
