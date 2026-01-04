<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Superadmin Dashboard')</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('admintle/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admintle/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('superadmin.dashboard') }}" class="brand-link">
      <span class="brand-text font-weight-light">TanyaKode Admin</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

          <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header">DATA MASTER</li>

          <li class="nav-item">
            <a href="{{ route('superadmin.sekolah.index') }}" class="nav-link {{ request()->is('superadmin/sekolah*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>Data Sekolah</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('superadmin.adminsekolah.index') }}" class="nav-link {{ request()->is('superadmin/adminsekolah*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Admin Sekolah</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('superadmin.admin.index') }}" class="nav-link {{ request()->is('superadmin/admin*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Admin Internal</p>
            </a>
          </li>

          <li class="nav-item mt-4">
             <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-danger text-white text-left">
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
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
</body>
</html>
