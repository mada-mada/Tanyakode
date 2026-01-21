<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Room - {{ $activeContent->title ?? 'Materi Kursus' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/theme-dracula.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-html.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-css.min.js"></script>

    <style>
        :root { --bg-dark: #0f172a; --primary: #22d3ee; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-dark); color: #e2e8f0; overflow: hidden; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
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
        .editor-container { border: 1px solid #334155; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; height: 500px; }
        .terminal-window { background: #1e1e1e; border-top: 1px solid #334155; height: 120px; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
        .module-item.active { background: rgba(34, 211, 238, 0.05); border-left: 3px solid var(--primary); color: white; }
        .glow-text { text-shadow: 0 0 10px rgba(34, 211, 238, 0.3); }
        
        /* Style untuk item terkunci */
        .locked-item { opacity: 0.5; pointer-events: none; cursor: not-allowed; }
        
        /* Preview HTML */
        #preview-frame { background: white; width: 100%; height: 100%; border: none; }
    </style>
</head>
<body class="flex flex-col h-screen">

    @php
        // 1. Ambil status completed dari controller (default false jika tidak ada)
        $courseCompleted = $isCompleted ?? false;

        // 2. Ratakan semua konten
        $allContents = collect();
        foreach($course->modules as $mod) {
            foreach($mod->contents as $cont) {
                $allContents->push($cont);
            }
        }

        // 3. Cari index saat ini
        $currentIndex = $allContents->search(function($item) use ($activeContent) {
            return $item->id == $activeContent->id;
        });

        // 4. Navigasi Next/Prev
        $prevContent = $currentIndex > 0 ? $allContents[$currentIndex - 1] : null;
        $nextContent = $currentIndex < $allContents->count() - 1 ? $allContents[$currentIndex + 1] : null;

        // 5. Hitung Persentase Progress
        // Jika completed, paksa 100%. Jika tidak, hitung berdasarkan posisi.
        $progressPercent = $courseCompleted ? 100 : round(($currentIndex + 1) / $allContents->count() * 100);

        // 6. Tab Logic
        $isPractice = $activeContent && $activeContent->type == 'practice';
        $tabMateriActive = !$isPractice ? 'active' : '';
        $tabPraktekActive = $isPractice ? 'active' : '';
    @endphp

    <nav class="h-16 bg-[#0f172a] border-b border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0 z-30">
        <div class="flex items-center gap-4">
            <a href="{{ url('/user/courses') }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition border border-gray-700">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
            <div class="h-8 w-[1px] bg-gray-700 hidden md:block"></div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-bold text-white text-sm md:text-base tracking-wide">{{ $course->name ?? 'Course Title' }}</h1>
                    @if($activeContent)
                        <span class="hidden md:inline-flex {{ $activeContent->type == 'practice' ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' }} text-[10px] font-bold px-2 py-0.5 rounded border uppercase">
                            {{ $activeContent->type ?? 'MODUL' }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px] md:max-w-none">{{ $activeContent->title ?? 'Pilih Materi' }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="hidden lg:flex flex-col w-40 gap-1 text-right">
                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    Progress <span class="text-cyan-400 ml-1">{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
            <div class="flex items-center gap-3 border-l border-gray-700 pl-6">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 p-[2px]">
                    <img src="https://ui-avatars.com/api/?name=User&background=0f172a&color=fff" class="rounded-full w-full h-full border-2 border-[#0f172a]" alt="User">
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        <main class="flex-1 flex flex-col bg-[#0b1120] overflow-y-auto custom-scroll relative">
            
            @if($activeContent && !empty($activeContent->video_url))
            <div class="w-full bg-black aspect-video max-h-[500px] relative flex items-center justify-center shadow-2xl shrink-0 border-b border-gray-800">
                <iframe src="{{ $activeContent->video_url }}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            </div>
            @endif

            <div class="px-6 lg:px-10 py-6 max-w-7xl mx-auto w-full">
                
                <div class="flex border-b border-gray-800 mb-6 sticky top-0 bg-[#0b1120]/95 backdrop-blur z-20 pt-2 gap-8">
                    <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn {{ $tabMateriActive }} pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-book-reader"></i> Materi
                    </button>
                    <button onclick="switchTab('praktek')" id="tab-praktek" class="tab-btn {{ $tabPraktekActive }} pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-code"></i> Lab Code
                    </button>
                    <button onclick="switchTab('diskusi')" id="tab-diskusi" class="tab-btn pb-3 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-comments"></i> Diskusi
                    </button>
                </div>

                <div id="content-materi" class="tab-content {{ $tabMateriActive }} space-y-8 pb-20">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">{{ $activeContent->title ?? 'Judul Materi' }}</h2>
                        <div class="flex items-center gap-3 text-sm text-gray-400">
                            <span><i class="far fa-clock mr-1"></i> Update: {{ $activeContent->updated_at ? $activeContent->updated_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="prose prose-invert prose-lg max-w-none text-gray-300 leading-relaxed">
                        {!! $activeContent->content_body ?? '<p class="text-gray-500 italic">Tidak ada konten teks.</p>' !!}
                    </div>

                    <div class="flex justify-between pt-8 border-t border-gray-800 mt-10">
                        @if($prevContent)
                            <a href="{{ route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $prevContent->id]) }}" 
                               class="group flex items-center gap-3 px-5 py-3 rounded-lg border border-gray-700 hover:border-gray-500 text-gray-400 hover:text-white transition">
                                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                <div class="text-left hidden sm:block">
                                    <div class="text-[10px] uppercase tracking-wider opacity-60">Materi Sebelumnya</div>
                                    <div class="font-bold text-sm truncate max-w-[150px]">{{ $prevContent->title }}</div>
                                </div>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextContent)
                            <a href="{{ route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $nextContent->id]) }}" 
                               class="group flex items-center gap-3 px-5 py-3 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white shadow-lg shadow-cyan-900/50 transition">
                                <div class="text-right hidden sm:block">
                                    <div class="text-[10px] uppercase tracking-wider opacity-80">Materi Selanjutnya</div>
                                    <div class="font-bold text-sm truncate max-w-[150px]">{{ $nextContent->title }}</div>
                                </div>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @else
                            <a href="{{ route('user.courses.index') }}" 
                               class="group flex items-center gap-3 px-5 py-3 rounded-lg bg-green-600 hover:bg-green-500 text-white shadow-lg shadow-green-900/50 transition">
                                <span class="font-bold text-sm">Selesai Kursus</span>
                                <i class="fas fa-check-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div id="content-praktek" class="tab-content {{ $tabPraktekActive }} pb-10">
                    <div class="mb-4 flex justify-between items-end">
                        <div>
                            <h3 class="font-bold text-white text-lg">🚀 Live Coding Lab</h3>
                            <p class="text-gray-400 text-xs">
                                Bahasa: <span class="text-cyan-400 font-bold uppercase">{{ $activeContent->compiler_lang ?? 'JAVASCRIPT' }}</span>
                            </p>
                        </div>
                        <button onclick="runCode()" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-green-900/30 flex items-center gap-2 transition transform hover:-translate-y-0.5">
                            <i class="fas fa-play"></i> Run Code
                        </button>
                    </div>

                    <div class="editor-container shadow-2xl">
                        <div class="bg-[#1e1e1e] flex items-center px-2 pt-2 border-b border-[#333]">
                            <div class="px-4 py-2 bg-[#282a36] text-gray-200 text-xs border-t border-l border-r border-[#333] rounded-t-md flex items-center gap-2 relative top-[1px]">
                                @if( ($activeContent->compiler_lang ?? '') == 'php')
                                    <i class="fab fa-php text-purple-400"></i> index.php
                                @elseif( ($activeContent->compiler_lang ?? '') == 'html')
                                    <i class="fab fa-html5 text-orange-500"></i> index.html
                                @else
                                    <i class="fab fa-js text-yellow-400"></i> script.js
                                @endif
                            </div>
                        </div>
                        <div id="editor" class="flex-1"></div>
                        <div class="terminal-window p-3 overflow-y-auto" id="terminal-container">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">OUTPUT</span>
                                <button onclick="clearConsole()" class="text-gray-500 hover:text-white text-[10px]"><i class="fas fa-trash"></i> Clear</button>
                            </div>
                            <div id="console-output" class="text-green-400 space-y-1 font-mono text-xs hidden"></div>
                            <div id="html-preview" class="w-full h-full bg-white hidden rounded"></div>
                        </div>
                    </div>
                </div>

                <div id="content-diskusi" class="tab-content pb-20">
                    <div class="bg-gray-800/30 border border-gray-700 rounded-xl p-8 text-center">
                        <h3 class="text-xl font-bold text-white mb-2">Forum Diskusi</h3>
                        <p class="text-gray-400 text-sm mb-6">Diskusikan materi ini bersama mentor.</p>
                        <button class="px-6 py-2.5 bg-white text-black font-bold rounded-full hover:bg-cyan-50 transition">Mulai Diskusi</button>
                    </div>
                </div>

            </div>
        </main>

        <aside class="w-80 bg-[#0f172a] border-l border-gray-800 flex flex-col shrink-0 z-20 hidden xl:flex">
            <div class="p-5 border-b border-gray-800">
                <h3 class="font-bold text-white text-xs uppercase tracking-widest mb-4 text-gray-400">Daftar Modul</h3>
            </div>

            <div class="flex-1 overflow-y-auto custom-scroll p-3 space-y-1">
                @foreach($course->modules as $index => $module)
                <div class="rounded-lg overflow-hidden mb-2">
                    <div class="px-3 py-2 text-xs font-bold text-cyan-400 uppercase flex justify-between items-center bg-cyan-900/10 border border-cyan-500/20 rounded cursor-pointer">
                        <span class="glow-text">Modul {{ $index + 1 }}: {{ $module->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <div class="mt-1 ml-2 border-l border-cyan-900/50 pl-2 space-y-1 py-1">
                        @foreach($module->contents as $content)
                            @php
                                $isActive = $activeContent && $activeContent->id == $content->id;
                                
                                // Cari urutan konten ini secara global
                                $thisContentIndex = $allContents->search(function($item) use ($content) {
                                    return $item->id == $content->id;
                                });

                                // LOGIKA PENGUNCIAN:
                                // Kunci jika:
                                // 1. User BELUM selesai kursus ($courseCompleted == false)
                                // 2. DAN materi ini berada di masa depan ($thisContentIndex > $currentIndex)
                                // Jika user sudah completed, maka (!true) menjadi false, sehingga isLocked selalu false.
                                $isLocked = !$courseCompleted && ($thisContentIndex > $currentIndex);
                            @endphp

                            <a href="{{ $isLocked ? '#' : route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $content->id]) }}" 
                               class="px-3 py-2 text-sm rounded flex items-center gap-2 transition relative
                               {{ $isActive ? 'module-item active' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}
                               {{ $isLocked ? 'locked-item' : '' }}">
                                
                                @if($isLocked)
                                    <i class="fas fa-lock text-xs text-gray-600"></i>
                                @elseif($content->type == 'video')
                                    <i class="fas fa-play-circle text-xs {{ $isActive ? 'animate-pulse' : '' }}"></i>
                                @elseif($content->type == 'practice')
                                    <i class="fas fa-code text-xs text-orange-400"></i>
                                @else
                                    <i class="fas fa-file-alt text-xs"></i>
                                @endif

                                <span class="truncate flex-1">{{ $content->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </aside>
    </div>

    <script>
        const dbLang = "{{ $activeContent->compiler_lang ?? 'javascript' }}"; 
        const dbSnippet = {!! json_encode($activeContent->snippet ?? '// Tulis kodemu disini...') !!};

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('content-' + tabName).classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        var editor;
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ace !== 'undefined') {
                editor = ace.edit("editor");
                editor.setTheme("ace/theme/dracula");
                
                let modeMap = {
                    'javascript': 'ace/mode/javascript',
                    'js': 'ace/mode/javascript',
                    'php': 'ace/mode/php',
                    'html': 'ace/mode/html',
                    'css': 'ace/mode/css'
                };

                let aceMode = modeMap[dbLang.toLowerCase()] || 'ace/mode/javascript';
                editor.session.setMode(aceMode);
                editor.setFontSize(14);
                editor.setShowPrintMargin(false);
                editor.setValue(dbSnippet);
                editor.clearSelection(); 
            }
        });

        function runCode() {
            var code = editor.getValue();
            var consoleDiv = document.getElementById('console-output');
            var previewDiv = document.getElementById('html-preview');
            
            consoleDiv.innerHTML = '';
            previewDiv.innerHTML = '';
            
            if(dbLang === 'html') {
                consoleDiv.classList.add('hidden');
                previewDiv.classList.remove('hidden');
                previewDiv.innerHTML = '<iframe id="preview-frame"></iframe>';
                var iframe = document.getElementById('preview-frame');
                var doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open();
                doc.write(code);
                doc.close();
            } else if (dbLang === 'javascript' || dbLang === 'js') {
                previewDiv.classList.add('hidden');
                consoleDiv.classList.remove('hidden');
                consoleDiv.innerHTML += `<div><span class="text-blue-400">➜</span> <span class="text-cyan-300">Run</span> script.js</div>`;
                try {
                    let originalLog = console.log;
                    let logs = [];
                    console.log = function(...args) { logs.push(args.join(' ')); };
                    eval(code); 
                    console.log = originalLog;
                    if(logs.length > 0) {
                        logs.forEach(log => { consoleDiv.innerHTML += `<div class="text-white ml-2">> ${log}</div>`; });
                    } else {
                        consoleDiv.innerHTML += `<div class="text-gray-500 ml-2">Program selesai (tanpa output).</div>`;
                    }
                } catch (e) {
                    consoleDiv.innerHTML += `<div class="text-red-400 ml-2">Error: ${e.message}</div>`;
                }
            } else {
                previewDiv.classList.add('hidden');
                consoleDiv.classList.remove('hidden');
                consoleDiv.innerHTML = `<div class="text-yellow-400 p-2">Simulasi untuk ${dbLang} butuh backend server.</div>`;
            }
            
            let terminal = document.querySelector('.terminal-window');
            terminal.scrollTop = terminal.scrollHeight;
        }

        function clearConsole() {
            document.getElementById('console-output').innerHTML = '';
            document.getElementById('html-preview').innerHTML = '';
        }
    </script>
</body>
</html>