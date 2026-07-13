<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment confirmation upload form.
     */
    public function create($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if (auth()->id() !== $order->buyer_id) {
            abort(403);
        }

        return view('payments.confirm', compact('order'));
    }

    /**
     * Store payment confirmation upload.
     */
    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if (auth()->id() !== $order->buyer_id) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:255'
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptPath = $request->file('receipt_image')->store('payments/receipts', 'public');
        }

        Payment::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'receipt_image' => $receiptPath,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('buyer.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi penjual.');
    }

    /**
     * Seller verifies and approves/rejects payment.
     */
    public function verifyPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $order = $payment->order;

        if ($order->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:255'
        ]);

        $payment->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'verified_at' => now()
        ]);

        if ($request->status === 'approved') {
            $order->update(['status' => 'paid']);
        } else {
            // Reverted back to pending or payment rejected state
            $order->update(['status' => 'pending']);
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
