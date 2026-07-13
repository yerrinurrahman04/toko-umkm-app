<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50/50">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Main Layout Wrapper -->
            <div class="flex-1 flex flex-col md:flex-row max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 gap-8">
                
                <!-- Conditionally render Sidebar for Sellers and Admins -->
                @auth
                    @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                        <aside class="w-full md:w-64 shrink-0">
                            <div class="glass-card shadow-premium rounded-[24px] p-6 sticky top-24 space-y-2 border border-slate-100/50">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block px-3 mb-4">Navigasi Panel</span>
                                
                                @if(auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('seller.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                                        Dashboard Toko
                                    </a>
                                    <a href="{{ route('seller.products.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('seller.products.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V4m0 12L4 11m8 4v10l8-4m-8-10l8 4m-8-4L4 11"></path></svg>
                                        Kelola Produk
                                    </a>
                                    <a href="{{ route('seller.orders.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('seller.orders.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Pesanan Masuk
                                    </a>
                                    <a href="{{ route('seller.vouchers.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('seller.vouchers.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        Voucher Promo
                                    </a>
                                    <a href="{{ route('seller.shop.edit') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('seller.shop.edit') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Profil Toko
                                    </a>
                                @elseif(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                                        Ringkasan KPI
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        Kelola Pengguna
                                    </a>
                                    <a href="{{ route('admin.reviews.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all hover:bg-indigo-50/50 hover:text-indigo-600 {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        Moderasi Ulasan
                                    </a>
                                @endif
                            </div>
                        </aside>
                    @endif
                @endauth

                <!-- Main Content Area -->
                <main class="flex-1 flex flex-col gap-6">
                    
                    <!-- Flash Messages / Notifications -->
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-100 text-rose-800 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="bg-amber-50 border border-amber-100 text-amber-800 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ session('warning') }}
                        </div>
                    @endif

                    <!-- Main View Slot -->
                    <div class="flex-1">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-100 mt-auto py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-black bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TokoKita</span>
                        <span class="text-xs text-slate-400">&copy; {{ date('Y') }} TokoKita Marketplace. All Rights Reserved.</span>
                    </div>
                    <div class="flex gap-6 text-sm font-medium text-slate-500">
                        <a href="{{ route('catalog') }}" class="hover:text-indigo-600 transition-colors">Katalog</a>
                        <a href="#" class="hover:text-indigo-600 transition-colors">Tentang Kami</a>
                        <a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
