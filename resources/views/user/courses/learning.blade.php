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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-html.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-java.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-c_cpp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-csharp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-sql.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-ruby.min.js"></script>

    <style>
        :root { --bg-dark: #0f172a; --primary: #22d3ee; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-dark); color: #e2e8f0; overflow: hidden; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .tab-btn { position: relative; color: #94a3b8; transition: all 0.2s; }
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
        .locked-item { opacity: 0.5; pointer-events: none; cursor: not-allowed; }
        #preview-frame { background: white; width: 100%; height: 100%; border: none; }
    </style>
</head>
<body class="flex flex-col h-screen">

    @php
        $courseCompleted = $isCompleted ?? false;
        $allContents = collect();
        foreach($course->modules as $mod) {
            foreach($mod->contents as $cont) { $allContents->push($cont); }
        }

        $currentIndex = $allContents->search(fn($item) => $item->id == $activeContent->id);
        $prevContent = $currentIndex > 0 ? $allContents[$currentIndex - 1] : null;
        $nextContent = $currentIndex < $allContents->count() - 1 ? $allContents[$currentIndex + 1] : null;

        $totalContents = $allContents->count();
        $progressPercent = $courseCompleted ? 100 : ($totalContents > 0 ? round(($currentIndex + 1) / $totalContents * 100) : 0);

        $isPractice = $activeContent && $activeContent->type == 'practice';
        $tabMateriActive = !$isPractice ? 'active' : '';
        $tabPraktekActive = $isPractice ? 'active' : '';

        // UI Helpers
        $lang = strtolower($activeContent->compiler_lang ?? 'javascript');
        $fileIcon = 'fab fa-js text-yellow-400'; $fileName = 'script.js';
        if($lang == 'python') { $fileIcon = 'fab fa-python text-blue-400'; $fileName = 'main.py'; }
        elseif($lang == 'php') { $fileIcon = 'fab fa-php text-purple-400'; $fileName = 'index.php'; }
        elseif($lang == 'html') { $fileIcon = 'fab fa-html5 text-orange-500'; $fileName = 'index.html'; }
        elseif($lang == 'java') { $fileIcon = 'fab fa-java text-red-500'; $fileName = 'Main.java'; }
    @endphp

    <nav class="h-16 bg-[#0f172a] border-b border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0 z-30">
        <div class="flex items-center gap-4">
            <a href="{{ url('/user/courses') }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 transition border border-gray-700">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-bold text-white text-sm md:text-base">{{ $course->name ?? 'Course Title' }}</h1>
                    <span class="hidden md:inline-flex {{ $isPractice ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' }} text-[10px] font-bold px-2 py-0.5 rounded border uppercase">
                        {{ $activeContent->type ?? 'MODUL' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 truncate">{{ $activeContent->title }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="hidden lg:flex flex-col w-40 text-right">
                <div class="text-[10px] font-bold text-gray-400 uppercase">Progress <span class="text-cyan-400">{{ $progressPercent }}%</span></div>
                <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        <main class="flex-1 flex flex-col bg-[#0b1120] overflow-y-auto custom-scroll relative">
            
            @if($activeContent->video_url)
            <div class="w-full bg-black aspect-video max-h-[500px] relative flex items-center justify-center shrink-0 border-b border-gray-800">
                <iframe src="{{ $activeContent->video_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
            @endif

            <div class="px-6 lg:px-10 py-6 max-w-7xl mx-auto w-full">
                <div class="flex border-b border-gray-800 mb-6 sticky top-0 bg-[#0b1120]/95 backdrop-blur z-20 pt-2 gap-8">
                    <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn {{ $tabMateriActive }} pb-3 text-sm font-bold uppercase tracking-wider">Materi</button>
                    <button onclick="switchTab('praktek')" id="tab-praktek" class="tab-btn {{ $tabPraktekActive }} pb-3 text-sm font-bold uppercase tracking-wider">Lab Code</button>
                    <button onclick="switchTab('diskusi')" id="tab-diskusi" class="tab-btn pb-3 text-sm font-bold uppercase tracking-wider">Diskusi</button>
                </div>

                <div id="content-materi" class="tab-content {{ $tabMateriActive }} space-y-8 pb-20">
                    <h2 class="text-3xl font-bold text-white mb-2">{{ $activeContent->title }}</h2>
                    <div class="prose prose-invert prose-lg max-w-none text-gray-300">
                        {!! $activeContent->content_body !!}
                    </div>

                    <div class="flex justify-between pt-8 border-t border-gray-800 mt-10">
                        @if($prevContent)
                            <a href="{{ route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $prevContent->id]) }}" class="group flex items-center gap-3 px-5 py-3 rounded-lg border border-gray-700 text-gray-400 hover:text-white transition">
                                <i class="fas fa-arrow-left"></i>
                                <div class="text-left hidden sm:block">
                                    <div class="text-[10px] uppercase opacity-60">Sebelumnya</div>
                                    <div class="font-bold text-sm truncate max-w-[150px]">{{ $prevContent->title }}</div>
                                </div>
                            </a>
                        @else <div></div> @endif

                        @if($nextContent)
                            <a href="{{ route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $nextContent->id]) }}" class="group flex items-center gap-3 px-5 py-3 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white shadow-lg transition">
                                <div class="text-right hidden sm:block">
                                    <div class="text-[10px] uppercase opacity-80">Selanjutnya</div>
                                    <div class="font-bold text-sm truncate max-w-[150px]">{{ $nextContent->title }}</div>
                                </div>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('user.courses.index') }}" class="bg-green-600 hover:bg-green-500 text-white px-5 py-3 rounded-lg font-bold transition">Selesai Kursus</a>
                        @endif
                    </div>
                </div>

                <div id="content-praktek" class="tab-content {{ $tabPraktekActive }} pb-10">
                    <div class="mb-4 flex justify-between items-end">
                        <div>
                            <h3 class="font-bold text-white text-lg">🚀 Live Coding Lab</h3>
                            <p class="text-gray-400 text-xs uppercase">{{ $activeContent->compiler_lang }}</p>
                        </div>
                        <button id="btn-run" onclick="runCode()" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition">
                            <i class="fas fa-play"></i> Run Code
                        </button>
                    </div>

                    <div class="editor-container shadow-2xl">
                        <div class="bg-[#1e1e1e] flex items-center px-2 pt-2 border-b border-[#333]">
                            <div class="px-4 py-2 bg-[#282a36] text-gray-200 text-xs rounded-t-md flex items-center gap-2 relative top-[1px]">
                                <i class="{{ $fileIcon }}"></i> {{ $fileName }}
                            </div>
                        </div>
                        <div id="editor" class="flex-1"></div>
                        <div class="terminal-window p-3 overflow-y-auto">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-500 text-[10px] uppercase font-bold">TERMINAL OUTPUT</span>
                                <button onclick="clearConsole()" class="text-gray-500 hover:text-white text-[10px]">Clear</button>
                            </div>
                            <div id="console-output" class="text-green-400 space-y-1 font-mono text-xs hidden"></div>
                            <div id="html-preview" class="w-full h-full bg-white hidden rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <aside class="w-80 bg-[#0f172a] border-l border-gray-800 flex flex-col shrink-0 hidden xl:flex">
            <div class="p-5 border-b border-gray-800 font-bold text-gray-400 uppercase text-xs">Daftar Modul</div>
            <div class="flex-1 overflow-y-auto custom-scroll p-3 space-y-1">
                @foreach($course->modules as $module)
                <div class="rounded-lg overflow-hidden mb-2">
                    <div class="px-3 py-2 text-xs font-bold text-cyan-400 bg-cyan-900/10 rounded cursor-pointer">{{ $module->name }}</div>
                    <div class="mt-1 ml-2 border-l border-cyan-900/50 pl-2 space-y-1 py-1">
                        @foreach($module->contents as $content)
                            @php
                                $isActive = $activeContent->id == $content->id;
                                $thisIndex = $allContents->search(fn($item) => $item->id == $content->id);
                                $isLocked = !$courseCompleted && ($thisIndex > $currentIndex);
                            @endphp
                            <a href="{{ $isLocked ? '#' : route('user.courses.learning', ['slug' => $course->slug, 'contentId' => $content->id]) }}" 
                               class="px-3 py-2 text-sm rounded flex items-center gap-2 transition {{ $isActive ? 'module-item active' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} {{ $isLocked ? 'locked-item' : '' }}">
                                @if($isLocked) <i class="fas fa-lock text-xs text-gray-600"></i>
                                @else <i class="fas {{ $content->type == 'video' ? 'fa-play-circle' : 'fa-file-alt' }} text-xs"></i> @endif
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
        const dbSnippet = {!! json_encode($activeContent->practice_snippet ?? '') !!};
        var editor;

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ace !== 'undefined') {
                editor = ace.edit("editor");
                editor.setTheme("ace/theme/dracula");
                
                const modeMap = {
                    'javascript': 'ace/mode/javascript', 'python': 'ace/mode/python',
                    'php': 'ace/mode/php', 'html': 'ace/mode/html', 'css': 'ace/mode/css',
                    'java': 'ace/mode/java', 'c++': 'ace/mode/c_cpp', 'cpp': 'ace/mode/c_cpp',
                    'sql': 'ace/mode/sql', 'ruby': 'ace/mode/ruby'
                };

                editor.session.setMode(modeMap[dbLang.toLowerCase()] || 'ace/mode/javascript');
                editor.setFontSize(14);
                editor.setValue(dbSnippet);
                editor.clearSelection(); 
            }
        });

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('content-' + tabName).classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        async function runCode() {
            const btn = document.getElementById('btn-run');
            const code = editor.getValue();
            const consoleDiv = document.getElementById('console-output');
            const previewDiv = document.getElementById('html-preview');
            const lang = dbLang.toLowerCase();

            consoleDiv.innerHTML = '<div class="text-cyan-400 italic">Compiling and running...</div>';
            consoleDiv.classList.remove('hidden');
            previewDiv.classList.add('hidden');

            if(lang === 'html') {
                consoleDiv.classList.add('hidden'); previewDiv.classList.remove('hidden');
                previewDiv.innerHTML = '<iframe id="preview-frame" style="width:100%; height:100%; border:none;"></iframe>';
                const doc = document.getElementById('preview-frame').contentDocument;
                doc.open(); doc.write(code); doc.close();
                return;
            }

            // Piston API Logic
            btn.disabled = true;
            btn.classList.add('opacity-50');

            // Mapping identifiers for Piston API
            const pistonMap = {
                'javascript': 'js', 'js': 'js', 'python': 'python3', 'py': 'python3',
                'php': 'php', 'java': 'java', 'cpp': 'cpp', 'c++': 'cpp', 'ruby': 'ruby'
            };

            try {
                const response = await fetch('https://emkc.org/api/v2/piston/execute', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        language: pistonMap[lang] || lang,
                        version: "*",
                        files: [{ content: code }]
                    })
                });

                const result = await response.json();
                consoleDiv.innerHTML = '';

                if (result.run) {
                    const output = result.run.output || "Program finished with no output.";
                    consoleDiv.innerHTML = `<div class="text-white whitespace-pre-wrap">${output}</div>`;
                    if(result.run.stderr) consoleDiv.innerHTML += `<div class="text-red-400 mt-2">${result.run.stderr}</div>`;
                } else {
                    consoleDiv.innerHTML = '<div class="text-red-500 italic">Compilation error or server unavailable.</div>';
                }
            } catch (e) {
                consoleDiv.innerHTML = `<div class="text-red-500">Error connecting to Piston API: ${e.message}</div>`;
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                const terminal = document.querySelector('.terminal-window');
                terminal.scrollTop = terminal.scrollHeight;
            }
        }

        function clearConsole() {
            document.getElementById('console-output').innerHTML = '';
            document.getElementById('html-preview').innerHTML = '';
        }
    </script>
</body>
</html>