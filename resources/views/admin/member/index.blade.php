@extends('layouts.main')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    {{-- Header Card --}}
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Data Pelanggan</h3>
        </div>
        <a href="{{ route('member.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            + Tambah Member
        </a>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4">Telepon</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($members as $m)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $m->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-xs font-bold {{ $m->gender == 'laki-laki' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                            {{ $m->gender == 'laki-laki' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 text-sm italic">
                        {{ $m->address ?? 'Alamat tidak diisi' }}
                    </td>
                    <td class="px-6 py-4 text-slate-600 text-sm font-mono">{{ $m->phone_number }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3 text-sm">
                            <a href="{{ route('member.edit', $m->id) }}" class="text-amber-600 hover:underline font-semibold">Edit</a>
                            <form action="{{ route('member.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data member.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection