@php
    $obj = new \stdClass();
    foreach ($fields as $field) {
        $key = $field->field->key;
        $obj->$key = $field->value;
        // limit category sort
    }
    $response = Http::post('https://cms-api.nordicstandard.net/api/articles', [
        'limit' => $obj->limit,
        'category' => $obj->category,
        'sort' => $obj->sort,
    ]);
    $body = $response->body();
    $articles = json_decode($body)->data;
@endphp

<section>
    <header class="major">
        <h2>Articles</h2>
    </header>
    <div class="posts">

        @foreach ($articles as $article)
            <article>
                <a href="#" class="image"><img src="{{ $article->image }}" alt="" /></a>
                <h3>{{ $article->title }}</h3>
                <p>{{ $article->description }}</p>
                <ul class="actions">
                    <li><a href="{{ $article->slug }}" class="button">More</a></li>
                </ul>
            </article>
        @endforeach

    </div>
</section>
