<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pesanan - TokoKita</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="antialiased">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('cart.index') }}" class="text-slate-600 hover:text-indigo-600 flex items-center gap-1 text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Keranjang
                </a>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-8">Checkout Pesanan</h1>

        <form action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- Form Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shop & Products Info -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 text-sm mb-4">Detail Toko & Produk</h3>
                    <div class="mb-4">
                        <span class="text-xs text-slate-400">Pembelian dari toko:</span>
                        <h4 class="font-bold text-indigo-600 text-sm mt-0.5">{{ $shopName }}</h4>
                    </div>

                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center text-sm border-b border-slate-50 pb-3 last:border-0">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $item['name'] }}</span>
                                    @if($item['variant_name'])
                                        <span class="text-xs text-slate-400 block">Varian: {{ $item['variant_name'] }}</span>
                                    @endif
                                    <span class="text-xs text-slate-500 block">Kuantitas: {{ $item['quantity'] }}x</span>
                                </div>
                                <span class="font-semibold text-slate-800">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Form -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm">Informasi Pengiriman</h3>

                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-slate-500 mb-2">Alamat Pengiriman Lengkap</label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required placeholder="Masukkan alamat lengkap pengiriman, beserta kode pos dan nomor telepon aktif..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                    </div>

                    <div>
                        <label for="courier" class="block text-xs font-bold text-slate-500 mb-2">Pilih Metode Pengiriman (Kurir)</label>
                        <select id="courier" name="courier" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            @foreach($couriers as $courier)
                                <option value="{{ $courier['name'] }}|{{ $courier['cost'] }}|{{ $courier['etd'] }}">
                                    {{ $courier['name'] }} (Estimasi: {{ $courier['etd'] }}) - Rp{{ number_format($courier['cost'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-500 mb-2">Catatan Tambahan (Opsional)</label>
                        <input type="text" id="notes" name="notes" placeholder="Contoh: Titipkan di satpam, warna cadangan..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Receipt Summary -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-4 mb-4">Total Rincian</h3>

                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal Belanja</span>
                            <span class="font-semibold text-slate-800">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-rose-500 font-semibold">
                                <span>Diskon Voucher</span>
                                <span>-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-500">
                            <span>Biaya Pengiriman</span>
                            <span class="font-semibold text-slate-800" id="shipping_fee_label">Rp15.000</span>
                        </div>
                        <div class="border-t border-slate-100 pt-4 flex justify-between font-extrabold text-indigo-600 text-base">
                            <span>Total Pembayaran</span>
                            <span id="grand_total_label">Rp{{ number_format(max(0, $subtotal - $discount) + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02] text-center">
                        Buat Pesanan Sekarang
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const courierSelect = document.getElementById('courier');
        const shippingFeeLabel = document.getElementById('shipping_fee_label');
        const grandTotalLabel = document.getElementById('grand_total_label');
        
        const subtotal = {{ max(0, $subtotal - $discount) }};

        courierSelect.addEventListener('change', function() {
            const parts = this.value.split('|');
            const fee = parseFloat(parts[1]);
            
            shippingFeeLabel.textContent = 'Rp' + fee.toLocaleString('id-ID');
            grandTotalLabel.textContent = 'Rp' + (subtotal + fee).toLocaleString('id-ID');
        });
    </script>
</body>
</html>
