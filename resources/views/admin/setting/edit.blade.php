@extends('admin.partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
        @method('patch')
        @csrf

        <div class="form-group">
            <label for="exampleInputEmail1">Key</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name"
                name="key" value="{{ isset($setting) ? $setting->key : '' }}" disabled>
            <small id="emailHelp" class="form-text text-muted">Key of the setting</small>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail12">Value</label>
            <input type="text" class="form-control" id="exampleInputEmail12" aria-describedby="emailHelp"
                placeholder="Enter link" name="value" value="{{ isset($setting) ? $setting->value : '' }}">
            <small id="emailHelp" class="form-text text-muted">Value of the setting</small>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail12">Description</label>
            <input type="text" class="form-control" id="exampleInputEmail12" aria-describedby="emailHelp"
                placeholder="Enter link" name="description" value="{{ isset($setting) ? $setting->description : '' }}">
            <small id="emailHelp" class="form-text text-muted">Description of the record</small>
        </div>

        <input type="submit" class="btn btn-success" value="Save">
    </form>

@endsection
