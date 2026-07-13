<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * View cart page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $voucher = session()->get('cart_voucher');

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($voucher) {
            $voucherModel = Voucher::where('code', $voucher['code'])->first();
            if ($voucherModel && $voucherModel->isValid($subtotal)) {
                $discount = $voucherModel->calculateDiscount($subtotal);
            } else {
                session()->forget('cart_voucher');
                $voucher = null;
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('cart.index', compact('cart', 'subtotal', 'discount', 'total', 'voucher'));
    }

    /**
     * Add product or variant to session cart.
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        $price = $product->discounted_price;
        $name = $product->name;
        $variantName = null;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);
            $price = $variant->price;
            $variantName = $variant->name;
        }

        $cart = session()->get('cart', []);
        $cartKey = $variantId ? $product->id . '-' . $variantId : $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'name' => $name,
                'variant_name' => $variantName,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $product->image,
                'shop_id' => $product->shop_id,
                'shop_name' => $product->shop->name
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity of cart item.
     */
    public function update(Request $request, $key)
    {
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$key]) && $quantity > 0) {
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui!');
        }

        return redirect()->route('cart.index')->with('error', 'Kuantitas tidak valid.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }

    /**
     * Apply voucher to the current cart.
     */
    public function applyVoucher(Request $request)
    {
        $code = $request->input('code');
        $voucher = Voucher::where('code', $code)->where('is_active', true)->first();

        if (!$voucher) {
            return redirect()->route('cart.index')->with('error', 'Kode voucher tidak valid.');
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if (!$voucher->isValid($subtotal)) {
            return redirect()->route('cart.index')->with('error', 'Minimal pembelanjaan Rp' . number_format($voucher->min_spend, 0, ',', '.') . ' tidak terpenuhi.');
        }

        session()->put('cart_voucher', [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => $voucher->value
        ]);

        return redirect()->route('cart.index')->with('success', 'Voucher berhasil digunakan!');
    }

    /**
     * Render the checkout form.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $subtotal = 0;
        $shopId = null;
        $shopName = '';
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $shopId = $item['shop_id'];
            $shopName = $item['shop_name'];
        }

        $voucher = session()->get('cart_voucher');
        $discount = 0;
        if ($voucher) {
            $voucherModel = Voucher::find($voucher['id']);
            if ($voucherModel) {
                $discount = $voucherModel->calculateDiscount($subtotal);
            }
        }

        // Mock courier options for simplicity
        $couriers = [
            ['name' => 'JNE Reguler', 'cost' => 15000, 'etd' => '2-3 Hari'],
            ['name' => 'Sicepat Halur', 'cost' => 12000, 'etd' => '1-2 Hari'],
            ['name' => 'J&T Express', 'cost' => 14000, 'etd' => '2 Hari'],
            ['name' => 'Ambil Sendiri', 'cost' => 0, 'etd' => 'Hari Ini']
        ];

        return view('cart.checkout', compact('cart', 'subtotal', 'discount', 'voucher', 'couriers', 'shopId', 'shopName'));
    }
}
