<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TanyaKode - Edukasi Koding untuk Pelajar</title>
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
            --shadow-md: 0 8px 15px rgba(0, git remote add origin https://github.com/mada-mada/Tanyakode.git0, 0, 0.1);
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
            background-color: #f0f8ff;
            overflow-x: hidden;
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
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        header {
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-img {
            height: 50px;       /* Mengatur tinggi logo di menu atas */
            width: auto;        /* Lebar menyesuaikan agar tidak gepeng */
            margin-right: 1px; /* Memberi jarak antara logo dan teks TanyaKode */
            object-fit: contain;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
        }

        .logo-text span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
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

        .nav-buttons {
            display: flex;
            gap: 15px;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
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

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: rgba(67, 97, 238, 0.1);
            transform: translateY(-3px);
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--dark);
            cursor: pointer;
            padding: 5px;
        }

        .hero {
            padding: 100px 0 80px;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(67, 97, 238, 0.2);
        }

        .hero-badge i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 25px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--gray);
            max-width: 650px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 60px;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            margin-top: 80px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--gray);
            font-weight: 600;
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 5px;
            background: var(--gradient-1);
            border-radius: 5px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto 60px;
            font-size: 1.1rem;
        }

        .story-section {
            background: rgba(255, 255, 255, 0.7);
            border-radius: var(--radius-lg);
            padding: 60px;
            margin-bottom: 80px;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .story-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .story-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary);
            text-align: center;
        }

        .story-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 25px;
            color: var(--dark);
        }

        .story-text.highlight {
            background: var(--light-blue);
            padding: 25px;
            border-radius: var(--radius);
            border-left: 5px solid var(--primary);
            font-style: italic;
            font-weight: 500;
        }

        .vision-mission {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
        }

        .vm-card {
            background: white;
            padding: 40px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border-top: 5px solid var(--primary);
        }

        .vm-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .vm-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-1);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: white;
            font-size: 1.8rem;
        }

        .vm-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .vm-list {
            list-style: none;
        }

        .vm-list li {
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
        }

        .vm-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--success);
            font-weight: bold;
        }

        .learning-path-section {
            background: rgba(255, 255, 255, 0.8);
            padding: 80px 0;
            border-radius: var(--radius-lg);
            margin-bottom: 80px;
            position: relative;
            overflow: hidden;
        }

        .path-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }

        .path-step {
            display: flex;
            align-items: center;
            gap: 30px;
            background: white;
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            position: relative;
            transition: all 0.3s ease;
        }

        .path-step:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-lg);
        }

        .path-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .path-content {
            flex-grow: 1;
        }

        .path-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .path-desc {
            color: var(--gray);
            margin-bottom: 15px;
        }

        .path-tag {
            display: inline-block;
            padding: 5px 15px;
            background: var(--light-blue);
            color: var(--primary);
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .path-tag.free {
            background: rgba(6, 214, 160, 0.2);
            color: #06a878;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }

        .benefit-card {
            background: white;
            padding: 35px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            text-align: center;
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .benefit-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 1.8rem;
        }

        .benefit-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .benefit-desc {
            color: var(--gray);
        }

        .testimonial-section {
            background: var(--gradient-1);
            padding: 80px 0;
            border-radius: var(--radius-lg);
            margin-bottom: 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .testimonial-section .section-title {
            color: white;
        }

        .testimonial-section .section-title::after {
            background: white;
        }

        .testimonial-carousel {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .testimonial-slide {
            min-width: 100%;
            padding: 20px;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .testimonial-text {
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 30px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .author-info h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .author-info p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .faq-section {
            margin-bottom: 80px;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: white;
            margin-bottom: 15px;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .faq-question {
            padding: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--light);
        }

        .faq-question i {
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 25px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 25px;
            max-height: 500px;
        }

        .articles-section {
            margin-bottom: 80px;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .article-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .article-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .article-header {
            padding: 25px 25px 15px;
        }

        .article-level {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 15px;
        }

        .level-basic {
            background: rgba(6, 214, 160, 0.2);
            color: #06a878;
        }

        .level-intermediate {
            background: rgba(255, 209, 102, 0.2);
            color: #cc9e00;
        }

        .level-advanced {
            background: rgba(239, 71, 111, 0.2);
            color: #c44569;
        }

        .article-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
            color: var(--dark);
        }

        .article-summary {
            color: var(--gray);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            color: var(--gray);
            font-size: 0.9rem;
            padding: 15px 25px;
            border-top: 1px solid #eee;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .read-more:hover {
            gap: 12px;
        }

        footer {
            background: var(--dark);
            color: white;
            padding: 80px 0 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            margin-bottom: 50px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 25px;
        }

        .footer-logo-img {
            height: 50px;       /* Mengatur tinggi logo di footer (bawah) */
            width: auto;
            margin-right: 1px;
            object-fit: contain;
        }

        .footer-logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary);
            transform: translateY(-5px);
        }

        .copyright {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .mobile-nav {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            background: white;
            padding: 20px;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            gap: 20px;
            transform: translateY(-100%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 999;
            display: none;
        }

        .mobile-nav.active {
            transform: translateY(0);
            opacity: 1;
        }

        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .path-step {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .mobile-nav {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.3rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .story-section {
                padding: 40px 25px;
            }
            
            .vm-card {
                padding: 30px;
            }
            
            .articles-grid {
                grid-template-columns: 1fr;
            }
            
            .stats {
                gap: 30px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 15px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .btn {
                padding: 10px 20px;
            }
            
            .testimonial-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="particles" id="particles"></div>
    </div>

    <header>
        <div class="container">
            <div class="nav-container">
                <a href="#" class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                    <div class="logo-text">Tanya<span>Kode</span></div>
                </a>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
                
                <nav class="nav-links" id="navLinks">
                    <a href="#home" class="nav-link">Beranda</a>
                    <a href="#story" class="nav-link">Cerita</a>
                    <a href="#levels" class="nav-link">Level Belajar</a>
                    <a href="#testimonials" class="nav-link">Testimoni</a>
                    <a href="#articles" class="nav-link">Artikel</a>
                    <a href="#faq" class="nav-link">FAQ</a>
                </nav>
                
                <div class="nav-buttons">
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Belajar Gratis</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="mobile-nav" id="mobileNav">
        <a href="#home" class="nav-link">Beranda</a>
        <a href="#story" class="nav-link">Cerita</a>
        <a href="#levels" class="nav-link">Level Belajar</a>
        <a href="#testimonials" class="nav-link">Testimoni</a>
        <a href="#articles" class="nav-link">Artikel</a>
        <a href="#faq" class="nav-link">FAQ</a>
        <div class="mobile-buttons" style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="#login" class="btn btn-outline btn-small">Masuk</a>
            <a href="#register" class="btn btn-primary btn-small">Belajar Gratis</a>
        </div>
    </nav>

    <main>
        <section class="hero" id="home">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-rocket"></i>
                        <span>Platform Edukasi Koding No. 1 untuk Pelajar</span>
                    </div>
                    
                    <h1 class="hero-title">Masa Depan Dimulai dari Satu Baris Kode</h1>
                    
                    <p class="hero-subtitle">
                        Bergabung dengan ribuan pelajar Indonesia yang sudah memulai perjalanan koding mereka di TanyaKode. 
                        Belajar dengan cara yang menyenangkan, terstruktur, dan aman bersama mentor berpengalaman.
                    </p>
                    
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-play-circle"></i>
                            Mulai Belajar Gratis
                        </a>
                    </div>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-number">10.000+</div>
                            <div class="stat-label">Pelajar Aktif</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Materi Pembelajaran</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Kepuasan Pengguna</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Sekolah Mitra</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="story">
            <div class="container">
                <h2 class="section-title">Cerita Awal TanyaKode</h2>
                
                <div class="story-section">
                    <div class="story-content">
                        <h3 class="story-title">Dari Pertanyaan Menjadi Solusi</h3>
                        
                        <p class="story-text">
                            Semua berawal dari sebuah pertanyaan sederhana yang sering muncul di benak pelajar: 
                            <strong>"Gimana sih cara belajar koding yang benar?"</strong>
                        </p>
                        
                        <p class="story-text">
                            Di tengah banyaknya informasi yang membingungkan dan kurangnya panduan yang terstruktur, 
                            banyak pelajar yang merasa kesulitan memulai perjalanan koding mereka. 
                            Mereka bertanya-tanya: mulai dari mana? bahasa pemrograman apa yang harus dipelajari? 
                            bagaimana cara praktik yang aman?
                        </p>
                        
                        <div class="story-text highlight">
                            "TanyaKode lahir sebagai jawaban atas semua kebingungan tersebut. 
                            Kami membangun platform yang aman, terarah, dan ramah untuk pemula. 
                            Tempat di mana setiap pertanyaan tentang koding mendapatkan jawaban yang jelas dan aplikatif."
                        </div>
                        
                        <p class="story-text">
                            Dengan pendekatan bertahap dan materi yang disesuaikan untuk pelajar SMP dan SMA, 
                            TanyaKode hadir untuk membimbing generasi muda Indonesia memahami teknologi 
                            dengan cara yang benar, aman, dan bertanggung jawab.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="section-title">Visi & Misi Kami</h2>
                <p class="section-subtitle">
                    Komitmen kami untuk membangun generasi digital Indonesia yang kompeten dan beretika
                </p>
                
                <div class="vision-mission">
                    <div class="vm-card">
                        <div class="vm-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="vm-title">Visi</h3>
                        <p>Membantu pelajar Indonesia memahami teknologi dengan benar dan aman</p>
                    </div>
                    
                    <div class="vm-card">
                        <div class="vm-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="vm-title">Misi</h3>
                        <ul class="vm-list">
                            <li>Edukasi koding bertahap sesuai level kemampuan</li>
                            <li>Menanamkan logika berpikir komputasional sejak dini</li>
                            <li>Mengajarkan keamanan digital dan etika berteknologi</li>
                            <li>Membimbing pelajar membuat proyek nyata</li>
                            <li>Menyediakan lingkungan belajar yang aman dan supportif</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="learning-path-section" id="levels">
            <div class="container">
                <h2 class="section-title">Learning Path</h2>
                <p class="section-subtitle">
                    Mulai dari dasar hingga mahir dengan kurikulum yang terstruktur
                </p>
                
                <div class="path-container">
                    <div class="path-step">
                        <div class="path-number">1</div>
                        <div class="path-content">
                            <h3 class="path-title">Level Dasar</h3>
                            <p class="path-desc">
                                Pengenalan konsep pemrograman dasar, logika komputasi, dan HTML/CSS sederhana. 
                                Cocok untuk pemula yang baru pertama kali belajar koding.
                            </p>
                            <span class="path-tag free">GRATIS</span>
                        </div>
                    </div>
                    
                    <div class="path-step">
                        <div class="path-number">2</div>
                        <div class="path-content">
                            <h3 class="path-title">Level Menengah</h3>
                            <p class="path-desc">
                                Memperdalam JavaScript, pengembangan web, dan dasar-dasar keamanan digital. 
                                Mulai belajar membuat aplikasi web sederhana.
                            </p>
                            <span class="path-tag">Premium</span>
                        </div>
                    </div>
                    
                    <div class="path-step">
                        <div class="path-number">3</div>
                        <div class="path-content">
                            <h3 class="path-title">Level Tinggi</h3>
                            <p class="path-desc">
                                Pengembangan aplikasi web lengkap, keamanan lanjutan, dan optimasi. 
                                Siap untuk membuat proyek nyata dan memecahkan masalah kompleks.
                            </p>
                            <span class="path-tag">Premium</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="section-title">Keuntungan Belajar di TanyaKode</h2>
                <p class="section-subtitle">
                    Mengapa ribuan pelajar memilih TanyaKode sebagai partner belajar koding mereka
                </p>
                
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 class="benefit-title">Belajar Bertahap</h3>
                        <p class="benefit-desc">
                            Kurikulum terstruktur dari dasar hingga lanjutan. Tidak ada loncatan materi 
                            yang membuat bingung.
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="benefit-title">Aman & Edukatif</h3>
                        <p class="benefit-desc">
                            Lingkungan belajar yang aman dengan konten yang sesuai untuk pelajar. 
                            Fokus pada pendidikan, bukan eksploitasi.
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="benefit-title">Cocok untuk Pelajar</h3>
                        <p class="benefit-desc">
                            Materi disesuaikan dengan usia dan kemampuan pelajar SMP/SMA. Bahasa yang mudah 
                            dipahami, contoh yang relevan.
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h3 class="benefit-title">Fokus pada Logika</h3>
                        <p class="benefit-desc">
                            Mengajarkan cara berpikir komputasional, bukan sekadar menghafal syntax. 
                            Keterampilan yang bertahan lama.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonial-section" id="testimonials">
            <div class="container">
                <h2 class="section-title">Kata Mereka tentang TanyaKode</h2>
                
                <div class="testimonial-carousel">
                    <div class="testimonial-track" id="testimonialTrack">
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <p class="testimonial-text">
                                    "Sebelumnya saya bingung harus mulai belajar koding dari mana. 
                                    TanyaKode memberikan roadmap yang jelas dan materi yang mudah dipahami. 
                                    Sekarang saya sudah bisa buat website sederhana sendiri!"
                                </p>
                                <div class="testimonial-author">
                                    <div class="author-avatar">AS</div>
                                    <div class="author-info">
                                        <h4>Andi Saputra</h4>
                                        <p>Siswa SMA Kelas 11</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <p class="testimonial-text">
                                    "Yang saya suka dari TanyaKode adalah penekanan pada keamanan digital. 
                                    Tidak hanya belajar koding, tapi juga bagaimana melindungi data dan privasi. 
                                    Sangat penting untuk pelajar jaman now."
                                </p>
                                <div class="testimonial-author">
                                    <div class="author-avatar">SD</div>
                                    <div class="author-info">
                                        <h4>Sari Dewi</h4>
                                        <p>Siswi SMP Kelas 9</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <p class="testimonial-text">
                                    "Metode belajar di TanyaKode benar-benar berbeda. Ada gamifikasi, 
                                    progress tracking, dan feedback langsung. Bikin belajar koding jadi 
                                    tidak membosankan dan lebih produktif."
                                </p>
                                <div class="testimonial-author">
                                    <div class="author-avatar">RF</div>
                                    <div class="author-info">
                                        <h4>Rizki Fauzi</h4>
                                        <p>Siswa SMA Kelas 12</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carousel-dots" id="carouselDots">
                        <div class="carousel-dot active" data-slide="0"></div>
                        <div class="carousel-dot" data-slide="1"></div>
                        <div class="carousel-dot" data-slide="2"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="articles-section" id="articles">
            <div class="container">
                <h2 class="section-title">Artikel Terbaru</h2>
                <p class="section-subtitle">
                    Update pengetahuanmu dengan artikel edukatif dari mentor TanyaKode
                </p>
                
                <div class="articles-grid">
                    <div class="article-card">
                        <div class="article-header">
                            <span class="article-level level-basic">Level Dasar</span>
                            <h3 class="article-title">Apa Itu Programmer dan Developer?</h3>
                            <p class="article-summary">
                                Memahami perbedaan dan peran programmer serta developer dalam dunia teknologi. 
                                Panduan untuk pemula yang ingin memulai karir di bidang IT.
                            </p>
                            <a href="{{ route('articles.index') }}" target="_blank" rel="noopener" class="read-more">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="article-meta">
                            <span>5 menit baca</span>
                            <span>Hari ini</span>
                        </div>
                    </div>
                    
                    <div class="article-card">
                        <div class="article-header">
                            <span class="article-level level-basic">Level Dasar</span>
                            <h3 class="article-title">Kenapa Harus Belajar Koding Sejak Dini?</h3>
                            <p class="article-summary">
                                Manfaat belajar pemrograman sejak usia sekolah. Bagaimana koding mengasah 
                                logika, kreativitas, dan mempersiapkan masa depan di era digital.
                            </p>
                            <a href="{{ route('articles.index') }}" target="_blank" rel="noopener" class="read-more">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="article-meta">
                            <span>7 menit baca</span>
                            <span>2 hari lalu</span>
                        </div>
                    </div>
                    
                    <div class="article-card">
                        <div class="article-header">
                            <span class="article-level level-intermediate">Level Menengah</span>
                            <h3 class="article-title">Cara Mengamankan Akun Website</h3>
                            <p class="article-summary">
                                Tips dan praktik terbaik untuk mengamakan akun pengguna di website. 
                                Pelajari teknik validasi, enkripsi, dan proteksi dari serangan umum.
                            </p>
                            <a href="{{ route('articles.index') }}" target="_blank" rel="noopener" class="read-more">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="article-meta">
                            <span>10 menit baca</span>
                            <span>3 hari lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section" id="faq">
            <div class="container">
                <h2 class="section-title">Pertanyaan yang Sering Ditanyakan</h2>
                
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            Apakah benar Level Dasar sepenuhnya gratis?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Ya, benar! Level Dasar di TanyaKode sepenuhnya gratis tanpa biaya apapun. 
                            Kamu bisa mengakses semua materi dasar, latihan, dan kuis tanpa perlu membayar. 
                            Ini adalah komitmen kami untuk membuat pendidikan koding dapat diakses oleh semua pelajar.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            Untuk usia berapa TanyaKode cocok digunakan?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>TanyaKode dirancang khusus untuk pelajar SMP dan SMA (usia 12-18 tahun). 
                            Materi disesuaikan dengan tingkat kognitif dan kurikulum pendidikan Indonesia. 
                            Namun, pemula dari usia lain juga dapat mengikuti dengan baik.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            Apakah perlu punya laptop untuk belajar di TanyaKode?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Kami merekomendasikan menggunakan laptop atau komputer untuk pengalaman belajar 
                            yang optimal. Namun, untuk materi teori dan beberapa latihan dasar, 
                            kamu juga bisa mengakses melalui smartphone atau tablet.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            Bagaimana cara naik level dari Dasar ke Menengah?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Untuk naik ke Level Menengah, kamu harus menyelesaikan semua materi dan latihan 
                            di Level Dasar, serta lulus ujian akhir level dengan nilai minimal 80%. 
                            Sistem akan secara otomatis membuka akses ke Level Menengah setelah persyaratan terpenuhi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="footer-logo-img">
                        <div class="footer-logo-text">TanyaKode</div>
                    </div>
                    <p class="footer-description">
                        Platform edukasi koding profesional untuk pelajar SMP dan SMA Indonesia. 
                        Membantu generasi muda memahami teknologi dengan cara yang benar, aman, dan inspiratif.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-discord"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-links-section">
                    <h3 class="footer-title">Level Belajar</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-circle" style="color: #06d6a0; font-size: 0.7rem;"></i> Level Dasar (Gratis)</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: #ffd166; font-size: 0.7rem;"></i> Level Menengah</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: #ef476f; font-size: 0.7rem;"></i> Level Tinggi</a></li>
                    </ul>
                </div>
                
                <div class="footer-links-section">
                    <h3 class="footer-title">Navigasi</h3>
                    <ul class="footer-links">
                        <li><a href="#home"><i class="fas fa-home"></i> Beranda</a></li>
                        <li><a href="#story"><i class="fas fa-book"></i> Cerita Kami</a></li>
                        <li><a href="#levels"><i class="fas fa-layer-group"></i> Level Belajar</a></li>
                        <li><a href="#articles"><i class="fas fa-newspaper"></i> Artikel</a></li>
                        <li><a href="#faq"><i class="fas fa-question-circle"></i> FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>© 2023 TanyaKode. Hak cipta dilindungi undang-undang.</p>
                <p style="margin-top: 10px;">Dibuat dengan ❤️ untuk pendidikan Indonesia</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileNav = document.getElementById('mobileNav');
            const testimonialTrack = document.getElementById('testimonialTrack');
            const carouselDots = document.querySelectorAll('.carousel-dot');
            const faqItems = document.querySelectorAll('.faq-item');
            
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 10 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 20}s`;
                particle.style.opacity = Math.random() * 0.1 + 0.05;
                
                const hue = Math.random() * 60 + 200;
                particle.style.background = `hsl(${hue}, 70%, 60%)`;
                
                particlesContainer.appendChild(particle);
            }
            
            mobileMenuBtn.addEventListener('click', function() {
                mobileNav.classList.toggle('active');
                mobileMenuBtn.textContent = mobileNav.classList.contains('active') ? '✕' : '☰';
            });
            
            document.addEventListener('click', function(event) {
                if (!mobileNav.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    mobileNav.classList.remove('active');
                    mobileMenuBtn.textContent = '☰';
                }
            });
            
            let currentSlide = 0;
            const totalSlides = 3;
            
            function updateCarousel() {
                testimonialTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                carouselDots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
            
            carouselDots.forEach(dot => {
                dot.addEventListener('click', function() {
                    currentSlide = parseInt(this.getAttribute('data-slide'));
                    updateCarousel();
                });
            });
            
            setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }, 5000);
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');
                    
                    faqItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                    });
                    
                    if (!isActive) {
                        item.classList.add('active');
                    }
                });
            });
            
            const navLinks = document.querySelectorAll('a[href^="#"]');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                        
                        if (mobileNav.classList.contains('active')) {
                            mobileNav.classList.remove('active');
                            mobileMenuBtn.textContent = '☰';
                        }
                    }
                });
            });
            
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                const scrollPosition = window.scrollY;
                
                if (scrollPosition > 100) {
                    header.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.1)';
                    header.style.background = 'rgba(255, 255, 255, 0.98)';
                } else {
                    header.style.boxShadow = 'var(--shadow-sm)';
                    header.style.background = 'rgba(255, 255, 255, 0.95)';
                }
                
                const elements = document.querySelectorAll('.benefit-card, .article-card, .vm-card');
                
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (elementTop < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            });
            
            const benefitCards = document.querySelectorAll('.benefit-card');
            const articleCards = document.querySelectorAll('.article-card');
            const vmCards = document.querySelectorAll('.vm-card');
            
            benefitCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            
            articleCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            
            vmCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            
            setTimeout(() => {
                window.dispatchEvent(new Event('scroll'));
            }, 100);
        });
    </script>
</body>
</html>