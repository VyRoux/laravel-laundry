@extends('layouts.main')

@section('title', 'Tempat Sampah - Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Tempat Sampah - Pengguna</h3>
            <p class="text-sm text-slate-500">Data yang dihapus masih bisa dipulihkan</p>
        </div>
        <a href="{{ route('user.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            Kembali
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4 text-center">NO</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Dihapus</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $u)
                <tr class="hover:bg-slate-50 transition-all bg-slate-50/50">
                    <td class="px-6 py-4 text-center text-xs text-slate-400 font-mono">{{ $u->created_at->format('dmY') }}{{ sprintf('%03d', $u->id) }}</td>
                    <td class="px-6 py-4 font-medium text-slate-500">{{ $u->name }}</td>
                    <td class="px-6 py-4 font-mono text-sm text-slate-400">{{ $u->username }}</td>
                    <td class="px-6 py-4"><span class="capitalize px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500">{{ $u->role }}</span></td>
                    <td class="px-6 py-4 text-sm text-red-500">{{ $u->deleted_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3 text-sm">
                            <form action="{{ route('user.restore', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Pulihkan user ini?')">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:underline font-semibold">Pulihkan</button>
                            </form>
                            <form action="{{ route('user.force', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-semibold">Hapus Permanen</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Tempat sampah kosong.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
