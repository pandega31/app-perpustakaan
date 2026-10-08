<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <style>
        body { font-family: sans-serif; margin: 40px; max-width: 500px; }
        h2 { margin-top: 32px; }
        table td { padding: 4px 12px 4px 0; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 4px; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
        .btn { margin-top: 20px; padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Profil Saya</h1>
    <p><a href="{{ route('books.index') }}">&larr; Kembali</a></p>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <tr><td><strong>Nama</strong></td><td>: {{ $user->name }}</td></tr>
        <tr><td><strong>Email</strong></td><td>: {{ $user->email }}</td></tr>
        <tr><td><strong>Role</strong></td><td>: {{ ucfirst($user->role) }}</td></tr>
    </table>

    <h2>Ganti Password</h2>
    <form action="{{ route('profile.password') }}" method="POST">
        @csrf
        @method('PUT')

        <label for="current_password">Password Lama</label>
        <input type="password" name="current_password" id="current_password">
        @error('current_password')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password">Password Baru</label>
        <input type="password" name="password" id="password">
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" id="password_confirmation">

        <button type="submit" class="btn">Ganti Password</button>
    </form>
</body>
</html>