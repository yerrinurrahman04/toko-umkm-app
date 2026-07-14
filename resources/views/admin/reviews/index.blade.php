<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Analisis & Moderasi Ulasan Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 1. Rating Distribution Chart & Avg Rating Table -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bar Chart: Distribusi Rating -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm md:col-span-1">
                    <h3 class="font-extrabold text-slate-800 text-sm mb-4">Distribusi Rating Bintang</h3>
                    <div class="relative h-[250px] flex items-center justify-center">
                        <canvas id="ratingsDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Table: Rata-rata Rating per Produk -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm md:col-span-2">
                    <h3 class="font-extrabold text-slate-800 text-sm mb-4">Rata-rata Rating per Produk</h3>
                    <div class="overflow-y-auto max-h-[250px]">
                        <table class="w-full text-left text-xs text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-4 py-2.5">Nama Produk</th>
                                    <th scope="col" class="px-4 py-2.5 text-center">Jumlah Ulasan</th>
                                    <th scope="col" class="px-4 py-2.5 text-right">Rata-rata Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($productRatings as $product)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-center font-semibold text-slate-600">{{ $product->reviews_count }}</td>
                                        <td class="px-4 py-3 text-right text-amber-500 font-extrabold">
                                            ⭐ {{ number_format($product->reviews_avg_rating, 1) }} / 5.0
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-slate-400 italic">Belum ada rating produk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. Ulasan Terbaru Perlu Moderasi Admin -->
            <div id="pending-reviews-card" class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <h3 class="font-extrabold text-slate-800 text-base mb-6">Ulasan Menunggu Moderasi (Pending)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Pembeli</th>
                                <th scope="col" class="px-6 py-3">Rating</th>
                                <th scope="col" class="px-6 py-3">Komentar</th>
                                <th scope="col" class="px-6 py-3">Aksi Moderasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingReviews as $review)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $review->product->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">{{ $review->buyer->name }}</td>
                                    <td class="px-6 py-4 text-amber-500 font-extrabold">⭐ {{ $review->rating }} / 5</td>
                                    <td class="px-6 py-4 text-slate-500 italic">"{{ $review->comment }}"</td>
                                    <td class="px-6 py-4 flex gap-1.5">
                                        <form action="{{ route('admin.reviews.moderate', $review->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.reviews.moderate', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                            @csrf
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-bold text-xs px-2.5 py-1 rounded-lg">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-slate-400 italic">Tidak ada ulasan baru yang perlu dimoderasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Riwayat Semua Ulasan (History) -->
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <h3 class="font-extrabold text-slate-800 text-base mb-6">Riwayat Seluruh Ulasan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-550">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Pembeli</th>
                                <th scope="col" class="px-6 py-3">Rating</th>
                                <th scope="col" class="px-6 py-3">Komentar</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $review->product->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">{{ $review->buyer->name }}</td>
                                    <td class="px-6 py-4 text-amber-500 font-extrabold">⭐ {{ $review->rating }} / 5</td>
                                    <td class="px-6 py-4 text-slate-500 italic">"{{ $review->comment }}"</td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $review->is_moderated ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                            {{ $review->is_moderated ? 'Diterbitkan' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-slate-400 italic">Belum ada ulasan terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- ChartJS Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ratingDist = {!! json_encode($ratingDistribution) !!};
        
        new Chart(document.getElementById('ratingsDistributionChart'), {
            type: 'bar',
            data: {
                labels: ['⭐ 1', '⭐ 2', '⭐ 3', '⭐ 4', '⭐ 5'],
                datasets: [{
                    label: 'Proporsi Bintang',
                    data: [ratingDist[1], ratingDist[2], ratingDist[3], ratingDist[4], ratingDist[5]],
                    backgroundColor: '#eab308',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
</x-app-layout>
