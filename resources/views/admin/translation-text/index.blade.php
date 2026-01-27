@extends('admin.partials.app')
@section('content-card-title', 'Translation Texts')
@section('content-body')

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
