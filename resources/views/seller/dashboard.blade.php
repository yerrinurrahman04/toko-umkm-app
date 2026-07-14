<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Dashboard Penjual (UMKM)') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.stock_pdf') }}" class="text-xs bg-rose-500 hover:bg-rose-600 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                    Cetak Stok (PDF)
                </a>
                <a href="{{ route('reports.stock_excel') }}" class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                    Ekspor Stok (Excel)
                </a>
                <a href="{{ route('reports.sales_recap') }}" class="text-xs bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                    Rekap Penjualan (Excel)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100">
                    <span class="text-xs uppercase font-bold text-indigo-200">Total Omzet</span>
                    <h3 class="text-2xl font-black mt-2">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-indigo-100 mt-2 font-medium">Dari transaksi selesai</p>
                </div>
                <!-- Total Orders -->
                <div class="glass-card card-hover rounded-[24px] p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Total Pesanan</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $totalOrders }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Total masuk toko</p>
                </div>
                <!-- Unique Buyers -->
                <div class="glass-card card-hover rounded-[24px] p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Pelanggan Unik</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $uniqueBuyers }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Pembeli terdaftar</p>
                </div>
                <!-- Pending Orders -->
                <div class="glass-card card-hover rounded-[24px] p-6 shadow-sm">
                    <span class="text-xs uppercase font-bold text-slate-400">Pesanan Pending</span>
                    <h3 class="text-2xl font-extrabold text-amber-600 mt-2">{{ $pendingOrders }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Menunggu pembayaran</p>
                </div>
            </div>

            <!-- Low Stock Warnings -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-rose-50 border border-rose-100 text-rose-800 p-4 rounded-3xl text-sm font-semibold flex items-center justify-between">
                    <div>
                        <span>⚠️ Peringatan Stok Tipis pada {{ $lowStockProducts->count() }} produk!</span>
                        <div class="text-xs text-rose-600 font-normal mt-1">
                            @foreach($lowStockProducts as $prod)
                                {{ $prod->name }} (Sisa: {{ $prod->stock }}), 
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('seller.products.index') }}" class="text-xs bg-rose-600 text-white px-3 py-1.5 rounded-xl">Update Stok</a>
                </div>
            @endif

            <!-- Graphical Reports Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sales Trend -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm md:col-span-2">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Tren Penjualan Toko (Line Chart)</h4>
                    <canvas id="salesTrendChart" class="max-h-72"></canvas>
                </div>
                <!-- Best Sellers -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Produk Terlaris (Pie Chart)</h4>
                    <canvas id="bestSellersChart" class="max-h-72"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Rating Analysis -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Analisis Rating & Ulasan (Bar Chart)</h4>
                    <canvas id="ratingsChart" class="max-h-72"></canvas>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm md:col-span-2">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">Pesanan Terbaru</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2">No. Pesanan</th>
                                    <th scope="col" class="px-4 py-2">Pelanggan</th>
                                    <th scope="col" class="px-4 py-2">Total</th>
                                    <th scope="col" class="px-4 py-2">Status</th>
                                    <th scope="col" class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-2.5 font-bold text-slate-800">{{ $order->order_number }}</td>
                                        <td class="px-4 py-2.5 font-semibold text-slate-700">{{ $order->buyer->name }}</td>
                                        <td class="px-4 py-2.5 font-extrabold text-slate-850">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $order->status }}</span>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <a href="{{ route('orders.show', $order->id) }}" class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-1 rounded font-bold">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-6 text-slate-450 italic">Belum ada pesanan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ChartJS Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Sales Trend
        const trendData = {!! json_encode($salesTrend) !!};
        const trendLabels = trendData.map(item => item.date);
        const trendValues = trendData.map(item => item.total);

        new Chart(document.getElementById('salesTrendChart'), {
            type: 'line',
            data: {
                labels: trendLabels.length ? trendLabels : ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Omzet Penjualan (Rp)',
                    data: trendValues.length ? trendValues : [0, 0, 0, 0, 0, 0, 0],
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // 2. Best Sellers
        const productData = {!! json_encode($topProducts) !!};
        const productLabels = productData.map(item => item.name);
        const productValues = productData.map(item => item.qty);

        new Chart(document.getElementById('bestSellersChart'), {
            type: 'pie',
            data: {
                labels: productLabels.length ? productLabels : ['Belum ada data'],
                datasets: [{
                    data: productValues.length ? productValues : [1],
                    backgroundColor: [
                        '#4f46e5', '#a855f7', '#ec4899', '#f43f5e', '#e2e8f0'
                    ]
                }]
            },
            options: { responsive: true }
        });

        // 3. Ratings Distribution
        const ratingData = {!! json_encode($reviewsDistribution) !!};
        const ratingMap = {1: 0, 2: 0, 3: 0, 4: 0, 5: 0};
        ratingData.forEach(item => {
            ratingMap[item.rating] = item.count;
        });

        new Chart(document.getElementById('ratingsChart'), {
            type: 'bar',
            data: {
                labels: ['⭐ 1', '⭐ 2', '⭐ 3', '⭐ 4', '⭐ 5'],
                datasets: [{
                    label: 'Jumlah Ulasan',
                    data: [ratingMap[1], ratingMap[2], ratingMap[3], ratingMap[4], ratingMap[5]],
                    backgroundColor: '#eab308',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // 5-minute auto-refresh (300000 ms)
        setTimeout(() => {
            window.location.reload();
        }, 300000);
    </script>
</x-app-layout>
