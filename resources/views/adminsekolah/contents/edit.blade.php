@extends('layouts.admin_course')

@section('title', 'Edit Materi')

@section('content')
<div class="container-fluid pt-4">
    {{-- Tampilkan Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-warning card-outline shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title fw-bold text-dark">
                <i class="fas fa-edit text-warning me-2"></i> Edit Materi: {{ $content->title }}
            </h3>
        </div>
        
        <form id="editForm" action="{{ route('contents.update', $content->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Judul Materi</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Tipe Konten</label>
                            <select name="type" id="contentTypeSelect" class="form-control">
                                <option value="theory" {{ old('type', $content->type) == 'theory' ? 'selected' : '' }}>Teori (Teks & Video)</option>
                                <option value="practice" {{ old('type', $content->type) == 'practice' ? 'selected' : '' }}>Praktek (Koding Simulation)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Isi Materi / Instruksi Tugas</label>
                    <textarea name="content_body" class="form-control summernote">{{ old('content_body', $content->content_body) }}</textarea>
                </div>

                {{-- Area Khusus Praktek Koding --}}
                <div id="practiceArea" style="{{ old('type', $content->type) == 'practice' ? '' : 'display:none;' }}; background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #ddd;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Bahasa Pemrograman</label>
                                <select name="compiler_lang" id="compilerLang" class="form-control">
                                    <option value="php" {{ old('compiler_lang', $content->compiler_lang) == 'php' ? 'selected' : '' }}>PHP</option>
                                    <option value="javascript" {{ old('compiler_lang', $content->compiler_lang) == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                                    <option value="python" {{ old('compiler_lang', $content->compiler_lang) == 'python' ? 'selected' : '' }}>Python</option>
                                    <option value="html" {{ old('compiler_lang', $content->compiler_lang) == 'html' ? 'selected' : '' }}>HTML/CSS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">Kerangka Kode (Ace Editor)</label>
                        <div id="aceEditor" style="height: 350px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"></div>
                        {{-- Input Hidden untuk mengirim data koding ke database --}}
                        <input type="hidden" name="practice_snippet" id="snippetInput" value="{{ old('practice_snippet', $content->practice_snippet) }}">
                    </div>
                </div>

                {{-- Area Khusus Teori --}}
                <div id="theoryArea" style="{{ old('type', $content->type) == 'theory' ? '' : 'display:none;' }}">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Video URL (Youtube/Vimeo)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                            <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $content->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="{{ route('courses.show', $content->module->course_id) }}" class="btn btn-light px-4">
                    <i class="fas fa-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-warning fw-bold px-4">
                    <i class="fas fa-save me-1"></i> Update Materi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .ace_editor { border-radius: 5px; }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi Summernote
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Tuliskan instruksi atau materi teori di sini...'
        });

        // 2. Inisialisasi Ace Editor
        var editor = ace.edit("aceEditor");
        editor.setTheme("ace/theme/monokai");
        
        // Set Bahasa Awal
        var currentLang = $('#compilerLang').val() || 'php';
        editor.session.setMode("ace/mode/" + (currentLang === 'html' ? 'html' : currentLang));

        // Load data koding dari input hidden ke Ace Editor
        var initialCode = $('#snippetInput').val();
        editor.setValue(initialCode, -1);

        // 3. Sinkronisasi Real-time (Sync Ace Editor ke Hidden Input)
        editor.getSession().on('change', function() {
            $('#snippetInput').val(editor.getValue());
        });

        // 4. Toggle Antara Teori dan Praktek
        $('#contentTypeSelect').change(function() {
            var val = $(this).val();
            if(val === 'practice') {
                $('#practiceArea').slideDown();
                $('#theoryArea').slideUp();
                editor.resize(); // Perbaiki ukuran editor saat tampil
            } else {
                $('#practiceArea').slideUp();
                $('#theoryArea').slideDown();
            }
        });

        // 5. Ubah Mode Bahasa Editor Secara Dinamis
        $('#compilerLang').change(function() {
            var lang = $(this).val();
            editor.session.setMode("ace/mode/" + (lang === 'html' ? 'html' : lang));
        });

        // 6. Validasi Sebelum Submit
        $('#editForm').on('submit', function() {
            var type = $('#contentTypeSelect').val();
            if(type === 'practice') {
                var code = editor.getValue();
                if(!code.trim()) {
                    alert('Kerangka kode tidak boleh kosong untuk materi praktek!');
                    return false;
                }
                $('#snippetInput').val(code);
            }
        });
    });
</script>
@endsection