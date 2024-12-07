<section id="blog-left">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="block">
                    <div class="row">
                        @foreach ($fields as $field)
                            <div class="col-sm-4" style="overflow-x: hidden">
                                {!! $field->value !!}
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
