<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .title { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th { background-color: #f3f4f6; text-align: left; padding: 8px; border: 1px solid #e5e7eb; font-weight: bold; }
        .details-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .totals-table { width: 40%; float: right; margin-top: 20px; border-collapse: collapse; }
        .totals-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .totals-table tr.grand-total { font-weight: bold; background-color: #f3f4f6; }
        .footer { margin-top: 150px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <span class="title">TokoKita Invoice</span><br>
                    Pesanan: #{{ $order->order_number }}<br>
                    Tanggal Pemesanan: {{ $order->created_at->format('d M Y') }}
                </td>
                <td style="text-align: right;">
                    <strong>Penjual (UMKM):</strong><br>
                    {{ $order->shop->name }}<br>
                    {{ $order->shop->address }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">
                    <strong>Penerima:</strong><br>
                    {{ $order->buyer->name }}<br>
                    {{ $order->shipping_address }}
                </td>
                <td style="text-align: right; padding-top: 20px;">
                    <strong>Metode Pengiriman:</strong><br>
                    {{ $order->shipment->courier_name ?? 'Regular Courier' }}
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Deskripsi Produk</th>
                    <th>Varian</th>
                    <th>Harga Satuan</th>
                    <th>Kuantitas</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->variant ? $item->variant->name : '-' }}</td>
                        <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td style="text-align: right;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount_amount > 0)
                <tr>
                    <td style="color: red;">Diskon</td>
                    <td style="text-align: right; color: red;">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td>Ongkos Kirim</td>
                <td style="text-align: right;">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total Bayar</td>
                <td style="text-align: right;">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div class="footer">
            Terima kasih telah berbelanja di TokoKita dan mendukung UMKM Indonesia!<br>
            Dokumen ini sah dan diterbitkan secara digital.
        </div>
    </div>
</body>
</html>
