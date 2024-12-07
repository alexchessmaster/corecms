@php
    $selectedValue = '';
    foreach($fields as $tmp){
        if($tmp->order === $data[0]){
            $selectedValue = $tmp->value;
        }
    }
@endphp

<div class="form-group">
    <label for="exampleFormControlSelect1">{!! $data[1] !!}</label>
    <select class="form-control" name="text-{{ $data[0] }}" id="exampleFormControlSelect1">
        @foreach ($data[3] as $key => $value)
            <option {{ !empty($selectedValue) && $key === $selectedValue ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    <small id="" class="form-text text-muted">{!! $data[2] !!}</small>
</div>
