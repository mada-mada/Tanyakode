<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Judul Halaman Dinamis --}}
  <title>{{ $title ?? 'Verifikasi OTP' }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  
  <link rel="stylesheet" href="{{ asset('admintle/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admintle/dist/css/adminlte.min.css') }}">

  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* KOTAK UTAMA (WRAPPER) */
    .auth-container {
        width: 1000px;
        max-width: 95%;
        height: 600px; /* Tinggi fix agar desain seimbang */
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        display: flex;
        overflow: hidden;
        position: relative;
    }

    /* BAGIAN KIRI (DARK BLUE) */
    .left-panel {
        width: 50%;
        background-color: #020617;
        color: white;
        /* Padding besar ini kuncinya agar tidak mentok ke atas */
        padding: 60px; 
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Memisahkan konten atas dan bawah */
        position: relative;
        z-index: 1;
    }

    /* Background Effects */
    .bg-dots {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: -1;
    }
    .bg-glow {
        position: absolute;
        bottom: -100px; left: -100px;
        width: 400px; height: 400px;
        background: #0066FF;
        filter: blur(120px);
        border-radius: 50%;
        opacity: 0.5;
        z-index: -1;
    }

    /* Typography Kiri */
    .logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 40px; /* Jarak antara Logo dan Judul Utama */
    }
    .logo-box {
        width: 48px; height: 48px;
        background: #0066FF;
        color: white;
        font-weight: bold;
        font-size: 24px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px;
    }
    .logo-text {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .headline {
        font-size: 42px; /* Font besar tapi proporsional */
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 20px;
    }
    .subhead {
        font-size: 16px;
        color: #94a3b8;
        line-height: 1.6;
        max-width: 90%;
    }

    /* Box Komunitas (Kiri Bawah) */
    .community-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 24px;
        border-radius: 16px;
        backdrop-filter: blur(10px);
    }

    /* BAGIAN KANAN (FORM PUTIH) */
    .right-panel {
        width: 50%;
        background: white;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Form pasti di tengah vertikal */
    }

    /* Styling Form Input */
    .input-group-custom {
        display: flex;
        align-items: stretch;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: 0.3s;
    }
    .input-group-custom:focus-within {
        border-color: #0066FF;
        box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.1);
    }
    .otp-field {
        flex: 1;
        border: none;
        padding: 16px;
        font-size: 20px;
        font-family: monospace;
        letter-spacing: 4px;
        font-weight: bold;
        text-align: center;
        outline: none;
    }
    .otp-field::placeholder {
        font-family: 'Poppins', sans-serif;
        letter-spacing: normal;
        font-size: 16px;
        font-weight: 400;
        color: #cbd5e1;
    }
    .icon-box {
        background: #f8fafc;
        padding: 0 20px;
        display: flex;
        align-items: center;
        color: #64748b;
        border-left: 1px solid #e2e8f0;
    }

    .btn-verify {
        display: block;
        width: 100%;
        background-color: #0066FF;
        color: white;
        font-weight: 600;
        padding: 16px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        margin-top: 24px;
        transition: 0.3s;
        cursor: pointer;
    }
    .btn-verify:hover {
        background-color: #0056b3;
    }

    /* Mobile Responsive */
    @media (max-width: 900px) {
        .auth-container { height: auto; flex-direction: column; width: 95%; margin: 20px; }
        .left-panel { display: none; } /* Hidden on Mobile */
        .right-panel { width: 100%; padding: 40px 20px; }
    }
  </style>
</head>
<body>

    <div class="auth-container">
        
        <div class="left-panel">
            <div class="bg-dots"></div>
            <div class="bg-glow"></div>

            <div>
                <div class="logo-area">
                    <div class="logo-box">T</div>
                    <div class="logo-text">TANYA<span style="color: #0EA5E9;">KODE</span></div>
                </div>

                <h1 class="headline">Akses Materi<br>Terbaik.</h1>
                <p class="subhead">
                    Bergabunglah dengan ribuan siswa lainnya dan mulai karir codingmu hari ini dengan aman.
                </p>
            </div>

            <div class="community-card">
                <div class="d-flex align-items-center mb-2" style="color: #fbbf24; font-weight: 700; font-size: 13px; text-transform: uppercase;">
                    <i class="fas fa-users mr-2"></i> Komunitas
                </div>
                <p style="margin: 0; font-size: 14px; font-style: italic; opacity: 0.9; line-height: 1.5;">
                    "Belajar coding sendirian itu berat, bersama TanyaKode jadi lebih mudah."
                </p>
            </div>
        </div>

        <div class="right-panel">
            
            <div class="text-center mb-5">
                {{-- JUDUL DINAMIS: Bisa berubah tergantung dari Controller --}}
                <h2 style="font-weight: 700; font-size: 28px; margin-bottom: 8px; color: #1e293b;">
                    {{ $title ?? 'SekolahCerdas' }}
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    {{ $message ?? 'Verifikasi Identitas Anda' }}
                </p>
            </div>

            <p class="text-center mb-4" style="color: #334155; font-size: 14px;">
                Masukkan 6 digit kode yang dikirim ke email:<br>
               <strong style="color: #0f172a; font-size: 15px;">
                  @if(Auth::check())
                            {{ Auth::user()->email }}  {{-- Jika User Login (Ganti Pass) --}}
                    @else
                            {{ session('reset_email') }} {{-- Jika User Tamu (Lupa Pass) --}}
                    @endif
                      </strong>
            </p>

            @if (session('error'))
                <div class="alert alert-danger text-center small p-2 mb-3 rounded" style="background:#fee2e2; color:#b91c1c; border:none;">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success text-center small p-2 mb-3 rounded" style="background:#dcfce7; color:#15803d; border:none;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 
                FORM ACTION DINAMIS:
                Jika variabel $actionUrl dikirim dari controller (saat ganti password), pakai itu.
                Jika tidak (saat login biasa), pakai route default 'otp.check'.
            --}}
            <form action="{{ $actionUrl ?? route('otp.check') }}" method="post">
                @csrf
                
                <div class="input-group-custom">
                    <input type="text" name="otp_code" class="otp-field" placeholder="Contoh: 123456" maxlength="6" required autofocus>
                    <div class="icon-box">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                @error('otp_code')
                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                @enderror

                <button type="submit" class="btn-verify">Verifikasi</button>
            </form>

            <div class="text-center mt-4">
                <p style="margin-bottom: 5px; font-size: 13px; color: #64748b;">Tidak menerima kode?</p>
                <form action="{{ route('otp.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link p-0" style="color: #0066FF; font-weight: 600; text-decoration: none; font-size: 14px;">
                        Kirim Ulang OTP
                    </button>
                </form>
            </div>
            
            <div class="text-center mt-4 pt-4 border-top">
                {{-- LOGIKA TOMBOL BAWAH --}}
                @if(Route::currentRouteName() == 'password.change.otp')
                    {{-- Jika sedang proses Ganti Password, tombolnya 'Batal' --}}
                    <a href="{{ route('password.change') }}" class="btn btn-link text-muted p-0" style="font-size: 13px; text-decoration: none; color: #94a3b8;">
                        <i class="fas fa-arrow-left mr-1"></i> Batal, Kembali
                    </a>
                @else
                    {{-- Jika sedang proses Aktivasi Login, tombolnya 'Logout' --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted p-0" style="font-size: 13px; text-decoration: none; color: #94a3b8;">
                            <i class="fas fa-sign-out-alt mr-1"></i> Ganti Akun
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

<script src="{{ asset('admintle/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admintle/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admintle/dist/js/adminlte.min.js') }}"></script>
</body>
</html>