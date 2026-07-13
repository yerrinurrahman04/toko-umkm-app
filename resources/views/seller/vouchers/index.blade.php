<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Kelola Voucher & Promosi Toko') }}
            </h2>
            <a href="{{ route('seller.vouchers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-md">
                Buat Voucher Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 overflow-hidden shadow-sm sm:rounded-3xl p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Kode Voucher</th>
                                <th scope="col" class="px-6 py-3">Tipe Potongan</th>
                                <th scope="col" class="px-6 py-3">Nilai</th>
                                <th scope="col" class="px-6 py-3">Min. Belanja</th>
                                <th scope="col" class="px-6 py-3">Periode Mulai</th>
                                <th scope="col" class="px-6 py-3">Periode Selesai</th>
                                <th scope="col" class="px-6 py-3">Status Aktif</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($vouchers as $voucher)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800 uppercase tracking-wider">{{ $voucher->code }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-500 capitalize">{{ $voucher->type }}</td>
                                    <td class="px-6 py-4 font-extrabold text-slate-800">
                                        {{ $voucher->type === 'percent' ? number_format($voucher->value, 0).'%' : 'Rp'.number_format($voucher->value, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">Rp{{ number_format($voucher->min_spend, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $voucher->start_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $voucher->end_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $voucher->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('seller.vouchers.edit', $voucher->id) }}" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                            Edit
                                        </a>
                                        <form action="{{ route('seller.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-slate-400 italic">Belum ada voucher aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
