<div class="form-group">
    <label for="">{!! $data[1] !!}</label>
    <input type="text" class="form-control" id="" aria-describedby=""
        placeholder="Enter title" name="text-{{ $data[0] }}" value="{!! isset($widget->fields[$data[0]]) ? $widget->fields[$data[0]]->value : '' !!}">
    <small id="" class="form-text text-muted">{!! $data[2] !!}</small>
</div>
