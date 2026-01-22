<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articles = [
        [
            'title' => 'Apa Itu Programmer dan Developer?',
            'slug' => 'apa-itu-programmer-dan-developer',
            'level' => 'Dasar',
            'read_time' => '5 menit baca',
            'content' => '
                <p>Programmer dan developer sering dianggap sama, padahal memiliki fokus yang berbeda.</p>
                <p><strong>Programmer</strong> berfokus pada penulisan kode, sedangkan
                <strong>Developer</strong> mencakup analisis, desain, pengembangan, dan pemeliharaan aplikasi.</p>
                <p>Keduanya sangat penting dalam dunia teknologi.</p>
            '
        ],
        [
            'title' => 'Kenapa Harus Belajar Koding Sejak Dini?',
            'slug' => 'kenapa-belajar-koding-sejak-dini',
            'level' => 'Dasar',
            'read_time' => '7 menit baca',
            'content' => '
                <p>Belajar koding sejak dini melatih logika, kreativitas, dan problem solving.</p>
                <p>Pelajar jadi tidak hanya pengguna teknologi, tapi juga pencipta.</p>
            '
        ],
        [
            'title' => 'Cara Mengamankan Akun Website',
            'slug' => 'cara-mengamankan-akun-website',
            'level' => 'Menengah',
            'read_time' => '10 menit baca',
            'content' => '
                <p>Keamanan akun adalah hal krusial dalam pengembangan web.</p>
                <ul>
                    <li>Gunakan password kuat</li>
                    <li>Aktifkan hashing</li>
                    <li>Validasi input</li>
                </ul>
            '
        ],
    ];

    public function index()
    {
        return view('articles.index', [
            'articles' => $this->articles
        ]);
    }

    public function show($slug)
    {
        $article = collect($this->articles)
            ->firstWhere('slug', $slug);

        abort_if(!$article, 404);

        return view('articles.show', compact('article'));
    }
}