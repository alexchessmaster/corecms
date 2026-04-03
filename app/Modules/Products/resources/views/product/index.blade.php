@extends('shared::partials.app')
@section('content-card-title', 'Products')
@section('content-body')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary">Manage Products</h2>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add a new product
            </a>
        </div>

        <div class="table-responsive">
            <table id="products-table" class="table table-striped table-hover">
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
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.products.index') }}",
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
                            data: 'status',
                            name: 'status'
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
                            width: "40%"
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
                            width: "20%"
                        },
                        {
                            targets: [5],
                            width: "15%"
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
