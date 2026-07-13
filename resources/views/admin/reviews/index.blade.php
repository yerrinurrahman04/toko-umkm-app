<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Moderasi Konten Ulasan & Rating') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <h3 class="font-extrabold text-slate-800 text-base mb-6">Ulasan Menunggu Moderasi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Pembeli</th>
                                <th scope="col" class="px-6 py-3">Rating</th>
                                <th scope="col" class="px-6 py-3">Komentar</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Aksi Moderasi</th>
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
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $review->is_moderated ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                            {{ $review->is_moderated ? 'Diterbitkan' : 'Menunggu Approval' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-1.5">
                                        @if(!$review->is_moderated)
                                            <form action="{{ route('admin.reviews.moderate', $review->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Approve</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.moderate', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                            @csrf
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-bold text-xs px-2.5 py-1 rounded-lg">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-400 italic">Belum ada ulasan terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
