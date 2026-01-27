@extends('admin.partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

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
