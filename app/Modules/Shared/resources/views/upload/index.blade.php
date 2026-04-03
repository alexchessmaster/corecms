@extends('shared::partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

@if (isset($path))
    <div class="alert alert-success" role="alert">
        {{ config('app.url') . $path }}
    </div>
@endif
<form action="{{ route('admin.upload.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <input type="submit" value="Upload" class="btn btn-info">
</form>

@endsection
