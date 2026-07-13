<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
                      x-data="{
                          name: '',
                          price: '',
                          stock: '',
                          get nameError() {
                              return this.name.length > 0 && this.name.length < 5 ? 'Nama produk minimal 5 karakter.' : '';
                          },
                          get priceError() {
                              return this.price !== '' && parseFloat(this.price) < 0 ? 'Harga tidak boleh negatif.' : '';
                          },
                          get stockError() {
                              if (this.stock === '') return '';
                              const val = parseFloat(this.stock);
                              if (val < 0) return 'Stok tidak boleh negatif.';
                              if (!Number.isInteger(val)) return 'Stok harus berupa angka bulat.';
                              return '';
                          },
                          get isFormInvalid() {
                              return this.name.length < 5 || this.price === '' || parseFloat(this.price) < 0 || this.stock === '' || parseFloat(this.stock) < 0 || !Number.isInteger(parseFloat(this.stock));
                          }
                      }">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 mb-2">Nama Produk</label>
                        <input type="text" id="name" name="name" required placeholder="Contoh: Beras Premium Pandan Wangi 5kg" x-model="name"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <p class="text-rose-500 text-xs mt-1.5 font-semibold" x-show="nameError" x-text="nameError" style="display: none;"></p>
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-500 mb-2">Kategori Produk</label>
                        <select id="category_id" name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-500 mb-2">Harga Utama (Rp)</label>
                            <input type="number" id="price" name="price" required placeholder="85000" x-model="price"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold" x-show="priceError" x-text="priceError" style="display: none;"></p>
                        </div>
                        <div>
                            <label for="stock" class="block text-xs font-bold text-slate-500 mb-2">Stok Awal</label>
                            <input type="number" id="stock" name="stock" required placeholder="50" x-model="stock"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold" x-show="stockError" x-text="stockError" style="display: none;"></p>
                        </div>
                    </div>

                    <div>
                        <label for="discount_percentage" class="block text-xs font-bold text-slate-500 mb-2">Diskon Langsung (%)</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" required min="0" max="100" value="0"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-500 mb-2">Deskripsi Produk</label>
                        <textarea id="description" name="description" rows="5" placeholder="Masukkan deskripsi lengkap mengenai kualitas, kandungan, dan manfaat produk..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-bold text-slate-500 mb-2">Foto Produk</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('seller.products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Batal</a>
                        <button type="submit" class="text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md"
                                :disabled="isFormInvalid"
                                :class="isFormInvalid ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700'">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
