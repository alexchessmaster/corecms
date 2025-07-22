@extends('admin.partials.app')
@section('content-card-title', 'Edit Article')
@section('content-body')

    @push('styles')
        <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
        <style>
            .tox-promotion {
                visibility: hidden;
            }

            .tox .tox-editor-container {
                border: 1px solid #d2d2d2 !important;
                /* Set your desired color */
            }

            .tox .tox-edit-area iframe {
                border: 1px solid #e7e7e7 !important;
                /* Set your desired color */
            }
        </style>
    @endpush

    <div class="container">
        <a href="{{ route('admin.articles.create') }}" class="btn btn-success"><strong style="">+ </strong>Create</a>
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            @include('admin.article.form')


            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <br>
    <br>

    @include('admin.partials.add-widget-form')

@endsection
