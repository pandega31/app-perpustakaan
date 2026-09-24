@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <h1>Edit Anggota</h1>
    <p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar anggota</a></p>

    <form action="{{ route('members.update', $member->id) }}" method="POST" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <label for="nama" style="display:block; margin-top:12px; font-weight:bold;">Nama</label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $member->nama) }}" style="width:100%; padding:6px;">
        @error('nama')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="nim" style="display:block; margin-top:12px; font-weight:bold;">NIM</label>
        <input type="text" name="nim" id="nim" value="{{ old('nim', $member->nim) }}" style="width:100%; padding:6px;">
        @error('nim')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="email" style="display:block; margin-top:12px; font-weight:bold;">Email</label>
        <input type="text" name="email" id="email" value="{{ old('email', $member->email) }}" style="width:100%; padding:6px;">
        @error('email')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="nomor_telepon" style="display:block; margin-top:12px; font-weight:bold;">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $member->nomor_telepon) }}" style="width:100%; padding:6px;">
        @error('nomor_telepon')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="alamat" style="display:block; margin-top:12px; font-weight:bold;">Alamat</label>
        <textarea name="alamat" id="alamat" rows="3" style="width:100%; padding:6px;">{{ old('alamat', $member->alamat) }}</textarea>
        @error('alamat')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <label for="status" style="display:block; margin-top:12px; font-weight:bold;">Status</label>
        <select name="status" id="status" style="width:100%; padding:6px;">
            <option value="">-- Pilih Status --</option>
            <option value="aktif" @selected(old('status', $member->status) == 'aktif')>Aktif</option>
            <option value="nonaktif" @selected(old('status', $member->status) == 'nonaktif')>Nonaktif</option>
        </select>
        @error('status')
            <div style="color:#b91c1c; font-size:14px;">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn" style="margin-top:20px;">Perbarui</button>
    </form>
@endsection