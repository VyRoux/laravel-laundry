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
                    <th class="px-6 py-4">NO</th>
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
                    <td class="px-6 py-4 text-center">
                        @if($t->member)
                            <span class="text-xs text-slate-400 font-mono">{{ $t->member->created_at->format('Ym') }}{{ sprintf('%03d', $t->member->id) }}</span>
                        @else
                            <span class="text-xs text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-700">{{ $t->member->name ?? 'N/A' }}</div>
                        @if($t->member && $t->member->phone_number)
                            <div class="text-xs text-slate-400 font-mono">telp: {{ $t->member->phone_number }}</div>
                        @endif
                        @if($t->member)
                            <div class="text-xs text-slate-300 font-mono">#{{ $t->member->id }}</div>
                        @endif
                    </td>
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
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('transaksi.show', $t->id) }}" class="inline-flex items-center bg-indigo-500 hover:bg-indigo-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Detail</a>
                            @if($t->status === 'baru')
                            <form action="{{ route('transaksi.update', $t->id) }}" method="POST" class="m-0 p-0">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="proses">
                                <input type="hidden" name="dibayar" value="{{ $t->dibayar }}">
                                <button type="submit" class="inline-flex items-center bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Proses</button>
                            </form>
                            @elseif($t->status === 'proses')
                            <form action="{{ route('transaksi.update', $t->id) }}" method="POST" class="m-0 p-0">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="selesai">
                                <input type="hidden" name="dibayar" value="{{ $t->dibayar }}">
                                <button type="submit" class="inline-flex items-center bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Selesai</button>
                            </form>
                            @elseif($t->status === 'selesai')
                            <form action="{{ route('transaksi.update', $t->id) }}" method="POST" class="m-0 p-0">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="diambil">
                                <input type="hidden" name="dibayar" value="{{ $t->dibayar }}">
                                <button type="submit" class="inline-flex items-center bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Diambil</button>
                            </form>
                            @endif
                            <a href="{{ route('transaksi.edit', $t->id) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded-md text-xs font-medium transition-colors">Edit</a>
                            @php $hapusDisabled = ($t->dibayar === 'dibayar'); @endphp
                            <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" onsubmit="return @js(!$hapusDisabled) && confirm('Transaksi akan dipindahkan ke tempat sampah. Lanjutkan?')" class="m-0 p-0">
                                @csrf @method('DELETE')
                                <button type="submit" {{ $hapusDisabled ? 'disabled' : '' }} class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium transition-colors {{ $hapusDisabled ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600 text-white' }}">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data transaksi.</td>
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
