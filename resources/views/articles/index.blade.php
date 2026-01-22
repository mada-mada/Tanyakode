<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Artikel Edukasi Pemrograman - TanyaKode</title>
    <meta name="description" content="Kumpulan artikel edukasi pemrograman untuk pelajar SMP dan SMA. Belajar koding dengan cara yang benar, aman, dan terstruktur bersama TanyaKode.">
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
            background-color: #f0f8ff;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 80px 20px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 15px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            text-align: center;
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 50px;
            line-height: 1.7;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .article-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 35px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .article-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(67, 97, 238, 0.2);
        }

        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 20px;
            align-self: flex-start;
        }

        .badge-dasar {
            background: rgba(6, 214, 160, 0.15);
            color: #06a878;
            border: 1px solid rgba(6, 214, 160, 0.3);
        }

        .badge-menengah {
            background: rgba(255, 209, 102, 0.15);
            color: #b68a00;
            border: 1px solid rgba(255, 209, 102, 0.3);
        }

        .badge-tinggi {
            background: rgba(239, 71, 111, 0.15);
            color: #c44569;
            border: 1px solid rgba(239, 71, 111, 0.3);
        }

        .article-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
            color: var(--dark);
        }

        .meta {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .read-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-top: auto;
            padding: 12px 20px;
            background: var(--light-blue);
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .read-link:hover {
            gap: 15px;
            background: var(--primary);
            color: white;
        }

        .header {
            background: white;
            padding: 20px 0;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            background: var(--light-blue);
        }

        .back-btn:hover {
            background: var(--primary);
            color: white;
        }

        .logo-img {
      height: 40px;       /* Tinggi logo disesuaikan */
            width: auto;        /* Lebar menyesuaikan proporsi */
            object-fit: contain;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 0;
            text-align: center;
            margin-top: 80px;
        }

        .footer p {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .intro-text {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
            color: var(--gray);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .container {
                padding: 60px 20px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .articles-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .article-card {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <img src="{{ asset('images/logoputih.jpg') }}" alt="Logo TanyaKode" class="logo-img">
                TanyaKode
            </a>
            <a href="/" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Beranda
            </a>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">Artikel Edukasi Pemrograman</h1>
        <p class="page-subtitle">
            Tingkatkan pengetahuan teknologi dengan artikel edukatif dari mentor TanyaKode
        </p>
        
        <div class="intro-text">
            <p>Temukan panduan lengkap belajar pemrograman untuk pelajar SMP dan SMA. Artikel-artikel ini dirancang khusus untuk membantu memahami konsep teknologi dengan cara yang benar, aman, dan terstruktur. Mulai dari dasar hingga tingkat menengah, setiap materi disajikan dengan bahasa yang mudah dipahami dan contoh yang relevan.</p>
        </div>

        <div class="articles-grid">
            @foreach($articles as $article)
                <div class="article-card">
                    <span class="badge badge-{{ strtolower($article['level']) }}">
                        Level {{ $article['level'] }}
                    </span>
                    
                    <h3 class="article-title">{{ $article['title'] }}</h3>
                    
                    <div class="meta">
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span>{{ $article['read_time'] }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Materi Pelajar</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('articles.show', $article['slug']) }}" class="read-link">
                        Baca Artikel Lengkap
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </main>

    <footer class="footer">
        <p>© 2023 TanyaKode. Hak cipta dilindungi undang-undang.</p>
        <p style="margin-top: 10px;">Dibuat dengan dedikasi untuk pendidikan Indonesia</p>
    </footer>
</body>
</html>