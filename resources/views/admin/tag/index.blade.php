@extends('admin.partials.app')
@section('content-card-title', 'Tags')
@section('content-card-body')

<div class="container">
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary mb-3">Create Tag</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
            <tr>
                <td>{{ empty($tag->getTranslation('name', app()->getLocale(), false)) ? '-Not translated- (' . $tag->getTranslation('name', app()->getLocale()) . ')' : $tag->getTranslation('name', app()->getLocale(), false) }}</td>
                <td>
                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection