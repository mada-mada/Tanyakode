@extends('layouts.admin_course')

@section('title', 'Kelola Materi')

@section('content')
<div class="container-fluid pt-3">
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    @if($course->thumbnail_url)
                        <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="Course Thumbnail" class="img-fluid rounded border">
                    @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 100px; width: 100%;">
                            <span>No Image</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <h3>{{ $course->title }}</h3>
                    <p class="text-muted mb-2">
                        <span class="badge badge-{{ $course->level == 'pemula' ? 'success' : ($course->level == 'menengah' ? 'warning' : 'danger') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                        | Harga: <b>{{ number_format($course->price) }}</b>
                    </p>
                    <p class="mb-2">Total Modul: <b>{{ $course->modules->count() }}</b></p>
                    
                    <button class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#modalAddModule">
                        <i class="fas fa-plus"></i> Tambah Bab Baru
                    </button>
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-warning btn-sm mt-2">
                        <i class="fas fa-edit"></i> Edit Info Kursus
                    </a>
                </div>
            </div>
        </div>
    </div>

    @foreach($course->modules as $module)
    <div class="card card-outline card-secondary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">
                <b>Bab {{ $module->sort_order }}:</b> {{ $module->title }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>

                <form action="{{ route('modules.destroy', $module->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-tool text-danger" onclick="return confirm('Hapus Bab beserta isinya?')"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body" style="background-color: #f9f9f9;">
            <table class="table table-sm table-bordered bg-white">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 10%">Tipe</th>
                        <th>Judul Materi</th>
                        <th style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($module->contents as $content)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">
                            @if($content->content_type == 'theory')
                                <span class="badge badge-info">Teori</span>
                            @else
                                <span class="badge badge-warning">Praktek</span>
                            @endif
                        </td>
                        <td>{{ $content->title }}</td>
                        <td>
                            <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-xs btn-warning">Edit</a>
                            <form action="{{ route('contents.destroy', $content->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-danger" onclick="return confirm('Hapus materi?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
           <button type="button" 
                class="btn btn-default btn-sm btn-block btn-add-content" 
                data-id="{{ $module->id }}"
                 data-title="{{ $module->title }}">
                 <i class="fas fa-plus-circle"></i> Tambah Materi ke Bab Ini
                </button>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="modalAddModule">
    <div class="modal-dialog">
        <form action="{{ route('modules.store', $course->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Bab (Modul)</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" class="form-control" placeholder="Judul Bab..." required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAddContent">
    <div class="modal-dialog modal-lg">
        <form id="formContent" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalContentTitle">Tambah Materi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Judul Materi</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipe Konten</label>
                                <select name="type" id="contentTypeSelect" class="form-control">
                                    <option value="theory">Teori (Teks/Video)</option>
                                    <option value="practice">Praktek (Koding)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Isi Materi / Instruksi</label>
                        <textarea name="content_body" class="form-control summernote" rows="3"></textarea>
                    </div>

                    <div id="practiceArea" style="display:none; background: #eee; padding: 15px; border-radius: 5px;">
                        <div class="form-group">
                            <label>Bahasa Pemrograman</label>
                            <select name="compiler_lang" id="compilerLang" class="form-control">
                                <option value="php">PHP</option>
                                <option value="javascript">JavaScript</option>
                                <option value="python">Python</option>
                                <option value="html">HTML</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kerangka Kode (Boilerplate)</label>
                            <div id="aceEditor" style="height: 200px; border: 1px solid #ccc;"></div>
                            <input type="hidden" name="practice_snippet" id="snippetInput">
                        </div>
                    </div>

                    <div id="theoryArea">
                        <div class="form-group">
                            <label>Video URL (Youtube)</label>
                            <input type="url" name="video_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Materi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection


@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function() {
        console.log("Sistem Script CRUD Materi Berhasil Dimuat!");

        // 1. Inisialisasi Summernote
        $('.summernote').summernote({
            height: 150,
            placeholder: 'Tulis materi atau instruksi di sini...'
        });

        // 2. Setup Ace Editor
        var editor;
        if ($('#aceEditor').length > 0) {
            editor = ace.edit("aceEditor");
            editor.setTheme("ace/theme/monokai");
            editor.session.setMode("ace/mode/php");
        }

        // 3. EVENT LISTENER: Tombol Tambah Materi
        $(document).on('click', '.btn-add-content', function(e) {
            e.preventDefault(); 
            var moduleId = $(this).data('id');
            var moduleTitle = $(this).data('title');
            console.log("Tombol diklik! Module ID: " + moduleId); 

            // Reset form
            $('#formContent')[0].reset();
            $('.summernote').summernote('code', '');

            // Reset dropdown ke default
            $('#contentTypeSelect').val('theory').trigger('change');

            // Set URL Action secara dinamis
            var url = "{{ route('contents.store', ':id') }}";
            url = url.replace(':id', moduleId);
            $('#formContent').attr('action', url);

            // Ganti Judul Modal & Tampilkan
            $('#modalContentTitle').text('Tambah Materi ke: ' + moduleTitle);
            $('#modalAddContent').modal('show');
        });

        // 4. EVENT LISTENER: Toggle Tipe Konten
        $('#contentTypeSelect').change(function() {
            var selectedType = $(this).val();
            if(selectedType === 'practice') {
                $('#practiceArea').slideDown();
                $('#theoryArea').slideUp();
                if(editor) editor.resize();
            } else {
                $('#practiceArea').slideUp();
                $('#theoryArea').slideDown();
            }
        });

        // 5. Ganti Bahasa Compiler
        $('#compilerLang').change(function() {
            var lang = $(this).val();
            if(editor) editor.session.setMode("ace/mode/" + lang);
        });

        // 6. Submit Form
        $('#formContent').on('submit', function() {
            if(editor) {
                var code = editor.getValue();
                $('#snippetInput').val(code);
            }
        });
    });
</script>
@endsection