@extends('layouts.main')

@section('title', 'Daftar Paket')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Data Paket Laundry</h3>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('paket.trashed') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                Tempat Sampah
            </a>
            <a href="{{ route('paket.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                + Tambah Paket
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Outlet</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4">Nama Paket</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($paket as $p)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <span class="px-3 py-1 bg-slate-100 rounded-lg">{{ $p->outlet->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                            {{ $p->jenis == 'kiloan' ? 'bg-blue-50 text-blue-600' : 
                                ($p->jenis == 'selimut' ? 'bg-amber-50 text-amber-600' : 
                                ($p->jenis == 'bed_cover' ? 'bg-purple-50 text-purple-600' : 
                                ($p->jenis == 'kaos' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600'))) }}">
                            {{ $p->jenis }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $p->nama_paket }}</td>
                    <td class="px-6 py-4 font-mono text-sm text-slate-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3 text-sm">
                            <a href="{{ route('paket.edit', $p->id) }}" class="text-amber-600 hover:underline">Edit</a>
                            <form action="{{ route('paket.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Paket akan dipindahkan ke tempat sampah. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data paket.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
