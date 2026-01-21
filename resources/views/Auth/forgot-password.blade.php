<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - TanyaKode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brand: { blue: '#0066FF', dark: '#020617', sky: '#0EA5E9' }
                    }
                }
            }
        }
    </script>
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-5xl h-[600px] rounded-2xl shadow-2xl overflow-hidden flex relative">

        <div class="hidden md:flex w-1/2 bg-brand-dark text-white flex-col justify-between p-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-20 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px]"></div>
            <div class="absolute bottom-[-50px] left-[-50px] w-64 h-64 bg-brand-blue rounded-full blur-[80px] opacity-50"></div>

            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-6 hover:opacity-80 transition">
                    <div class="h-10 w-10 bg-brand-blue rounded flex items-center justify-center font-bold text-xl">T</div>
                    <span class="text-2xl font-bold tracking-wide">
                        <span class="text-white">TANYA</span><span class="text-brand-sky">KODE</span>
                    </span>
                </a>
                <h2 class="text-4xl font-bold leading-tight mb-4">Reset Password.</h2>
                <p class="text-blue-200">Jangan khawatir, kami akan membantu mengembalikan akun Anda.</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col justify-center p-8 md:p-12 relative">

            <a href="{{ route('login') }}" class="absolute top-6 left-6 text-gray-500 flex items-center text-sm hover:text-brand-blue transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
            </a>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
                <p class="text-gray-500 text-sm">Masukkan email yang terdaftar. Kami akan mengirimkan kode OTP untuk mereset password Anda.</p>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.sendOtp') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Terdaftar</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('email') border-red-500 @enderror" required autofocus>
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-brand-blue text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-lg transform active:scale-95">
                    Kirim Kode OTP
                </button>
            </form>

        </div>
    </div>

</body>
</html>