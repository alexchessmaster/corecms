{{-- admin.comments.index --}}
@extends('admin.partials.app')
@section('content-card-title', 'Comments')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Comments</h2>
            <a href="{{ route('admin.comments.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Comment
            </a>
        </div>

        <div class="table-responsive">
            <table id="comments-table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>ID</th>
                        <th>Content Type</th>
                        <th>Content ID</th>
                        <th>Author</th>
                        <th>Comment Preview</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#comments-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.comments.index') }}", // Ajax route to fetch data
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'commentable_type',
                            name: 'commentable_type'
                        },
                        {
                            data: 'commentable_id',
                            name: 'commentable_id'
                        },
                        {
                            data: 'user_id',
                            name: 'user_id'
                        },
                        {
                            data: 'content',
                            name: 'content',
                            render: function(data) {
                                return data.length > 100 ? data.substr(0, 100) + '...' : data;
                            }
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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
                    lengthMenu: [25, 50, 100, 200, 1000],
                    pageLength: 25,
                });
            });
        </script>
    @endpush

@endsection
