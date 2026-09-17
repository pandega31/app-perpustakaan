@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
    <p><a href="{{ route('books.index') }}">&larr; Kembali ke daftar</a></p>

    <h1>Tambah Buku Baru</h1>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <div>
            <label for="judul">Judul Buku:</label><br>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}">
            @error('judul')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="penulis">Penulis:</label><br>
            <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}">
            @error('penulis')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="penerbit">Penerbit:</label><br>
            <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit') }}">
            @error('penerbit')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="tahun_terbit">Tahun Terbit:</label><br>
            <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}">
            @error('tahun_terbit')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="isbn">ISBN:</label><br>
            <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}">
            @error('isbn')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="stok">Stok:</label><br>
            <input type="number" id="stok" name="stok" value="{{ old('stok') }}">
            @error('stok')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <div>
            <label for="category_id">Kategori:</label><br>
            <select id="category_id" name="category_id">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                        {{ $category['nama_kategori'] }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <button type="submit" class="btn">Simpan</button>
    </form>
@endsection