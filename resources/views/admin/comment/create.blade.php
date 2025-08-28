@extends('admin.partials.app')

@section('title', 'Add New Comment')

@section('content-card-body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add New Comment</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Return to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('admin.comment.form', ['comment' => null])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
