@extends('layouts.main')

@section('title', 'Laporan')

@section('content')
<style>
@media print {
    body * { visibility: hidden; }
    .report-print, .report-print * { visibility: visible; }
    .report-print { position: absolute; left: 0; top: 0; width: 100%; }
    aside, header, nav, .no-print { display: none !important; }
    .report-print table { font-size: 10px; }
    .report-print th, .report-print td { padding: 4px 8px !important; }
}
</style>

<div class="max-w-7xl mx-auto space-y-6"
     x-data="{ showDetail: false }">
    {{-- FORM FILTER --}}
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-700">Filter Laporan</h3>
            </div>

            <form action="{{ route('report.generate') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Laporan</label>
                    <select name="jenis" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('jenis') border-red-300 bg-red-50 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="keuangan" {{ old('jenis', $jenis ?? '') == 'keuangan' ? 'selected' : '' }}>Laporan Keuangan</option>
                        <option value="transaksi" {{ old('jenis', $jenis ?? '') == 'transaksi' ? 'selected' : '' }}>Laporan Transaksi</option>
                        <option value="per_outlet" {{ old('jenis', $jenis ?? '') == 'per_outlet' ? 'selected' : '' }}>Laporan Per Outlet</option>
                        <option value="member" {{ old('jenis', $jenis ?? '') == 'member' ? 'selected' : '' }}>Laporan Member</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $start_date ?? '') }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('start_date') border-red-300 bg-red-50 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $end_date ?? '') }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('end_date') border-red-300 bg-red-50 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Outlet</label>
                    <select name="outlet_id"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">-- Semua Outlet --</option>
                        @foreach($outlets as $o)
                            <option value="{{ $o->id }}" {{ old('outlet_id', $outlet_id ?? '') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">-- Semua Status --</option>
                            <option value="baru" {{ old('status', $status ?? '') == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="proses" {{ old('status', $status ?? '') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ old('status', $status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ old('status', $status ?? '') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pembayaran</label>
                        <select name="dibayar"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">-- Semua --</option>
                            <option value="dibayar" {{ old('dibayar', $dibayar ?? '') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="belum_dibayar" {{ old('dibayar', $dibayar ?? '') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">
                        Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- HASIL LAPORAN --}}
    @isset($results)
    <div class="report-print bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-700">
                    Hasil Laporan {{ $jenis == 'keuangan' ? 'Keuangan' : ($jenis == 'transaksi' ? 'Transaksi' : ($jenis == 'per_outlet' ? 'Per Outlet' : 'Member')) }}
                </h3>
                <span class="text-sm text-slate-500">{{ date('d/m/Y', strtotime($start_date)) }} - {{ date('d/m/Y', strtotime($end_date)) }}</span>
            </div>
            <div class="flex gap-2 no-print">
                <button onclick="window.print()" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition-all">
                    Cetak
                </button>
            </div>
        </div>

        @if($results->count() > 0)
        <div class="overflow-x-auto">
            @if($jenis == 'keuangan')
            @php
                $maxTotal = $results->max('total_bersih') ?: 1;
                $grand = (object)['jumlah_transaksi'=>0,'subtotal'=>0,'biaya_tambahan'=>0,'diskon_amount'=>0,'pajak_amount'=>0,'total_bersih'=>0];
            @endphp

            {{-- Grafik --}}
            <div class="p-6 border-b border-slate-100 no-print">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Grafik Pendapatan Per Outlet</h4>
                    <button @click="showDetail = !showDetail" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                        <span x-text="showDetail ? 'Sembunyikan Detail' : 'Tampilkan Detail'"></span>
                    </button>
                </div>
                <div class="space-y-3">
                    @foreach($results as $r)
                    @php $pct = ($r->total_bersih / $maxTotal) * 100; @endphp
                    <div>
                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                            <span class="font-semibold">{{ $r->outlet }}</span>
                            <span class="font-mono font-bold text-indigo-600">Rp {{ number_format($r->total_bersih, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Detail per outlet (collapsible) --}}
            <div x-show="showDetail" x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-200">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 no-print">
                <h4 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-3">Rincian Per Outlet</h4>
                @foreach($results as $r)
                <div class="mb-4 p-4 bg-white rounded-xl border border-slate-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400">Outlet</span>
                            <p class="font-semibold text-slate-700">{{ $r->outlet }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Transaksi</span>
                            <p class="font-semibold text-slate-700">{{ $r->jumlah_transaksi }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Subtotal</span>
                            <p class="font-mono font-semibold text-slate-700">Rp {{ number_format($r->subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Biaya Tambahan</span>
                            <p class="font-mono font-semibold text-slate-700">Rp {{ number_format($r->biaya_tambahan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Diskon</span>
                            <p class="font-mono font-semibold text-red-500">-Rp {{ number_format($r->diskon_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Pajak</span>
                            <p class="font-mono font-semibold text-slate-700">Rp {{ number_format($r->pajak_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            </div>

            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Outlet</th>
                        <th class="px-6 py-4 text-center">Transaksi</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                        <th class="px-6 py-4 text-right">Biaya</th>
                        <th class="px-6 py-4 text-right">Diskon</th>
                        <th class="px-6 py-4 text-right">Pajak</th>
                        <th class="px-6 py-4 text-right">Total Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($results as $r)
                    @php
                        $grand->jumlah_transaksi += $r->jumlah_transaksi;
                        $grand->subtotal += $r->subtotal;
                        $grand->biaya_tambahan += $r->biaya_tambahan;
                        $grand->diskon_amount += $r->diskon_amount;
                        $grand->pajak_amount += $r->pajak_amount;
                        $grand->total_bersih += $r->total_bersih;
                    @endphp
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $r->outlet }}</td>
                        <td class="px-6 py-4 text-center">{{ $r->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->biaya_tambahan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-500">-Rp {{ number_format($r->diskon_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->pajak_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-indigo-600">Rp {{ number_format($r->total_bersih, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 text-slate-700 text-sm font-bold">
                    <tr>
                        <td class="px-6 py-4">TOTAL</td>
                        <td class="px-6 py-4 text-center">{{ $grand->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grand->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grand->biaya_tambahan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-500">-Rp {{ number_format($grand->diskon_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grand->pajak_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-indigo-600">Rp {{ number_format($grand->total_bersih, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- Ringkasan Total Keuangan --}}
            <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-t border-indigo-100 no-print">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-indigo-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                        <p class="text-2xl font-bold text-slate-700 mt-1">{{ $grand->jumlah_transaksi }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-indigo-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider">Total Subtotal</p>
                        <p class="text-lg font-bold text-slate-700 mt-1">Rp {{ number_format($grand->subtotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-indigo-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider">Total Diskon</p>
                        <p class="text-lg font-bold text-red-500 mt-1">-Rp {{ number_format($grand->diskon_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-indigo-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider">Total Biaya + Pajak</p>
                        <p class="text-lg font-bold text-slate-700 mt-1">Rp {{ number_format($grand->biaya_tambahan + $grand->pajak_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-indigo-200 ring-2 ring-indigo-200">
                        <p class="text-xs text-indigo-500 uppercase tracking-wider font-bold">PENDAPATAN BERSIH</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">Rp {{ number_format($grand->total_bersih, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            @elseif($jenis == 'transaksi')
            @php $grandTrans = (object)['total'=>0,'count'=>0]; @endphp
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Member</th>
                        <th class="px-6 py-4">Outlet</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Bayar</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $t)
                    @php
                        $grandTrans->total += $t->total;
                        $grandTrans->count++;
                        $memberId = $t->member ? '#'.Carbon\Carbon::parse($t->member->created_at)->format('Ym').sprintf('%03d', $t->member->id) : '';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-6 py-4 font-mono text-sm">
                            <a href="{{ route('transaksi.show', $t->id) }}" class="text-indigo-600 hover:underline">{{ $t->kode_invoice }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-700">{{ $t->member->name ?? '-' }}</div>
                            @if($memberId)<div class="text-xs text-slate-400 font-mono">{{ $memberId }}</div>@endif
                        </td>
                        <td class="px-6 py-4">{{ $t->outlet->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ date('d/m/Y', strtotime($t->tgl)) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                @if($t->status == 'baru') bg-blue-100 text-blue-700
                                @elseif($t->status == 'proses') bg-yellow-100 text-yellow-700
                                @elseif($t->status == 'selesai') bg-green-100 text-green-700
                                @else bg-slate-100 text-slate-700 @endif">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $t->dibayar == 'dibayar' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $t->dibayar == 'dibayar' ? 'Dibayar' : 'Belum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Tidak ada transaksi.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 text-slate-700 text-sm font-bold">
                    <tr>
                        <td colspan="5" class="px-6 py-4">Subtotal (halaman ini)</td>
                        <td class="px-6 py-4 text-center">{{ $grandTrans->count }}</td>
                        <td class="px-6 py-4 text-right text-indigo-600">Rp {{ number_format($grandTrans->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="p-4 no-print">{{ $results->links() }}</div>

            @elseif($jenis == 'per_outlet')
            @php $grandOutlet = (object)['jumlah_transaksi'=>0,'subtotal'=>0,'biaya_tambahan'=>0,'diskon_amount'=>0,'pajak_amount'=>0,'total_bersih'=>0]; @endphp
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Outlet</th>
                        <th class="px-6 py-4">Bulan</th>
                        <th class="px-6 py-4 text-center">Transaksi</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                        <th class="px-6 py-4 text-right">Biaya</th>
                        <th class="px-6 py-4 text-right">Diskon</th>
                        <th class="px-6 py-4 text-right">Pajak</th>
                        <th class="px-6 py-4 text-right">Total Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $r)
                    @php
                        $grandOutlet->jumlah_transaksi += $r->jumlah_transaksi;
                        $grandOutlet->subtotal += $r->subtotal;
                        $grandOutlet->biaya_tambahan += $r->biaya_tambahan;
                        $grandOutlet->diskon_amount += $r->diskon_amount;
                        $grandOutlet->pajak_amount += $r->pajak_amount;
                        $grandOutlet->total_bersih += $r->total_bersih;
                    @endphp
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $r->outlet }}</td>
                        <td class="px-6 py-4">{{ $r->bulan }}</td>
                        <td class="px-6 py-4 text-center">{{ $r->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->biaya_tambahan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-500">-Rp {{ number_format($r->diskon_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($r->pajak_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-indigo-600">Rp {{ number_format($r->total_bersih, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 text-slate-700 text-sm font-bold">
                    <tr>
                        <td colspan="2" class="px-6 py-4">TOTAL</td>
                        <td class="px-6 py-4 text-center">{{ $grandOutlet->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grandOutlet->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grandOutlet->biaya_tambahan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-500">-Rp {{ number_format($grandOutlet->diskon_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($grandOutlet->pajak_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-indigo-600">Rp {{ number_format($grandOutlet->total_bersih, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            @elseif($jenis == 'member')
            @php $grandMember = (object)['jumlah_transaksi'=>0,'total_belanja'=>0]; @endphp
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Member</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4 text-center">Transaksi</th>
                        <th class="px-6 py-4 text-right">Total Belanja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $r)
                    @php
                        $grandMember->jumlah_transaksi += $r->jumlah_transaksi;
                        $grandMember->total_belanja += $r->total_belanja;
                        $mId = $r->member ? '#'.Carbon\Carbon::parse($r->member->created_at)->format('Ym').sprintf('%03d', $r->member->id) : '';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-700">{{ $r->member->name ?? '-' }}</div>
                            @if($mId)<div class="text-xs text-slate-400 font-mono">{{ $mId }}</div>@endif
                        </td>
                        <td class="px-6 py-4">{{ $r->member->phone_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">{{ $r->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right font-bold text-indigo-600">Rp {{ number_format($r->total_belanja, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">Tidak ada data member.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 text-slate-700 text-sm font-bold">
                    <tr>
                        <td colspan="2" class="px-6 py-4">TOTAL</td>
                        <td class="px-6 py-4 text-center">{{ $grandMember->jumlah_transaksi }}</td>
                        <td class="px-6 py-4 text-right text-indigo-600">Rp {{ number_format($grandMember->total_belanja, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
            @endif
        </div>
        @else
        <div class="p-10 text-center text-slate-400 italic">
            Tidak ada data ditemukan untuk periode ini.
        </div>
        @endif
    </div>
    @endisset
</div>
@endsection
