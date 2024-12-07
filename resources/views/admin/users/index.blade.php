@extends('admin.partials.app')
@section('content-card-title', 'User')
@section('content-card-body')

    <a class="btn btn-success" href="{{ route('admin.users.create') }}">Create</a>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">NAME</th>
                <th scope="col">EMAIL</th>
                <th scope="col">ROLE</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ !empty($user->is_admin) ? "Admin" : 'Normal user' }}</td>
                    <td>
                        <a class="btn btn-info" href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" id="" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="display: inline"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    </script>

@endsection
