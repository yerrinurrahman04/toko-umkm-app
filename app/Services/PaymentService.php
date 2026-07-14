<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Store payment confirmation upload.
     *
     * @param int $orderId
     * @param float $amount
     * @param string $paymentMethod
     * @param UploadedFile|null $receiptFile
     * @param string|null $notes
     * @return Payment
     * @throws Exception
     */
    public function storePayment(int $orderId, float $amount, string $paymentMethod, ?UploadedFile $receiptFile, ?string $notes = null): Payment
    {
        $order = Order::findOrFail($orderId);

        $receiptPath = null;
        if ($receiptFile) {
            $receiptPath = $receiptFile->store('payments/receipts', 'public');
        }

        return Payment::create([
            'order_id' => $order->id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'receipt_image' => $receiptPath,
            'status' => 'pending',
            'notes' => $notes
        ]);
    }

    /**
     * Verify and approve/reject payment.
     *
     * @param int $paymentId
     * @param string $status
     * @param string|null $notes
     * @return Payment
     */
    public function verifyPayment(int $paymentId, string $status, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($paymentId, $status, $notes) {
            $payment = Payment::findOrFail($paymentId);
            $order = $payment->order;

            $payment->update([
                'status' => $status,
                'notes' => $notes,
                'verified_at' => now()
            ]);

            if ($status === 'approved') {
                $order->update(['status' => 'paid']);
            } else {
                $order->update(['status' => 'pending']);
            }

            return $payment;
        });
    }
}
