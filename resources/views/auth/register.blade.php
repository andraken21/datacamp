<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center py-10" style="background:#0a0e1a">

<div class="w-full max-w-md px-6">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="/" class="text-green-400 text-2xl font-medium">&#9632; agentcamp</a>
        <p class="text-white/40 text-sm mt-2">Buat akun gratis kamu</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl p-8">

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-green-400"
                    placeholder="Nama kamu">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1.5">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-green-400"
                    placeholder="nama@email.com">
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1.5">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-green-400"
                    placeholder="Min. 8 karakter">
                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-green-400"
                    placeholder="Ulangi password">
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300 transition-colors">
                Buat Akun Gratis
            </button>

            <p class="text-xs text-gray-400 text-center mt-4">
                Dengan mendaftar, kamu menyetujui <a href="#" class="text-green-600">Syarat Layanan</a> dan <a href="#" class="text-green-600">Kebijakan Privasi</a> kami.
            </p>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-500 font-medium">Masuk di sini</a>
        </p>
    </div>

</div>
</body>
</html>