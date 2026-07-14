<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * Store new order from checkout.
     */
    public function store(Request $request, OrderService $orderService)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'courier' => 'required|string',
            'notes' => 'nullable|string|max:255'
        ]);

        try {
            $order = $orderService->createOrder(
                $cart,
                auth()->id(),
                $request->shipping_address,
                $request->courier,
                $request->notes,
                session()->get('cart_voucher')
            );

            // Clear session cart
            session()->forget('cart');
            session()->forget('cart_voucher');

            return redirect()->route('payments.confirm', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan konfirmasi pembayaran.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Buyer's dashboard listing orders.
     */
    public function buyerDashboard()
    {
        $orders = Order::where('buyer_id', auth()->id())->with(['shop', 'payment', 'shipment'])->latest()->get();
        return view('buyer.dashboard', compact('orders'));
    }

    /**
     * Show order details.
     */
    public function show($id)
    {
        $order = Order::with(['buyer', 'shop', 'items.product', 'items.variant', 'payment', 'shipment'])->findOrFail($id);
        
        // Ensure user is authorized to see the order
        $user = auth()->user();
        if ($user->role === 'buyer' && $order->buyer_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'seller' && $order->shop_id !== $user->shop->id) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        
        // Authorization check
        $user = auth()->user();
        if ($user->role === 'buyer' && $order->buyer_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'seller' && $order->shop_id !== $user->shop->id) {
            abort(403);
        }

        if ($order->status !== 'pending' && $order->status !== 'paid') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan pada status saat ini.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
    }

    /**
     * Request a return for an order.
     */
    public function returnOrder($id)
    {
        $order = Order::findOrFail($id);

        if (auth()->id() !== $order->buyer_id) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Hanya pesanan selesai yang dapat diajukan retur.');
        }

        $order->update(['status' => 'returned']);

        return redirect()->back()->with('success', 'Pengajuan retur berhasil dikirim.');
    }

    /**
     * Seller dashboard order manager.
     */
    public function sellerOrders(Request $request)
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('seller.dashboard');
        }

        $query = Order::where('shop_id', $shop->id)->with(['buyer', 'payment']);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Seller processes the order.
     */
    public function processOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah dibayar yang dapat diproses.');
        }

        $order->update(['status' => 'processed']);

        return redirect()->back()->with('success', 'Pesanan berhasil diproses.');
    }

    /**
     * Seller ships the order and inputs tracking number.
     */
    public function shipOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $request->validate([
            'tracking_number' => 'required|string|max:100'
        ]);

        if ($order->status !== 'processed') {
            return redirect()->back()->with('error', 'Hanya pesanan yang diproses yang dapat dikirim.');
        }

        $order->update(['status' => 'shipping']);

        $shipment = $order->shipment;
        if ($shipment) {
            $shipment->update([
                'tracking_number' => $request->tracking_number,
                'status' => 'shipped',
                'shipped_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Pesanan dikirim dan nomor resi disimpan.');
    }

    /**
     * Complete order.
     */
    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);
        
        $user = auth()->user();
        if ($user->role === 'buyer' && $order->buyer_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'seller' && $order->shop_id !== $user->shop->id) {
            abort(403);
        }

        $order->update(['status' => 'completed']);

        $shipment = $order->shipment;
        if ($shipment) {
            $shipment->update([
                'status' => 'delivered',
                'delivered_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Pesanan telah selesai.');
    }
}
