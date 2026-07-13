<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Pembeli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
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

            <!-- Orders Table / List -->
            <div class="bg-white border border-slate-100 overflow-hidden shadow-sm sm:rounded-3xl p-6">
                <h3 class="font-extrabold text-slate-800 text-lg mb-6">Riwayat & Status Pesanan</h3>
                
                @if($orders->isEmpty())
                    <p class="text-slate-400 text-sm text-center py-8">Anda belum memiliki riwayat pesanan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-500">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-xl">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No. Pesanan</th>
                                    <th scope="col" class="px-6 py-3">Toko UMKM</th>
                                    <th scope="col" class="px-6 py-3">Total Belanja</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Metode Bayar</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-800">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $order->shop->name }}</td>
                                        <td class="px-6 py-4 font-extrabold text-slate-800">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            @if($order->status === 'pending')
                                                <span class="bg-amber-50 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Menunggu Bayar</span>
                                            @elseif($order->status === 'paid')
                                                <span class="bg-indigo-50 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Sudah Bayar</span>
                                            @elseif($order->status === 'processed')
                                                <span class="bg-blue-50 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Diproses Toko</span>
                                            @elseif($order->status === 'shipping')
                                                <span class="bg-orange-50 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Dikirim</span>
                                            @elseif($order->status === 'completed')
                                                <span class="bg-emerald-50 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Selesai</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="bg-slate-100 text-slate-500 text-xs font-semibold px-2.5 py-0.5 rounded-full">Dibatalkan</span>
                                            @elseif($order->status === 'returned')
                                                <span class="bg-rose-50 text-rose-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Retur</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium">
                                            {{ $order->payment ? $order->payment->payment_method : 'Belum Bayar' }}
                                        </td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('orders.show', $order->id) }}" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                                Detail
                                            </a>
                                            
                                            @if($order->status === 'pending' && !$order->payment)
                                                <a href="{{ route('payments.confirm', $order->id) }}" class="text-xs bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                                    Bayar
                                                </a>
                                            @endif

                                            @if($order->status === 'shipping')
                                                <form action="{{ route('seller.orders.complete', $order->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                                        Terima Barang
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
