@extends('admin.partials.app')
@section('content-card-title', 'News')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage News</h2>
            <a href="{{ route('admin.news.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add a new news
            </a>
        </div>

        <div class="table-responsive">
            <table id="news-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Translated languages</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#news-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.news.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'title',
                            name: 'title',
                            render: function(data, type, row) {
                                if (!data || data.includes('-Not translated-')) {
                                    return `<span style="background-color:orange;">${data}</span>`;
                                }
                                return data;
                            }
                        },
                        {
                            data: 'category',
                            name: 'category'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'translated_languages',
                            name: 'translated_languages'
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
                            width: "50%"
                        },
                        {
                            targets: [2],
                            width: "10%"
                        },
                        {
                            targets: [3],
                            width: "10%"
                        },
                        {
                            targets: [4],
                            width: "5%"
                        },
                        {
                            targets: [5],
                            width: "30%"
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
