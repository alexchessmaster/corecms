@extends('admin.partials.app')
@section('content-card-title', 'Pages')
@section('content-card-body')

    @push('styles')
        
    <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <style>
        .tox-promotion {
            visibility: hidden;
        }
        .tox .tox-editor-container {
            border: 1px solid #d2d2d2 !important;  /* Set your desired color */
        }
        .tox .tox-edit-area iframe {
            border: 1px solid #e7e7e7 !important;  /* Set your desired color */
        }
    </style>
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
                                placeholder="Page title" value="{{ !empty($page) ? $page->getTranslation('title', app()->getLocale(), false) : '' }}">
                            <small id="" class="form-text text-muted">The title of the page.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="page_slug">Page slug</label>
                            <input type="text" class="form-control" id="page_slug" aria-describedby=""
                                placeholder="Page URL" value="{{ !empty($page) ? $page->slug : '' }}">
                            <small class="form-text text-muted"><a id="visit-page" target="_blank"
                                    href="{{ !empty($page) ? $page->slug : '' }}">{{ !empty($page) ? $page->getTranslation('slug', app()->getLocale(), false) : '' }}</a></small>
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
                    <form action="" id="edit-fieldValue-form" enctype="multipart/form-data">
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
        
        const createInputTextInput = (item) => {
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-12');

            const labelEl = document.createElement('label');
            labelEl.textContent = item.user_note + ':';
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
            labelEl.textContent = item.user_note + ':' || 'Color picker with addon:';
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

        const createSelectInput = (item) => {
            const divEl = document.createElement('div');
            divEl.classList.add('form-group', 'col-md-12');

            const labelEl = document.createElement('label');
            labelEl.setAttribute('for', 'alignmentSelect');
            labelEl.textContent = 'Alignment';

            const selectEl = document.createElement('select');
            selectEl.classList.add('form-control');
            selectEl.id = 'alignmentSelect';
            selectEl.name = 'field_id-' + item.id + '-field_value_id-' + item?.vf?.id;

            // Add options for alignment: left, center, right
            ['left', 'center', 'right'].forEach(optionValue => {
                const optionEl = document.createElement('option');
                optionEl.textContent = optionValue;
                optionEl.value = optionValue;
                if (optionValue === item?.vf?.value) {
                    optionEl.selected = true;
                }
                selectEl.appendChild(optionEl);
            });

            divEl.appendChild(labelEl);
            divEl.appendChild(selectEl);

            document.getElementById('field-edit-container').appendChild(divEl);

            //  <div class="form-group col-md-12">
            //      <label for="alignmentSelect">Alignment</label>
            //      <select class="form-control" 
            //      id="alignmentSelect" 
            //      name="field_id-10-field_value_id-7">
            //          <option value="left">left</option>  
            //          <option value="center">center</option>
            //          <option value="right">right</option>
            //      </select>
            //  </div>
        };

        const createTextareaInput = (item, size = 'normal') => {
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-12');

            const labelEl = document.createElement('label');
            labelEl.textContent = item.user_note + ':';
            labelEl.classList.add('form-label');
            
            const textareaEl = document.createElement('textarea');
            textareaEl.name = 'field_id-' + item.id + '-field_value_id-' + item?.vf?.id;
            textareaEl.classList.add('form-control');
            textareaEl.value = item?.vf?.value || '';
            textareaEl.id = 'textarea-' + item.id; // Add a unique ID for TinyMCE initialization

            divEl.appendChild(labelEl);
            divEl.appendChild(textareaEl);

            document.getElementById('field-edit-container').appendChild(divEl);

            // Destroy existing TinyMCE instances if any
            tinymce.remove(`#textarea-${item.id}`);

            // Initialize TinyMCE for the new textarea
            let tinyConfig = {};
            if(size === 'text') {
                tinyConfig = {
                    selector: `#textarea-` + item.id, // Replace with the ID or class of your target element
                    menubar: false,
                    toolbar: 'bold italic underline | forecolor backcolor', // Added color options to toolbar
                    height: 110,
                    forced_root_block: false, // Prevents wrapping content in <p>
                    valid_elements: '*[*]', // Allow any inline elements (if applicable)
                    content_style: 'white-space: nowrap;', // Forces single-line editing
                    statusbar: false,
                    setup: (editor) => {
                        editor.on('keydown', (e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault(); // Prevent multi-line entry
                            }
                        });
                    }
                }
            }
            if(size === 'small') {
                tinyConfig = {
                    selector: `#textarea-` + item.id, // Replace with the ID or class of your target element
                    menubar: false,
                    toolbar: 'bold italic underline | forecolor backcolor', // Added color options to toolbar
                    height: 230,
                    forced_root_block: false, // Prevents wrapping content in <p>
                    valid_elements: '*[*]', // Allow any inline elements (if applicable)
                    content_style: 'white-space: nowrap;', // Forces single-line editing
                    statusbar: false,
                    newline_behavior: 'linebreak',
                }
            }
            if(size === 'large') {
                tinyConfig = {
                    selector: `#textarea-` + item.id,
                    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
                    // toolbar_mode: 'floating',
                    // menubar: false,
                }
            }
            tinymce.init(tinyConfig);
        }

        const createFileInput = (item) => {
            const divEl = document.createElement('div');
            divEl.classList.add('mb-3');

            const labelEl = document.createElement('label');
            labelEl.setAttribute('for', 'file-input');
            labelEl.textContent = 'File';
            labelEl.classList.add('form-label');

            const inputEl = document.createElement('input');
            inputEl.type = 'file';
            inputEl.name = 'field_id-' + item.id + '-field_value_id-' + item?.vf?.id;
            inputEl.id = 'file-input' + item.id;
            inputEl.classList.add('form-control');

            const imgEl = document.createElement('img');
            imgEl.classList.add('mt-2');
            imgEl.style.maxWidth = '100px';

            const aEl = document.createElement('a');
            aEl.classList.add('mt-2');
            aEl.innerHTML = 'Link to download';

            const infoEl = document.createElement('div');
            infoEl.classList.add('mt-2');
            
            divEl.appendChild(labelEl);
            divEl.appendChild(inputEl);
            divEl.appendChild(inputEl);

            // If there's an old value, determine if it's an image or not
            if (item?.vf?.value) {
                const value = item.vf.value;
                const isImage = value.match(/(jpg|jpeg|png|gif|bmp|webp)/i);
                if (isImage?.length === 2) {
                    imgEl.src = value;
                    imgEl.alt = 'Widget Image';
                    divEl.appendChild(imgEl);
                }
                aEl.href = value;
                aEl.download = value;
                divEl.appendChild(aEl);
            }

            // Add event listener for live preview of uploaded image
            inputEl.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const isImage = file.type.startsWith('image/');
                    if (isImage) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            imgEl.src = e.target.result;
                            imgEl.alt = 'Selected Image';
                            if (!imgEl.parentElement) {
                                divEl.appendChild(imgEl);
                            }
                            if (infoEl.parentElement) {
                                infoEl.remove();
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        infoEl.textContent = 'File selected: ' + file.name;
                        if (!infoEl.parentElement) {
                            divEl.appendChild(infoEl);
                        }
                        if (imgEl.parentElement) {
                            imgEl.remove();
                        }
                    }
                }
            });

            document.getElementById('field-edit-container').appendChild(divEl);
        };


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
            imgEl.alt = 'Option 12345';
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
            if(widget.image ) {
                widgetOption.appendChild(imgEl);
            }
            widgetOption.appendChild(cardBody);
            divEl.appendChild(widgetOption);

            // Append the new widget div to the container
            widgetContainer.appendChild(divEl);

            // Add event listeners for the buttons (example: log actions)
            editBtn.addEventListener('click', async () => {
                emptyFieldEditContainer();
                const widgetPosition = widget.pivot.position;
                const pageId = '{!! $page->id !!}';
                // Add your edit functionality here
                // get widget fields
                // TODO: we should go to the fieldValue here: not widget
                // let allFields = await fetch(`/api/widgets/${widget.id}`)
                //     .then(res => res.json())
                //     .then(data => {
                //         return data.fields
                //         // .forEach(item => {
                //         //     console.log('item', item)
                //         // })
                //     });

                // console.log('allFields', allFields)
                
                // let allFieldValues = await fetch(`/api/pages/${pageId}/widget-position/${widgetPosition}/field-values/${currentLanguage}`)
                //     .then(res => res.json())
                //     .then(data => {
                //         // console.log('dataaaaa', data);
                //         return data.fieldValues;
                //     });

                // console.log('allFieldValues', allFieldValues)

                // let allFieldsWithValues = [];
                // allFields.forEach(field => {
                //     allFieldValues.forEach((fieldValue) => {
                //         if(field.id === fieldValue.field_id) {
                //             // console.log('field', field)
                //             const exists = allFieldsWithValues.some(item => item.id === field.id);
                //             if(!exists) {
                //                 field.vf = fieldValue
                //                 // TODO: we should take care of repeated data  
                //             }
                //         }
                //     })
                //     allFieldsWithValues.push(field)
                // });

                let allFieldsWithValues = await fetch(`/api/page/${pageId}/widget/${widget.id}/widget-position/${widgetPosition}/fields-with-values/${currentLanguage}`)
                    .then(res => res.json())
                    .then(data => data);

                createHiddenInformationInput(pageId, widgetPosition);
                allFieldsWithValues.forEach((item, index) => {
                    switch(item.type){
                        case 'color':
                            createColorPickerInput(item);
                            break;
                        case 'select_option':
                            createSelectInput(item);
                            break;
                        case 'text':
                            createTextareaInput(item, 'text');
                            break;
                        case 'textarea_small':
                            createTextareaInput(item, 'small');
                            break;
                        case 'textarea_large':
                            createTextareaInput(item, 'large');
                            break;
                        case 'input':
                            createInputTextInput(item);
                            break;
                        case 'file':
                            createFileInput(item);
                            break;

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

        document.getElementById('edit-fieldValue-save-btn').addEventListener('click', async (e) => {
            const form = document.getElementById('edit-fieldValue-form');
            const formData = {};

            // Process all inputs
            form.querySelectorAll('input').forEach(input => {
                if (input.type === 'file' && input.files.length > 0) {
                    // Convert file to base64
                    const file = input.files[0];
                    formData[input.name] = 'Processing file...'; // Placeholder until conversion is done
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        formData[input.name] = event.target.result; // Base64 encoded string
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Save non-file input values
                    formData[input.name] = input.value;
                }
            });

            // Process all selects
            form.querySelectorAll('select').forEach(select => {
                formData[select.name] = select.value;
            });

            // Save TinyMCE editor content
            tinymce.triggerSave();
            form.querySelectorAll('textarea').forEach(textarea => {
                formData[textarea.name] = textarea.value;
            });

            // Wait for all file inputs to finish reading
            await new Promise(resolve => setTimeout(resolve, 100)); // Ensure files are read (adjust time as necessary)

            // Send the data to the backend
            fetch("/api/pages/widget-position/update-field-value", {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData)
            })
                .then(res => res.json())
                .then(data => {
                    console.log('form saved !!!!!!!!', data);
                    // emptyFieldEditContainer();
                });

            console.log('save clicked', formData);
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
                        lang: currentLanguage,
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

    <script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
            
    </script>
    @endpush
@endsection
