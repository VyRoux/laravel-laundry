@extends('layouts.main')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Data Transaksi</h3>
        </div>
        <a href="{{ route('transaksi.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            + Transaksi Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Invoice</th>
                    <th class="px-6 py-4">Member</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Pembayaran</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksi as $t)
                @php
                    $subtotal = $t->details->sum(function($d) { return $d->qty * $d->paket->harga; });
                    $diskon = $subtotal * ($t->diskon / 100);
                    $pajak = $subtotal * ($t->pajak / 100);
                    $total = $subtotal - $diskon + ($t->biaya_tambahan ?? 0) + $pajak;
                @endphp
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-bold text-indigo-600">{{ $t->kode_invoice }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $t->member->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($t->tgl)->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                            {{ $t->status == 'baru' ? 'bg-blue-50 text-blue-600' : 
                               ($t->status == 'proses' ? 'bg-amber-50 text-amber-600' : 
                               ($t->status == 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                            {{ $t->dibayar == 'dibayar' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            {{ str_replace('_', ' ', $t->dibayar) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-sm font-bold text-slate-700">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3 text-sm">
                            <a href="{{ route('transaksi.show', $t->id) }}" class="text-indigo-600 hover:underline">Detail</a>
                            <a href="{{ route('transaksi.edit', $t->id) }}" class="text-amber-600 hover:underline">Edit</a>
                            @if($t->dibayar !== 'dibayar')
                            <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Transaksi akan dipindahkan ke tempat sampah. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<a :style="window.innerWidth >= 1024 ? 'left: ' + (((sidebarExpanded || expanded) ? 256 : 64) + 8) + 'px' : ''"
   href="{{ route('transaksi.trashed') }}" 
   class="fixed bottom-6 z-50 right-6 lg:right-auto inline-flex items-center justify-center w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-full shadow-lg transition-all duration-300">
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
</a>
@endsection
