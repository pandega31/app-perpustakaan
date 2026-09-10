<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private array $members = [
        ['id' => 1, 'nama' => 'Pandega Surya', 'nim' => '3125600091', 'email' => 'pandega@example.com', 'nomor_telepon' => '081234567890', 'alamat' => 'Surabaya', 'status' => 'aktif'],
        ['id' => 2, 'nama' => 'Abditama', 'nim' => '312560002', 'email' => 'surya@example.com', 'nomor_telepon' => '089876543210', 'alamat' => 'Sidoarjo', 'status' => 'aktif'],
    ];

    public function index()
    {
        $members = $this->members;

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        return redirect()->route('members.index')
            ->with('success', "Anggota \"{$validated['nama']}\" berhasil ditambahkan (data dummy, belum tersimpan ke database).");
    }

    public function show(string $id)
    {
        return "MemberController@show, id: {$id}";
    }

    public function edit(string $id)
    {
        return "MemberController@edit, id: {$id}";
    }

    public function update(Request $request, string $id)
    {
        return "MemberController@update, id: {$id}";
    }

    public function destroy(string $id)
    {
        return redirect()->route('members.index')
            ->with('success', "Anggota dengan id {$id} berhasil dihapus (data dummy).");
    }
}