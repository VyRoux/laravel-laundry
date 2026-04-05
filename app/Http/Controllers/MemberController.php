<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data member dari database
        $members = Member::all();
        // Kirim ke view di folder resources/views/admin/member/index.blade.php
        return view('admin.member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan form untuk membuat member baru
        return view('admin.member.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang baru masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|min:5',
            'phone_number' => 'required|numeric',
            'gender' => 'required|in:laki-laki,perempuan',
        ]);

        // JIka validasi berhasil, maka simpan data ini ke database
        Member::create($request->all());

        // Seletah selesai, kembalikan ke halaman index
        return redirect()->route('member.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
