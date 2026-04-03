@extends('shared::partials.app')

@section('content-card-title', 'Dashboard')

@section('content-card-body')
<div class="container">
    <h1>Create Widget</h1>

    @include('widgets::widgets.form', ['action' => route('admin.widgets.store'), 'method' => 'POST'])
</div>
@endsection
