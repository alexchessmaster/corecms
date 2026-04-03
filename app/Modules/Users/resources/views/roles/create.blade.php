@extends('admin.partials.app')
@section('content-card-title', 'Create Role')
@section('content-body')

<div class="container">
    <div class="mb-3">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        @include('admin.roles.form')
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Role
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection