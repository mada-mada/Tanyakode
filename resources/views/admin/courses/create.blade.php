@extends('layouts.admin_course')

@section('title', 'Buat Kursus')

@section('content')
<div class="col-md-6 offset-md-3 pt-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Informasi Kursus</h3>
        </div>
        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Judul Kursus</label>
                    <input type="text" name="title" class="form-control" required placeholder="Contoh: Belajar Laravel Dasar">
                </div>

            <div class="form-group">
                    <label>Deskripsi Kursus</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi lengkap mengenai materi yang akan dipelajari..."></textarea>
                </div>

                <div class="form-group">
                    <label>Thumbnail Kursus</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                </div>

                <div class="form-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="level" id="level_select" class="form-control">
                        <option value="pemula">Pemula (Gratis)</option>
                        <option value="menengah">Menengah (Berbayar)</option>
                        <option value="expert">Expert (Berbayar)</option>
                    </select>
                </div>

                <div class="form-group" id="price_section" style="display:none;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" id="price_input" class="form-control" value="0">
                </div>

                <div class="form-group">
                    <label>Hadiah Merchandise?</label>
                    <select name="has_merchandise_reward" id="merch_select" class="form-control">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>

                <div class="form-group" id="merch_name_section" style="display:none;">
                    <label>Nama Merchandise</label>
                    <input type="text" name="merchandise_name" class="form-control">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('courses.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</div>

@section('js')
<script>
    // Logika Harga vs Level
    $('#level_select').change(function() {
        if($(this).val() === 'pemula') {
            $('#price_section').slideUp();
            $('#price_input').val(0);
        } else {
            $('#price_section').slideDown();
        }
    });

    // Logika Merchandise
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