@extends('layouts.admin_course')

@section('title', 'Edit Bab')

@section('content')
<div class="col-md-6 offset-md-3 pt-4">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Judul Bab</h3>
        </div>
        <form action="{{ route('modules.update', $module->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Judul Bab</label>
                    <input type="text" name="title" class="form-control" value="{{ $module->title }}" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update Bab</button>
                <a href="{{ route('courses.show', $module->course_id) }}" class="btn btn-default">Kembali</a>
            </div>
        </form>
    </div>
</div>
@stop
