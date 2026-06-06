@extends('layouts.main')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    {{-- Header Card --}}
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Data Pengguna (Staff)</h3>
        </div>
        <a href="{{ route('user.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            + Tambah User
        </a>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4 text-center">NO</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Outlet</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 text-center text-xs text-slate-400 font-mono">{{ $user->created_at->format('dmY') }}{{ sprintf('%03d', $user->id) }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $user->name }}</td>
                    <td class="px-6 py-4 font-mono text-sm text-slate-500">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <span class="px-3 py-1 bg-slate-100 rounded-lg">
                            {{ $user->outlet->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                            {{ $user->role == 'admin' ? 'bg-purple-50 text-purple-600' : ($user->role == 'kasir' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('user.edit', $user->id) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Edit</a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('User akan dipindahkan ke tempat sampah. Lanjutkan?')" class="m-0 p-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<a :style="window.innerWidth >= 1024 ? 'left: ' + (((sidebarExpanded || expanded) ? 256 : 64) + 8) + 'px' : ''"
   href="{{ route('user.trashed') }}" 
   class="fixed bottom-6 z-50 right-6 lg:right-auto inline-flex items-center justify-center w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-full shadow-lg transition-all duration-300">
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
</a>
@endsection