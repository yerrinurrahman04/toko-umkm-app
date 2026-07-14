<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Performa Penjual UMKM (3 Bulan Terakhir)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Chart: Perbandingan Omzet Penjual -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="font-extrabold text-slate-800 text-sm mb-4">Grafik Perbandingan Omzet Antar Penjual (Rp)</h3>
                <div class="relative h-[300px]">
                    <canvas id="sellerRevenueChart"></canvas>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="font-extrabold text-slate-800 text-base mb-6">Tabel Ringkasan Performa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Toko</th>
                                <th scope="col" class="px-6 py-3">Pemilik</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah Transaksi Selesai</th>
                                <th scope="col" class="px-6 py-3 text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sellerPerformance as $performance)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $performance->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">
                                        {{ $performance->user->name }} ({{ $performance->user->email }})
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-indigo-600">{{ $performance->total_orders }}</td>
                                    <td class="px-6 py-4 text-right font-black text-slate-800">
                                        Rp{{ number_format($performance->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400 italic">Belum ada transaksi penjual dalam 3 bulan terakhir.</td>
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
        const rawData = {!! json_encode($sellerPerformance) !!};
        const labels = rawData.map(item => item.name);
        const dataValues = rawData.map(item => parseFloat(item.total_revenue));

        new Chart(document.getElementById('sellerRevenueChart'), {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['Belum ada data'],
                datasets: [{
                    label: 'Total Omzet (Rp)',
                    data: dataValues.length ? dataValues : [0],
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgb(79, 70, 229)',
                    borderWidth: 1.5,
                    borderRadius: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
