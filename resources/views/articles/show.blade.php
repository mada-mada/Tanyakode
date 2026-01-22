<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $article['title'] }} - TanyaKode</title>
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
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
            line-height: 1.8;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 80px 20px;
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
            max-width: 800px;
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

        .navigation {
            display: flex;
            gap: 15px;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            background: var(--light-blue);
        }

        .article-header {
            margin-bottom: 40px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 20px;
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

        .article-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 25px;
            color: var(--dark);
        }

        .meta-info {
            display: flex;
            align-items: center;
            gap: 25px;
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .article-content h2 {
            font-size: 1.8rem;
            margin: 50px 0 25px;
            color: var(--primary);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-blue);
        }

        .article-content h3 {
            font-size: 1.4rem;
            margin: 40px 0 20px;
            color: var(--primary-dark);
        }

        .article-content h4 {
            font-size: 1.2rem;
            margin: 30px 0 15px;
            color: var(--dark);
        }

        .article-content p {
            margin-bottom: 25px;
            text-align: justify;
        }

        .article-content ul,
        .article-content ol {
            margin: 25px 0;
            padding-left: 30px;
        }

        .article-content li {
            margin-bottom: 12px;
            padding-left: 10px;
        }

        .article-content ul li {
            position: relative;
        }

        .article-content ul li:before {
            content: "•";
            color: var(--primary);
            font-weight: bold;
            position: absolute;
            left: -15px;
        }

        .article-content ol {
            counter-reset: item;
        }

        .article-content ol li {
            counter-increment: item;
        }

        .article-content ol li:before {
            content: counter(item) ".";
            color: var(--primary);
            font-weight: bold;
            position: absolute;
            left: -25px;
        }

        .article-content blockquote {
            border-left: 4px solid var(--primary);
            padding: 20px 25px;
            margin: 30px 0;
            font-style: italic;
            color: var(--gray);
            background: var(--light);
            border-radius: 0 8px 8px 0;
        }

        .article-content strong {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .article-content pre {
            background: #1e1e1e;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .article-content code {
            background: #f1f3f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--primary-dark);
        }

        .article-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .logo-img {
            height: 40px;       /* Sesuaikan tingginya */
            width: auto;
            object-fit: contain;
            /* margin-right tidak wajib jika parent .logo sudah ada gap */
        }

        .article-content th {
            background: var(--light-blue);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #ddd;
        }

        .article-content td {
            padding: 15px;
            border: 1px solid #ddd;
        }

        .article-content tr:nth-child(even) {
            background: #f9f9f9;
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

        .toc {
            background: var(--light);
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border-left: 4px solid var(--primary);
        }

        .toc h3 {
            margin-top: 0;
            color: var(--primary);
        }

        .toc ul {
            margin: 15px 0 0 0;
        }

        .toc li {
            margin-bottom: 10px;
        }

        .toc a {
            color: var(--primary);
            text-decoration: none;
        }

        .toc a:hover {
            text-decoration: underline;
        }

        .highlight-box {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border-left: 5px solid var(--primary);
        }

        .highlight-box strong {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 60px 15px;
            }
            
            .article-title {
                font-size: 1.8rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .navigation {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .article-header,
            .article-content {
                padding: 25px;
            }
            
            .article-content h2 {
                font-size: 1.5rem;
            }
            
            .article-content h3 {
                font-size: 1.2rem;
            }
            
            .meta-info {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .article-title {
                font-size: 1.5rem;
            }
            
            .nav-link {
                padding: 8px 15px;
                font-size: 0.85rem;
            }
            
            .article-content {
                font-size: 1rem;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo TanyaKode" class="logo-img">
                TanyaKode
            </a>
            <div class="navigation">
                <a href="{{ route('articles.index') }}" class="nav-link">
                    <i class="fas fa-arrow-left"></i>
                    Semua Artikel
                </a>
                <a href="/" class="nav-link">
                    <i class="fas fa-home"></i>
                    Beranda
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <article>
            <div class="article-header">
                <span class="badge badge-{{ strtolower($article['level']) }}">
                    Level {{ $article['level'] }}
                </span>
                
                <h1 class="article-title">{{ $article['title'] }}</h1>
                
                <div class="meta-info">
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>{{ $article['read_time'] }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>Diterbitkan: {{ date('d M Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user-graduate"></i>
                        <span>Materi untuk Pelajar</span>
                    </div>
                </div>
            </div>
            
            <div class="article-content">
                {!! $article['content'] !!}
            </div>
        </article>
    </main>

    <footer class="footer">
        <p>© 2023 TanyaKode. Hak cipta dilindungi undang-undang.</p>
        <p style="margin-top: 10px;">Dibuat dengan dedikasi untuk pendidikan Indonesia</p>
    </footer>
</body>
</html>