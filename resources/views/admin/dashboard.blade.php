<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Admin Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Real-time KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100">
                    <span class="text-xs uppercase font-bold text-indigo-200">Omzet Sistem</span>
                    <h3 class="text-2xl font-black mt-2">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-indigo-100 mt-2 font-medium">Platform-wide completed transactions</p>
                </div>
                <!-- Total Orders -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Total Transaksi</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $totalOrders }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Seluruh pesanan masuk</p>
                </div>
                <!-- Total Users -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Total Pengguna</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $totalUsers }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Sellers: {{ $totalSellers }} | Buyers: {{ $totalBuyers }}</p>
                </div>
                <!-- Reviews Moderation -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Moderasi Ulasan</span>
                    <h3 class="text-2xl font-extrabold text-amber-600 mt-2">{{ $pendingReviews }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Ulasan menunggu persetujuan</p>
                </div>
            </div>

            <!-- Unduh Laporan (PDF & Excel) -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Unduh Laporan Sistem</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('reports.stock_pdf') }}" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan Stok Sistem (PDF)
                    </a>
                    <a href="{{ route('reports.stock_excel') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Ekspor Stok Excel
                    </a>
                    <a href="{{ route('reports.sales_recap') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                        Rekap Penjualan Excel
                    </a>
                    <a href="{{ route('reports.orders_buyers') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Ekspor Pesanan & Pembeli Excel
                    </a>
                </div>
            </div>

            <!-- Recent System Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Pesanan Platform Terbaru</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2">No. Pesanan</th>
                                    <th scope="col" class="px-4 py-2">Toko</th>
                                    <th scope="col" class="px-4 py-2">Total</th>
                                    <th scope="col" class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-2.5 font-bold text-slate-800">{{ $order->order_number }}</td>
                                        <td class="px-4 py-2.5 font-semibold text-slate-600">{{ $order->shop->name }}</td>
                                        <td class="px-4 py-2.5 font-extrabold text-slate-800">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="bg-slate-150 text-slate-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $order->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Pendaftaran Pengguna Baru</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2">Nama</th>
                                    <th scope="col" class="px-4 py-2">Email</th>
                                    <th scope="col" class="px-4 py-2">Role</th>
                                    <th scope="col" class="px-4 py-2">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($recentUsers as $usr)
                                    <tr>
                                        <td class="px-4 py-2.5 font-bold text-slate-800">{{ $usr->name }}</td>
                                        <td class="px-4 py-2.5 text-slate-550">{{ $usr->email }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="bg-indigo-50 text-indigo-750 px-2 py-0.5 rounded text-[9px] font-bold uppercase">{{ $usr->role }}</span>
                                        </td>
                                        <td class="px-4 py-2.5 text-slate-400">{{ $usr->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
