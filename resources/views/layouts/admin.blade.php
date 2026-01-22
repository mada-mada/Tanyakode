<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Superadmin Dashboard')</title>

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
    
    .bg-gradient-primary {
      background: var(--gradient-1);
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
    }
    
    .logo-text {
      font-size: 1.8rem;
      font-weight: 800;
      color: #212529;
    }
    
    .logo-text span {
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
    }
    
    .nav-link.active {
      background: rgba(67, 97, 238, 0.2);
      border-left: 4px solid var(--primary);
      color: white !important;
    }
    
    .nav-link:hover:not(.active) {
      background: rgba(255, 255, 255, 0.05);
    }
  </style>
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
          <div class="logo-icon mr-2">TK</div>
          <div class="logo-text">Tanya<span>Kode</span></div>
        </div>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('superadmin.dashboard') }}" class="brand-link d-flex align-items-center">
      <div class="logo-icon">TK</div>
      <div class="logo-text ml-2 text-white">TanyaKode</div>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

          <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header text-uppercase text-muted mt-3">DATA MASTER</li>

          <li class="nav-item">
            <a href="{{ route('superadmin.sekolah.index') }}" class="nav-link {{ request()->routeIs('superadmin.sekolah*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>Data Sekolah</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('superadmin.adminsekolah.index') }}" class="nav-link {{ request()->routeIs('superadmin.adminsekolah*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Admin Sekolah</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('superadmin.admin.index') }}" class="nav-link {{ request()->routeIs('superadmin.admin*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Admin Internal</p>
            </a>
          </li>

          <li class="nav-item mt-4">
             <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-danger text-white text-left w-100">
                    <i class="nav-icon fas fa-sign-out-alt"></i> Logout
                </button>
             </form>
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
            <h1 class="m-0">@yield('header')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        
        @yield('content')
      </div>
    </section>
    </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 TanyaKode.</strong> All rights reserved.
  </footer>
</div>

<script src="{{ asset('admintle/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admintle/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admintle/dist/js/adminlte.js') }}"></script>

@yield('js')
</body>
</html>