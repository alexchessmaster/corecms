@php
    if (isset($page) && !empty($page)) {
        $model = $page;
        $widgetableId = $page->id;
        $widgetableType = substr(get_class($page), 11);
    } elseif (isset($article) && !empty($article)) {
        $model = $article;
        $widgetableId = $article->id;
        $widgetableType = substr(get_class($article), 11);
    } else {
        $model = $category;
        $widgetableId = $category->id;
        $widgetableType = substr(get_class($category), 11);
    }
@endphp


@push('scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $('.my-colorpicker2').colorpicker()
    </script>

    <script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
@endpush


<div id="widgets-container"></div>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#widgetModal">
    <i class="fa fa-plus"></i> Add Widget
</button>
<br><br>
<div id="field-edit-container"></div>
<br><br>
<button type="button" id="save-all" class="btn btn-success margin-auto">
    <i class="fa fa-check"></i> Save all
</button>

<style>
    .widget-option {
        border: 2px solid rgb(201, 201, 255);
        /* cursor: pointer; */
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

    const createCardForWidget = (widget) => {
        // Create a new div element for the widget card
        const divEl = document.createElement('div');
        divEl.classList.add('col-md-12', 'mb-3');

        const widgetOption = document.createElement('div');
        widgetOption.classList.add('widget-option', 'card', 'flex-md-row'); // horizontal on md+
        widgetOption.setAttribute('data-value', widget.position);

        const imgEl = document.createElement('img');
        imgEl.src = widget.image;
        imgEl.style.height = 'auto';
        imgEl.style.width = '100%';
        imgEl.style.objectFit = 'contain';
        imgEl.classList.add('col-md-5', 'card-img-left', 'img-fluid', 'd-md-block');
        // hidden on mobile, shown on md+

        const cardBody = document.createElement('div');
        cardBody.classList.add('col-md-6', 'card-body', 'text-center', 'flex-fill');

        const cardTitle = document.createElement('h2');
        cardTitle.classList.add('card-title');
        cardTitle.innerHTML = `<h4>${widget.name}</h4>`;

        const form = document.createElement('form');
        form.id = 'card-body-position-' + widget.position
        const saveBtn = document.createElement('button');
        saveBtn.style.display = 'none'; // save-all button will press all the save buttons
        saveBtn.classList.add('btn', 'btn-success', 'save-button'); // Styling button
        saveBtn.innerHTML = '<i class="fas fa-check"></i> save'; // FontAwesome save icon
        saveBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            const formData = {};

            // Process all inputs
            form.querySelectorAll('input').forEach(input => {
                if (input.type === 'file' && input.files.length > 0) {
                    // Convert file to base64
                    const file = input.files[0];
                    formData[input.name] =
                        'Processing file...'; // Placeholder until conversion is done
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

            console.log('save formData', formData)

            // Wait for all file inputs to finish reading
            await new Promise(resolve => setTimeout(resolve,
                100)); // Ensure files are read (adjust time as necessary)

            // Send the data to the backend
            fetch("/api/widget-field-values", {
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

                    toastr.success('Widget inputs updated successfully.');
                }).catch(error => {
                    console.error('Error saving widget inputs:', error, formData);
                    toastr.error('Failed to save widget inputs.');
                });

            console.log('save clicked', formData);
        });
        form.appendChild(saveBtn);

        const deleteBtn = document.createElement('button');
        deleteBtn.classList.add('btn', 'btn-danger'); // Styling button
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Delete'; // FontAwesome delete icon
        deleteBtn.addEventListener('click', () => {
            fetch('/api/widgets/detach', {
                    method: "PATCH",
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        positionId: widget.position,
                        widgetableId: '{{ $widgetableId }}',
                        widgetableType: '{{ $widgetableType }}',
                    })
                })
                .then(response => response.json)
                .then(data => {
                    console.log('widget deleted', data);
                    refreshWidgetList();

                    toastr.success('Widget removed successfully.');
                })
            // Add your delete functionality here
        });

        const cardFooter = document.createElement('div');
        cardFooter.classList.add('card-footer', 'col-md-1', 'text-center');
        cardFooter.id = 'card-footer-position-' + widget.position;
        cardFooter.appendChild(deleteBtn);

        cardBody.appendChild(cardTitle);
        cardBody.appendChild(form);
        widgetOption.appendChild(imgEl);
        widgetOption.appendChild(cardBody);
        widgetOption.appendChild(cardFooter);
        divEl.appendChild(widgetOption);

        document.getElementById('field-edit-container').appendChild(divEl);

        // add "+ Add Widget" button
        const btnEl = document.createElement('button');
        btnEl.classList.add('btn', 'btn-primary', 'mb-3');
        btnEl.setAttribute('data-toggle', 'modal');
        btnEl.setAttribute('data-target', '#widgetModal');
        const iconEl = document.createElement('i');
        iconEl.classList.add('fa', 'fa-plus');
        btnEl.appendChild(iconEl);
        btnEl.appendChild(document.createTextNode(' Add Widget'));
        document.getElementById('field-edit-container').appendChild(btnEl);
    };

    const createInformationInputs = (widgetableId, widgetableType, widgetPosition, widget) => {
        const divEl = document.createElement('div');
        divEl.classList.add('col-md-12');

        const widgetableIdInputEl = document.createElement('input');
        widgetableIdInputEl.name = 'widgetable-id';
        widgetableIdInputEl.type = 'hidden';
        widgetableIdInputEl.value = widgetableId;

        const widgetableTypeInputEl = document.createElement('input');
        widgetableTypeInputEl.name = 'widgetable-type';
        widgetableTypeInputEl.type = 'hidden';
        widgetableTypeInputEl.value = widgetableId;

        const widgetPositionInputEl = document.createElement('input');
        widgetPositionInputEl.name = 'widget-position';
        widgetPositionInputEl.type = 'hidden';
        widgetPositionInputEl.value = widgetPosition;

        const languageInputEl = document.createElement('input');
        languageInputEl.name = 'language';
        languageInputEl.type = 'hidden';
        languageInputEl.value = '{!! App::currentLocale() !!}';

        const widgetInputEl = document.createElement('input');
        widgetInputEl.name = 'widget-id';
        widgetInputEl.type = 'hidden';
        widgetInputEl.value = widget.id;

        const widgetLockedInputEl = document.createElement('input');
        widgetLockedInputEl.name = 'widget-locked';
        widgetLockedInputEl.type = 'hidden';
        widgetLockedInputEl.value = widget.locked_fields_value;

        const widgetLockedMessageEl = document.createElement('div');
        widgetLockedMessageEl.innerHTML =
            '<div><small class="form-text mb-4 text-muted"><span style="color:red;">* </span>This widget is locked. if you change the values, everywhere else that you used this widget, the values will change.</small></div>';

        divEl.appendChild(widgetableIdInputEl);
        divEl.appendChild(widgetableTypeInputEl);
        divEl.appendChild(widgetPositionInputEl);
        divEl.appendChild(languageInputEl);
        divEl.appendChild(widgetInputEl);
        divEl.appendChild(widgetLockedInputEl);
        if (widget.locked_fields_value) {
            divEl.appendChild(widgetLockedMessageEl);
        }

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);
    };

    const createInputTextInput = (widget, item) => {
        const divEl = document.createElement('div');
        divEl.id = `position-${widget.position}`;
        divEl.classList.add('col-md-12');

        const labelEl = document.createElement('label');
        labelEl.textContent = item.key + ':';
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');

        const inputEl = document.createElement('input');
        inputEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;
        inputEl.classList.add('form-control');
        inputEl.value = item?.value;

        divEl.appendChild(labelEl);
        divEl.appendChild(inputEl);

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);

        // <div class="mb-3">
        //     <label for="name" class="form-label">Name</label>
        //     <input type="text" name="name" id="name" class="form-control"
        //         value="{{ old('name', $widget->name ?? '') }}" required>
        // </div>
    };

    const createCodeInput = (widget, item) => {
        const divEl = document.createElement('div');
        divEl.classList.add('col-md-12');

        const labelEl = document.createElement('label');
        labelEl.textContent = item.key + ':';
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');

        const textareaEl = document.createElement('textarea');
        textareaEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;
        textareaEl.classList.add('form-control');
        textareaEl.innerHTML = item?.value;

        divEl.appendChild(labelEl);
        divEl.appendChild(textareaEl);

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);
        // <div class="mb-3">
        //     <label for="name" class="form-label">Name</label>
        //     <textarea type="text" name="name" id="name" class="form-control">
        //         {{ old('name', $widget->name ?? '') }}
        //     </textarea>
        // </div>
    };

    const createColorPickerInput = (widget, item) => {
        const divGroup = document.createElement('div');
        divGroup.classList.add('form-group', 'col-md-12', 'mt-3');

        const labelEl = document.createElement('label');
        labelEl.textContent = item.key + ':' || 'Color picker with addon:';
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');
        divGroup.appendChild(labelEl);

        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'my-colorpicker2', 'colorpicker-element');

        const inputEl = document.createElement('input');
        inputEl.type = 'text';
        inputEl.classList.add('form-control');
        inputEl.title = item.title || '';
        inputEl.value = item?.value;
        inputEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;
        inputGroup.appendChild(inputEl);

        const addonDiv = document.createElement('div');
        addonDiv.classList.add('input-group-append');

        const iconSpan = document.createElement('span');
        iconSpan.classList.add('input-group-text');

        const icon = document.createElement('i');
        icon.classList.add('fas', 'fa-square');
        icon.style.color = item?.value || 'rgb(119, 27, 27)'; // Set the color if provided

        iconSpan.appendChild(icon);
        addonDiv.appendChild(iconSpan);
        inputGroup.appendChild(addonDiv);
        divGroup.appendChild(inputGroup);
        document.getElementById('card-body-position-' + widget.position).appendChild(divGroup);

        if (typeof $(inputGroup).colorpicker === 'function') {
            $(inputGroup).colorpicker();
        }
    }

    const createSelectInput = (widget, item, optionsArray) => {
        const divEl = document.createElement('div');
        divEl.classList.add('form-group', 'col-md-12');

        const labelEl = document.createElement('label');
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');
        labelEl.setAttribute('for', 'alignmentSelect');
        labelEl.textContent = 'Select';

        const selectEl = document.createElement('select');
        selectEl.classList.add('form-control');
        selectEl.id = 'alignmentSelect';
        selectEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;


        // Add options for alignment: left, center, right
        // ['left', 'center', 'right']
        optionsArray.forEach(optionValue => {
            const optionEl = document.createElement('option');
            optionEl.textContent = optionValue;
            optionEl.value = optionValue;
            if (optionValue === item?.value) {
                optionEl.selected = true;
            }
            selectEl.appendChild(optionEl);
        });

        divEl.appendChild(labelEl);
        divEl.appendChild(selectEl);

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);

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

    const createTextareaInput = (widget, item, size = 'normal') => {
        const divEl = document.createElement('div');
        divEl.classList.add('col-md-12');

        const labelEl = document.createElement('label');
        labelEl.textContent = item.key + ':';
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');

        const textareaEl = document.createElement('textarea');
        textareaEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;
        textareaEl.classList.add('form-control');
        textareaEl.value = item?.value || '';
        textareaEl.id = 'textarea-' + widget.id + '-' + item
            .field_widget; // Add a unique ID for TinyMCE initialization

        divEl.appendChild(labelEl);
        divEl.appendChild(textareaEl);

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);

        // Destroy existing TinyMCE instances if any
        tinymce.remove(`#textarea-` + widget.id + '-' + item.field_widget);

        // Initialize TinyMCE for the new textarea
        let tinyConfig = {};
        if (size === 'text') {
            tinyConfig = {
                selector: `#textarea-` + widget.id + '-' + item
                    .field_widget, // Replace with the ID or class of your target element
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
        if (size === 'small') {
            tinyConfig = {
                selector: `#textarea-` + widget.id + '-' + item
                    .field_widget, // Replace with the ID or class of your target element
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
        if (size === 'large') {
            tinyConfig = {
                selector: `#textarea-` + widget.id + '-' + item.field_widget,
                plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
                // toolbar_mode: 'floating',
                // menubar: false,
            }
        }
        tinymce.init(tinyConfig);
    }

    const createFileInput = (widget, item) => {
        const divEl = document.createElement('div');
        divEl.classList.add('mb-3');

        const labelEl = document.createElement('label');
        labelEl.textContent = `${item.key}:`;
        labelEl.style.textAlign = 'start';
        labelEl.classList.add('col-sm-12', 'mt-2', 'form-label');

        const inputEl = document.createElement('input');
        inputEl.type = 'file';
        inputEl.name = 'widgetable_id-' + widget.id + '-field_widget_id-' + item?.field_widget +
            '-widget_field_value_id-' + item?.widget_field_value_id;
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
        if (item?.value) {
            const value = item.value;
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

        document.getElementById('card-body-position-' + widget.position).appendChild(divEl);
    };

    // Modal for add a new widget
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
                        widgetableId: '{{ $widgetableId }}',
                        widgetableType: '{{ $widgetableType }}',
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

                    toastr.success('Widget added successfully.');
                })


        } else {
            alert('Please select a widget.');
        }
    });

    const widgetContainer = document.getElementById('field-edit-container');
    const createWidget = async widget => {

        const widgetPosition = widget.position;
        const widgetableId = '{!! $widgetableId !!}';
        const widgetableType = '{!! $widgetableType !!}';

        createCardForWidget(widget);
        createInformationInputs(widgetableId, widgetableType, widgetPosition, widget);
        widget.fields.forEach((item, index) => {
            switch (item.type) {
                case 'color':
                    createColorPickerInput(widget, item);
                    break;
                case 'select_option_left_center_right':
                    createSelectInput(widget, item, ['left', 'center', 'right']);
                    break;
                case 'select_option_on_off':
                    createSelectInput(widget, item, ['on', 'off']);
                    break;
                case 'textarea_one_line':
                    createTextareaInput(widget, item, 'text');
                    break;
                case 'textarea_small':
                    createTextareaInput(widget, item, 'small');
                    break;
                case 'textarea_large':
                    createTextareaInput(widget, item, 'large');
                    break;
                case 'input':
                    createInputTextInput(widget, item);
                    break;
                case 'file':
                    createFileInput(widget, item);
                    break;
                case 'code':
                    createCodeInput(widget, item);
                    break;

            }
        });
    }
    let addWidgetButtonPosition = null;
    const refreshWidgetList = () => {
        widgetContainer.innerHTML = null;
        fetch("/api/{!! strtolower($widgetableType) !!}s/{!! $widgetableId !!}")
            .then(response => {
                return response.json()
            })
            .then(data => {
                data.widgets.forEach((item, index) => {

                    createWidget(item);
                })
                const buttons = document.querySelectorAll(
                    'button.btn.btn-primary[data-toggle="modal"][data-target="#widgetModal"]');
                buttons.forEach((button, index) => {
                    button.setAttribute('data-position', index);

                    // Add a click event listener to log the data-position
                    button.addEventListener('click', () => {
                        // console.log(`data-position: ${button.getAttribute('data-position')}`);
                        addWidgetButtonPosition = button.getAttribute('data-position');
                    });
                });
            })
    }
    refreshWidgetList();

    document.getElementById('save-all').addEventListener('click', (e) => {
        e.preventDefault();
        const allSaveButtons = document.querySelectorAll('.btn.btn-success.save-button');
        // click on each save button
        allSaveButtons.forEach((button) => {
            button.click();
        })
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
