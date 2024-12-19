@extends('admin.partials.app')
@section('content-card-title', ucfirst($pageType) . 's')
@section('content-card-body')

<a href="{{ $pageType === 'page' ? route('admin.pages.create') : route('admin.templates.create') }}" class="btn btn-success">Create</a>
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
                    <a class="btn btn-info" href="{{ $pageType === 'page' ? route('admin.pages.edit', $page->id) : route('admin.templates.edit', $page->id) }}"><i class="fa fa-edit"></i></a>
                    <form method="POST" action="{{ $pageType === 'page' ? route('admin.pages.destroy', $page->id) : route('admin.templates.destroy', $page->id) }}" id="" style="display: inline">
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
