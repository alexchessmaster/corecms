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
                            name: 'key'
                        },
                        {
                            data: 'text',
                            name: 'text'
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
                });
            });
        </script>
    @endpush

@endsection
