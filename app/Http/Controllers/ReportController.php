<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Exports\StockExport;
use App\Exports\SalesRecapExport;
use App\Exports\OrdersBuyersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Generate Invoice PDF.
     */
    public function invoicePdf($id)
    {
        $order = Order::with(['buyer', 'shop', 'items.product', 'items.variant', 'payment', 'shipment'])->findOrFail($id);
        
        // Safety check: only buyer or seller of the order, or admin can print
        $user = auth()->user();
        if ($user->role === 'buyer' && $order->buyer_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'seller' && $order->shop_id !== $user->shop->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('reports.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Generate Surat Jalan (Delivery Note) PDF.
     */
    public function suratJalanPdf($id)
    {
        $order = Order::with(['buyer', 'shop', 'items.product', 'items.variant', 'shipment'])->findOrFail($id);

        $user = auth()->user();
        if ($user->role === 'seller' && $order->shop_id !== $user->shop->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('reports.surat_jalan', compact('order'));
        return $pdf->download('surat-jalan-' . $order->order_number . '.pdf');
    }

    /**
     * Generate Stock Report PDF.
     */
    public function stockPdf()
    {
        $user = auth()->user();
        $shopId = null;
        $shopName = 'Sistem Utama';

        if ($user->role === 'seller') {
            $shop = $user->shop;
            if (!$shop) {
                return redirect()->route('seller.dashboard');
            }
            $shopId = $shop->id;
            $shopName = $shop->name;
            $products = Product::where('shop_id', $shopId)->with(['category', 'variants'])->get();
        } else {
            // Admin can view all
            $products = Product::with(['category', 'shop', 'variants'])->get();
        }

        $pdf = Pdf::loadView('reports.stock', compact('products', 'shopName'));
        return $pdf->download('laporan-stok-' . Str::slug($shopName) . '-' . date('Ymd') . '.pdf');
    }

    /**
     * Export Stock Report Excel.
     */
    public function stockExcel()
    {
        $user = auth()->user();
        $shopId = $user->role === 'seller' ? $user->shop->id : null;
        return Excel::download(new StockExport($shopId), 'laporan-stok-' . date('Ymd') . '.xlsx');
    }

    /**
     * Export Sales Recap Excel.
     */
    public function salesRecapExcel()
    {
        $user = auth()->user();
        $shopId = $user->role === 'seller' ? $user->shop->id : null;
        return Excel::download(new SalesRecapExport($shopId), 'rekap-penjualan-' . date('Ymd') . '.xlsx');
    }

    /**
     * Export Orders and Buyers Excel.
     */
    public function ordersBuyersExcel()
    {
        $user = auth()->user();
        $shopId = $user->role === 'seller' ? $user->shop->id : null;
        return Excel::download(new OrdersBuyersExport($shopId), 'ekspor-pesanan-pembeli-' . date('Ymd') . '.xlsx');
    }
}
