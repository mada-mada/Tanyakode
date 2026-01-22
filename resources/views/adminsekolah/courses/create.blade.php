@extends('layouts.admin_course')

@section('title', 'Buat Kursus Baru')

@section('content')
<div class="col-md-8 offset-md-2 pt-4">
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title fw-bold">Informasi Kursus Baru</h3>
        </div>
        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group mb-3">
                    <label class="fw-bold">Judul Kursus</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: Logika Pemrograman Python">
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Deskripsi Kursus</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan apa yang akan dipelajari siswa...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Thumbnail Kursus</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: jpg, png. Maks: 2MB.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Tingkat Kesulitan</label>
                            <select name="level" id="level_select" class="form-control">
                                <option value="pemula" {{ old('level') == 'pemula' ? 'selected' : '' }}>Pemula (Gratis)</option>
                                <option value="menengah" {{ old('level') == 'menengah' ? 'selected' : '' }}>Menengah (Berbayar)</option>
                                <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert (Berbayar)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="price_section" style="{{ old('level') == 'pemula' || !old('level') ? 'display:none;' : '' }}">
                            <label class="fw-bold">Harga (Rp)</label>
                            <input type="number" name="price" id="price_input" class="form-control" value="{{ old('price', 0) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Hadiah Merchandise?</label>
                            <select name="has_merchandise_reward" id="merch_select" class="form-control">
                                <option value="0" {{ old('has_merchandise_reward') == '0' ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('has_merchandise_reward') == '1' ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="merch_name_section" style="{{ old('has_merchandise_reward') == '1' ? '' : 'display:none;' }}">
                            <label class="fw-bold">Nama Merchandise</label>
                            <input type="text" name="merchandise_name" class="form-control" value="{{ old('merchandise_name') }}" placeholder="Contoh: Kaos TanyaKode">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('courses.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Kursus</button>
            </div>
        </form>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        $('#level_select').change(function() {
            if($(this).val() === 'pemula') {
                $('#price_section').slideUp();
                $('#price_input').val(0);
            } else {
                $('#price_section').slideDown();
            }
        });

        $('#merch_select').change(function() {
            if($(this).val() == '1') {
                $('#merch_name_section').slideDown();
            } else {
                $('#merch_name_section').slideUp();
            }
        });
    });
</script>
@stop
@endsection