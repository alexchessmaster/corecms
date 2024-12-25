@php
    $obj = new \stdClass;
    foreach ($fields as $field) {
        $key = $field->field->key;
        $obj->$key = $field->value;
    }
@endphp

<!-- Section -->
<section>
    <header class="major">
        <h2>Get in touch</h2>
    </header>
    <p>{!! $obj->description !!}</p>
    <ul class="contact">
        <li class="icon solid fa-envelope"><a href="#">{{ $obj->email }}</a></li>
        <li class="icon solid fa-phone">{{ $obj->tel }}</li>
        <li class="icon solid fa-home">{{ $obj->address }}</li>
    </ul>
</section>

<!-- Footer -->
<footer id="footer">
    <p class="copyright">&copy; Untitled. All rights reserved. Demo Images: <a href="https://unsplash.com">Unsplash</a>.
        Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
</footer>
