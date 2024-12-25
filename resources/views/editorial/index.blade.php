@extends('editorial.layouts.app')
@section('content')
    @php
        $response = Http::post('https://cms-api.nordicstandard.net/api/content', [
            'path' => '/en',
        ]);
        $body = $response->body();
        if (!$body) {
            dd('api not working');
        }
        $data = json_decode($body)->data;
        // dd($data);
    @endphp

    <!-- Main -->
    <div id="main">
        <div class="inner">

            @if (!empty($data->page))
                @include('editorial.pages.page-page', ['data' => $data])
            @elseif(!empty($data->article))
                @include('editorial.pages.article-page', ['data' => $data])
            @elseif(!empty($data->category))
                @include('editorial.pages.category-page', ['data' => $data])
            @endif

            <!-- Header -->
            

            <!-- Banner -->
            {{-- <section id="banner">
                <div class="content">
                    <header>
                        <h1>Hi, I’m Editorial<br />
                            by HTML5 UP</h1>
                        <p>A free and fully responsive site template</p>
                    </header>
                    <p>Aenean ornare velit lacus, ac varius enim ullamcorper eu. Proin aliquam facilisis ante interdum
                        congue. Integer mollis, nisl amet convallis, porttitor magna ullamcorper, amet egestas mauris. Ut
                        magna finibus nisi nec lacinia. Nam maximus erat id euismod egestas. Pellentesque sapien ac quam.
                        Lorem ipsum dolor sit nullam.</p>
                    <ul class="actions">
                        <li><a href="#" class="button big">Learn More</a></li>
                    </ul>
                </div>
                <span class="image object">
                    <img src="/editorial/images/pic10.jpg" alt="" />
                </span>
            </section> --}}

            <!-- Section -->
            {{-- <section>
                <header class="major">
                    <h2>Erat lacinia</h2>
                </header>
                <div class="features">
                    <article>
                        <span class="icon fa-gem"></span>
                        <div class="content">
                            <h3>Portitor ullamcorper</h3>
                            <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis
                                ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
                        </div>
                    </article>
                    <article>
                        <span class="icon solid fa-paper-plane"></span>
                        <div class="content">
                            <h3>Sapien veroeros</h3>
                            <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis
                                ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
                        </div>
                    </article>
                    <article>
                        <span class="icon solid fa-rocket"></span>
                        <div class="content">
                            <h3>Quam lorem ipsum</h3>
                            <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis
                                ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
                        </div>
                    </article>
                    <article>
                        <span class="icon solid fa-signal"></span>
                        <div class="content">
                            <h3>Sed magna finibus</h3>
                            <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis
                                ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
                        </div>
                    </article>
                </div>
            </section>   --}}

            <!-- Section -->






        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">

            <!-- Search -->
            <section id="search" class="alt">
                <form method="post" action="#">
                    <input type="text" name="query" id="query" placeholder="Search" />
                </form>
            </section>

            @include('editorial.partials.menu')

            <!-- Section -->
            {{-- <section>
                <header class="major">
                    <h2>Ante interdum</h2>
                </header>
                <div class="mini-posts">
                    <article>
                        <a href="#" class="image"><img src="/editorial/images/pic07.jpg" alt="" /></a>
                        <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore aliquam.</p>
                    </article>
                    <article>
                        <a href="#" class="image"><img src="/editorial/images/pic08.jpg" alt="" /></a>
                        <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore aliquam.</p>
                    </article>
                    <article>
                        <a href="#" class="image"><img src="/editorial/images/pic09.jpg" alt="" /></a>
                        <p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore aliquam.</p>
                    </article>
                </div>
                <ul class="actions">
                    <li><a href="#" class="button">More</a></li>
                </ul>
            </section> --}}

            @foreach ($data?->page?->page_widgets as $widget)
                @if ($widget->widget->key === 'footer')
                    @include('editorial.widgets.footer', ['fields' => $widget->field_values])
                @endif
            @endforeach

        </div>
    </div>
@endsection
