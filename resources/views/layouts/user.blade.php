<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | TanyaKode</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --navy-dark: #0a192f;
            --navy-medium: #112240;
            --navy-light: #233554;
            --accent-blue: #64ffda;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* --- ANIMATED BACKGROUND SHAPES --- */
        .bg-shape-1 {
            position: fixed; width: 600px; height: 600px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.03) 0%, rgba(100, 255, 218, 0.03) 100%);
            top: -300px; right: -200px; animation: float-rotate 40s linear infinite; z-index: -1;
        }
        .bg-shape-2 {
            position: fixed; width: 400px; height: 400px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(100, 255, 218, 0.02) 0%, rgba(10, 25, 47, 0.02) 100%);
            bottom: -200px; left: -100px; animation: float-rotate 35s linear infinite reverse; z-index: -1;
        }
        .bg-shape-3 {
            position: fixed; width: 300px; height: 300px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(17, 34, 64, 0.03) 0%, rgba(100, 255, 218, 0.03) 100%);
            top: 50%; right: 10%; animation: pulse-glow 8s ease-in-out infinite; z-index: -1;
        }
        .floating-line {
            position: fixed; width: 2px; height: 200px;
            background: linear-gradient(to bottom, transparent, var(--accent-blue), transparent);
            animation: fall-down 15s linear infinite; z-index: -1;
        }
        .floating-dot {
            position: fixed; width: 8px; height: 8px; background: var(--accent-blue);
            border-radius: 50%; animation: float-around 25s linear infinite; z-index: -1; opacity: 0.3;
        }

        @keyframes float-rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes pulse-glow { 0%, 100% { opacity: 0.3; transform: scale(1); } 50% { opacity: 0.6; transform: scale(1.1); } }
        @keyframes fall-down { 0% { top: -200px; opacity: 0; } 10%, 90% { opacity: 1; } 100% { top: 100vh; opacity: 0; } }
        @keyframes float-around { 0% { transform: translate(0, 0); } 100% { transform: translate(100vw, 100vh); } }

        /* --- SIDEBAR --- */
        .sidebar {
            background: linear-gradient(180deg, var(--navy-dark), var(--navy-medium));
            min-height: 100vh; color: var(--white); width: 250px; position: fixed; z-index: 1000;
            box-shadow: 5px 0 25px rgba(10, 25, 47, 0.2);
        }
        .sidebar-header { padding: 30px 25px; border-bottom: 1px solid rgba(100, 255, 218, 0.1); }
        .sidebar-header h4 { font-weight: 700; color: var(--accent-blue); margin: 0; }

        .nav-item { padding: 12px 25px; transition: all 0.3s ease; border-left: 3px solid transparent; }
        .nav-item a { color: rgba(255, 255, 255, 0.8); text-decoration: none; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .nav-item i { width: 20px; font-size: 1.1rem; }
        
        .nav-item:hover { background: rgba(100, 255, 218, 0.05); }
        .nav-item:hover a { color: var(--white); transform: translateX(5px); transition: 0.3s; }
        
        /* Class Active Dinamis */
        .nav-item.active { background: rgba(100, 255, 218, 0.1); border-left-color: var(--accent-blue); }
        .nav-item.active a { color: var(--accent-blue); }

        .content-wrapper { margin-left: 250px; padding: 30px; min-height: 100vh; }

        /* --- BUTTONS & BADGES --- */
        .btn-accent { background: var(--accent-blue); color: var(--navy-dark); font-weight: 600; border-radius: 10px; transition: 0.3s; border: none; }
        .btn-accent:hover { background: #52e0c4; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(100, 255, 218, 0.3); }
        .notification-badge { position: absolute; top: 5px; right: 10px; background: #dc3545; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; }
    </style>
    @yield('css')
</head>
<body>
<div class="bg-shape-1"></div>
<div class="bg-shape-2"></div>
<div class="bg-shape-3"></div>

<div class="d-flex">
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>TANYAKODE</h4>
        </div>
        
        <div class="nav flex-column pt-4">
            <div class="nav-item {{ Route::is('user.dashboard') ? 'active' : '' }}">
                <a href="{{ route('user.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-item {{ Route::is('user.courses.index') ? 'active' : '' }}">
                <a href="{{ route('user.courses.index') }}">    
                    <i class="fa-solid fa-book-open"></i>
                    <span>Kursus Saya</span>
                </a>
            </div>  

            <div class="nav-item {{ Route::is('user.courses.catalog') ? 'active' : '' }}">
                <a href="{{ route('user.courses.catalog') }}">    
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Katalog Kursus</span>
                </a>
            </div>  

            <div class="nav-item {{ Request::is('user/payment*') ? 'active' : '' }}">
                <a href="#">    
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Pembayaran</span>
                </a>
            </div>

            <div class="nav-item {{ Route::is('user.spin') ? 'active' : '' }}">
                <a href="{{ route('user.spin') }}">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Spin Wheel</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" onclick="markNotificationsRead()">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notifikasi</span>
                    <span id="notif-badge" class="notification-badge" style="display:none"></span>
                </a>
            </div>

            <div class="nav-item {{ Route::is('user.profiles.*') ? 'active' : '' }}">
                <a href="{{ route('user.profiles.show') }}">
                    <i class="fa-solid fa-user"></i>
                    <span>Profil</span>
                </a>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-0 end-0 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-accent w-100 p-2">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="content-wrapper flex-grow-1">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Polling Notifikasi Unread
    function fetchNotif() {
        fetch('/notifications/unread-count')
            .then(r => r.text())
            .then(c => {
                const badge = document.getElementById('notif-badge');
                if (c > 0) {
                    badge.innerText = c;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            });
    }
    
    // Tandai Semua Notifikasi Dibaca
    function markNotificationsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => {
            document.getElementById('notif-badge').style.display = 'none';
            window.location.href = "{{ route('user.dashboard') }}";
        });
    }
    
    // Inisialisasi
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotif();
        setInterval(fetchNotif, 10000); // Poll setiap 10 detik
    });
</script>
@stack('scripts') 
@yield('js')
</body>
</html>