@extends('layouts.admin_course')

@section('title', 'Edit Materi')

@section('content')
<div class="container-fluid pt-3">
    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-warning card-outline">
        <div class="card-header">
            <h3 class="card-title">Edit Materi: {{ $content->title }}</h3>
        </div>
        
        <form id="editForm" action="{{ route('contents.update', $content->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Judul Materi</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Konten</label>
                            <select name="type" id="contentTypeSelect" class="form-control">
                                <option value="theory" {{ old('type', $content->type) == 'theory' ? 'selected' : '' }}>Teori</option>
                                <option value="practice" {{ old('type', $content->type) == 'practice' ? 'selected' : '' }}>Praktek (Koding)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Isi Materi / Instruksi</label>
                    <textarea name="content_body" class="form-control summernote">{{ old('content_body', $content->content_body) }}</textarea>
                </div>

                <div id="practiceArea" style="{{ old('type', $content->type) == 'practice' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label>Bahasa Pemrograman</label>
                        <select name="compiler_lang" id="compilerLang" class="form-control">
                            <option value="php" {{ old('compiler_lang', $content->compiler_lang) == 'php' ? 'selected' : '' }}>PHP</option>
                            <option value="javascript" {{ old('compiler_lang', $content->compiler_lang) == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                            <option value="python" {{ old('compiler_lang', $content->compiler_lang) == 'python' ? 'selected' : '' }}>Python</option>
                            <option value="html" {{ old('compiler_lang', $content->compiler_lang) == 'html' ? 'selected' : '' }}>HTML</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kerangka Kode (Ace Editor)</label>
                        <div id="aceEditor" style="height: 300px; border: 1px solid #ccc; font-size: 14px;"></div>
                        {{-- ID snippetInput ini yang akan mengirim data ke database --}}
                        <input type="hidden" name="practice_snippet" id="snippetInput" value="{{ old('practice_snippet', $content->practice_snippet) }}">
                    </div>
                </div>

                <div id="theoryArea" style="{{ old('type', $content->type) == 'theory' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label>Video URL (Opsional)</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $content->video_url) }}">
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('courses.show', $content->module->course_id) }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold">Update Materi</button>
            </div>
        </form>
    </div>
</div>

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Summernote
        $('.summernote').summernote({ height: 200 });

        // 2. Ace Editor Setup
        var editor = ace.edit("aceEditor");
        editor.setTheme("ace/theme/monokai");
        
        var currentLang = $('#compilerLang').val() || 'php';
        editor.session.setMode("ace/mode/" + currentLang);

        // Load data awal ke editor
        var initialCode = $('#snippetInput').val();
        editor.setValue(initialCode, -1);

        // 3. REAL-TIME SYNC (SOLUSI UTAMA)
        // Setiap kali editor diketik, langsung update input hidden
        editor.getSession().on('change', function() {
            $('#snippetInput').val(editor.getValue());
        });

        // 4. Toggle UI
        $('#contentTypeSelect').change(function() {
            var val = $(this).val();
            if(val === 'practice') {
                $('#practiceArea').slideDown();
                $('#theoryArea').slideUp();
            } else {
                $('#practiceArea').slideUp();
                $('#theoryArea').slideDown();
                $('#snippetInput').val(''); // Reset jika pindah ke teori
            }
        });

        $('#compilerLang').change(function() {
            editor.session.setMode("ace/mode/" + $(this).val());
        });

        // 5. Final Check pada saat Submit
        $('#editForm').on('submit', function() {
            var type = $('#contentTypeSelect').val();
            if(type === 'practice') {
                $('#snippetInput').val(editor.getValue());
            }
        });
    });
</script>
@stop
@endsection