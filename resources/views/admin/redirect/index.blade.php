@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <style>
        table.dataTable,
        .card-body {
            width: 100% !important;
            overflow-x: auto;
        }
    </style>

    <h2>Redirects List</h2>

    <a href="{{ route('admin.redirects.create') }}" class="btn btn-success mb-3">Add New Redirect</a>

    <table id="redirects-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>From</th>
                <th>To</th>
                <th>Language</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        {{-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#redirects-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.redirects.index') }}", // Ajax route to fetch data
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'from',
                            name: 'from'
                        },
                        {
                            data: 'to',
                            name: 'to'
                        },
                        {
                            data: 'language',
                            name: 'language'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                            targets: "_all",
                            defaultContent: ""
                        }, // Prevents undefined cells
                        {
                            targets: [0],
                            width: "1%"
                        }, // Set widths of the columns
                        {
                            targets: [1],
                            width: "20%"
                        },
                        {
                            targets: [2],
                            width: "20%"
                        },
                        {
                            targets: [3],
                            width: "5%"
                        },
                        {
                            targets: [4],
                            width: "10%"
                        },
                        {
                            targets: [5],
                            width: "10%"
                        },
                        {
                            targets: [5],
                            width: "10%"
                        },
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
                    lengthMenu: [5, 25, 50, 100, 200, 1000],
                    pageLength: 5,
                });
            });
        </script>
    @endpush

@endsection
