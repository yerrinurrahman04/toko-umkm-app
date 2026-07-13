<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - TokoKita</title>
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
                <a href="{{ route('buyer.dashboard') }}" class="text-slate-600 hover:text-indigo-600 flex items-center gap-1 text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard Saya
                </a>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
            </div>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Konfirmasi Pembayaran</h1>
                <p class="text-slate-500 text-xs mt-1">Silakan lakukan transfer manual ke salah satu rekening bank mitra kami, lalu unggah bukti transfer di bawah ini.</p>
            </div>

            <!-- Bank Accounts Info -->
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 space-y-3">
                <h4 class="text-xs font-bold text-indigo-800 uppercase tracking-wider">Rekening Tujuan Transfer:</h4>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <strong class="text-slate-700 block">Bank BCA (TokoKita)</strong>
                        <span class="font-mono text-indigo-700 font-bold block mt-0.5">8002-1234-99</span>
                    </div>
                    <div>
                        <strong class="text-slate-700 block">Bank Mandiri (TokoKita)</strong>
                        <span class="font-mono text-indigo-700 font-bold block mt-0.5">131-00-5555-888</span>
                    </div>
                </div>
            </div>

            <!-- Order Total Info -->
            <div class="border-y border-slate-100 py-4 flex justify-between items-center text-sm">
                <div>
                    <span class="text-slate-500 block text-xs">Nomor Pesanan</span>
                    <strong class="text-slate-800">{{ $order->order_number }}</strong>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 block text-xs">Jumlah Tagihan</span>
                    <strong class="text-indigo-600 font-black text-lg">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('payments.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="payment_method" class="block text-xs font-bold text-slate-500 mb-2">Metode Pembayaran (Bank Anda)</label>
                    <input type="text" id="payment_method" name="payment_method" required placeholder="Contoh: Transfer Bank BCA / Mandiri / BNI atas nama..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label for="amount" class="block text-xs font-bold text-slate-500 mb-2">Jumlah Transfer (Rp)</label>
                    <input type="number" id="amount" name="amount" required value="{{ $order->final_amount }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label for="receipt_image" class="block text-xs font-bold text-slate-500 mb-2">Unggah Bukti Struk / Screenshot Transfer</label>
                    <input type="file" id="receipt_image" name="receipt_image" required accept="image/*"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-500 mb-2">Catatan Pembayaran (Opsional)</label>
                    <input type="text" id="notes" name="notes" placeholder="Contoh: Pembayaran order beras pandan wangi..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02] text-center mt-6">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </main>
</body>
</html>
