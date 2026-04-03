{{-- admin.categories.edit --}}
@extends('shared::partials.app')
@section('content-card-title', 'Categories')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Categories</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Category
            </a>
        </div>

        <div class="table-responsive">
            <table id="articles-table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Parent</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#articles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.categories.index') }}", // Ajax route to fetch data
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'parent', name: 'parent' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
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
