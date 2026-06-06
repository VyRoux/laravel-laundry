<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $outlets = Outlet::whereNull('deleted_at')->get();
        return view('admin.report.index', compact('outlets'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'jenis'      => 'required|in:keuangan,transaksi,per_outlet,member',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'outlet_id'  => 'nullable|exists:tbl_outlet,id',
            'status'     => 'nullable|in:baru,proses,selesai,diambil',
            'dibayar'    => 'nullable|in:dibayar,belum_dibayar',
        ]);

        $user = auth()->user();
        $outlets = Outlet::whereNull('deleted_at')->get();
        $results = null;
        $jenis = $request->jenis;

        switch ($jenis) {
            case 'keuangan':
                $results = $this->laporanKeuangan($request, $user);
                break;
            case 'transaksi':
                $results = $this->laporanTransaksi($request, $user);
                break;
            case 'per_outlet':
                $results = $this->laporanPerOutlet($request, $user);
                break;
            case 'member':
                $results = $this->laporanMember($request, $user);
                break;
        }

        return view('admin.report.index', array_merge(
            compact('outlets', 'results', 'jenis'),
            [
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'outlet_id'  => $request->outlet_id,
                'status'     => $request->status,
                'dibayar'    => $request->dibayar,
            ]
        ));
    }

    private function laporanKeuangan(Request $request, $user)
    {
        $query = DB::table('tbl_transaksi')
            ->whereNull('tbl_transaksi.deleted_at')
            ->join('tbl_detail_transaksi', 'tbl_transaksi.id', '=', 'tbl_detail_transaksi.transaksi_id')
            ->whereNull('tbl_detail_transaksi.deleted_at')
            ->join('tbl_paket', 'tbl_detail_transaksi.paket_id', '=', 'tbl_paket.id')
            ->whereNull('tbl_paket.deleted_at')
            ->whereBetween('tbl_transaksi.tgl', [$request->start_date, $request->end_date])
            ->select(
                'tbl_transaksi.outlet_id',
                DB::raw('COUNT(DISTINCT tbl_transaksi.id) as jumlah_transaksi'),
                DB::raw('SUM(tbl_detail_transaksi.qty * tbl_paket.harga) as subtotal'),
                DB::raw('COALESCE(SUM(tbl_transaksi.biaya_tambahan), 0) as biaya_tambahan'),
                DB::raw('SUM(tbl_transaksi.diskon * tbl_detail_transaksi.qty * tbl_paket.harga / 100) as diskon_amount'),
                DB::raw('SUM(tbl_transaksi.pajak * tbl_detail_transaksi.qty * tbl_paket.harga / 100) as pajak_amount')
            );

        if ($user->role === 'kasir') {
            $query->where('tbl_transaksi.outlet_id', $user->outlet_id);
        } elseif ($request->outlet_id) {
            $query->where('tbl_transaksi.outlet_id', $request->outlet_id);
        }

        $results = $query->groupBy('tbl_transaksi.outlet_id')->get();

        return $results->map(function ($item) {
            $item->outlet       = Outlet::withTrashed()->find($item->outlet_id)->name ?? 'Unknown';
            $item->total_bersih = $item->subtotal + $item->biaya_tambahan - $item->diskon_amount + $item->pajak_amount;
            return $item;
        });
    }

    private function laporanTransaksi(Request $request, $user)
    {
        $query = Transaksi::with(['member', 'outlet', 'details.paket'])
            ->whereNull('deleted_at')
            ->whereBetween('tgl', [$request->start_date, $request->end_date]);

        if ($user->role === 'kasir') {
            $query->where('outlet_id', $user->outlet_id);
        } elseif ($request->outlet_id) {
            $query->where('outlet_id', $request->outlet_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->dibayar) {
            $query->where('dibayar', $request->dibayar);
        }

        $results = $query->orderBy('tgl', 'desc')->paginate(20);
        $results->withQueryString();

        $results->getCollection()->transform(function ($t) {
            $subtotal = $t->details->sum(fn($d) => $d->qty * ($d->paket->harga ?? 0));
            $diskon = $subtotal * ($t->diskon / 100);
            $pajak  = $subtotal * ($t->pajak / 100);
            $t->subtotal      = $subtotal;
            $t->diskon_amount = $diskon;
            $t->pajak_amount  = $pajak;
            $t->total         = $subtotal - $diskon + ($t->biaya_tambahan ?? 0) + $pajak;
            return $t;
        });

        return $results;
    }

    private function laporanPerOutlet(Request $request, $user)
    {
        $query = DB::table('tbl_transaksi')
            ->whereNull('tbl_transaksi.deleted_at')
            ->join('tbl_detail_transaksi', 'tbl_transaksi.id', '=', 'tbl_detail_transaksi.transaksi_id')
            ->whereNull('tbl_detail_transaksi.deleted_at')
            ->join('tbl_paket', 'tbl_detail_transaksi.paket_id', '=', 'tbl_paket.id')
            ->whereNull('tbl_paket.deleted_at')
            ->whereBetween('tbl_transaksi.tgl', [$request->start_date, $request->end_date])
            ->select(
                'tbl_transaksi.outlet_id',
                DB::raw("DATE_FORMAT(tbl_transaksi.tgl, '%Y-%m') as bulan"),
                DB::raw('COUNT(DISTINCT tbl_transaksi.id) as jumlah_transaksi'),
                DB::raw('SUM(tbl_detail_transaksi.qty * tbl_paket.harga) as subtotal'),
                DB::raw('COALESCE(SUM(tbl_transaksi.biaya_tambahan), 0) as biaya_tambahan'),
                DB::raw('SUM(tbl_transaksi.diskon * tbl_detail_transaksi.qty * tbl_paket.harga / 100) as diskon_amount'),
                DB::raw('SUM(tbl_transaksi.pajak * tbl_detail_transaksi.qty * tbl_paket.harga / 100) as pajak_amount')
            );

        if ($user->role === 'kasir') {
            $query->where('tbl_transaksi.outlet_id', $user->outlet_id);
        } elseif ($request->outlet_id) {
            $query->where('tbl_transaksi.outlet_id', $request->outlet_id);
        }

        $results = $query->groupBy('tbl_transaksi.outlet_id', 'bulan')
            ->orderBy('tbl_transaksi.outlet_id')
            ->orderBy('bulan')
            ->get();

        return $results->map(function ($item) {
            $item->outlet       = Outlet::withTrashed()->find($item->outlet_id)->name ?? 'Unknown';
            $item->total_bersih = $item->subtotal + $item->biaya_tambahan - $item->diskon_amount + $item->pajak_amount;
            return $item;
        });
    }

    private function laporanMember(Request $request, $user)
    {
        $query = Transaksi::with(['member', 'outlet', 'details.paket'])
            ->whereNull('deleted_at')
            ->whereBetween('tgl', [$request->start_date, $request->end_date]);

        if ($user->role === 'kasir') {
            $query->where('outlet_id', $user->outlet_id);
        } elseif ($request->outlet_id) {
            $query->where('outlet_id', $request->outlet_id);
        }

        $transaksis = $query->orderBy('tgl', 'desc')->get();

        return $transaksis->groupBy('member_id')->map(function ($items, $memberId) {
            $member = $items->first()->member;
            $totalBelanja = $items->sum(function ($t) {
                $subtotal = $t->details->sum(fn($d) => $d->qty * ($d->paket->harga ?? 0));
                $diskon = $subtotal * ($t->diskon / 100);
                $pajak  = $subtotal * ($t->pajak / 100);
                return $subtotal - $diskon + ($t->biaya_tambahan ?? 0) + $pajak;
            });
            return (object) [
                'member'           => $member,
                'jumlah_transaksi' => $items->count(),
                'total_belanja'    => $totalBelanja,
            ];
        })->values();
    }
}
