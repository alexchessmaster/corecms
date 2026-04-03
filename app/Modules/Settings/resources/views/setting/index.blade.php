@extends('shared::partials.app')
@section('content-card-title', 'Settings')
@section('content-card-body')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">KEY</th>
                <th scope="col">VALUE</th>
                <th scope="col">DESCRIPTION</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($settings as $setting)
                <tr>
                    <td>{{ $setting->key }}</td>
                    <td>{!! $setting->value !!}</td>
                    <td><div style="font-size: 14px; color:gray">{!! $setting->description !!}</div></td>
                    <td><a class="btn btn-info" href="{{ route('admin.settings.edit', $setting->id) }}"><i class="fa fa-edit"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
