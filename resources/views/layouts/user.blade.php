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

        .bg-shape-1 {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.03) 0%, rgba(100, 255, 218, 0.03) 100%);
            top: -300px;
            right: -200px;
            animation: float-rotate 40s linear infinite;
            z-index: -1;
        }

        .bg-shape-2 {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(100, 255, 218, 0.02) 0%, rgba(10, 25, 47, 0.02) 100%);
            bottom: -200px;
            left: -100px;
            animation: float-rotate 35s linear infinite reverse;
            z-index: -1;
        }

        .bg-shape-3 {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(17, 34, 64, 0.03) 0%, rgba(100, 255, 218, 0.03) 100%);
            top: 50%;
            right: 10%;
            animation: pulse-glow 8s ease-in-out infinite;
            z-index: -1;
        }

        .floating-line {
            position: fixed;
            width: 2px;
            height: 200px;
            background: linear-gradient(to bottom, transparent, var(--accent-blue), transparent);
            left: 20%;
            top: -200px;
            animation: fall-down 15s linear infinite;
            z-index: -1;
        }

        .floating-dot {
            position: fixed;
            width: 8px;
            height: 8px;
            background: var(--accent-blue);
            border-radius: 50%;
            animation: float-around 25s linear infinite;
            z-index: -1;
            opacity: 0.3;
        }

        @keyframes float-rotate {
            0% { transform: rotate(0deg) translate(0, 0); }
            100% { transform: rotate(360deg) translate(30px, 30px); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }

        @keyframes fall-down {
            0% { top: -200px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100vh; opacity: 0; }
        }

        @keyframes float-around {
            0% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(100vw, 50vh) rotate(90deg); }
            50% { transform: translate(50vw, 100vh) rotate(180deg); }
            75% { transform: translate(0vw, 50vh) rotate(270deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        @keyframes slide-in-right {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slide-in-up {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .sidebar {
            background: linear-gradient(180deg, var(--navy-dark), var(--navy-medium));
            min-height: 100vh;
            color: var(--white);
            box-shadow: 5px 0 25px rgba(10, 25, 47, 0.2);
            position: fixed;
            width: 250px;
            z-index: 1000;
            animation: slide-in-right 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(100, 255, 218, 0.1);
        }

        .sidebar-header h4 {
            font-weight: 700;
            color: var(--accent-blue);
            margin: 0;
        }

        .nav-item {
            padding: 12px 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .nav-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-item i {
            width: 20px;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .nav-item:hover i {
            transform: scale(1.2);
            color: var(--accent-blue);
        }

        .nav-item:hover a {
            color: var(--white);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(100, 255, 218, 0.1);
            border-left: 3px solid var(--accent-blue);
        }

        .nav-item.active a {
            color: var(--accent-blue);
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(100, 255, 218, 0.2), transparent);
            transition: left 0.7s ease;
        }

        .nav-item:hover::before {
            left: 100%;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 30px;
            animation: fade-in 0.8s ease-out;
        }

        .navy-card {
            background: var(--white);
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(10, 25, 47, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            animation: slide-in-up 0.6s ease-out;
            animation-fill-mode: both;
        }

        .navy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(10, 25, 47, 0.12);
        }

        .navy-card-header {
            background: var(--navy-dark);
            color: var(--white);
            padding: 20px;
            border-bottom: 2px solid var(--accent-blue);
        }

        .navy-card-body {
            padding: 25px;
        }

        .progress-navy {
            height: 10px;
            border-radius: 10px;
            background: rgba(10, 25, 47, 0.1);
            overflow: hidden;
        }

        .progress-bar-navy {
            background: linear-gradient(90deg, var(--navy-medium), var(--accent-blue));
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            animation: progress-grow 1.5s ease-out;
        }

        .progress-bar-navy::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent
            );
            animation: shimmer 2s infinite;
        }

        @keyframes progress-grow {
            from { width: 0; }
        }

        .btn-navy {
            background: var(--navy-dark);
            color: var(--white);
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-navy:hover {
            background: var(--navy-medium);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(10, 25, 47, 0.2);
        }

        .btn-accent {
            background: var(--accent-blue);
            color: var(--navy-dark);
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: #52e0c4;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(100, 255, 218, 0.3);
        }

        .badge-navy {
            background: var(--navy-dark);
            color: var(--white);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-accent {
            background: var(--accent-blue);
            color: var(--navy-dark);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .stat-card {
            padding: 25px;
            border-radius: 16px;
            background: var(--white);
            border: 1px solid rgba(10, 25, 47, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--accent-blue);
            box-shadow: 0 10px 25px rgba(10, 25, 47, 0.1);
        }

        .timeline-item {
            position: relative;
            padding-left: 25px;
            margin-bottom: 25px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-blue);
            border: 3px solid var(--white);
            box-shadow: 0 0 0 3px var(--accent-blue);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 23px;
            width: 2px;
            height: calc(100% + 10px);
            background: rgba(100, 255, 218, 0.3);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 10px;
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
        }
    </style>
    @yield('css')
</head>
<body>
<div class="bg-shape-1"></div>
<div class="bg-shape-2"></div>
<div class="bg-shape-3"></div>
<div class="floating-line" style="animation-delay: 0s;"></div>
<div class="floating-line" style="left: 40%; animation-delay: 3s;"></div>
<div class="floating-line" style="left: 60%; animation-delay: 6s;"></div>
<div class="floating-line" style="left: 80%; animation-delay: 9s;"></div>
<div class="floating-dot" style="animation-delay: 0s;"></div>
<div class="floating-dot" style="width: 12px; height: 12px; animation-delay: 5s;"></div>
<div class="floating-dot" style="width: 6px; height: 6px; animation-delay: 10s;"></div>

<div class="d-flex">
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>TANYAKODE</h4>
        </div>
        
        <div class="nav flex-column pt-4">
            <div class="nav-item active">
                <a href="{{ route('user.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.courses.index') }}">    
                    <i class="fa-solid fa-book-open"></i>
                    <span>Kursus Saya</span>
                </a>
            </div>  
            <div class="nav-item">
                <a href="{{ route('user.courses.catalog') }}">    
                    <i class="fa-solid fa-book-open"></i>
                    <span>Kursus</span>
                </a>
            </div>  
            <div class="nav-item">
                <a href="#">    
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Pembayaran</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.spin') }}">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Spin Wheel</span>
                </a>
            </div>
            <div class="nav-item position-relative">
                <a href="#" onclick="markNotificationsRead()">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notifikasi</span>
                    <span id="notif-badge" class="notification-badge" style="display:none"></span>
                </a>
            </div>
            <div class="nav-item {{ Request::is('user/profile*') ? 'active' : '' }}">
                <a href="{{ route('user.profiles.show') }}">
                          <i class="fa-solid fa-user"></i>
                          <span>Profil</span>
                       </a>
              </div>
        </div>

        <div class="position-absolute bottom-0 start-0 end-0 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-accent w-100">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    Logout
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
    let notifInterval;
    
    function fetchNotif() {
        fetch('/notifications/unread-count')
            .then(r => r.text())
            .then(c => {
                const badge = document.getElementById('notif-badge')
                if (c > 0) {
                    badge.innerText = c
                    badge.style.display = 'inline-block'
                } else {
                    badge.style.display = 'none'
                }
            })
    }
    
    function markNotificationsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            document.getElementById('notif-badge').style.display = 'none';
            window.location.href = "{{ route('user.dashboard') }}";
        });
    }
    
    function startPolling() {
        fetchNotif();
        notifInterval = setInterval(fetchNotif, 5000);
    }
    
    function stopPolling() {
        if (notifInterval) {
            clearInterval(notifInterval);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        startPolling();
        
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
            }
        });
        
        const observerOptions = { threshold: 0.5 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = entry.target;
                    const width = progressBar.getAttribute('data-width');
                    progressBar.style.width = width + '%';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.progress-bar-navy').forEach(bar => {
            observer.observe(bar);
        });

        const cards = document.querySelectorAll('.navy-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 30);
        }

        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent);
            if (!isNaN(target)) {
                animateCounter(stat, target);
            }
        });
    });
</script>
@yield('js')
@stack('scripts')
</body>
</html>