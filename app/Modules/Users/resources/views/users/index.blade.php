@extends('admin.partials.app')
@section('content-card-title', 'User')
@section('content-card-body')

    <a class="btn btn-success" href="{{ route('admin.users.create') }}">Create</a>
    <hr>
    <table class="table" id="users-table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NAME</th>
                <th scope="col">EMAIL</th>
                <th scope="col">ROLE</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
    </table>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.users.index') }}", // Ajax route to fetch data
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'role', name: 'role' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    columnDefs: [
                        { targets: "_all", defaultContent: "" }, // Prevents undefined cells
                        { targets: [0], width: "10%" },
                        { targets: [1], width: "20%" },
                        { targets: [2], width: "40%" }, // Set widths of the columns
                        { targets: [3], width: "10%" },
                        { targets: [4], width: "20%" },
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
                    order: [[0, 'desc']]
                });
            });
        </script>
    @endpush

@endsection
