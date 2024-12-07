@extends('admin.partials.app')
@section('content-card-title', 'Pages')
@section('content-card-body')

    <div class="row">
        <div class="col-sm-12">
            <form id="page_form">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="hidden" name="page_id" id="page_id" value="{{ !empty($page) ? $page->id : '' }}">
                        <div class="form-group">
                            <label for="page_title">Page title</label>
                            <input type="text" class="form-control" id="page_title" aria-describedby=""
                                placeholder="Page title" value="{{ !empty($page) ? $page->title : '' }}">
                            <small id="" class="form-text text-muted">The title of the page.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="page_slug">Page slug</label>
                            <input type="text" class="form-control" id="page_slug" aria-describedby=""
                                placeholder="Page URL" value="{{ !empty($page) ? $page->slug : '' }}">
                            <small class="form-text text-muted"><a id="visit-page" target="_blank"
                                    href="{{ !empty($page) ? $page->slug : '' }}">{{ !empty($page) ? $page->slug : '' }}</a></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>

    <div id="widgets-container">
    </div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#widgetModal">
        <i class="fa fa-plus"></i> Add Widget
    </button>

    <style>
        .widget-option {
            border: 2px solid rgb(201, 201, 255);
            cursor: pointer;
            transition: border-color 0.1s;
        }

        .widget-option:hover {
            border-color: #63afff;
        }

        .widget-option.selected {
            border-color: #007bff;
        }
    </style>

    <!-- Modal Widget Box -->
    <div class="modal fade" id="widgetModal" tabindex="-1" role="dialog" aria-labelledby="widgetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="widgetModalLabel">Widget Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $numberOfWidgetsInARow = 3;
                        $i = 0;
                    @endphp
                    @foreach ($allWidgets as $widget)
                        @if ($i % $numberOfWidgetsInARow === 0)
                            <div class="row" id="widgetOptions">
                        @endif

                        <div class="col-md-4">
                            <div class="widget-option card" data-value="{{ $widget->id }}">
                                <img src="{{ $widget->image }}" alt="" class="card-img-top">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $widget->name }}</h5>
                                </div>
                            </div>
                        </div>

                        @if ($i % $numberOfWidgetsInARow === $numberOfWidgetsInARow - 1)
                </div>
                @endif
                @php
                    $i++;
                @endphp
                @endforeach

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmSelection">Add</button>
        </div>
    </div>
    </div>
    </div>

    @if ($i > 1 && $i % $numberOfWidgetsInARow !== $numberOfWidgetsInARow - 1)
    </div>
    @endif


    <script>
        // Add event listeners for the widget options
        document.querySelectorAll('.widget-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove 'selected' class from all options
                document.querySelectorAll('.widget-option').forEach(opt => opt.classList.remove(
                    'selected'));
                // Add 'selected' class to clicked option
                this.classList.add('selected');
            });
        });

        // const widgetList = [];

        // Handle the Confirm button click
        document.getElementById('confirmSelection').addEventListener('click', function() {
            const selectedOption = document.querySelector('.widget-option.selected');
            if (selectedOption) {
                const value = selectedOption.getAttribute('data-value');

                fetch('/api/widgets/attach', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            pageId: '{{ $page->id }}',
                            widgetId: value,
                            addWidgetPosition: addWidgetButtonPosition,
                        })
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        console.log('updated', data)

                        refreshWidgetList();
                    })


            } else {
                alert('Please select a widget.');
            }
        });

        
        const widgetContainer = document.getElementById('widgets-container');
        const createWidget = widget => {
            // Create the outer div (col-md-4)
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-12');

            // Create the widget option div (widget-option card)
            const widgetOption = document.createElement('div');
            widgetOption.classList.add('widget-option', 'card');
            widgetOption.setAttribute('data-value', widget.id);

            // Create the img element for the widget image
            const imgEl = document.createElement('img');
            imgEl.src = widget.image; // Use the widget's image URL
            imgEl.alt = 'Option 1';
            imgEl.classList.add('card-img-top');

            // Create the card body div
            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body', 'text-center');

            // Create the title (h5 element)
            const cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.textContent = widget.name; // Use the widget's name

            // Create the Edit button with an icon
            const editBtn = document.createElement('button');
            editBtn.classList.add('btn', 'btn-primary', 'mr-2'); // Styling button
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit'; // FontAwesome edit icon

            // Create the Delete button with an icon
            const deleteBtn = document.createElement('button');
            deleteBtn.classList.add('btn', 'btn-danger'); // Styling button
            deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Delete'; // FontAwesome delete icon

            // Append the buttons to the card body
            cardBody.appendChild(cardTitle);
            cardBody.appendChild(editBtn);
            cardBody.appendChild(deleteBtn);

            // Create a div to hold the card body and the image (keep the layout tidy)
            widgetOption.appendChild(imgEl);
            widgetOption.appendChild(cardBody);
            divEl.appendChild(widgetOption);

            // Append the new widget div to the container
            widgetContainer.appendChild(divEl);

            // Add event listeners for the buttons (example: log actions)
            editBtn.addEventListener('click', () => {
                console.log('Edit button clicked for widget ID:', widget.pivot.position);
                // Add your edit functionality here
            });

            deleteBtn.addEventListener('click', () => {
                console.log('Delete button clicked for widget ID:', widget.pivot.position);
                fetch('/api/widgets/detach', {
                    method: "PATCH",
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        positionId: widget.pivot.position,
                        pageId: '{{ $page->id }}'
                    })
                })
                .then(response => response.json)
                .then(data => {
                    console.log('widget deleted', data);
                    refreshWidgetList();
                })
                // Add your delete functionality here
            });
        }
        let addWidgetButtonPosition = null;
        const refreshWidgetList = () => {
            widgetContainer.innerHTML = null;
            fetch('/api/pages/{!! $page->id !!}')
                .then(response => {
                    return response.json()
                })
                .then(data => {
                    data.page.widgets.forEach((item, index) => {

                        const btnEl = document.createElement('button');
                        btnEl.classList.add('btn', 'btn-primary');
                        btnEl.setAttribute('data-toggle', 'modal');
                        btnEl.setAttribute('data-target', '#widgetModal');
                        const iconEl = document.createElement('i');
                        iconEl.classList.add('fa', 'fa-plus');
                        btnEl.appendChild(iconEl);
                        btnEl.appendChild(document.createTextNode(' Add Widget'));
                        widgetContainer.appendChild(btnEl);

                        createWidget(item);
                    })
                    const buttons = document.querySelectorAll('button.btn.btn-primary[data-toggle="modal"][data-target="#widgetModal"]');
                    buttons.forEach((button, index) => {
                        button.setAttribute('data-position', index);

                        // Add a click event listener to log the data-position
                        button.addEventListener('click', () => {
                            // console.log(`data-position: ${button.getAttribute('data-position')}`);
                            addWidgetButtonPosition = button.getAttribute('data-position');
                            console.log('addWidgetButtonPosition', addWidgetButtonPosition)
                        });
                    });
                })
        }
        refreshWidgetList();
    </script>

    <script>
        // Close button inside the modal footer
        document.querySelector('.btn-secondary[data-dismiss="modal"]').addEventListener('click', () => {
            $('#widgetModal').modal('hide');
        });

        // "X" button in the modal header
        document.querySelector('.close[data-dismiss="modal"]').addEventListener('click', () => {
            $('#widgetModal').modal('hide');
        });
    </script>
    <script>
        const slugify = str => {
            console.log('slugify function', str)
            if (str === '/') {
                return '/';
            }

            return str.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        const titleInput = document.getElementById('page_title'); // ID of the title input
        const slugInput = document.getElementById('page_slug'); // ID of the slug input
        const currentLanguage = '{!! App::currentLocale() !!}';
        // Listen for input changes in the title field


        const updatePostUrlOrSlug = () => {
            console.log('hererererer 1111111')
            const title = document.getElementById('page_title');
            const slug = document.getElementById('page_slug');
            console.log(title, slug)
            fetch('/api/pages/{!! $page->id !!}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        title: title.value,
                        slug: slug.value,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('page updated!', data)
                    
                    document.getElementById('page_title').value = data.page.title[currentLanguage];
                    document.getElementById('page_slug').value = data.page.slug[currentLanguage];
                });
        }
        titleInput.addEventListener('focusout', updatePostUrlOrSlug);
        slugInput.addEventListener('focusout', updatePostUrlOrSlug);
    </script>

@endsection
