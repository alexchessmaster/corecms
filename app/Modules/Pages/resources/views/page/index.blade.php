@extends('shared::partials.app')
@section('content-card-title', 'pages')
@section('content-card-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Pages</h2>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create New Page
            </a>
        </div>

        <div class="table-responsive">
            <table id="pages-table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#pages-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.pages.index') }}", // Ajax route to fetch data
                    columns: [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'slug',
                            name: 'slug'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    language: {
                        search: "Filter records:",
                        paginate: {
                            first: "<<",
                            last: ">>",
                            next: ">",
                            previous: "<"
                        }
                    },
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
