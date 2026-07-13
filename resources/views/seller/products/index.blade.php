<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Kelola Produk Anda') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-md">
                Tambah Produk Baru
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

            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Gambar</th>
                                <th scope="col" class="px-6 py-3">Nama Produk</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Harga Pokok</th>
                                <th scope="col" class="px-6 py-3">Stok</th>
                                <th scope="col" class="px-6 py-3">Diskon</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $product->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-500">{{ $product->categories->first()->name ?? 'Umum' }}</td>
                                    <td class="px-6 py-4 font-extrabold text-slate-800">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold {{ $product->stock < 5 ? 'text-rose-600' : 'text-slate-600' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-rose-500">
                                        {{ $product->discount_percentage > 0 ? number_format($product->discount_percentage, 0).'%' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('seller.products.edit', $product->id) }}" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white font-bold px-3 py-1.5 rounded-xl transition-all">
                                            Edit & Varian
                                        </a>
                                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
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
                                    <td colspan="7" class="text-center py-8 text-slate-400 italic">Belum ada produk terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
