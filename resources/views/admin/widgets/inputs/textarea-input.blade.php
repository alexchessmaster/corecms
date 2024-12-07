<div class="form-group">
    <label for="">{!! $data[1] !!}</label>
    <textarea class="tinymce" name="text-{{ $data[0] }}">{!! isset($widget->fields[$data[0]]) ? $widget->fields[$data[0]]->value : '' !!}</textarea>
    <small id="" class="form-text text-muted">{!! $data[2] !!}</small>
</div>
