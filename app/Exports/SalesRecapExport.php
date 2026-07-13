<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesRecapExport implements FromCollection, WithHeadings, WithMapping
{
    protected $shopId;

    public function __construct($shopId = null)
    {
        $this->shopId = $shopId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Order::with(['buyer', 'shop'])->where('status', 'completed');
        if ($this->shopId) {
            $query->where('shop_id', $this->shopId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pesanan',
            'Toko',
            'Pembeli',
            'Subtotal (Rp)',
            'Diskon (Rp)',
            'Ongkir (Rp)',
            'Total Bersih (Rp)',
            'Tanggal Selesai'
        ];
    }

    /**
    * @var Order $order
    */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->shop->name,
            $order->buyer->name,
            $order->total_amount,
            $order->discount_amount,
            $order->shipping_cost,
            $order->final_amount,
            $order->updated_at->format('Y-m-d H:i')
        ];
    }
}
