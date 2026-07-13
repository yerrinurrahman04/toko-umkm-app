<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersBuyersExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Order::with(['buyer', 'shop']);
        if ($this->shopId) {
            $query->where('shop_id', $this->shopId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pesanan',
            'Toko UMKM',
            'Nama Pembeli',
            'Email Pembeli',
            'Total Belanja (Rp)',
            'Status Pesanan',
            'Alamat Pengiriman',
            'Tanggal Dibuat'
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
            $order->buyer->email,
            $order->final_amount,
            strtoupper($order->status),
            $order->shipping_address,
            $order->created_at->format('Y-m-d H:i')
        ];
    }
}
