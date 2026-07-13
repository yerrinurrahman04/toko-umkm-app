<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Voucher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                
                <form action="{{ route('seller.vouchers.update', $voucher->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="code" class="block text-xs font-bold text-slate-500 mb-2">Kode Voucher</label>
                        <input type="text" id="code" name="code" required value="{{ $voucher->code }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all uppercase font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-xs font-bold text-slate-500 mb-2">Tipe Potongan</label>
                            <select id="type" name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="percent" {{ $voucher->type === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="fixed" {{ $voucher->type === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label for="value" class="block text-xs font-bold text-slate-500 mb-2">Nilai Potongan</label>
                            <input type="number" id="value" name="value" required value="{{ $voucher->value }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="min_spend" class="block text-xs font-bold text-slate-500 mb-2">Minimal Pembelanjaan (Rp)</label>
                        <input type="number" id="min_spend" name="min_spend" required value="{{ $voucher->min_spend }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-slate-500 mb-2">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" required value="{{ $voucher->start_date->format('Y-m-d') }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-slate-500 mb-2">Tanggal Berakhir</label>
                            <input type="date" id="end_date" name="end_date" required value="{{ $voucher->end_date->format('Y-m-d') }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }} class="rounded border-slate-350 text-indigo-650 focus:ring-indigo-550">
                        <label for="is_active" class="text-xs font-bold text-slate-600">Aktifkan Voucher</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('seller.vouchers.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
