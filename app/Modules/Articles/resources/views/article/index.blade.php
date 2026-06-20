@extends('shared::partials.app')
@section('content-card-title', 'Articles')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Articles</h2>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Create Article
            </a>
        </div>

        <div class="table-responsive">
            <table id="articles-table" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Date</th>
                        <th>Languages</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#articles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Enables responsiveness for the table
                    autoWidth: false, // Prevents DataTable from calculating column width
                    ajax: "{{ route('admin.articles.index') }}", // Ajax route to fetch data
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'category',
                            name: 'category'
                        },
                        {
                            data: 'tags',
                            name: 'tags',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'date',
                            name: 'date',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'translated_languages',
                            name: 'translated_languages',
                            render: function(data, type, row) {
                                if (!data.includes('{{ app()->getLocale() }}')) {
                                    return `<span style="background-color:orange;">${data}</span>`;
                                }
                                return data;
                            }
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    columnDefs: [{
                            targets: "_all",
                            defaultContent: ""
                        },
                        {
                            targets: [0],
                            width: "5%"
                        },
                        {
                            targets: [1],
                            width: "30%"
                        },
                        {
                            targets: [2],
                            width: "10%"
                        },
                        {
                            targets: [3],
                            width: "15%"
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
                            width: "20%"
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
                    lengthMenu: [10, 25, 50, 100, 200, 1000],
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ]
                });
            });
        </script>
    @endpush

@endsection
