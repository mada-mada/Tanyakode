<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Room - {{ $title ?? 'Materi Kursus' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/theme-dracula.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-javascript.min.js"></script>

    <style>
        :root {
            --bg-dark: #0f172a;
            --primary: #22d3ee;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-dark); color: #e2e8f0; overflow: hidden; }
        
        /* SCROLLBAR */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* TAB ACTIVE */
        .tab-btn { position: relative; color: #94a3b8; transition: all 0.2s; }
        .tab-btn:hover { color: #fff; }
        .tab-btn.active { color: var(--primary); }
        .tab-btn.active::after {
            content: ''; position: absolute; bottom: -11px; left: 0; width: 100%; height: 2px;
            background: var(--primary); box-shadow: 0 -2px 10px var(--primary);
        }
        
        .tab-content { display: none; animation: fadeIn 0.3s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* EDITOR & TERMINAL */
        .editor-container { border: 1px solid #334155; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; height: 500px; }
        .terminal-window { background: #1e1e1e; border-top: 1px solid #334155; height: 120px; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
        
        /* MODULE ACTIVE */
        .module-item.active { 
            background: rgba(34, 211, 238, 0.05); border-left: 3px solid var(--primary); color: white;
        }
        
        .glow-text { text-shadow: 0 0 10px rgba(34, 211, 238, 0.3); }
    </style>
</head>
<body class="flex flex-col h-screen">

    <nav class="h-16 bg-[#0f172a] border-b border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0 z-30">
        <div class="flex items-center gap-4">
            <a href="{{ url('/user/courses') }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition border border-gray-700">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
            <div class="h-8 w-[1px] bg-gray-700 hidden md:block"></div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-bold text-white text-sm md:text-base tracking-wide">{{ $title ?? 'Fullstack JavaScript' }}</h1>
                    <span class="hidden md:inline-flex bg-cyan-500/10 text-cyan-400 text-[10px] font-bold px-2 py-0.5 rounded border border-cyan-500/20">MODUL 2</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px] md:max-w-none">Materi: Dasar Pemrograman & Variabel</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden lg:flex flex-col w-40 gap-1 text-right">
                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    Progress <span class="text-cyan-400 ml-1">25%</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full shadow-[0_0_8px_rgba(34,211,238,0.4)]" style="width: 25%"></div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 border-l border-gray-700 pl-6">
                <button class="w-9 h-9 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white transition flex items-center justify-center relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0f172a]"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 p-[2px] cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=User&background=0f172a&color=fff" class="rounded-full w-full h-full border-2 border-[#0f172a]" alt="User">
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">

        <main class="flex-1 flex flex-col bg-[#0b1120] overflow-y-auto custom-scroll relative">
            
            <div class="w-full bg-black aspect-video max-h-[500px] relative flex items-center justify-center shadow-2xl shrink-0 group border-b border-gray-800">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center opacity-40"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>

                <button class="relative w-16 h-16 md:w-20 md:h-20 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center group-hover:scale-110 group-hover:bg-cyan-500 group-hover:border-cyan-400 transition-all duration-300 z-10 shadow-[0_0_30px_rgba(0,0,0,0.5)]">
                    <i class="fas fa-play text-white text-2xl md:text-3xl ml-1"></i>
                </button>

                <div class="absolute bottom-0 left-0 w-full p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/90 to-transparent">
                    <div class="w-full bg-gray-700/50 h-1.5 rounded-full mb-4 cursor-pointer relative group/line">
                        <div class="absolute top-0 left-0 h-full bg-cyan-500 w-[35%] rounded-full">
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-white rounded-full shadow opacity-0 group-hover/line:opacity-100"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center text-white">
                        <div class="flex gap-4 items-center">
                            <button class="hover:text-cyan-400"><i class="fas fa-play"></i></button>
                            <button class="hover:text-cyan-400"><i class="fas fa-volume-up"></i></button>
                            <span class="text-xs font-mono text-gray-300">04:20 / 12:00</span>
                        </div>
                        <div class="flex gap-4 items-center">
                            <span class="text-xs font-bold bg-gray-800 px-2 py-0.5 rounded text-gray-300">HD</span>
                            <button class="hover:text-cyan-400"><i class="fas fa-cog"></i></button>
                            <button class="hover:text-cyan-400"><i class="fas fa-expand"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 lg:px-10 py-6 max-w-7xl mx-auto w-full">
                
                <div class="flex border-b border-gray-800 mb-6 sticky top-0 bg-[#0b1120]/95 backdrop-blur z-20 pt-2 gap-8">
                    <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn active pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-book-reader"></i> Materi
                    </button>
                    <button onclick="switchTab('praktek')" id="tab-praktek" class="tab-btn pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-code"></i> Lab Code
                    </button>
                    <button onclick="switchTab('diskusi')" id="tab-diskusi" class="tab-btn pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-comments"></i> Diskusi
                    </button>
                </div>

                <div id="content-materi" class="tab-content active space-y-8 pb-20">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Pengenalan Variabel & Tipe Data</h2>
                        <div class="flex items-center gap-3 text-sm text-gray-400">
                            <span><i class="far fa-clock mr-1"></i> 10 Menit Baca</span>
                            <span>&bull;</span>
                            <span><i class="far fa-calendar mr-1"></i> Diupdate Jan 2026</span>
                        </div>
                    </div>

                    <div class="prose prose-invert prose-lg max-w-none text-gray-300 leading-relaxed">
                        <p>
                            Variabel adalah salah satu konsep paling fundamental dalam pemrograman. Bayangkan variabel sebagai sebuah <strong class="text-cyan-300">kotak penyimpanan</strong> yang memiliki label nama. Kotak ini bisa kita isi dengan berbagai jenis data, mulai dari teks, angka, hingga objek kompleks.
                        </p>
                        
                        <div class="p-5 bg-blue-500/5 border-l-4 border-blue-500 rounded-r-lg my-6">
                            <h4 class="text-blue-400 font-bold mb-1 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Key Takeaway
                            </h4>
                            <p class="text-sm text-blue-200/80 m-0">
                                Di JavaScript modern (ES6+), hindari penggunaan <code>var</code>. Gunakan <code>let</code> jika nilainya bisa berubah, dan <code>const</code> jika nilainya tetap (konstan).
                            </p>
                        </div>

                        <h3 class="text-xl font-bold text-white mt-8 mb-4">Deklarasi Variabel</h3>
                        <p>Berikut adalah contoh cara mendeklarasikan variabel di JavaScript:</p>
                        
                        <div class="relative group mt-4">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                            <div class="relative bg-[#1e293b] p-5 rounded-lg border border-gray-700 font-mono text-sm shadow-xl">
<pre><code class="language-javascript"><span class="text-purple-400">let</span> <span class="text-cyan-300">namaUser</span> = <span class="text-green-400">"Budi Santoso"</span>;  <span class="text-gray-500">// String</span>
<span class="text-purple-400">const</span> <span class="text-cyan-300">umur</span> = <span class="text-orange-400">25</span>;                <span class="text-gray-500">// Number</span>
<span class="text-purple-400">let</span> <span class="text-cyan-300">isActive</span> = <span class="text-red-400">true</span>;          <span class="text-gray-500">// Boolean</span></code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800/50 rounded-xl p-5 border border-gray-700 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center text-cyan-400 text-2xl">
                                <i class="fab fa-js"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-sm">Source Code Latihan</h4>
                                <p class="text-xs text-gray-400">latihan-variabel.js (2KB)</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-gray-700 hover:bg-white hover:text-black text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>

                    <div class="flex justify-between pt-8 border-t border-gray-800">
                        <button class="group flex items-center gap-3 px-5 py-3 rounded-lg border border-gray-700 hover:border-gray-500 text-gray-400 hover:text-white transition">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            <div class="text-left">
                                <div class="text-[10px] uppercase tracking-wider opacity-60">Modul Sebelumnya</div>
                                <div class="font-bold text-sm">Intro & Setup</div>
                            </div>
                        </button>
                        <button class="group flex items-center gap-3 px-5 py-3 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white shadow-lg shadow-cyan-900/50 transition">
                            <div class="text-right">
                                <div class="text-[10px] uppercase tracking-wider opacity-80">Modul Selanjutnya</div>
                                <div class="font-bold text-sm">Logika Percabangan</div>
                            </div>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <div id="content-praktek" class="tab-content pb-10">
                    <div class="mb-4 flex justify-between items-end">
                        <div>
                            <h3 class="font-bold text-white text-lg">🚀 Live Coding Lab</h3>
                            <p class="text-gray-400 text-xs">Tulis kode JavaScript di bawah dan lihat hasilnya di terminal.</p>
                        </div>
                        <button onclick="runCode()" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-green-900/30 flex items-center gap-2 transition transform hover:-translate-y-0.5">
                            <i class="fas fa-play"></i> Run Code
                        </button>
                    </div>

                    <div class="editor-container shadow-2xl">
                        <div class="bg-[#1e1e1e] flex items-center px-2 pt-2 border-b border-[#333]">
                            <div class="px-4 py-2 bg-[#282a36] text-gray-200 text-xs border-t border-l border-r border-[#333] rounded-t-md flex items-center gap-2 border-b border-[#282a36] relative top-[1px]">
                                <i class="fab fa-js text-yellow-400"></i> script.js
                            </div>
                            <div class="px-4 py-2 text-gray-500 text-xs hover:text-gray-300 cursor-pointer flex items-center gap-2 transition">
                                <i class="fas fa-file-code"></i> style.css
                            </div>
                        </div>

                        <div id="editor" class="flex-1"></div>

                        <div class="terminal-window p-3 overflow-y-auto">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">TERMINAL</span>
                                <button onclick="clearConsole()" class="text-gray-500 hover:text-white text-[10px]"><i class="fas fa-trash"></i> Clear</button>
                            </div>
                            <div id="console-output" class="text-green-400 space-y-1">
                                <div><span class="text-blue-400">➜</span> <span class="text-cyan-300">~/project</span> node script.js</div>
                                <div class="text-gray-400 italic">// Output akan muncul di sini...</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg text-yellow-200/80 text-sm flex gap-3 items-start">
                        <i class="fas fa-lightbulb mt-0.5"></i>
                        <p><strong>Tantangan:</strong> Coba buat variabel <code>nama</code> dengan isi namamu, lalu tampilkan menggunakan <code>console.log(nama)</code>.</p>
                    </div>
                </div>

                <div id="content-diskusi" class="tab-content pb-20">
                    <div class="bg-gray-800/30 border border-gray-700 rounded-xl p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700/50 mb-4 text-gray-400">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Forum Diskusi Kelas</h3>
                        <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">Punya pertanyaan tentang materi ini? Tanyakan di sini dan diskusikan bersama mentor dan siswa lainnya.</p>
                        <button class="px-6 py-2.5 bg-white text-black font-bold rounded-full hover:bg-cyan-50 transition">
                            Mulai Diskusi Baru
                        </button>
                    </div>
                </div>

            </div>
        </main>

        <aside class="w-80 bg-[#0f172a] border-l border-gray-800 flex flex-col shrink-0 z-20 hidden xl:flex">
            <div class="p-5 border-b border-gray-800">
                <h3 class="font-bold text-white text-xs uppercase tracking-widest mb-4 text-gray-400">Daftar Modul</h3>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="text" placeholder="Cari materi..." class="w-full bg-gray-900 border border-gray-700 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scroll p-3 space-y-1">
                
                <div class="rounded-lg overflow-hidden mb-2">
                    <div class="px-3 py-2 text-xs font-bold text-gray-500 uppercase flex justify-between items-center bg-gray-900/50 rounded cursor-pointer hover:bg-gray-800 transition">
                        <span>Modul 1: Intro</span>
                        <i class="fas fa-check text-green-500"></i>
                    </div>
                    <div class="mt-1 ml-2 border-l border-gray-800 pl-2 space-y-1">
                        <div class="px-3 py-2 text-sm text-gray-500 flex items-center gap-2 cursor-pointer hover:text-gray-300">
                            <i class="fas fa-play-circle text-xs"></i> Pengenalan Tools
                        </div>
                    </div>
                </div>

                <div class="rounded-lg overflow-hidden">
                    <div class="px-3 py-2 text-xs font-bold text-cyan-400 uppercase flex justify-between items-center bg-cyan-900/10 border border-cyan-500/20 rounded cursor-pointer">
                        <span class="glow-text">Modul 2: Dasar</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mt-1 ml-2 border-l border-cyan-900/50 pl-2 space-y-1 py-1">
                        <div class="module-item active px-3 py-2 text-sm font-medium rounded flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-play-circle text-xs animate-pulse"></i> Variabel & Data
                        </div>
                        <div class="px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded flex items-center gap-2 cursor-pointer transition">
                            <i class="fas fa-code text-xs text-orange-400"></i> Latihan Coding
                        </div>
                        <div class="px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded flex items-center gap-2 cursor-pointer transition">
                            <i class="fas fa-bolt text-xs text-yellow-400"></i> Kuis Singkat
                        </div>
                    </div>
                </div>

            </div>
        </aside>

    </div>

    <script>
        // Tab Logic
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('content-' + tabName).classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        // Ace Editor Setup
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ace !== 'undefined') {
                var editor = ace.edit("editor");
                editor.setTheme("ace/theme/dracula");
                editor.session.setMode("ace/mode/javascript");
                editor.setFontSize(14);
                editor.setShowPrintMargin(false);
                editor.setValue("// Tulis kodemu di sini\nlet pesan = 'Halo Dunia!';\nconsole.log(pesan);");
                editor.clearSelection(); 
            }
        });

        // Simulasi Run Code
        function runCode() {
            var editor = ace.edit("editor");
            var code = editor.getValue();
            var outputDiv = document.getElementById('console-output');
            
            // Simulasi output sederhana
            // Di real app, ini dikirim ke backend/compiler API
            outputDiv.innerHTML += `<div class="mt-1"><span class="text-blue-400">➜</span> <span class="text-cyan-300">~/project</span> node script.js</div>`;
            
            // Cek manual isi code untuk simulasi response
            if(code.includes('console.log')) {
                // Ambil isi dalam console.log (sangat sederhana regex)
                let match = code.match(/console\.log\((.*)\)/);
                if(match && match[1]) {
                    // Bersihkan quote jika string
                    let val = match[1].replace(/['"]/g, ''); 
                    // Jika variabel, kita hardcode aja simulasi nilainya
                    if(val === 'pesan') val = 'Halo Dunia!';
                    if(val === 'nama') val = 'User TanyaKode';
                    
                    outputDiv.innerHTML += `<div class="text-white ml-2">${val}</div>`;
                }
            } else {
                outputDiv.innerHTML += `<div class="text-gray-500 ml-2">Program finished with exit code 0</div>`;
            }
            
            // Auto scroll ke bawah terminal
            let terminal = document.querySelector('.terminal-window');
            terminal.scrollTop = terminal.scrollHeight;
        }

        function clearConsole() {
            document.getElementById('console-output').innerHTML = '';
        }
    </script>
</body>
</html>