<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TokoKita - Marketplace UMKM</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts & CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Header/Navigation -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded-full">UMKM</span>
                </div>

                <!-- Search bar in center -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form action="{{ route('catalog') }}" method="GET" class="w-full relative">
                        <input type="text" name="search" placeholder="Cari produk kebutuhan Anda..." value="{{ request('search') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Dashboard</a>
                        @if(auth()->user()->isBuyer())
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @if(count(session('cart', [])) > 0)
                                    <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 text-white rounded-full text-[10px] flex items-center justify-center font-bold">{{ count(session('cart', [])) }}</span>
                                @endif
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-2xl transition-all">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-2xl shadow-lg shadow-indigo-100 transition-all">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-xl">
            <div class="absolute right-0 top-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -z-0"></div>
            <div class="relative z-10 max-w-xl">
                <span class="bg-indigo-500/20 text-indigo-300 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Skripsi UMKM Marketplace</span>
                <h1 class="text-3xl md:text-5xl font-black mt-4 leading-tight">Dukung Produk Lokal, Kembangkan UMKM Bersama TokoKita</h1>
                <p class="text-slate-300 mt-4 text-sm md:text-base font-light">Belanja langsung dari pemilik UMKM terbaik dengan sistem tracking aman, ulasan terverifikasi, dan manajemen pengiriman tepercaya.</p>
                <div class="mt-8 flex gap-4">
                    <a href="#katalog" class="bg-white text-indigo-900 font-bold px-6 py-3 rounded-2xl hover:scale-105 transition-all text-sm shadow-lg">Mulai Belanja</a>
                    <a href="{{ route('register') }}?role=seller" class="border border-indigo-400 hover:bg-indigo-800/40 text-white font-bold px-6 py-3 rounded-2xl transition-all text-sm">Gabung Jadi Penjual</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories & Filtering -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="katalog">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 pb-6 mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800">Katalog Produk UMKM</h2>
                <p class="text-slate-500 text-sm mt-1">Temukan produk unggulan dan diskon menarik dari mitra UMKM kami.</p>
            </div>

            <!-- Categories horizontal scroll -->
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('catalog') }}" 
                   class="px-4 py-2 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog') }}?category={{ $cat->slug }}" 
                       class="px-4 py-2 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

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

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="glass-card card-hover rounded-[24px] overflow-hidden flex flex-col h-full group">
                    <div class="relative bg-slate-100 aspect-square overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-all">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        @if($product->discount_percentage > 0)
                            <span class="absolute top-3 left-3 bg-rose-500 text-white text-xs font-extrabold px-2.5 py-1 rounded-full">
                                Diskon {{ number_format($product->discount_percentage, 0) }}%
                            </span>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ $product->category->name }}</span>
                        <h3 class="font-bold text-slate-800 text-sm mt-1 line-clamp-2 min-h-[40px]">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">{{ $product->shop->name }}</span>
                        </div>

                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                @if($product->discount_percentage > 0)
                                    <span class="text-xs text-slate-400 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <p class="font-extrabold text-slate-800 text-base">Rp{{ number_format($product->discounted_price, 0, ',', '.') }}</p>
                                @else
                                    <p class="font-extrabold text-slate-800 text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            
                            <a href="{{ route('products.show', $product->slug) }}" class="bg-indigo-50 group-hover:bg-indigo-600 group-hover:text-white p-2.5 rounded-2xl transition-all">
                                <svg class="w-5 h-5 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-slate-400 font-medium">Belum ada produk yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-12 mt-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold text-slate-300">TokoKita &copy; 2026. Aplikasi UMKM Skripsi.</p>
            <p class="text-xs text-slate-500 mt-2">Dibuat menggunakan Laravel 10 + MySQL + Tailwind CSS</p>
        </div>
    </footer>
</body>
</html>
