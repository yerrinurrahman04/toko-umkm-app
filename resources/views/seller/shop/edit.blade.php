<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Pengaturan Profil Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                
                <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 mb-2">Nama Toko UMKM</label>
                        <input type="text" id="name" name="name" required value="{{ $shop->name }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="operating_hours" class="block text-xs font-bold text-slate-500 mb-2">Jam Operasional Toko</label>
                        <input type="text" id="operating_hours" name="operating_hours" value="{{ $shop->operating_hours }}" placeholder="Contoh: 08:00 - 20:00"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="shipping_area" class="block text-xs font-bold text-slate-500 mb-2">Area Pengiriman Terlayani</label>
                        <input type="text" id="shipping_area" name="shipping_area" value="{{ $shop->shipping_area }}" placeholder="Contoh: Kota Bandung, Cimahi, Jawa Barat"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-500 mb-2">Alamat Fisik Toko</label>
                        <input type="text" id="address" name="address" value="{{ $shop->address }}" placeholder="Masukkan alamat lengkap toko Anda..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-500 mb-2">Deskripsi / Slogan Toko</label>
                        <textarea id="description" name="description" rows="4" placeholder="Jelaskan mengenai keistimewaan produk toko Anda..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">{{ $shop->description }}</textarea>
                    </div>

                    <div>
                        <label for="logo" class="block text-xs font-bold text-slate-500 mb-2">Unggah Logo Baru</label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        @if($shop->logo)
                            <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                <span>Logo saat ini:</span>
                                <img src="{{ asset('storage/' . $shop->logo) }}" class="w-10 h-10 rounded-lg object-cover">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('seller.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
