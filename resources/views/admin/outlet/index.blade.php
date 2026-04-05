@extends('layouts.main')

@section('title', 'Daftar Outlet')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-700">Data Outlet</h3>
        <a href="{{ route('outlet.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-all">
            + Tambah Outlet
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Telepon</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($outlets as $outlet)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $outlet->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $outlet->phone_number }}</td>
                    <td class="px-6 py-4 text-slate-600 text-sm">
                        {{ $outlet->address ?? 'Alamat tidak diisi' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('outlet.edit', $outlet->id) }}" class="text-amber-600 hover:underline">Edit</a>
                            <form action="{{ route('outlet.destroy', $outlet->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">Belum ada data outlet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection