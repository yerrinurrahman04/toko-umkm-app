<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Produk - {{ $shopName }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 11px; text-align: center; color: #666; margin-bottom: 25px; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th { background-color: #f3f4f6; text-align: left; padding: 6px; border: 1px solid #ddd; font-weight: bold; }
        .details-table td { padding: 6px; border: 1px solid #ddd; }
        .footer { margin-top: 50px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="title">Laporan Stok Produk UMKM</div>
    <div class="subtitle">Unit Usaha: {{ $shopName }} | Tanggal Cetak: {{ date('d M Y H:i') }}</div>

    <table class="details-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Harga Pokok</th>
                <th>Stok Utama</th>
                <th>Daftar Varian Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if($product->variants->count() > 0)
                            @foreach($product->variants as $variant)
                                {{ $variant->name }} (Stok: {{ $variant->stock }}, Rp{{ number_format($variant->price, 0, ',', '.') }}), 
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dikelola secara digital oleh sistem TokoKita
    </div>
</body>
</html>
