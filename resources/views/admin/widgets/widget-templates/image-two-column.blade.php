<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="block">

                <div class="row">
                    @foreach ($fields as $field)
                        <div class="col-md-6">
                            <div style="display: flex; justify-content:center; align-items:center;">
                                <img class="img-responsive" src="{!! $field->value !!}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </div>
    </div>
</div>
