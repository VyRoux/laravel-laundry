<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Member;
use App\Models\Paket;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $transaksi = Transaksi::with(['member', 'outlet', 'details.paket'])
                ->whereNull('deleted_at')
                ->orderBy('tgl', 'desc')
                ->get();
        } else {
            $transaksi = Transaksi::with(['member', 'outlet', 'details.paket'])
                ->where('outlet_id', $user->outlet_id)
                ->whereNull('deleted_at')
                ->orderBy('tgl', 'desc')
                ->get();
        }

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $user = auth()->user();
        $members = Member::whereNull('deleted_at')->get();
        $outlets = Outlet::whereNull('deleted_at')->get();

        if ($user->role === 'admin') {
            $pakets = Paket::whereNull('deleted_at')->get();
        } else {
            $pakets = Paket::whereNull('deleted_at')->where('outlet_id', $user->outlet_id)->get();
        }

        return view('admin.transaksi.create', compact('members', 'pakets', 'outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id'         => 'required|exists:tbl_outlet,id',
            'member_id'         => 'required|exists:tbl_member,id',
            'tgl'               => 'required|date',
            'batas_waktu'       => 'required|date|after_or_equal:tgl',
            'biaya_tambahan'    => 'nullable|integer|min:0',
            'diskon'            => 'nullable|numeric|min:0|max:100',
            'pajak'             => 'nullable|numeric|min:0|max:100',
            'status'            => 'required|in:baru,proses,selesai,diambil',
            'dibayar'           => 'required|in:dibayar,belum_dibayar',
            'items'             => 'required|array|min:1',
            'items.*.paket_id'  => 'required|exists:tbl_paket,id',
            'items.*.qty'       => 'required|numeric|min:0',
            'items.*.keterangan'=> 'nullable|string',
        ]);

        $kodeInvoice = $this->generateKodeInvoice();

        DB::transaction(function () use ($request, $kodeInvoice) {

            $transaksi = Transaksi::create([
                'outlet_id'      => $request->outlet_id,
                'kode_invoice'   => $kodeInvoice,
                'member_id'      => $request->member_id,
                'tgl'            => $request->tgl,
                'batas_waktu'    => $request->batas_waktu,
                'tgl_bayar'      => $request->dibayar === 'dibayar' ? now() : null,
                'biaya_tambahan' => $request->biaya_tambahan,
                'diskon'         => $request->diskon,
                'pajak'          => $request->pajak,
                'status'         => $request->status,
                'dibayar'        => $request->dibayar,
                'user_id'        => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'paket_id'     => $item['paket_id'],
                    'qty'          => $item['qty'],
                    'keterangan'   => $item['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat dengan invoice ' . $kodeInvoice);
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['member', 'outlet', 'user', 'details.paket']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        return view('admin.transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status'  => 'required|in:baru,proses,selesai,diambil',
            'dibayar' => 'required|in:dibayar,belum_dibayar',
        ]);

        $data = $request->only(['status', 'dibayar']);

        if ($request->dibayar === 'dibayar' && $transaksi->dibayar !== 'dibayar') {
            $data['tgl_bayar'] = now();
        }

        $transaksi->update($data);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->dibayar === 'dibayar') {
            return back()->with('error', 'Transaksi yang sudah dibayar tidak bisa dihapus!');
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dipindahkan ke tempat sampah.');
    }

    public function trashed()
    {
        $transaksi = Transaksi::with(['member', 'outlet', 'details.paket'])
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.transaksi.trashed', compact('transaksi'));
    }

    public function restore($id)
    {
        $transaksi = Transaksi::withTrashed()->findOrFail($id);
        $transaksi->restore();

        DetailTransaksi::onlyTrashed()
            ->where('transaksi_id', $id)
            ->restore();

        return redirect()->route('transaksi.trashed')
            ->with('success', 'Transaksi berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang bisa menghapus permanen!');
        }

        $transaksi = Transaksi::withTrashed()->findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            $transaksi->details()->forceDelete();
            $transaksi->forceDelete();
        });

        return redirect()->route('transaksi.trashed')
            ->with('success', 'Transaksi berhasil dihapus permanen.');
    }

    private function generateKodeInvoice()
    {
        $prefix = 'INV/' . date('Ymd') . '/';
        $lastTransaksi = Transaksi::withTrashed()
            ->where('kode_invoice', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = (int) substr($lastTransaksi->kode_invoice, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
