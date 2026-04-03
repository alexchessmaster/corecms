@extends('resources.views.admin.partials.app')
@section('content-card-title', 'Edit Permission')
@section('content-body')

    <div class="container">
        <div class="mb-3">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Permissions
            </a>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>

        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')

            @include('permissions.form')

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Permission
                </button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

@endsection
