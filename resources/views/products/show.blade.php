<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - TokoKita</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                    Kembali ke Katalog
                </a>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Left: Product Image -->
            <div class="bg-slate-50 rounded-2xl aspect-square overflow-hidden border border-slate-100 flex items-center justify-center relative">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-24 h-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                @endif
                
                @if($product->discount_percentage > 0)
                    <span class="absolute top-4 left-4 bg-rose-500 text-white font-extrabold px-3 py-1 rounded-full text-xs">
                        Diskon {{ number_format($product->discount_percentage, 0) }}%
                    </span>
                @endif
            </div>

            <!-- Right: Product Info & Buy Form -->
            <div class="flex flex-col">
                <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full w-max uppercase tracking-wider">{{ $product->category->name }}</span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mt-4 leading-tight">{{ $product->name }}</h1>
                
                <!-- Shop badge -->
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-sm text-slate-500">Penjual:</span>
                    <a href="{{ route('shops.show', $product->shop->slug) }}" class="text-sm font-bold text-indigo-600 hover:underline">{{ $product->shop->name }}</a>
                </div>

                <!-- Pricing -->
                <div class="bg-slate-50 rounded-2xl p-4 mt-6">
                    @if($product->discount_percentage > 0)
                        <span class="text-sm text-slate-400 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        <div class="text-3xl font-black text-slate-800 mt-1">Rp{{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                    @else
                        <div class="text-3xl font-black text-slate-800">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                    
                    <div class="text-xs text-slate-500 mt-2">
                        Stok Tersedia: <strong class="text-slate-800">{{ $product->stock }}</strong>
                    </div>
                </div>

                <!-- Form to Add to Cart -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-6 flex-1 flex flex-col justify-end">
                    @csrf
                    
                    @if($product->variants->count() > 0)
                        <!-- Variant selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Varian:</label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($product->variants as $index => $variant)
                                    <label class="border border-slate-200 rounded-xl p-3 flex flex-col cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}" class="sr-only" {{ $index === 0 ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-slate-800">{{ $variant->name }}</span>
                                        <span class="text-xs text-indigo-600 font-semibold mt-1">Rp{{ number_format($variant->price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-slate-400 mt-1">Stok: {{ $variant->stock }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity selection -->
                    <div class="flex items-center gap-4 mb-6">
                        <label for="quantity" class="text-sm font-bold text-slate-700">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="1"
                               class="w-20 border border-slate-200 rounded-xl px-3 py-2 text-center text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    @auth
                        @if(auth()->user()->isBuyer())
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-100 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Masukkan Keranjang Belanja
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-4 px-6 rounded-2xl cursor-not-allowed">
                                Hanya Pembeli yang Bisa Belanja
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-4 px-6 rounded-2xl shadow-lg transition-all">
                            Masuk Untuk Membeli
                        </a>
                    @endauth
                </form>
            </div>
        </div>

        <!-- Description & Reviews -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <!-- Description -->
            <div class="md:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
                <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4">Deskripsi Produk</h2>
                <div class="text-slate-600 text-sm leading-relaxed mt-4 whitespace-pre-line">
                    {{ $product->description ?: 'Tidak ada deskripsi produk.' }}
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
                <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4">Ulasan Pembeli</h2>
                
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-3xl font-extrabold text-amber-500">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-sm text-slate-400">/ 5.0 ({{ $reviews->count() }} ulasan)</span>
                </div>

                <div class="mt-6 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($reviews as $rev)
                        <div class="border-b border-slate-50 pb-4 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <strong class="text-sm text-slate-800 block">{{ $rev->buyer->name }}</strong>
                                    <div class="flex gap-0.5 mt-1 text-amber-400">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-2 italic">"{{ $rev->comment }}"</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-6">Belum ada ulasan untuk produk ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>
