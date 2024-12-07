@extends('admin.partials.app')

@section('content-card-title', 'Dashboard')

@section('content-card-body')
<div class="container">
    <h1>Widgets</h1>
    <a href="{{ route('admin.widgets.create') }}" class="btn btn-primary">Add New Widget</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Name</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($widgets as $widget)
            <tr>
                <td>{{ $widget->id }}</td>
                <td>{{ $widget->key }}</td>
                <td>{{ $widget->name }}</td>
                <td>{{ $widget->active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.widgets.edit', $widget->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.widgets.destroy', $widget->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection