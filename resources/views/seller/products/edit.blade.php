<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Produk & Varian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            
            @if(session('success'))
                <div class="col-span-full bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Left Form: Edit Details -->
            <div class="md:col-span-2 bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                <h3 class="font-extrabold text-slate-800 text-base border-b border-slate-50 pb-3">Informasi Produk</h3>
                <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 mb-2">Nama Produk</label>
                        <input type="text" id="name" name="name" required value="{{ $product->name }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-500 mb-2">Kategori Produk</label>
                        <select id="category_id" name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-500 mb-2">Harga Utama (Rp)</label>
                            <input type="number" id="price" name="price" required value="{{ $product->price }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label for="stock" class="block text-xs font-bold text-slate-500 mb-2">Stok Utama</label>
                            <input type="number" id="stock" name="stock" required value="{{ $product->stock }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="discount_percentage" class="block text-xs font-bold text-slate-500 mb-2">Diskon Langsung (%)</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" required min="0" max="100" value="{{ $product->discount_percentage }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-500 mb-2">Deskripsi Produk</label>
                        <textarea id="description" name="description" rows="5"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">{{ $product->description }}</textarea>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-bold text-slate-500 mb-2">Foto Produk (Kosongkan jika tidak diubah)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('seller.products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Right Sidebar: Variants Manager -->
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                <h3 class="font-extrabold text-slate-800 text-base border-b border-slate-50 pb-3">Kelola Varian Harga</h3>
                
                <!-- Variants List -->
                <div class="space-y-3">
                    @forelse($product->variants as $variant)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 flex justify-between items-center text-xs">
                            <div>
                                <strong class="text-slate-800 block">{{ $variant->name }}</strong>
                                <span class="text-indigo-600 font-semibold mt-0.5 block">Rp{{ number_format($variant->price, 0, ',', '.') }}</span>
                                <span class="text-slate-400 text-[10px]">Stok: {{ $variant->stock }}</span>
                            </div>
                            <form action="{{ route('seller.variants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Hapus varian ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">Belum ada varian produk.</p>
                    @endforelse
                </div>

                <!-- Add Variant Form -->
                <form action="{{ route('seller.products.variants', $product->id) }}" method="POST" class="border-t border-slate-100 pt-4 space-y-3">
                    @csrf
                    <h4 class="text-xs font-bold text-slate-700">Tambah Varian Baru</h4>
                    <div>
                        <input type="text" name="variant_name" required placeholder="Nama varian (cth: Kemasan 10kg, Warna Merah)"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="variant_price" required placeholder="Harga Varian (Rp)"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        <input type="number" name="variant_stock" required placeholder="Stok Varian"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs py-2 rounded-xl transition-all">
                        Simpan Varian
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
