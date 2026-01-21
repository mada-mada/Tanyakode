@extends('layouts.admin_course')

@section('title', 'Edit Kursus')

@section('content')
<div class="col-md-6 offset-md-3 pt-4">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Kursus: {{ $course->title }}</h3>
        </div>
        <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <div class="card-body">
                <div class="form-group">
                    <label>Judul Kursus</label>
                    <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
                </div>

                <div class="form-group">
                    <label>Thumbnail Kursus</label>
                    <div class="mb-2">
                        @if($course->thumbnail_url)
                            <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="Thumbnail Lama" class="img-thumbnail" style="max-height: 150px;">
                            <p class="text-muted text-sm mt-1"><i>Gambar saat ini. Biarkan kosong jika tidak ingin mengubah.</i></p>
                        @else
                            <p class="text-muted text-sm"><i>Belum ada thumbnail.</i></p>
                        @endif
                    </div>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB.</small>
                </div>

                <div class="form-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="level" id="level_select" class="form-control">
                        <option value="pemula" {{ $course->level == 'pemula' ? 'selected' : '' }}>Pemula (Gratis)</option>
                        <option value="menengah" {{ $course->level == 'menengah' ? 'selected' : '' }}>Menengah (Berbayar)</option>
                        <option value="expert" {{ $course->level == 'expert' ? 'selected' : '' }}>Expert (Berbayar)</option>
                    </select>
                </div>

                <div class="form-group" id="price_section" style="{{ $course->level == 'pemula' ? 'display:none;' : '' }}">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" id="price_input" class="form-control" value="{{ $course->price }}">
                </div>

                <div class="form-group">
                    <label>Hadiah Merchandise?</label>
                    <select name="has_merchandise_reward" id="merch_select" class="form-control">
                        <option value="0" {{ $course->has_merchandise_reward == 0 ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ $course->has_merchandise_reward == 1 ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

                <div class="form-group" id="merch_name_section" style="{{ $course->has_merchandise_reward == 1 ? '' : 'display:none;' }}">
                    <label>Nama Merchandise</label>
                    <input type="text" name="merchandise_name" class="form-control" value="{{ $course->merchandise_name }}">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update Kursus</button>
                <a href="{{ route('courses.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</div>

@section('js')
<script>
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
</script>
@stop
@endsection