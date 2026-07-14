<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl mb-6 text-sm font-semibold border border-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6 md:p-8 space-y-6">
                
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-4"
                      x-data="{
                          name: '{{ addslashes($category->name) }}',
                          description: '{{ addslashes($category->description ?? '') }}',
                          get nameError() {
                              return this.name.length > 0 && this.name.length < 3 ? 'Nama kategori minimal 3 karakter.' : '';
                          },
                          get descriptionError() {
                              return this.description.length > 500 ? 'Deskripsi maksimal 500 karakter.' : '';
                          },
                          get isFormInvalid() {
                              return this.name.length < 3 || this.description.length > 500;
                          }
                      }">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 mb-2">Nama Kategori</label>
                        <input type="text" id="name" name="name" required x-model="name"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <p class="text-rose-500 text-xs mt-1.5 font-semibold" x-show="nameError" x-text="nameError" style="display: none;"></p>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-500 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" x-model="description"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                        <p class="text-rose-500 text-xs mt-1.5 font-semibold" x-show="descriptionError" x-text="descriptionError" style="display: none;"></p>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('admin.categories.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-2xl transition-all">Batal</a>
                        <button type="submit" class="text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md"
                                :disabled="isFormInvalid"
                                :class="isFormInvalid ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700'">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
