<header id="header">
    <a href="index.html" class="logo"><strong>{{ $data->page->title }}</strong></a>
    {{-- <ul class="icons">
        <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
        <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
        <li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a>
        </li>
        <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
        <li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
    </ul> --}}
</header>

@foreach ($data?->page?->page_widgets as $widget)
    @if($widget->widget->key === 'article-list')
        @include('editorial.widgets.article-list', ['fields' => $widget->field_values])
    @endif
@endforeach