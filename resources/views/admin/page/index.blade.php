@extends('admin.partials.app')
@section('content-card-title', 'Pages')
@section('content-card-body')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}
<a href="{{ route('admin.pages.create') }}" class="btn btn-success">Create</a>
<hr>
<table class="table">
    <thead>
        <tr>
            <th scope="col">TITLE</th>
            <th scope="col">URL</th>
            <th scope="col">ACTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pages as $page)
            <tr>
                <td>{{ $page->title }}</td>
                <td><a href="/{{ $page->slug }}" target="_blank">{{ $page->slug }}</a></td>
                <td>
                    <a class="btn btn-info" href="{{ route('admin.pages.edit', $page->id) }}"><i class="fa fa-edit"></i></a>
                    <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}" id="" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="display: inline"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
