@extends('shared::partials.app')
@section('content-card-title', 'News Authors')
@section('content-card-body')

    <a class="btn btn-success" href="{{ route('admin.news-authors.create') }}">Create</a>
    <hr>

    <table id="table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Nationality</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.news-authors.index') }}',
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'nationality',
                            name: 'nationality'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush

@endsection
