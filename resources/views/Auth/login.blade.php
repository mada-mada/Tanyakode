<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar - TanyaKode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brand: {
                            blue: '#0066FF',
                            dark: '#020617',
                            sky: '#0EA5E9'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-5xl h-[650px] md:h-[600px] rounded-2xl shadow-2xl overflow-hidden flex relative">

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

                <h2 class="text-4xl font-bold leading-tight mb-4">Akses Materi Terbaik.</h2>
                <p class="text-blue-200">Bergabunglah dengan ribuan siswa lainnya dan mulai karir codingmu hari ini.</p>
            </div>

            <div class="relative z-10 bg-white/10 p-6 rounded-xl border border-white/10 backdrop-blur-sm">
                <div class="flex items-center mb-2">
                    <i class="fas fa-users text-yellow-400 text-xl mr-2"></i>
                    <h3 class="font-bold text-sm text-yellow-400 uppercase">Komunitas</h3>
                </div>
                <p class="text-sm italic text-blue-100">"Belajar coding sendirian itu berat, bersama TanyaKode jadi lebih mudah."</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col justify-start p-8 md:p-12 pt-24 md:pt-20 overflow-y-auto relative">

            <a href="/" class="md:hidden text-gray-500 mb-6 flex items-center text-sm hover:text-brand-blue transition absolute top-6 left-6">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

           <div id="tab-container" class="flex bg-gray-100 p-1 rounded-full mb-8 w-max mx-auto md:mx-0 mt-0 md:mt-0 ">
                <button onclick="switchTab('login')" id="tab-login" class="px-6 py-2 rounded-full text-sm font-bold transition-all bg-white text-brand-blue shadow-sm">Masuk</button>
                <button onclick="switchTab('register')" id="tab-register" class="px-6 py-2 rounded-full text-sm font-bold transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-200">Daftar</button>
            </div>

            <div id="form-login" class="block animate-fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
                <p class="text-gray-500 mb-6">Silakan masuk untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('email') border-red-500 @enderror" required>
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-brand-blue hover:underline">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" id="login-pass" placeholder="••••••••" class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('password') border-red-500 @enderror" required>
                            <button type="button" onclick="togglePassword('login-pass', this)" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-brand-blue text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-lg transform active:scale-95">
                        Masuk Sekarang
                    </button>
                </form>
            </div>

            <div id="form-register" class="hidden animate-fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun</h2>
                <p class="text-gray-500 mb-6">Hanya butuh Username, Email, & Password.</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="Username Kamu" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('username') border-red-500 @enderror" required>
                        </div>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('email') border-red-500 @enderror" required>
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" id="reg-pass" placeholder="Buat password kuat" class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition @error('password') border-red-500 @enderror" required>
                            <button type="button" onclick="togglePassword('reg-pass', this)" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <i class="fas fa-check-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password_confirmation" id="reg-pass-conf" placeholder="Ulangi password" class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 focus:border-brand-blue outline-none transition" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-brand-dark text-white font-bold py-3 rounded-lg hover:bg-gray-900 transition shadow-lg transform active:scale-95">
                        Daftar Gratis
                    </button>
                </form>
                <div class="mt-6 text-center text-xs text-gray-500">
                    Dengan mendaftar, kamu menyetujui <a href="#" class="text-brand-blue hover:underline">Syarat & Ketentuan</a> kami.
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('form-login');
            const registerForm = document.getElementById('form-register');
            const btnLogin = document.getElementById('tab-login');
            const btnRegister = document.getElementById('tab-register');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');

                btnLogin.classList.add('bg-white', 'text-brand-blue', 'shadow-sm');
                btnLogin.classList.remove('text-gray-500', 'hover:bg-gray-200');
                btnRegister.classList.remove('bg-white', 'text-brand-blue', 'shadow-sm');
                btnRegister.classList.add('text-gray-500', 'hover:bg-gray-200');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');

                btnRegister.classList.add('bg-white', 'text-brand-blue', 'shadow-sm');
                btnRegister.classList.remove('text-gray-500', 'hover:bg-gray-200');
                btnLogin.classList.remove('bg-white', 'text-brand-blue', 'shadow-sm');
                btnLogin.classList.add('text-gray-500', 'hover:bg-gray-200');
            }
        }

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // LOGIKA TAMBAHAN:
        // Jika user gagal Register (ada error username/password_confirmation), otomatis pindah ke tab Register saat halaman reload.
        document.addEventListener("DOMContentLoaded", function() {
            @if($errors->has('username') || $errors->has('password_confirmation') || ($errors->has('email') && old('username')))
                switchTab('register');
            @endif
        });
    </script>
</body>
</html>
