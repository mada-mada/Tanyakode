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
                {{-- Judul Kursus --}}
                <div class="form-group">
                    <label>Judul Kursus</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
                </div>

                {{-- Deskripsi Kursus (PERBAIKAN DI SINI) --}}
                <div class="form-group">
                    <label>Deskripsi Kursus</label>
                    <textarea name="description" class="form-control" rows="4" 
                        placeholder="Masukkan deskripsi lengkap mengenai materi yang akan dipelajari...">{{ old('description', $course->description) }}</textarea>
                </div>
                
                {{-- Thumbnail --}}
                <div class="form-group">
                    <label>Thumbnail Kursus</label>
                    <div class="mb-2">
                        @if($course->thumbnail_url)
                            {{-- Gunakan Storage::url untuk keamanan path --}}
                            <img src="{{ Storage::url($course->thumbnail_url) }}" alt="Thumbnail Lama" class="img-thumbnail" style="max-height: 150px;">
                            <p class="text-muted text-sm mt-1"><i>Gambar saat ini. Biarkan kosong jika tidak ingin mengubah.</i></p>
                        @else
                            <p class="text-muted text-sm"><i>Belum ada thumbnail.</i></p>
                        @endif
                    </div>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB.</small>
                </div>

                {{-- Level --}}
                <div class="form-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="level" id="level_select" class="form-control">
                        <option value="pemula" {{ old('level', $course->level) == 'pemula' ? 'selected' : '' }}>Pemula (Gratis)</option>
                        <option value="menengah" {{ old('level', $course->level) == 'menengah' ? 'selected' : '' }}>Menengah (Berbayar)</option>
                        <option value="expert" {{ old('level', $course->level) == 'expert' ? 'selected' : '' }}>Expert (Berbayar)</option>
                    </select>
                </div>

                {{-- Harga --}}
                <div class="form-group" id="price_section" style="{{ old('level', $course->level) == 'pemula' ? 'display:none;' : '' }}">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" id="price_input" class="form-control" value="{{ old('price', $course->price) }}">
                </div>

                {{-- Merchandise --}}
                <div class="form-group">
                    <label>Hadiah Merchandise?</label>
                    <select name="has_merchandise_reward" id="merch_select" class="form-control">
                        <option value="0" {{ old('has_merchandise_reward', $course->has_merchandise_reward) == 0 ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('has_merchandise_reward', $course->has_merchandise_reward) == 1 ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

                <div class="form-group" id="merch_name_section" style="{{ old('has_merchandise_reward', $course->has_merchandise_reward) == 1 ? '' : 'display:none;' }}">
                    <label>Nama Merchandise</label>
                    <input type="text" name="merchandise_name" class="form-control" value="{{ old('merchandise_name', $course->merchandise_name) }}">
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('courses.index') }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold">Update Kursus</button>
            </div>
        </form>
    </div>
</div>
@endsection