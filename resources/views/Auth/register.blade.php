<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - Sekolah Cerdas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen py-10">

    <div class="w-full max-w-4xl bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Pendaftaran Siswa Baru</h2>
            <p class="text-gray-500">Buat akun untuk memulai verifikasi OTP</p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-blue-600 border-b pb-2 mb-4">1. Informasi Akun</h3>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror" required>
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Email (Aktif)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Kode OTP akan dikirim kesini"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-semibold text-blue-600 border-b pb-2 mb-4">2. Data Pribadi & Sekolah</h3>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('full_name') border-red-500 @enderror" required>
                    @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">NIS</label>
                    <input type="number" name="nis" value="{{ old('nis') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('nis') border-red-500 @enderror">
                    @error('nis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">NISN</label>
                    <input type="number" name="nisn" value="{{ old('nisn') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('nisn') border-red-500 @enderror">
                    @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Sekolah</label>
                    <input type="text" name="school_name" value="{{ old('school_name') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('school_name') border-red-500 @enderror">
                    @error('school_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kategori Sekolah</label>
                    <select name="school_category" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="SMP" {{ old('school_category') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('school_category') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kelas</label>
                    <select name="grade" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Pilih Kelas</option>
                        <option value="1" {{ old('grade') == '1' ? 'selected' : '' }}>Kelas 1</option>
                        <option value="2" {{ old('grade') == '2' ? 'selected' : '' }}>Kelas 2</option>
                        <option value="3" {{ old('grade') == '3' ? 'selected' : '' }}>Kelas 3</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Domisili</label>
                    <input type="text" name="domisili" value="{{ old('domisili') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('domisili') border-red-500 @enderror">
                </div>

            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                    Daftar & Kirim OTP
                </button>
            </div>

            <p class="mt-4 text-center text-gray-600 text-sm">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login disini</a>
            </p>
        </form>
    </div>

</body>
</html>
