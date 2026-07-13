<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Manajemen Pesanan Masuk') }}
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

            <!-- Table of orders -->
            <div class="bg-white border border-slate-100 overflow-hidden shadow-sm sm:rounded-3xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-extrabold text-slate-800 text-base">Semua Transaksi Masuk</h3>
                    
                    <!-- Status Filter tabs -->
                    <div class="flex gap-2">
                        <a href="{{ route('seller.orders.index') }}" class="text-xs px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Semua</a>
                        <a href="{{ route('seller.orders.index') }}?status=pending" class="text-xs px-3 py-1.5 rounded-lg {{ request('status') === 'pending' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Pending</a>
                        <a href="{{ route('seller.orders.index') }}?status=paid" class="text-xs px-3 py-1.5 rounded-lg {{ request('status') === 'paid' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Sudah Bayar</a>
                        <a href="{{ route('seller.orders.index') }}?status=processed" class="text-xs px-3 py-1.5 rounded-lg {{ request('status') === 'processed' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Diproses</a>
                        <a href="{{ route('seller.orders.index') }}?status=shipping" class="text-xs px-3 py-1.5 rounded-lg {{ request('status') === 'shipping' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Dikirim</a>
                        <a href="{{ route('seller.orders.index') }}?status=completed" class="text-xs px-3 py-1.5 rounded-lg {{ request('status') === 'completed' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">Selesai</a>
                    </div>
                </div>

                @if($orders->isEmpty())
                    <p class="text-slate-400 text-sm text-center py-8">Belum ada pesanan masuk pada filter ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-500">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No. Pesanan</th>
                                    <th scope="col" class="px-6 py-3">Pembeli</th>
                                    <th scope="col" class="px-6 py-3">Total Belanja</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Verifikasi Bayar</th>
                                    <th scope="col" class="px-6 py-3">Aksi Proses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-800">
                                            <a href="{{ route('orders.show', $order->id) }}" class="hover:text-indigo-650 hover:underline">{{ $order->order_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $order->buyer->name }}</td>
                                        <td class="px-6 py-4 font-extrabold text-slate-800">Rp{{ number_format($order->final_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase
                                                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-800' : '' }}
                                                {{ $order->status === 'paid' ? 'bg-indigo-50 text-indigo-800' : '' }}
                                                {{ $order->status === 'processed' ? 'bg-blue-50 text-blue-800' : '' }}
                                                {{ $order->status === 'shipping' ? 'bg-orange-50 text-orange-800' : '' }}
                                                {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-800' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : '' }}
                                                {{ $order->status === 'returned' ? 'bg-rose-50 text-rose-800' : '' }}
                                            ">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        
                                        <!-- Verification of payments uploaded -->
                                        <td class="px-6 py-4">
                                            @if($order->payment)
                                                @if($order->payment->status === 'pending')
                                                    <div class="flex items-center gap-1.5">
                                                        <a href="{{ asset('storage/' . $order->payment->receipt_image) }}" target="_blank" class="text-xs text-indigo-600 hover:underline font-bold">Struk 📎</a>
                                                        <form action="{{ route('seller.payments.verify', $order->payment->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="bg-emerald-500 text-white text-[10px] px-1.5 py-0.5 rounded">Setujui</button>
                                                        </form>
                                                        <form action="{{ route('seller.payments.verify', $order->payment->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded">Tolak</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-xs uppercase font-bold {{ $order->payment->status === 'approved' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                        {{ $order->payment->status }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum ada struk</span>
                                            @endif
                                        </td>

                                        <!-- Processing buttons -->
                                        <td class="px-6 py-4 flex gap-1.5">
                                            @if($order->status === 'paid')
                                                <form action="{{ route('seller.orders.process', $order->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                                        Proses Order
                                                    </button>
                                                </form>
                                            @endif

                                            @if($order->status === 'processed')
                                                <!-- Form for inputting tracking number and shipping -->
                                                <form action="{{ route('seller.orders.ship', $order->id) }}" method="POST" class="flex items-center gap-1.5">
                                                    @csrf
                                                    <input type="text" name="tracking_number" required placeholder="No. Resi Kirim"
                                                           class="bg-slate-50 border border-slate-200 rounded-lg text-xs py-1 px-2 focus:outline-none w-28">
                                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs px-3 py-1.5 rounded-xl transition-all">
                                                        Kirim
                                                    </button>
                                                </form>
                                            @endif

                                            @if($order->status === 'shipping')
                                                <span class="text-xs text-slate-400 italic">Menunggu Pembeli</span>
                                            @endif

                                            @if($order->status === 'completed')
                                                <span class="text-xs text-emerald-600 font-bold">✓ Selesai</span>
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
