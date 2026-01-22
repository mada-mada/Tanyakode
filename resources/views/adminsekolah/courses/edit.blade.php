@extends('layouts.admin_course')

@section('title', 'Edit Kursus')

@section('content')
<div class="col-md-8 offset-md-2 pt-4">
    <div class="card card-warning card-outline shadow">
        <div class="card-header">
            <h3 class="card-title fw-bold">Edit Kursus: {{ $course->title }}</h3>
        </div>
        <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Judul Kursus</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Deskripsi Kursus</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label class="fw-bold">Thumbnail Kursus</label>
                    <div class="mb-3">
                        @if($course->thumbnail_url)
                            <img src="{{ Storage::url($course->thumbnail_url) }}" alt="Thumbnail" class="img-thumbnail shadow-sm" style="max-height: 180px;">
                            <p class="text-muted small mt-1"><i>Gambar saat ini. Unggah baru untuk mengganti.</i></p>
                        @endif
                    </div>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Tingkat Kesulitan</label>
                            <select name="level" id="level_select" class="form-control">
                                <option value="pemula" {{ old('level', $course->level) == 'pemula' ? 'selected' : '' }}>Pemula (Gratis)</option>
                                <option value="menengah" {{ old('level', $course->level) == 'menengah' ? 'selected' : '' }}>Menengah (Berbayar)</option>
                                <option value="expert" {{ old('level', $course->level) == 'expert' ? 'selected' : '' }}>Expert (Berbayar)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="price_section" style="{{ old('level', $course->level) == 'pemula' ? 'display:none;' : '' }}">
                            <label class="fw-bold">Harga (Rp)</label>
                            <input type="number" name="price" id="price_input" class="form-control" value="{{ old('price', $course->price) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Hadiah Merchandise?</label>
                            <select name="has_merchandise_reward" id="merch_select" class="form-control">
                                <option value="0" {{ old('has_merchandise_reward', $course->has_merchandise_reward) == 0 ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('has_merchandise_reward', $course->has_merchandise_reward) == 1 ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="merch_name_section" style="{{ old('has_merchandise_reward', $course->has_merchandise_reward) == 1 ? '' : 'display:none;' }}">
                            <label class="fw-bold">Nama Merchandise</label>
                            <input type="text" name="merchandise_name" class="form-control" value="{{ old('merchandise_name', $course->merchandise_name) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('courses.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold px-4">Update Kursus</button>
            </div>
        </form>
    </div>
</div>
@stop