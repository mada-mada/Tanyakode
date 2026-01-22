@extends('layouts.admin_course')

@section('title', 'Kelola Kurikulum')

@section('content')
<div class="container-fluid pt-4">
    {{-- Header Info Kursus --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if($course->thumbnail_url)
                        <img src="{{ Storage::url($course->thumbnail_url) }}" alt="Course" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 150px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h2 class="fw-bold">{{ $course->title }}</h2>
                    <div class="mb-3">
                        <span class="badge badge-{{ $course->level == 'pemula' ? 'success' : ($course->level == 'menengah' ? 'warning' : 'danger') }} px-3 py-2">
                            {{ ucfirst($course->level) }}
                        </span>
                        <span class="ms-2 fw-bold text-primary">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    </div>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-toggle="modal" data-target="#modalAddModule">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Bab Baru
                    </button>
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 ms-2">
                        <i class="fas fa-edit me-1"></i> Edit Info
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Bab (Modules) --}}
    @forelse($course->modules as $module)
    <div class="card card-outline card-dark mb-3 shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title fw-bold">
                Bab {{ $module->sort_order }}: {{ $module->title }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <form action="{{ route('modules.destroy', $module->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-tool text-danger" onclick="return confirm('Hapus Bab beserta isinya?')"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 80px">No</th>
                        <th style="width: 120px">Tipe</th>
                        <th>Judul Materi</th>
                        <th class="text-end pe-4" style="width: 200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($module->contents as $content)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td>
                            {{-- PENYESUAIAN: Gunakan 'type' bukan 'content_type' --}}
                            @if($content->type == 'theory')
                                <span class="badge bg-info text-white">Teori</span>
                            @else
                                <span class="badge bg-warning text-dark">Praktek</span>
                            @endif
                        </td>
                        <td>{{ $content->title }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-sm btn-outline-warning border-0">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('contents.destroy', $content->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus materi?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3 small">Belum ada materi di bab ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 bg-light border-top">
                <button class="btn btn-default btn-sm btn-block btn-add-content fw-bold" 
                        data-id="{{ $module->id }}" 
                        data-title="{{ $module->title }}">
                    <i class="fas fa-plus-circle me-1 text-primary"></i> Tambah Materi Ke Bab Ini
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
        <p class="text-muted">Kursus ini belum memiliki materi. Silakan buat bab pertama.</p>
    </div>
    @endforelse
</div>

{{-- Modal Tambah Bab (Module) --}}
<div class="modal fade" id="modalAddModule">
    <div class="modal-dialog">
        <form action="{{ route('modules.store', $course->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h4 class="modal-title fw-bold">Tambah Bab Baru</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body"><input type="text" name="title" class="form-control" placeholder="Judul Bab..." required></div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Bab</button></div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Materi (Content) --}}
<div class="modal fade" id="modalAddContent">
    <div class="modal-dialog modal-lg">
        <form id="formContent" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title fw-bold" id="modalContentTitle">Tambah Materi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group"><label class="fw-bold">Judul Materi</label><input type="text" name="title" class="form-control" required></div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="fw-bold">Tipe Konten</label>
                                <select name="type" id="contentTypeSelect" class="form-control"> {{-- Gunakan 'type' --}}
                                    <option value="theory">Teori (Teks/Video)</option>
                                    <option value="practice">Praktek (Koding)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group"><label class="fw-bold">Isi Materi / Instruksi</label><textarea name="content_body" class="form-control summernote"></textarea></div>
                    <div id="practiceArea" style="display:none; background: #f4f4f4; padding: 20px; border-radius: 10px;">
                        <div class="form-group"><label class="fw-bold">Bahasa Pemrograman</label>
                            <select name="compiler_lang" id="compilerLang" class="form-control">
                                <option value="php">PHP</option><option value="javascript">JavaScript</option><option value="python">Python</option><option value="html">HTML</option>
                            </select>
                        </div>
                        <div class="form-group"><label class="fw-bold">Kerangka Kode (Boilerplate)</label><div id="aceEditor" style="height: 250px; border: 1px solid #ddd;"></div><input type="hidden" name="practice_snippet" id="snippetInput"></div>
                    </div>
                    <div id="theoryArea" class="form-group"><label class="fw-bold">Video URL (Youtube)</label><input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..."></div>
                </div>
                <div class="modal-footer border-0 pt-0"><button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Materi</button></div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({ height: 180 });

        var editor = ace.edit("aceEditor");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");

        $(document).on('click', '.btn-add-content', function() {
            var moduleId = $(this).data('id');
            var moduleTitle = $(this).data('title');
            $('#formContent')[0].reset();
            $('.summernote').summernote('code', '');
            
            // Set Dynamic URL
            var url = "{{ route('contents.store', ':id') }}".replace(':id', moduleId);
            $('#formContent').attr('action', url);

            $('#modalContentTitle').text('Tambah Materi ke: ' + moduleTitle);
            $('#modalAddContent').modal('show');
            setTimeout(() => { editor.resize(); }, 500);
        });

        $('#contentTypeSelect').change(function() {
            if($(this).val() === 'practice') {
                $('#practiceArea').slideDown(); $('#theoryArea').slideUp();
            } else {
                $('#practiceArea').slideUp(); $('#theoryArea').slideDown();
            }
        });

        $('#compilerLang').change(function() {
            editor.session.setMode("ace/mode/" + $(this).val());
        });

        $('#formContent').on('submit', function() {
            $('#snippetInput').val(editor.getValue());
        });
    });
</script>
@stop