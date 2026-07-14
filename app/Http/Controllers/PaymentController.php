<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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

        try {
            $this->paymentService->storePayment(
                $order->id,
                $request->amount,
                $request->payment_method,
                $request->file('receipt_image'),
                $request->notes
            );

            return redirect()->route('buyer.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi penjual.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
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

        try {
            $this->paymentService->verifyPayment($payment->id, $request->status, $request->notes);
            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
