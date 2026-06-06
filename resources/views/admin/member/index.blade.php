@extends('layouts.main')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Data Pelanggan</h3>
        </div>
        <a href="{{ route('member.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            + Tambah Member
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4 text-center">NO</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Jenis Kelamin</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4">Telepon</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($members as $m)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 text-center text-xs text-slate-400 font-mono">{{ $m->created_at->format('Ym') }}{{ sprintf('%03d', $m->id) }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $m->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-xs font-bold {{ $m->gender == 'laki-laki' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                            {{ $m->gender == 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 text-sm italic">
                        {{ $m->address ?? 'Alamat tidak diisi' }}
                    </td>
                    <td class="px-6 py-4 text-slate-600 text-sm font-mono">{{ $m->phone_number }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('member.edit', $m->id) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Edit</a>
                            <form action="{{ route('member.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Member akan dipindahkan ke tempat sampah. Lanjutkan?')" class="m-0 p-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data member.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<a :style="window.innerWidth >= 1024 ? 'left: ' + (((sidebarExpanded || expanded) ? 256 : 64) + 8) + 'px' : ''"
   href="{{ route('member.trashed') }}" 
   class="fixed bottom-6 z-50 right-6 lg:right-auto inline-flex items-center justify-center w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-full shadow-lg transition-all duration-300">
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
</a>
@endsection