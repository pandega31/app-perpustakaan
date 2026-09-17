@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <p><a href="{{ route('categories.index') }}">&larr; Kembali ke daftar</a></p>

    <h1>Tambah Kategori Baru</h1>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div>
            <label for="nama_kategori">Nama Kategori:</label><br>
            <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}">
            @error('nama_kategori')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="deskripsi">Deskripsi:</label><br>
            <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <button type="submit" class="btn">Simpan</button>
    </form>
@endsection