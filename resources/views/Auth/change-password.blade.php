<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ganti Password</h2>
            <p class="text-gray-500 text-sm">Amankan akun Anda dengan verifikasi email.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('otp_sent'))
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kode OTP dari Email</label>
                    <input type="text" name="otp_code" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan 6 digit kode" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 6 karakter" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                    Simpan Password Baru
                </button>
            </form>

            <form action="{{ route('password.sendOtp') }}" method="POST" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-sm text-blue-500 hover:underline bg-transparent border-none cursor-pointer">
                    Kirim Ulang Kode OTP
                </button>
            </form>

        @else
            <div class="text-center">
                <p class="text-gray-600 mb-4 text-sm">
                    Untuk mengubah password, kami perlu memverifikasi bahwa ini benar-benar Anda.
                    Klik tombol di bawah untuk mengirim kode OTP ke email:
                    <strong>{{ Auth::user()->email }}</strong>
                </p>

                <form action="{{ route('password.sendOtp') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                        Kirim Kode Verifikasi
                    </button>
                </form>

                <div class="mt-4">
                    <a href="{{ url('/') }}" class="text-gray-500 text-sm hover:text-gray-800">Kembali ke Dashboard</a>
                </div>
            </div>
        @endif
    </div>

</body>
</html>
