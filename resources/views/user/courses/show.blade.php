<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - TanyaKode</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --accent: #4cc9f0;
            --success: #06d6a0;
            --warning: #ffd166;
            --danger: #ef476f;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-blue: #e3f2fd;
            --gradient-1: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-2: linear-gradient(135deg, #7209b7, #4361ee);
            --gradient-3: linear-gradient(135deg, #4cc9f0, #4895ef);
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-md: 0 8px 15px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.15);
            --radius: 12px;
            --radius-lg: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f8fafd;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            overflow: hidden;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.05;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-md-4, .col-md-8 {
            padding: 0 15px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .col-md-4 { width: 33.333%; }
            .col-md-8 { width: 66.667%; }
        }

        .card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            margin-bottom: 30px;
            animation: cardAppear 0.6s ease-out;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card-primary {
            border-top: 5px solid var(--primary);
        }

        .card-primary.card-outline {
            border: 1px solid var(--primary);
            border-top: 5px solid var(--primary);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05));
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px 25px;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        .box-profile {
            text-align: center;
        }

        .profile-user-img {
            width: 150px;
            height: 150px;
            border: 5px solid white;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }

        .profile-user-img:hover {
            transform: scale(1.05);
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .img-circle {
            border-radius: 50%;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .profile-username {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 10px;
            color: var(--dark);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-muted {
            color: var(--gray) !important;
        }

        .btn {
            padding: 12px 28px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: var(--gradient-3);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #3abfd4, #2d8fe3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        strong {
            font-weight: 700;
            color: var(--primary);
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        p {
            margin-bottom: 20px;
            line-height: 1.7;
        }

        hr {
            margin: 25px 0;
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(67, 97, 238, 0.2), transparent);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark);
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius);
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .card-footer {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02));
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px 25px;
            display: flex;
            gap: 15px;
        }

        .progress-section {
            margin-top: 30px;
        }

        .progress-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-container {
            background: #f0f4f8;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 15px;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .progress-bar {
            height: 12px;
            background: #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-1);
            border-radius: 6px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .badge-primary {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background: rgba(6, 214, 160, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(255, 209, 102, 0.1);
            color: var(--warning);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02));
            padding: 20px;
            border-radius: var(--radius);
            text-align: center;
            border: 1px solid rgba(67, 97, 238, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 20px 15px;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .profile-user-img {
                width: 120px;
                height: 120px;
            }
            
            .profile-username {
                font-size: 1.5rem;
            }
            
            .card-footer {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .profile-user-img {
                width: 100px;
                height: 100px;
            }
            
            .profile-username {
                font-size: 1.3rem;
            }
            
            .card-title {
                font-size: 1.2rem;
            }
            
            .stat-number {
                font-size: 1.7rem;
            }
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: floatBtn 3s infinite ease-in-out;
        }

        @keyframes floatBtn {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .floating-btn:hover {
            transform: scale(1.1);
            animation: none;
        }

        .nav-header {
            background: white;
            padding: 20px 0;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-1);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            box-shadow: var(--shadow-md);
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
        }

        .logo-text span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            padding: 8px 0;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-1);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            width: 100%;
        }

        .logout-form {
            display: inline;
        }

        .logout-btn {
            background: none;
            border: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            padding: 8px 0;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            position: relative;
        }

        .logout-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-1);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .logout-btn:hover {
            color: var(--primary);
        }

        .logout-btn:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="particles" id="particles"></div>
    </div>

    <header class="nav-header">
        <div class="nav-container">
            <a href="{{ route('user.dashboard') }}" class="logo">
                <div class="logo-icon">TK</div>
                <div class="logo-text">Tanya<span>Kode</span></div>
            </a>
            
            <nav class="nav-menu">
                <a href="{{ route('user.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('user.courses.index') }}" class="nav-link">Belajar</a>
                <a href="{{ route('user.profile.show') }}" class="nav-link active">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_lengkap ?? $user->name) }}&background=4361ee&color=ffffff&size=150&font-size=0.5"
                             alt="User profile picture">

                        <h3 class="profile-username mt-3">
                            {{ $user->nama_lengkap ?? $user->name }}
                        </h3>

                        <p class="text-muted">{{ $user->email }}</p>
                        
                        <div class="stats-grid">
                            <div class="stat-card">
                                {{-- TODO: ambil dari tabel user_progress --}}
                                <div class="stat-number">-</div>
                                <div class="stat-label">Progress</div>
                            </div>
                            <div class="stat-card">
                                {{-- TODO: ambil dari tabel completed_courses --}}
                                <div class="stat-number">-</div>
                                <div class="stat-label">Selesai</div>
                            </div>
                        </div>

                        <a href="{{ route('user.profile.edit') }}" class="btn btn-primary btn-block mt-3">
                            <i class="fas fa-edit"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Level Saat Ini</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-container">
                            <div class="progress-info">
                                {{-- TODO: ambil dari tabel user_levels --}}
                                <span>-</span>
                                <span>0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%;"></div>
                            </div>
                        </div>
                        
                        <div class="badge-container mt-3">
                            {{-- TODO: ambil dari tabel user_badges --}}
                            <span class="badge badge-primary"><i class="fas fa-medal"></i> Belum ada badge</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-circle"></i> Biodata</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <strong><i class="fas fa-school"></i> Sekolah</strong>
                            <p class="text-muted">{{ $user->sekolah ?? '-' }}</p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <strong><i class="fas fa-map-marker-alt"></i> Alamat</strong>
                            <p class="text-muted">{{ $user->alamat ?? '-' }}</p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <strong><i class="fas fa-phone"></i> No HP</strong>
                            <p class="text-muted">{{ $user->no_hp ?? '-' }}</p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <strong><i class="fas fa-calendar-alt"></i> Bergabung Sejak</strong>
                            <p class="text-muted">{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <strong><i class="fas fa-trophy"></i> Prestasi</strong>
                            <p class="text-muted">{{ $user->prestasi ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Aktivitas Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item" style="padding: 15px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.2)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <strong>Belum ada aktivitas</strong>
                                        <p style="color: var(--gray); margin: 5px 0 0 0; font-size: 0.9rem;">Mulai belajar untuk melihat aktivitas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="floating-btn" onclick="window.location.href='{{ route('user.courses.index') }}'">
        <i class="fas fa-play"></i>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 8 + 3;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 20}s`;
                particle.style.opacity = Math.random() * 0.05 + 0.02;
                
                const hue = Math.random() * 60 + 200;
                particle.style.background = `hsl(${hue}, 70%, 60%)`;
                
                particlesContainer.appendChild(particle);
            }
            
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
            
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    const targetUrl = this.getAttribute('href');
                    if (targetUrl) {
                        setTimeout(() => {
                            window.location.href = targetUrl;
                        }, 300);
                    }
                });
            });
            
            document.querySelector('.btn-primary').addEventListener('mouseenter', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
            
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            document.querySelector('.logout-btn').addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin keluar?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>