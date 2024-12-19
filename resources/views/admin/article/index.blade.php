@extends('admin.partials.app')
@section('content-card-title', 'Articles')
@section('content-card-body')

<div class="container">
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary mb-3">Create Article</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Tags</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <tr>
                <td>{{ empty($article->getTranslation('title', app()->getLocale(), false)) ? '-Not translated- (' . $article->getTranslation('title', app()->getLocale()) . ')' : $article->getTranslation('title', app()->getLocale(), false) }}</td>
                <td>{{ $article->category->getTranslation('name', app()->getLocale()) }}</td>
                <td>
                    @foreach($article->tags as $tag)
                        <span class="badge bg-secondary">{{ $tag->getTranslation('name', app()->getLocale()) }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" style="display:inline;">
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