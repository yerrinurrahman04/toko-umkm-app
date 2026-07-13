<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Product::with(['category', 'shop', 'variants']);
        if ($this->shopId) {
            $query->where('shop_id', $this->shopId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Produk',
            'Nama Toko',
            'Kategori',
            'Nama Produk',
            'Harga Pokok',
            'Stok Utama',
            'Varian Terdaftar',
            'Tanggal Dibuat'
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        return [
            $product->id,
            $product->shop->name,
            $product->category->name,
            $product->name,
            $product->price,
            $product->stock,
            $product->variants->pluck('name')->implode(', ') ?: 'Tidak ada varian',
            $product->created_at->format('Y-m-d H:i')
        ];
    }
}
