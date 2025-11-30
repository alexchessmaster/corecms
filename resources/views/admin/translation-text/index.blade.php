@extends('admin.partials.app')
@section('content-card-title', 'Translation Texts')
@section('content-body')

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Translation Texts</h2>
            <a href="{{ route('admin.translation-texts.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create
            </a>
        </div>

        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>Key</th>
                        <th>Text</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
        <style>
            /* Ensure text wraps and doesn't push columns off-screen */
            #table td {
                word-wrap: break-word;
                word-break: break-word;
                white-space: normal !important;
            }
            
            /* Set max-width for text columns to prevent overflow */
            #table td:nth-child(1),
            #table td:nth-child(2) {
                max-width: 300px;
            }
            
            /* Keep actions column visible and compact */
            #table td:nth-child(3) {
                min-width: 100px;
                white-space: nowrap;
            }
            
            /* Mobile optimization */
            @media (max-width: 768px) {
                #table td:nth-child(1),
                #table td:nth-child(2) {
                    max-width: 150px;
                }
            }
        </style>

        <script>
            $(document).ready(function() {
                $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.translation-texts.index') }}", // Ajax route to fetch data
                    columns: [{
                            data: 'key',
                            name: 'key',
                            width: '30%'
                        },
                        {
                            data: 'text',
                            name: 'text',
                            width: '50%',
                            render: function(data, type, row) {
                                // Truncate very long text for display
                                if (type === 'display' && data && data.length > 100) {
                                    return '<span title="' + data + '">' + data.substr(0, 100) + '...</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            width: '20%'
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
                    lengthMenu: [[25, 100, 500, -1], [25, 100, 500, "All"]],
                    pageLength: 25,
                    columnDefs: [
                        { targets: [0, 1], className: 'text-break' }
                    ]
                });
            });
        </script>
    @endpush

@endsection
