@extends('admin.partials.app')
@section('content-card-title', 'Books')
@section('content-body')

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Books</h2>
            <a href="{{ route('admin.books.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Book
            </a>
        </div>

        <div class="table-responsive">
            <table id="books-table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Book Genre</th>
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
        {{-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#books-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.books.index') }}", // Ajax route to fetch data
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'title', name: 'title' },
                        { data: 'book_genre', name: 'book_genre' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    columnDefs: [
                        { targets: "_all", defaultContent: "" }, // Prevents undefined cells
                        { targets: [0], width: "5%" },
                        { targets: [1], width: "30%" }, // Set widths of the columns
                        { targets: [2], width: "20%" },
                        { targets: [3], width: "25%" },
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
