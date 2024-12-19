@extends('admin.partials.app')
@section('content-card-title', 'Categories')
@section('content-card-body')

<div class="container">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Create Category</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ empty($category->getTranslation('name', app()->getLocale(), false)) ? '-Not translated- (' . $category->getTranslation('name', app()->getLocale()) . ')' : $category->getTranslation('name', app()->getLocale(), false)  }}</td>
                <td>{{ $category->parent ? $category->parent->getTranslation('name', app()->getLocale()) : 'None' }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
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