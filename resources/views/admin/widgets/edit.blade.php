@extends('admin.partials.app')

@section('content-card-title', 'Widget')

@section('content-card-body')
    <div class="container">
        <h1>Edit Widget</h1>

        @include('admin.widgets.form', [
            'action' => route('admin.widgets.update', $widget->id),
            'method' => 'PUT',
            'widget' => $widget,
        ])

        <hr>
        <h3>Fields:</h3>
        <div id="fields-container">
        </div>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fieldModal">
            <i class="fa fa-plus"></i> Add A Field
        </button>

        <style>
            .field-type-option {
                border: 2px solid rgb(201, 201, 255);
                cursor: pointer;
                transition: border-color 0.1s;
            }

            .field-type-option:hover {
                border-color: #63afff;
            }

            .field-type-option.selected {
                border-color: #007bff;
            }
        </style>

        <style>
            @media screen and (min-width: 768px) {
                #widgetOptions .field-type-option .card-body .card-title {
                    overflow-wrap: break-word !important;
                    max-width: 122px;
                }
            }
        </style>

        <!-- Modal Widget Box -->
        <div class="modal fade" id="fieldModal" tabindex="-1" role="dialog" aria-labelledby="Label"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Label">Add Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="field_key" class="form-label required">Field Key</label>
                            <input value="{{ old('key', $field->key ?? '') }}" name="field_key" id="field_key" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <h5 class="modal-title" id="Label">Choose field type</h5>
                        </div>
                        @php
                            $numberOfFieldTypesInARow = 3;
                            $i = 0;
                        @endphp
                        @foreach ($fieldTypes as $fieldType)
                            @if ($i % $numberOfFieldTypesInARow === 0)
                            <div class="row" id="widgetOptions">
                            @endif

                                <div class="col-md-4">
                                    <div class="field-type-option card" data-value="{{ $fieldType->id }}">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $fieldType->type }}</h5>
                                        </div>
                                    </div>
                                </div>

                            @if (($i % $numberOfFieldTypesInARow) === ($numberOfFieldTypesInARow - 1))
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
    @if ($i > 1 && (($i % $numberOfFieldTypesInARow) !== ($numberOfFieldTypesInARow - 1)))
    </div>
    @endif


    <script>
        // Add event listeners for the widget options
        document.querySelectorAll('.field-type-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove 'selected' class from all options
                document.querySelectorAll('.field-type-option').forEach(opt => opt.classList.remove(
                    'selected'));
                // Add 'selected' class to clicked option
                this.classList.add('selected');
            });
        });

        // const widgetList = [];

        // Handle the Confirm button click
        document.getElementById('confirmSelection').addEventListener('click', function() {
            const selectedOption = document.querySelector('.field-type-option.selected');
            if (selectedOption) {
                const key = document.getElementById('field_key');
                const fieldId = selectedOption.getAttribute('data-value');
                if(key.value !== ""){
                    const widgetId = '{{ $widget->id }}';
                    fetch(`/api/widgets/${widgetId}/fields`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: fieldId,
                            key: key.value,
                        })
                    }).then(response => {
                        return response.json();
                    }).then(data => {
                        console.log('updated', data)

                        refreshFieldsList();
                    })
                }
            } else {
                alert('Please select a widget.');
            }
        });


        const widgetContainer = document.getElementById('fields-container');
        const createField = field => {
            console.log('field', field);
            // Create the outer div (col-md-4)
            const divEl = document.createElement('div');
            divEl.classList.add('col-md-6');

            // Create the widget option div (field-type-option card)
            const card = document.createElement('div');
            card.classList.add('field-type-option', 'card');

            // Create the card body div
            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body', 'text-center');

            // Create the title (h5 element)
            const cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.innerHTML = '<strong>Type:</strong> ' + field.field_type + ' <strong>Key:</strong> ' + field.key; // Use the widget's name

            // Create the Delete button with an icon
            const deleteBtn = document.createElement('button');
            deleteBtn.classList.add('btn', 'btn-danger'); // Styling button
            deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Delete'; // FontAwesome delete icon

            // Append the buttons to the card body
            card.appendChild(cardTitle);
            // cardBody.appendChild(editBtn);
            cardBody.appendChild(deleteBtn);

            // Create a div to hold the card body and the image (keep the layout tidy)
            card.appendChild(cardBody);
            divEl.appendChild(card);

            // Append the new widget div to the container
            widgetContainer.appendChild(divEl);

            deleteBtn.addEventListener('click', () => {
                // console.log('Delete button clicked for widget ID:', widget.pivot.position);
                fetch('/api/widgets/{{ $widget->id }}/fields/' + field.id, {
                        method: "DELETE",
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            field_key : field.key,
                        })
                    })
                    .then(response => response.json)
                    .then(data => {
                        console.log('filed deleted', data);
                        refreshFieldsList();
                    })
                // Add your delete functionality here
            });
        }
        let addWidgetButtonPosition = null;
        const refreshFieldsList = () => {
            document.getElementById('field_key').value = '';
            widgetContainer.innerHTML = null;
            fetch('/api/widgets/{!! $widget->id !!}')
                .then(response => {
                    return response.json()
                })
                .then(data => {
                    data.fields.forEach((item, index) => {
                        createField(item);
                    })
                })
        }
        refreshFieldsList();
    </script>

    <script>
        // Close button inside the modal footer
        document.querySelector('.btn-secondary[data-dismiss="modal"]').addEventListener('click', () => {
            $('#fieldModal').modal('hide');
        });

        // "X" button in the modal header
        document.querySelector('.close[data-dismiss="modal"]').addEventListener('click', () => {
            $('#fieldModal').modal('hide');
        });
    </script>

    </div>
@endsection
