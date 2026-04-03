@extends('shared::partials.app')
@section('content-card-title', 'Create Permission')
@section('content-body')

<div class="container">
    <div class="mb-3">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Permissions
        </a>
    </div>

    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        @include('users::permissions.form')

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Permission
            </button>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
