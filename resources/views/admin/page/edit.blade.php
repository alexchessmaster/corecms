@extends('admin.partials.app')
@section('content-card-title', 'Pages')
@section('content-card-body')

    @push('styles')
        
    <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    @endpush


    {{-- <div class="form-group">
        <label>Color picker with addon:</label>

        <div class="input-group my-colorpicker2d colorpicker-element" data-colorpicker-id="2">
            <input type="text" class="form-control" data-original-title="" title="">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-square" style="color: rgb(119, 27, 27);"></i>
                </span>
            </div>
        </div>
    </div> --}}

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

    <div class="modal fade" id="widgetEditModal" tabindex="-1" role="dialog" aria-labelledby="widgetEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="widgetEditModalLabel">Fields</h5>
                    <button type="button" class="close" id="edit-fieldValue-close-btn-x" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id>
                    <form action="" id="edit-fieldValue-form">
                        <div id="field-edit-container"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="edit-fieldValue-close-btn">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="edit-fieldValue-save-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

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

        const createHiddenInformationInput = (pageId, widgetPosition) => {
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-12');
            
            const pageIdInputEl = document.createElement('input');
            pageIdInputEl.name = 'page-id';
            pageIdInputEl.type = 'hidden';
            pageIdInputEl.value = pageId;

            const widgetPositionInputEl = document.createElement('input');
            widgetPositionInputEl.name = 'widget-position';
            widgetPositionInputEl.type = 'hidden';
            widgetPositionInputEl.value = widgetPosition;

            const languageInputEl = document.createElement('input');
            languageInputEl.name = 'language';
            languageInputEl.type = 'hidden';
            languageInputEl.value = currentLanguage;

            divEl.appendChild(pageIdInputEl);
            divEl.appendChild(widgetPositionInputEl);
            divEl.appendChild(languageInputEl);

            document.getElementById('field-edit-container').appendChild(divEl);
        };
        
        const createTextInput = (item) => {
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-12');

            const labelEl = document.createElement('label');
            labelEl.textContent = item.user_note;
            labelEl.classList.add('form-label');
            
            const inputEl = document.createElement('input');
            inputEl.name = 'field_id-' + item.id + '-field_value_id-' + item?.vf?.id;
            inputEl.classList.add('form-control');
            inputEl.value = item?.vf?.value;

            divEl.appendChild(labelEl);
            divEl.appendChild(inputEl);

            document.getElementById('field-edit-container').appendChild(divEl);

            // <div class="mb-3">
            //     <label for="name" class="form-label">Name</label>
            //     <input type="text" name="name" id="name" class="form-control"
            //         value="{{ old('name', $widget->name ?? '') }}" required>
            // </div>
        };

        const createColorPickerInput = (item) => {
            const divGroup = document.createElement('div');
            divGroup.classList.add('form-group', 'col-md-12', 'mt-3');

            const labelEl = document.createElement('label');
            labelEl.textContent = item.label || 'Color picker with addon:';
            divGroup.appendChild(labelEl);

            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group', 'my-colorpicker2', 'colorpicker-element');

            const inputEl = document.createElement('input');
            inputEl.type = 'text';
            inputEl.classList.add('form-control');
            inputEl.title = item.title || '';
            inputEl.value = item?.vf?.value;
            inputEl.name = 'field_id-' + item.id + '-field_value_id-' + item?.vf?.id;
            inputGroup.appendChild(inputEl);

            const addonDiv = document.createElement('div');
            addonDiv.classList.add('input-group-append');

            const iconSpan = document.createElement('span');
            iconSpan.classList.add('input-group-text');
            
            const icon = document.createElement('i');
            icon.classList.add('fas', 'fa-square');
            icon.style.color = item?.vf?.value || 'rgb(119, 27, 27)'; // Set the color if provided

            iconSpan.appendChild(icon);
            addonDiv.appendChild(iconSpan);
            inputGroup.appendChild(addonDiv);
            divGroup.appendChild(inputGroup);
            document.getElementById('field-edit-container').appendChild(divGroup);
            
            if (typeof $(inputGroup).colorpicker === 'function') {
                $(inputGroup).colorpicker();
            }

            // <div class="form-group">
            //     <label>Color picker with addon:</label>
            //     <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
            //         <input type="text" class="form-control" data-original-title="" title="">
            //         <div class="input-group-append">
            //             <span class="input-group-text"><i class="fas fa-square"></i></span>
            //         </div>
            //     </div>
            // </div>
        }

        // const createSelectInput = (item) => {
        //     console.log('guguguuuuuuuuuuuuuuuu');
        //     const divEl = document.createElement('div');
        //     divEl.classList.add('form-group');

        //     const labelEl = document.createElement('label');
        //     labelEl.setAttribute('for', 'exampleFormControlSelect1');
        //     labelEl.textContent = item.labelText || 'Example select';

        //     const selectEl = document.createElement('select');
        //     selectEl.classList.add('form-control');
        //     selectEl.id = 'exampleFormControlSelect1';

        //     // Parse options and set the first as selected
        //     const optionsArray = item.options.split(',');
        //     optionsArray.forEach((optionValue, index) => {
        //         const optionEl = document.createElement('option');
        //         optionEl.textContent = optionValue;
        //         if (index === 0) {
        //             optionEl.selected = true;
        //         }
        //         selectEl.appendChild(optionEl);
        //     });

        //     divEl.appendChild(labelEl);
        //     divEl.appendChild(selectEl);

        //     document.getElementById('field-edit-container').appendChild(divEl);
        // };


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
            editBtn.setAttribute('data-toggle', 'modal');
            editBtn.setAttribute('data-target', '#widgetEditModal');
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
            editBtn.addEventListener('click', async () => {
                emptyFieldEditContainer();
                // console.log('widget dsjfkj3j3fj3j',widget)
                const widgetPosition = widget.pivot.position;
                const pageId = '{!! $page->id !!}';
                // Add your edit functionality here
                // get widget fields
                // TODO: we should go to the fieldValue here: not widget
                let allFields = await fetch(`/api/widgets/${widget.id}`)
                    .then(res => res.json())
                    .then(data => {
                        return data.fields
                        // .forEach(item => {
                        //     console.log('item', item)
                        // })
                    });

                console.log('allFields', allFields)
                
                let allFieldValues = await fetch(`/api/pages/${pageId}/widget-position/${widgetPosition}/field-values/${currentLanguage}`)
                    .then(res => res.json())
                    .then(data => {
                        // console.log('dataaaaa', data);
                        return data.fieldValues;
                    });

                console.log('allFieldValues', allFieldValues)

                let allFieldsWithValues = [];
                allFields.forEach(field => {
                    allFieldValues.forEach((fieldValue) => {
                        if(field.id === fieldValue.field_id) {
                            // console.log('field', field)
                            const exists = allFieldsWithValues.some(item => item.id === field.id);
                            if(!exists) {
                                field.vf = fieldValue
                                // TODO: we should take care of repeated data  
                            }
                        }
                    })
                    allFieldsWithValues.push(field)
                });

                createHiddenInformationInput(pageId, widgetPosition);
                allFieldsWithValues.forEach((item, index) => {
                    // console.log('itemmmm', item)
                    switch(item.type){
                        case 'text':
                            createTextInput(item);
                            break;
                        case 'color':
                            createColorPickerInput(item);
                            break;
                        // case 'select_option':
                        //     createSelectInput(item);
                        //     break;

                    }
                });

                console.log('allFieldsWithValues', allFieldsWithValues)
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
                        btnEl.classList.add('btn', 'btn-primary', 'mb-3');
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

        const emptyFieldEditContainer = () => {
            document.getElementById('field-edit-container').innerHTML = '';
        };

        document.getElementById('edit-fieldValue-save-btn').addEventListener('click', (e) => {
            const form = document.getElementById('edit-fieldValue-form');
            const formData = {};
            form.querySelectorAll('input').forEach(input => {
                // Save each input's name and value to the formData object
                formData[input.name] = input.value;
            });
            fetch("/api/pages/widget-position/update-field-value", {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData)
            })
                .then(res => res.json())
                .then(data => {
                    console.log('form saved !!!!!!!!', data)
                    // emptyFieldEditContainer();
                });

            console.log('save clicked', formData)
        });

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

        const titleInput = document.getElementById('page_title');
        const slugInput = document.getElementById('page_slug');
        const currentLanguage = '{!! App::currentLocale() !!}';

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

    @push('scripts')
        
    <script src="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $('.my-colorpicker2').colorpicker()
    </script>
    @endpush
@endsection
