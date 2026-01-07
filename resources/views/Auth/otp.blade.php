<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifikasi OTP</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('admintle/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admintle/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admintle/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Sekolah</b>Cerdas</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Masukkan 6 digit kode yang dikirim ke email <strong>{{ Auth::user()->email }}</strong></p>

      @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      <form action="{{ route('otp.check') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="otp_code" class="form-control" placeholder="Contoh: 123456" maxlength="6" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        @error('otp_code')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror

        <div class="row mt-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Verifikasi</button>
          </div>
        </div>
      </form>

      <p class="mb-1">Tidak menerima kode?</p>

<form action="{{ route('otp.resend') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-bold">
        Kirim Ulang OTP
    </button>
</form>

    </div>
    </div>
  </div>
<script src="{{ asset('admintle/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admintle/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admintle/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
