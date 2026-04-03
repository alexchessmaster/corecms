@extends('shared::partials.app')
@section('content-card-title', 'Book Authors')
@section('content-card-body')

    <a class="btn btn-success" href="{{ route('admin.book-authors.create') }}">Create</a>
    <hr>

    <table id="authors-table" class="table table-striped">
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
                $('#authors-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.book-authors.index') }}',
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
                    ],
                    pagingType: "full_numbers",
                    lengthMenu: [10, 25, 50, 100, 200, 1000],
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ]
                });
            });
        </script>
    @endpush

@endsection
