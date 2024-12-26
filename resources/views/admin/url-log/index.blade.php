@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <style>
        table.dataTable, .card-body {
            width: 100% !important;
            overflow-x: auto;
        }

        td,
        th {
            max-width: 200px;
            overflow: hidden;
            /* text-overflow: ellipsis; */
            white-space: wrap;
        }

        /* td:active,
        th:active {
            overflow: scroll;
            text-overflow: initial;
            white-space: pre-wrap;
            background-color: red;
        } */
    </style>
    {{-- <div class="container"> --}}
        <h1>Logs</h1>
        <table id="logs-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>URL</th>
                    <th>Params</th>
                    <th>HTTP Method</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Referrer</th>
                    <th>Is Robot</th>
                    <th>Created At</th>
                </tr>
            </thead>
        </table>
    {{-- </div> --}}


    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        {{-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#logs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.url-logs.index') }}", // Ajax route to fetch data
                    columns: [{
                            data: 'user_id',
                            name: 'user_id'
                        },
                        {
                            data: 'url',
                            name: 'url'
                        },
                        {
                            data: 'params',
                            name: 'params'
                        },
                        {
                            data: 'http_method',
                            name: 'http_method'
                        },
                        {
                            data: 'ip_address',
                            name: 'ip_address'
                        },
                        {
                            data: 'user_agent',
                            name: 'user_agent'
                        },
                        {
                            data: 'referrer',
                            name: 'referrer'
                        },
                        {
                            data: 'is_robot',
                            name: 'is_robot'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                    columnDefs: [{
                            targets: "_all",
                            defaultContent: ""
                        }, // Prevents undefined cells
                        {
                            targets: [0],
                            width: "0%"
                        }, // Set widths of the columns
                        {
                            targets: [1],
                            width: "20%"
                        },
                        {
                            targets: [2],
                            width: "5%"
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
                            targets: [6],
                            width: "10%"
                        },
                        {
                            targets: [7],
                            width: "10%"
                        },
                        {
                            targets: [8],
                            width: "10%"
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
                    lengthMenu: [5, 25, 50, 100, 200, 1000],
                    pageLength: 5,
                });
            });
        </script>
    @endpush

@endsection
