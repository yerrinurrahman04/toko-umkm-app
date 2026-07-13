<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan {{ $order->order_number }} - TokoKita</title>
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
                <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-indigo-600 flex items-center gap-1 text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-12 space-y-8">
        
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl text-sm font-semibold border border-rose-100">
                {{ session('error') }}
            </div>
        @endif

        <!-- Order Header & Downloads -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs text-slate-400">Nomor Pesanan</span>
                <h1 class="text-xl md:text-2xl font-black text-slate-800">{{ $order->order_number }}</h1>
                <p class="text-xs text-slate-500 mt-1">Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.invoice', $order->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Cetak Invoice (PDF)
                </a>
                
                @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                    <a href="{{ route('reports.surat_jalan', $order->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Surat Jalan (PDF)
                    </a>
                @endif
            </div>
        </div>

        <!-- Tracking Visual Funnel -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-6">Status Perjalanan Pesanan</h3>
            <div class="grid grid-cols-5 text-center items-center relative">
                <!-- Line -->
                <div class="absolute left-1/10 right-1/10 top-5 h-1 bg-slate-100 -z-0"></div>
                
                @php
                    $statusSteps = ['pending', 'paid', 'processed', 'shipping', 'completed'];
                    $currentIndex = array_search($order->status, $statusSteps);
                    if ($order->status === 'returned' || $order->status === 'cancelled') {
                        $currentIndex = -1; // special handling
                    }
                @endphp

                @foreach($statusSteps as $index => $step)
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition-all 
                            {{ $currentIndex >= $index ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-100 text-slate-400' }}">
                            {{ $index + 1 }}
                        </div>
                        <span class="text-[10px] uppercase font-bold mt-2 tracking-wider 
                            {{ $currentIndex >= $index ? 'text-indigo-600' : 'text-slate-400' }}">
                            {{ $step }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if($order->status === 'cancelled')
                <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl mt-6 text-sm font-semibold border border-rose-100 text-center">
                    Pesanan Ini Telah Dibatalkan.
                </div>
            @endif

            @if($order->status === 'returned')
                <div class="bg-orange-50 text-orange-800 p-4 rounded-2xl mt-6 text-sm font-semibold border border-orange-100 text-center">
                    Pesanan Ini Sedang Dalam Proses Retur Pengembalian Barang.
                </div>
            @endif
        </div>

        <!-- Order Items Detail -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-4 mb-4">Rincian Belanja</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <strong class="text-slate-800">{{ $item->product->name }}</strong>
                            @if($item->variant)
                                <span class="text-xs text-slate-400 block">Varian: {{ $item->variant->name }}</span>
                            @endif
                            <span class="text-xs text-slate-500 block">Harga: Rp{{ number_format($item->price, 0, ',', '.') }} | Jumlah: {{ $item->quantity }}x</span>
                        </div>
                        <span class="font-extrabold text-slate-800">Rp{{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>

                    <!-- Leave review option if order is completed and buyer is reading -->
                    @if($order->status === 'completed' && auth()->user()->isBuyer() && !$item->review)
                        <div class="bg-slate-50 rounded-2xl p-4 mt-2">
                            <h4 class="text-xs font-bold text-slate-700 mb-2">Tulis Ulasan untuk Produk ini:</h4>
                            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                
                                <div class="flex items-center gap-2">
                                    <label class="text-xs text-slate-500 font-semibold">Rating Bintang (1-5):</label>
                                    <select name="rating" required class="bg-white border border-slate-200 rounded-lg text-xs py-1 px-2">
                                        <option value="5">5 - Sangat Bagus</option>
                                        <option value="4">4 - Bagus</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="2">2 - Kurang</option>
                                        <option value="1">1 - Kecewa</option>
                                    </select>
                                </div>

                                <textarea name="comment" rows="2" placeholder="Tulis masukan Anda mengenai produk..."
                                          class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none"></textarea>
                                
                                <button type="submit" class="bg-indigo-600 text-white font-bold text-[10px] px-3 py-1.5 rounded-lg">Kirim Ulasan</button>
                            </form>
                        </div>
                    @elseif($item->review)
                        <div class="bg-emerald-50 text-emerald-800 p-3 rounded-2xl text-xs mt-2 italic border border-emerald-100">
                            Ulasan Anda: "{{ $item->review->comment }}" (Rating: {{ $item->review->rating }}/5)
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="border-t border-slate-100 pt-4 mt-6 text-sm space-y-2.5">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal Produk</span>
                    <span class="font-semibold text-slate-800">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-rose-500 font-semibold">
                        <span>Diskon Voucher</span>
                        <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-500">
                    <span>Biaya Kirim</span>
                    <span class="font-semibold text-slate-800">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-slate-100 pt-3 flex justify-between font-extrabold text-indigo-600 text-base">
                    <span>Total Bayar</span>
                    <span>Rp{{ number_format($order->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Address & Shipping Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-sm">Informasi Pengiriman</h3>
                <div class="text-xs text-slate-600 space-y-1.5">
                    <p><strong class="text-slate-700">Penerima:</strong> {{ $order->buyer->name }}</p>
                    <p><strong class="text-slate-700">Alamat:</strong> {{ $order->shipping_address }}</p>
                    <p><strong class="text-slate-700">Kurir:</strong> {{ $order->shipment->courier_name ?? 'Belum Ditentukan' }}</p>
                    <p><strong class="text-slate-700">No. Resi:</strong> {{ $order->shipment->tracking_number ?? 'Belum Tersedia' }}</p>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-sm">Status Pembayaran</h3>
                @if($order->payment)
                    <div class="text-xs text-slate-600 space-y-1.5">
                        <p><strong class="text-slate-700">Metode Bayar:</strong> {{ $order->payment->payment_method }}</p>
                        <p><strong class="text-slate-700">Jumlah Bayar:</strong> Rp{{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                        <p><strong class="text-slate-700">Status Verifikasi:</strong> 
                            <span class="font-bold uppercase {{ $order->payment->status === 'approved' ? 'text-emerald-600' : ($order->payment->status === 'rejected' ? 'text-rose-600' : 'text-amber-500') }}">
                                {{ $order->payment->status }}
                            </span>
                        </p>
                        @if($order->payment->receipt_image)
                            <a href="{{ asset('storage/' . $order->payment->receipt_image) }}" target="_blank" class="text-indigo-600 hover:underline block font-bold mt-2">Lihat Bukti Transfer</a>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum mengunggah bukti pembayaran.</p>
                @endif
            </div>
        </div>

        <!-- Order Cancellation / Return actions at bottom -->
        @if(auth()->user()->isBuyer())
            <div class="flex justify-end gap-2">
                @if($order->status === 'pending' || $order->status === 'paid')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if($order->status === 'completed')
                    <form action="{{ route('orders.return', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan retur pengembalian barang?')">
                        @csrf
                        <button type="submit" class="bg-orange-50 text-orange-700 hover:bg-orange-600 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                            Ajukan Retur Barang
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </main>
</body>
</html>
