<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Kelola Kategori Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-4 py-2.5 rounded-2xl shadow-md transition-all">
                    + Tambah Kategori
                </a>
            </div>

            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <h3 class="font-extrabold text-slate-800 text-base mb-6">Daftar Kategori</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Kategori</th>
                                <th scope="col" class="px-6 py-3">Slug</th>
                                <th scope="col" class="px-6 py-3">Deskripsi</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        <a href="{{ route('admin.categories.show', $category->id) }}" class="hover:text-indigo-600 transition-colors">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ Str::limit($category->description, 80) ?: '-' }}</td>
                                    <td class="px-6 py-4 flex gap-1.5">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white font-bold text-xs px-2.5 py-1.5 rounded-lg transition-all">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua relasi produk dengan kategori ini akan dilepas.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-bold text-xs px-2.5 py-1.5 rounded-lg transition-all">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400 italic">Belum ada kategori terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
