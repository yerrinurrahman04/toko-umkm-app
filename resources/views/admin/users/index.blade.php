<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Manajemen Pengguna Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl text-sm font-semibold border border-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 shadow-sm sm:rounded-3xl p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-3">Alamat Email</th>
                                <th scope="col" class="px-6 py-3">Tanggal Terdaftar</th>
                                <th scope="col" class="px-6 py-3">Role Saat Ini</th>
                                <th scope="col" class="px-6 py-3">Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-bold uppercase px-2.5 py-0.5 rounded-full
                                            {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-800' : '' }}
                                            {{ $user->role === 'seller' ? 'bg-indigo-50 text-indigo-800' : '' }}
                                            {{ $user->role === 'buyer' ? 'bg-slate-100 text-slate-600' : '' }}
                                        ">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" required class="bg-slate-50 border border-slate-200 rounded-lg text-xs py-1 px-2 focus:ring-indigo-500">
                                                    <option value="buyer" {{ $user->role === 'buyer' ? 'selected' : '' }}>Buyer</option>
                                                    <option value="seller" {{ $user->role === 'seller' ? 'selected' : '' }}>Seller</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-2.5 py-1 rounded-lg">Simpan</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Akun Anda Sendiri</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
