@extends('layouts.main')

@section('title', 'Tempat Sampah - Transaksi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Tempat Sampah - Transaksi</h3>
            <p class="text-sm text-slate-500">Data yang dihapus masih bisa dipulihkan</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            Kembali
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Invoice</th>
                    <th class="px-6 py-4">Member</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dihapus</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksi as $t)
                <tr class="hover:bg-slate-50 transition-all bg-slate-50/50">
                    <td class="px-6 py-4 font-mono text-sm font-bold text-indigo-500">{{ $t->kode_invoice }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $t->member->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500">{{ $t->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-red-500">{{ $t->deleted_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3 text-sm">
                            <form action="{{ route('transaksi.restore', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Pulihkan transaksi ini?')">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:underline font-semibold">Pulihkan</button>
                            </form>
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('transaksi.force', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-semibold">Hapus Permanen</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Tempat sampah kosong.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
