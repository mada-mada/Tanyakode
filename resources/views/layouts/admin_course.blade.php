<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Admin Panel') | TanyaKode</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('admintle/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admintle/dist/css/adminlte.min.css') }}">
  
  <style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #7209b7;
        --accent: #4cc9f0;
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ef476f;
        --gradient-1: linear-gradient(135deg, #4361ee, #3a0ca3);
        --gradient-2: linear-gradient(135deg, #7209b7, #4361ee);
    }
    
    body {
        font-family: 'Source Sans Pro', sans-serif;
    }
    
    .logo-icon {
        width: 48px;
        height: 48px;
        background: var(--gradient-1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.5rem;
        margin-right: 10px;
    }
    
    .brand-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
    }
    
    .brand-text b {
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .main-sidebar {
        background: #0B132B;
    }
    
    .brand-link {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
    }
    
    .nav-link.active {
        background: rgba(67, 97, 238, 0.2);
        border-left: 4px solid var(--primary);
        color: white !important;
    }
    
    .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .nav-header {
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }
    
    .user-panel .info {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .user-panel .info a {
        color: white;
        font-weight: 600;
    }
    
    .user-panel .text-muted {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .content-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 0 0 12px 12px;
        margin-bottom: 20px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    
    .info-box {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    
    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-gradient {
        background: var(--gradient-1);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        background: var(--gradient-2);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(67, 97, 238, 0.3);
    }
    
    .card-primary.card-outline {
        border-top: 3px solid var(--primary);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .btn-app {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        text-align: center;
        display: block;
        width: 100%;
        height: auto;
    }
    
    .btn-app:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }
    
    .btn-app i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 10px;
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin-bottom: 10px;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        background-color: #e9ecef;
        border-left-color: var(--primary);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        font-size: 1rem;
    }
    
    .activity-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
    }
    
    .activity-time {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .list-group-item {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-color: var(--primary);
    }
    
    .main-footer {
        background: #0B132B;
        color: white;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .main-footer a {
        color: var(--accent);
        text-decoration: none;
    }
    
    .main-footer a:hover {
        color: white;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .content-header {
            padding: 15px;
        }
        
        .btn-app {
            margin-bottom: 15px;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
        }
        
        .activity-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
  </style>

  @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <div class="d-flex align-items-center">
          <div class="logo-icon">TK</div>
          <span class="text-dark fw-bold">TanyaKode Admin</span>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">3 Notifikasi</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user-plus mr-2"></i> 2 pengguna baru
            <span class="float-right text-muted text-sm">3 menit</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-book mr-2"></i> Kursus baru ditambahkan
            <span class="float-right text-muted text-sm">12 jam</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
        </div>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fas fa-user-circle mr-1"></i> {{ Auth::user()->name ?? 'User' }}
          <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
            <strong>{{ Auth::user()->name ?? 'User' }}</strong>
            <div class="text-muted small">{{ ucfirst(Auth::user()->role ?? '') }}</div>
          </div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profil
          </a>
          <a href="#" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> Pengaturan
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
             <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center">
      <div class="logo-icon">TK</div>
      <span class="brand-text font-weight-light">TanyaKode <b>Admin</b></span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('admintle/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name ?? 'User' }}</a>
          <small class="text-muted">{{ ucfirst(Auth::user()->role ?? '') }}</small>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header">AKADEMIK</li>

          <li class="nav-item">
            <a href="{{ route('courses.index') }}"
               class="nav-link {{ request()->routeIs('courses.*') || request()->routeIs('modules.*') || request()->routeIs('contents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Kelola Kursus
                <span class="right badge badge-primary">Utama</span>
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('modules.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Modul
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('contents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Konten
              </p>
            </a>
          </li>

          <li class="nav-header">MANAJEMEN USER</li>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Siswa</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Analitik</p>
            </a>
          </li>
          
          <li class="nav-header">LAINNYA</li>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Pengaturan</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <section class="content">
      <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="icon fas fa-check"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="icon fas fa-ban"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

      </div>
    </section>
  </div>
  
  <footer class="main-footer">
    <strong>Copyright &copy; 2026 <a href="#">TanyaKode</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

</div>

<script src="{{ asset('admintle/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admintle/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admintle/dist/js/adminlte.js') }}"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
      $('.alert').alert('close');
    }, 5000);
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add active class to parent nav-item when child is active
    $('.nav-link.active').closest('.nav-item').addClass('menu-open');
    
    // Sidebar menu toggle animation
    $('.nav-link').on('click', function() {
      if ($(this).hasClass('active')) {
        $(this).closest('.nav-item').addClass('menu-open');
      }
    });
    
    // Dashboard stats animation
    $('.info-box-number').each(function() {
      const $this = $(this);
      const target = parseInt($this.text());
      let current = 0;
      const increment = target / 100;
      const timer = setInterval(function() {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        $this.text(Math.floor(current));
      }, 20);
    });
  });
</script>

@yield('js')

</body>
</html>