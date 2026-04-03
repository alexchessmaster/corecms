@extends('shared::partials.app')

@section('title', 'Modify Comment')

@section('content-card-body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modify Comment</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Return to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('comments::comment.form', ['comment' => $comment])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
