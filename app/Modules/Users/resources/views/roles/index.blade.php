@extends('shared::partials.app')
@section('content-card-title', 'Roles')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Roles</h2>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Role
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Users Count</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td><strong>{{ $role->name }}</strong></td>
                            <td>
                                @if ($role->permissions->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No permissions</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->users->count() }} users</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No roles found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $roles->links() }}
        </div>
    </div>

@endsection
