@extends('admin.partials.app')
@section('content-title', 'Page builder')
@section('style')
    <style>
        #sortable-list2 {
            border: 1px solid #a0a0a0;
        }
        .card {
            margin-bottom: 0;
        }

        .list-group-item {
            padding: 2px;
            padding-bottom: -10px
        }
        .list-group-item div{
            user-select: none;
        }

        #sortable-list1 .fa-edit,
        #sortable-list1 .fa-trash {
            display: none;
        }

        #sortable-list2 .fa-edit,
        #sortable-list2 .fa-trash {
            display: inherit;
        }

        #sortable-list1 .fa.fa-edit,
        #sortable-list2 .fa.fa-edit {
            padding: 0 10px;
        }

        #sortable-list1 .fa.fa-trash,#sortable-list2 .fa.fa-trash, 
        #sortable-list1 .fa.fa-edit,#sortable-list2 .fa.fa-edit{
            color: white;
        }

        .card-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-content: center;
        }
        .handle{
            padding: 0 10px 0 0;
            cursor: move;
        }
    </style>
@endsection

@section('content-body')
    <div class="row">
        <div class="col-sm-12">
            <form id="page_form">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="hidden" name="page_id" id="page_id" value="{{ $page->id }}">
                        <div class="form-group">
                            <label for="title">Page title</label>
                            <input type="text" class="form-control" id="title" aria-describedby="" placeholder="Page title"
                                value="{{ $page->title === 'Draft' ? '' : $page->title }}">
                            <small id="" class="form-text text-muted">The title of the page.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="url">Page slug</label>
                            <input type="text" class="form-control" id="url" aria-describedby="" placeholder="Page URL"
                                value="{{ $page->slug === 'draft' ? '' : $page->slug }}">
                            <small class="form-text text-muted"><a id="visit-page" target="_blank"
                                    href="{{ $page->slug === 'draft' ? '' : ( $page->slug === '/' ? $page->slug : '/page/' . $page->slug) }}"
                                    target="blank">{{ $page->slug === 'draft' ? '' : ( $page->slug === '/' ? $page->slug : '/page/' . $page->slug) }}</a></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <br>
            <div>Drag widgets to this box for building the page:</div>
            <div>
                <span id="saving-text" style="color: #FFC107; display:none;"><i class="fa fa-spinner fa-spin"></i>
                    saving</span>
                <span id="saved-text" style="color: #218838; display:none;"><i class="fa fa-check-circle"></i> saved</span>
                &nbsp;
            </div>
            <div style="overflow-y: auto; max-height: calc( 100vh - 220px ); background-color:white; min-height:70px">
                <ul id="sortable-list2" class="list-group" style="min-height: 70px">
                    @foreach ($widgets as $widget)
                        <li class="list-group-item" data-value="{{ $widget->key }}" data-name="{{ $widget->name }}"
                            data-widget_id="{{ $widget->id }}">
                            <div class="card text-white 
                            @php
                                if(in_array($widget->key, ['text-one-column', 'text-two-column', 'text-three-column'])){
                                    echo 'bg-secondary';
                                }else if(in_array($widget->key, ['text-free-style-one-column', 'text-free-style-two-column', 'text-free-style-three-column'])){
                                    echo 'bg-dark';
                                }else if(in_array($widget->key, ['image-one-column', 'image-two-column', 'image-three-column'])){
                                    echo 'bg-info';
                                }else if (in_array($widget->key, ['code'])){
                                    echo 'bg-danger';
                                }else if(in_array($widget->key, ['space'])){
                                    echo 'bg-light';
                                }else if(in_array($widget->key, ['block-starts', 'block-ends'])) {
                                    echo 'bg-purple';
                                }else {
                                    echo 'bg-success';
                                }
                            @endphp
                            "
                                style="">
                                <div class="card-header">
                                    <div class="card-container">
                                        <div>
                                            <i class="fas fa-arrows-alt handle"></i>
                                            {{ $widget->name }}
                                        </div>
                                        <div>{{ !empty($widget->user_note) ? 'Note: ' . $widget->user_note : ''}}</div>
                                        <div>
                                            <a href="{{ route('admin.widgets.edit', $widget->id) }}" target="_blank">
                                                <i class="fa fa-edit"
                                                style="{{ in_array($widget->key, ['space']) ? 'color:gray' : '' }}"
                                                ></i>
                                            </a>
                                            <a href="">
                                                <i class="fa fa-trash js-remove" 
                                                style="{{ in_array($widget->key, ['space']) ? 'color:gray' : '' }}"
                                                ></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="page-preview-button">
                    <label class="custom-control-label" for="page-preview-button">Preview</label>
                </div>
                <div id="page-preview" style="border: 1px solid gray;min-height:70px;max-height:50vh;display:none;overflow:hidden">
                    <iframe src="" id="page-preview-ifame" frameborder="0" style="width: 200% !important;height: 100vh !important;transform: scale(0.5);transform-origin: 0 0;"></iframe>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <br>
            <div>Available widgets:</div>
            <br>
            <div style="overflow-y: auto; max-height: calc( 100vh - 220px )">
                <ul id="sortable-list1" class="list-group">
                    @php
                        // List of all available widgets
                        $directory = resource_path('views/admin/widgets/widget-templates');
                        $files = \File::files($directory);
                        $allWidgets = collect();

                        foreach ($files as $file) {
                            $fileName = str_replace('.blade.php', '', $file->getFilename());
                            $widget = new stdClass;
                            $widget->key = $fileName;
                            $fileName = str_replace('-', ' ', $fileName);
                            $widget->name = ucwords($fileName);

                            $widget->order = 0;
                            if($widget->key === 'code'){
                                $widget->order = 15;
                            }else if($widget->key === 'space'){
                                $widget->order = 10;
                            }else if($widget->key === 'block-starts'){
                                $widget->order = 8;
                            }else if($widget->key === 'block-ends'){
                                $widget->order = 9;
                            }else if($widget->key === 'block-ends'){
                                $widget->order = 6;
                            }else if($widget->key === 'text-one-column'){
                                $widget->order = 1;
                            }else if($widget->key === 'text-two-column'){
                                $widget->order = 2;
                            }else if($widget->key === 'text-three-column'){
                                $widget->order = 3;
                            }else if($widget->key === 'image-one-column'){
                                $widget->order = 4;
                            }else if($widget->key === 'image-two-column'){
                                $widget->order = 5;
                            }else if($widget->key === 'image-three-column'){
                                $widget->order = 6;
                            }

                            $allWidgets[] = $widget;
                        }
                        $allWidgets = $allWidgets->sortBy('order');
                    @endphp
                    @foreach ($allWidgets as $widget)
                        <li class="list-group-item" data-value="{{ $widget->key }}" data-name="{{ $widget->name }}">
                            <div class="card text-white 
                            @php
                                if(in_array($widget->key, ['text-one-column', 'text-two-column', 'text-three-column'])){
                                    echo 'bg-secondary';
                                }else if(in_array($widget->key, ['text-free-style-one-column', 'text-free-style-two-column', 'text-free-style-three-column'])){
                                    echo 'bg-dark';
                                }else if(in_array($widget->key, ['image-one-column', 'image-two-column', 'image-three-column'])){
                                    echo 'bg-info';
                                }else if (in_array($widget->key, ['code'])){
                                    echo 'bg-danger';
                                }else if(in_array($widget->key, ['space'])){
                                    echo 'bg-light';
                                }else if(in_array($widget->key, ['block-starts', 'block-ends'])) {
                                    echo 'bg-purple';
                                }else {
                                    echo 'bg-success';
                                }
                            @endphp
                            " style="">
                                <div class="card-header">
                                    <div class="card-container">
                                        <div>
                                            <i class="fas fa-arrows-alt handle"></i>
                                            {{ $widget->name }}
                                        </div>
                                        <div>
                                            <a href="" target="_blank">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="">
                                                <i class="fa fa-trash js-remove"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    
                    {{-- <li class="list-group-item" data-value="background-image-h2-effect" data-name="Background image h2 effect">
                        <div class="card text-white bg-success" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Background image h2 effect
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="iphone-15-pro" data-name="Iphone 15 pro">
                        <div class="card text-white bg-success" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Iphone 15 pro
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="team" data-name="Team">
                        <div class="card text-white bg-success" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Team
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="text-one-column" data-name="Text - one column">
                        <div class="card text-white bg-secondary" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text - one column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-input-item" data-value="text-two-column" data-name="Text - two column">
                        <div class="card text-white bg-secondary" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text - two column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="text-three-column" data-name="Text - three column">
                        <div class="card text-white bg-secondary" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text - three column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" data-value="text-free-style-one-column"
                        data-name="Text free style - one column">
                        <div class="card text-white bg-dark" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text free style - one column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-input-item" data-value="text-free-style-two-column"
                        data-name="Text free style - two column">
                        <div class="card text-white bg-dark" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text free style - two column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="text-free-style-three-column"
                        data-name="Text free style - three column">
                        <div class="card text-white bg-dark" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Text free style - three column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" data-value="image-one-column" data-name="Image - one column">
                        <div class="card text-white bg-info" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Image - one column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-input-item" data-value="image-two-column"
                        data-name="Image - two column">
                        <div class="card text-white bg-info" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Image - two column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="image-three-column" data-name="Image - three column">
                        <div class="card text-white bg-info" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Image - three column
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" data-value="space" data-name="Space">
                        <div class="card text-white bg-light" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Space
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" data-value="block-starts" data-name="Block starts">
                        <div class="card text-white bg-purple" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Block starts
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" data-value="block-ends" data-name="Block ends">
                        <div class="card text-white bg-purple" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Block ends
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="list-group-item" data-value="code" data-name="Code">
                        <div class="card text-white bg-danger" style="">
                            <div class="card-header">
                                <div class="card-container">
                                    <div>
                                        <i class="fas fa-arrows-alt handle"></i>
                                        Code
                                    </div>
                                    <div>
                                        <a href="" target="_blank">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="">
                                            <i class="fa fa-trash js-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                     --}}


                    {{-- <li class="list-group-item">
                        <div class="card text-white bg-success" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Success card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="card text-white bg-danger" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Danger card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="card text-white bg-warning" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Warning card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="card text-white bg-info" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Info card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="card bg-light" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Light card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="card text-white bg-dark" style="">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Dark card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                    of the card's content.</p>
                            </div>
                        </div>
                    </li> --}}

                </ul>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-sm-6 mt-4">
            <button class="btn btn-success">Save</button>
        </div>
    </div> --}}

@endsection
@section('script')

    <script>
        // saving and saved text

        const showSavingMessage = (isSaving = true) => {
            console.log('showSavingMessage function', isSaving);
            const saving = document.getElementById('saving-text');
            const saved = document.getElementById('saved-text');
            if (isSaving) {
                saving.style.display = 'inline';
                saved.style.display = 'none';
            } else {
                saving.style.display = 'none';
                saved.style.display = 'inline';
            }
        };
    </script>

    <script src="/sortable/script.min.js"></script>

    <script>
        let page_id = document.getElementById('page_id').value;
        let page_title = document.getElementById('title').value;
        let page_url = document.getElementById('url').value;

        const slugify = str => {
            console.log('slugify function', str)
            if(str === '/'){
                return '/';
            }
            
            return str.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        const sortable1 = new Sortable(document.getElementById('sortable-list1'), {
            group: {
                name: 'shared',
                pull: 'clone',
                put: false // Do not allow items to be put into this list
            },
            animation: 150,
            sort: false, // To disable sorting: set sort to false
            handle: '.handle'
        });

        const sortable2 = new Sortable(document.getElementById('sortable-list2'), {
            group: 'shared',
            animation: 150,
            handle: '.handle',
            filter: ".js-remove, .js-edit",
            onFilter: function(evt) {
                var item = evt.item,
                    ctrl = evt.target;

                if (Sortable.utils.is(ctrl, ".js-remove")) { // Click on remove button

                    ///
                    let widget_id = item.getAttribute('data-widget_id')
                    showSavingMessage();
                    fetch(`/admin/widgets/${widget_id}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": "{{ csrf_token() }}"
                        }
                    }).then((response) => {
                        // console.log('response', response);
                        return response.json();
                    }).then(data => {
                        console.log('data', data);
                        if (data.status === 'ok') {
                            console.log('Item removed !! 3234223');

                            onChangeSave();
                        }
                    });
                    ///

                    item.parentNode.removeChild(item); // remove sortable item

                } else if (Sortable.utils.is(ctrl, ".js-edit")) { // Click on edit link
                    // ...
                }
            },

            // Element dragging ended
            onSort: function( /**Event*/ evt) {
                // console.log('my list changed! haha');
                // console.log('evt', evt)
                var item = evt.item; // dragged HTMLElement
                if (!item.hasAttribute('data-widget_id')) {
                    showSavingMessage();
                    fetch('/admin/widgets', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            page_id,
                            key: item.getAttribute('data-value'),
                            name: item.getAttribute('data-name')
                        })
                    }).then((response) => {
                        console.log(response);
                        return response.json();
                    }).then(data => {
                        // console.log('dataaaaa', data);
                        if (data.status === 'ok') {
                            // console.log('dataaaaa item', item);
                            item.setAttribute('data-widget_id', data.widget_id)
                            const editWidgetLink = item.querySelector('.fa.fa-edit').parentNode.href = `/admin/widgets/${data.widget_id}/edit`;
                            console.log('Item created !! 4234223', data);

                            onChangeSave();
                        }
                    });
                } else {
                    onChangeSave();
                }


                // console.log('evt.item', itemEl);
                // console.log('evt.to', evt.to);    // target list
                // console.log('evt.from', evt.from);  // previous list
                // console.log('evt.oldIndex', evt.oldIndex);  // element's old index within old parent
                // console.log('evt.newIndex', evt.newIndex);  // element's new index within new parent
                // console.log('evt.oldDraggableIndex', evt.oldDraggableIndex); // element's old index within old parent, only counting draggable elements
                // console.log('evt.newDraggableIndex', evt.newDraggableIndex); // element's new index within new parent, only counting draggable elements
                // console.log('evt.clone', evt.clone) // the clone element
                // console.log('evt.pullMode', evt.pullMode);  // when item is in another sortable: `"clone"` if cloning, `true` if moving
            },
        });

        let onChangeSave = () => {
            let list = document.getElementById('sortable-list2');
            // console.log('list',list)
            // console.log('list.children',list.children)
            let widget_ids = [];
            for (const child of list.children) {
                // console.log(child.getAttribute('data-value'));
                widget_ids.push(child.getAttribute('data-widget_id'));
            }

            showSavingMessage();
            fetch('/admin/widgets/sort', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    ids: widget_ids
                })
            }).then(response => {
                console.log(response);
                return response.json();
            }).then(data => {
                console.log('data', data)

                showSavingMessage(false);

                //reload iframe
                pagePreviwReload();
            });

            console.log('widget_ids', widget_ids);
        }

        const pageTitle = document.getElementById('title');
        const pageUrl = document.getElementById('url');
        const savePageForms = (event) => {
            if (pageUrl.value === '') {
                pageUrl.value = slugify(pageTitle.value);
            } else {
                pageUrl.value = slugify(pageUrl.value);
            }

            showSavingMessage();
            fetch("{{ route('admin.pages.update', $page->id) }}", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    title: pageTitle.value,
                    slug: pageUrl.value
                })
            }).then(response => {
                console.log('dfjskdjsfk response', response);
                return response.json();
            }).then(data => {
                console.log(data.message)

                showSavingMessage(false);
            }).catch(e => {
                console.log('An error occurred while updating the page in widget-builder.blade.php', e)
            })
            const pagePrefix = pageUrl.value === '/' ? '' : '/page/';
            document.getElementById('visit-page').href = pagePrefix + pageUrl.value
            document.getElementById('visit-page').innerHTML = pagePrefix + pageUrl.value
        };
        pageTitle.addEventListener('focusout', savePageForms);
        pageUrl.addEventListener('focusout', savePageForms);

        // preview page
        const pagePreviewIframe = document.getElementById('page-preview-ifame');
        const pagePreviwReload = () => {
            const url = "{{ env('APP_URL') }}" + "{{ $page->slug === '/' ? $page->slug : '/page/' . $page->slug }}";
            pagePreviewIframe.src = url;
            pagePreviewIframe.src = pagePreviewIframe.src;
        };
        

        const pagePreviewButton = document.getElementById('page-preview-button');
        const pagePreview = document.getElementById('page-preview');
        pagePreviewButton.addEventListener('change', ()=>{
            localStorage.setItem('pagePreviewInputChecked', pagePreviewButton.checked);
            if(pagePreviewButton.checked){
                pagePreview.style.display = 'block';
                pagePreviwReload();
            }else{
                pagePreview.style.display = 'none';
            }
        });
        pagePreviewButton.checked = localStorage.getItem('pagePreviewInputChecked') === 'true';
        if(pagePreviewButton.checked){
            pagePreview.style.display = 'block';
            pagePreviwReload();
        }
    </script>

@endsection
