<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .surat-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .title { font-size: 20px; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 20px; }
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th { background-color: #f3f4f6; text-align: left; padding: 8px; border: 1px solid #ddd; font-weight: bold; }
        .details-table td { padding: 8px; border: 1px solid #ddd; }
        .sign-table { width: 100%; margin-top: 80px; text-align: center; }
        .sign-table td { width: 33%; }
        .sign-line { margin-top: 60px; border-bottom: 1px solid #333; width: 150px; display: inline-block; }
    </style>
</head>
<body>
    <div class="surat-box">
        <div class="title">Surat Jalan Pengiriman Barang</div>
        
        <table class="header-table">
            <tr>
                <td>
                    <strong>Dari (Toko Pengirim):</strong><br>
                    {{ $order->shop->name }}<br>
                    {{ $order->shop->address }}
                </td>
                <td style="text-align: right;">
                    <strong>Kepada (Penerima):</strong><br>
                    {{ $order->buyer->name }}<br>
                    {{ $order->shipping_address }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">
                    <strong>Nomor Pesanan:</strong> #{{ $order->order_number }}<br>
                    <strong>Tanggal Kirim:</strong> {{ date('d M Y') }}
                </td>
                <td style="text-align: right; padding-top: 20px;">
                    <strong>Ekspedisi Kurir:</strong> {{ $order->shipment->courier_name ?? '-' }}<br>
                    <strong>Nomor Resi:</strong> {{ $order->shipment->tracking_number ?? 'Belum ada' }}
                </td>
            </tr>
        </table>

        <p>Dengan surat ini kami kirimkan barang-barang berikut dengan baik:</p>

        <table class="details-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>Varian</th>
                    <th style="text-align: center;">Jumlah Kuantitas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->variant ? $item->variant->name : '-' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td>Baik</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="sign-table">
            <tr>
                <td>
                    Penerima,<br><br>
                    <span class="sign-line"></span>
                </td>
                <td>
                    Kurir Ekspedisi,<br><br>
                    <span class="sign-line"></span>
                </td>
                <td>
                    Hormat Kami (Penjual),<br><br>
                    <span class="sign-line"></span><br>
                    {{ $order->shop->name }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
