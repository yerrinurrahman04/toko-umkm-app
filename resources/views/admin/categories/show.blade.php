<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Detail Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Kategori</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1">{{ $category->name }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Slug</h3>
                    <p class="text-sm font-semibold text-slate-600 mt-1">{{ $category->slug }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Kategori</h3>
                    <p class="text-sm text-slate-600 mt-1">{{ $category->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.categories.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Kembali</a>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md">Edit Kategori</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
