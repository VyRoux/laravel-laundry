<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::whereNull('deleted_at')->get();
        return view('admin.member.index', compact('members'));
    }

    public function create()
    {
        return view('admin.member.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required',
            'phone_number' => 'required|numeric',
            'gender' => 'required|in:laki-laki,perempuan',
        ]);

        Member::create($request->all());

        return redirect()->route('member.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        //
    }

    public function edit(Member $member)
    {
        return view('admin.member.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'gender' => 'required|in:laki-laki,perempuan',
            'phone_number' => 'required|numeric',
        ]);

        $member->update($request->all());

        return redirect()->route('member.index')
            ->with('success', 'Data member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->transaksis()->count() > 0) {
            return back()->with('error', 'Member tidak bisa dihapus karena sudah memiliki transaksi!');
        }

        $member->delete();

        return redirect()->route('member.index')
            ->with('success', 'Member berhasil dipindahkan ke tempat sampah.');
    }

    public function trashed()
    {
        $members = Member::onlyTrashed()->get();
        return view('admin.member.trashed', compact('members'));
    }

    public function restore($id)
    {
        $member = Member::withTrashed()->findOrFail($id);
        $member->restore();

        return redirect()->route('member.trashed')
            ->with('success', 'Member berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang bisa menghapus permanen!');
        }

        $member = Member::withTrashed()->findOrFail($id);
        $member->forceDelete();

        return redirect()->route('member.trashed')
            ->with('success', 'Member berhasil dihapus permanen.');
    }
}
