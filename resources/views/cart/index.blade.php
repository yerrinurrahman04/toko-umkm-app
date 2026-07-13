<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - TokoKita</title>
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
                <a href="{{ route('catalog') }}" class="text-slate-600 hover:text-indigo-600 flex items-center gap-1 text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Katalog Utama
                </a>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-8">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-6 text-sm font-semibold border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl mb-6 text-sm font-semibold border border-rose-100">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <svg class="w-20 h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-lg font-bold text-slate-700">Keranjang Belanja Kosong</h3>
                <p class="text-slate-500 text-sm mt-1 mb-6">Silakan pilih produk unggulan UMKM terlebih dahulu di katalog kami.</p>
                <a href="{{ route('catalog') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-2xl transition-all shadow-md">Belanja Sekarang</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $key => $item)
                        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm flex gap-4 items-center">
                            <!-- Image -->
                            <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden flex-shrink-0 flex items-center justify-center border border-slate-100">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1">
                                <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-full">{{ $item['shop_name'] }}</span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1 leading-tight">{{ $item['name'] }}</h4>
                                @if($item['variant_name'])
                                    <span class="text-xs text-slate-500 font-medium block mt-0.5">Varian: {{ $item['variant_name'] }}</span>
                                @endif
                                <span class="text-sm font-extrabold text-slate-800 block mt-2">Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center gap-3">
                                <form action="{{ route('cart.update', $key) }}" method="POST" class="flex items-center gap-1.5 border border-slate-200 rounded-xl p-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                           class="w-12 border-0 bg-transparent text-center text-sm font-semibold p-1 focus:ring-0 focus:outline-none"
                                           onchange="this.form.submit()">
                                </form>

                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:bg-rose-50 p-2.5 rounded-2xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary & Voucher -->
                <div class="space-y-6">
                    <!-- Voucher Form -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-sm mb-4">Gunakan Voucher Belanja</h3>
                        <form action="{{ route('cart.voucher') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="KODE VOUCHER" value="{{ $voucher['code'] ?? '' }}"
                                   class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-indigo-500 focus:border-indigo-500 uppercase font-bold">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md">Pakai</button>
                        </form>
                        @if($voucher)
                            <div class="bg-indigo-50 text-indigo-800 p-2.5 rounded-xl mt-3 text-xs flex justify-between items-center font-bold">
                                <span>Voucher Terpasang: {{ $voucher['code'] }}</span>
                                <span class="bg-indigo-600 text-white px-2 py-0.5 rounded-md">
                                    {{ $voucher['type'] === 'percent' ? $voucher['value'].'%' : 'Rp'.number_format($voucher['value'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Receipt Summary -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-4 mb-4">Ringkasan Belanja</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Total Item</span>
                                <span class="font-semibold text-slate-800">{{ collect($cart)->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal Produk</span>
                                <span class="font-semibold text-slate-800">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-rose-500 font-semibold">
                                    <span>Potongan Diskon</span>
                                    <span>-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="border-t border-slate-100 pt-4 flex justify-between font-extrabold text-slate-800 text-base">
                                <span>Total Tagihan</span>
                                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-100 mt-6 block text-center transition-all hover:scale-[1.02]">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </main>
</body>
</html>
