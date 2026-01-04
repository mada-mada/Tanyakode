@extends('adminlte::page')

@section('title', 'Edit Materi')

@section('content')
<div class="container-fluid pt-3">
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
                            <input type="text" name="title" class="form-control" value="{{ $content->title }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Konten</label>
                            <select name="content_type" id="contentTypeSelect" class="form-control">
                                <option value="theory" {{ $content->content_type == 'theory' ? 'selected' : '' }}>Teori</option>
                                <option value="practice" {{ $content->content_type == 'practice' ? 'selected' : '' }}>Praktek (Koding)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Isi Materi / Instruksi</label>
                    <textarea name="content_body" class="form-control summernote">{{ $content->content_body }}</textarea>
                </div>

                <div id="practiceArea" style="{{ $content->content_type == 'practice' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label>Bahasa Pemrograman</label>
                        <select name="compiler_lang" id="compilerLang" class="form-control">
                            <option value="php" {{ $content->compiler_lang == 'php' ? 'selected' : '' }}>PHP</option>
                            <option value="javascript" {{ $content->compiler_lang == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                            <option value="python" {{ $content->compiler_lang == 'python' ? 'selected' : '' }}>Python</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kerangka Kode (Ace Editor)</label>
                        <div id="aceEditor" style="height: 300px; border: 1px solid #ccc;"></div>
                        <input type="hidden" name="practice_code_snippet" id="snippetInput">
                    </div>
                </div>

                <div id="theoryArea" style="{{ $content->content_type == 'theory' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label>Video URL</label>
                        <input type="url" name="video_url" class="form-control" value="{{ $content->video_url }}">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update Materi</button>
                <a href="{{ route('courses.show', $content->module->course_id) }}" class="btn btn-default">Kembali</a>
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
        $('.summernote').summernote({height: 150});

        var editor = ace.edit("aceEditor");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/{{ $content->compiler_lang ?? 'php' }}");

        // LOAD KODE LAMA DARI DATABASE KE EDITOR
        var existingCode = `{!! addslashes($content->practice_code_snippet ?? '') !!}`;
        editor.setValue(existingCode, -1); // -1 agar kursor ada di awal

        // Logic Toggle
        $('#contentTypeSelect').change(function() {
            if($(this).val() === 'practice') {
                $('#practiceArea').slideDown();
                $('#theoryArea').slideUp();
            } else {
                $('#practiceArea').slideUp();
                $('#theoryArea').slideDown();
            }
        });

        // Ganti Bahasa
        $('#compilerLang').change(function() {
            editor.session.setMode("ace/mode/" + $(this).val());
        });

        // Submit Logic
        $('#editForm').on('submit', function() {
            $('#snippetInput').val(editor.getValue());
        });
    });
</script>
@stop
@stop
