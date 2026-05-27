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
                            <form action="{{ route('outlet.destroy', $outlet->id) }}" method="POST" onsubmit="return confirm('Outlet akan dipindahkan ke tempat sampah. Lanjutkan?')">
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

@section('footer')
<a :style="window.innerWidth >= 1024 ? 'left: ' + (((sidebarExpanded || expanded) ? 256 : 64) + 8) + 'px' : ''"
   href="{{ route('outlet.trashed') }}" 
   class="fixed bottom-6 z-50 right-6 lg:right-auto inline-flex items-center justify-center w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-full shadow-lg transition-all duration-300">
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
</a>
@endsection