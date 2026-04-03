@extends('shared::partials.app')
@section('content-card-title', 'Edit Role')
@section('content-body')

    <div class="container">
        <div class="mb-3">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Roles
            </a>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            @include('users::roles.form')

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

@endsection
