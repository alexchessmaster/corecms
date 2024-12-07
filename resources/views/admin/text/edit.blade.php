@extends('admin.partials.app')
@section('content-card-title', 'Edit text')
@section('content-card-body')
    
    <script>
        tinymce.init({
            selector: '.tinymce',
            plugins: 'image'
        });
    </script>
    <form action="/admin/texts/update" method="POST">
        @method('patch')
        @csrf
        <input type="text" name="id" value="{!! $text->id !!}" hidden>
        <br>
        @php
            $textsSeparatedByLang = App\Helpers\TranslateHelper::htmlToJson($text->value);
        @endphp
        @foreach ($textsSeparatedByLang as $lang => $value) 
            <div>
                <span class="btn btn-info">Edit {{ $lang }}:</span>
            </div>
            <textarea name="{{ $lang }}" class="tinymce" cols="30" rows="10">{!! $value !!}</textarea>
            <hr>
            <br>
        @endforeach
        
        <input type="submit" class="btn btn-success" value="Save">
    </form>
    
@endsection
