@if(!empty($fields[5]->value))
<style>
    .title-right {
        text-align: right;
        padding-bottom: 70px;
    }
    .title-right h2 {
        color: #333;
        font-size: 25px;
        padding-bottom: 25px;
        text-transform: uppercase;
        display: inline-block;
        position: relative;
    }

    .title-right h2:before {
        content: '';
        width: 40px;
        height: 2px;
        background: var(--accent-color);
        position: absolute;
        top: 12px;
        left: -60px;
    }
    .title-right p {
        font-size: 16px;
        line-height: 24px;
        font-weight: 300;
        color: #817C7C;
    }

    .title-left {
        text-align: left;
        padding-bottom: 70px;
    }
    .title-left h2 {
        color: #333;
        font-size: 25px;
        padding-bottom: 25px;
        text-transform: uppercase;
        display: inline-block;
        position: relative;
    }
    .title-left h2:after {
        content: '';
        width: 40px;
        height: 2px;
        background: var(--accent-color);
        position: absolute;
        top: 12px;
        right: -60px;
        overflow: hidden;
    }
    .title-left p {
        font-size: 16px;
        line-height: 24px;
        font-weight: 300;
        color: #817C7C;
    }
    .textbox-on-image{
        padding: 20px; 
        background-color: white; 
        display: inline-block; 
        border: 2px solid #F89E52;
    }
    .textbox-on-image h2{
        word-wrap: anywhere;
        max-width: calc(100% - 23px);
    }
</style>
@endif
@php
    $hasOverlay = false;
    $gradientDegrees = $fields[1]->value;
    $overlayColorWithOpacityStarts = $fields[2]->value;
    $overlayColorWithOpacityEnds = $fields[3]->value;
    $overlayText = "";
    if(!empty($fields[0]->value) && $fields[0]->value !== "no"){
        $hasOverlay = true;
        $overlayText = "linear-gradient($gradientDegrees" . "deg, $overlayColorWithOpacityStarts, $overlayColorWithOpacityEnds)";
    }

    $backgroundImageText = "";
    if(!empty($fields[4]->value)){
        $backgroundImageText = "url('" . $fields[4]->value . "') no-repeat; background-size: cover; background-position: center;";
    }

    $hasBackground = false;
    if($hasOverlay && $backgroundImageText){
        $backgroundStyle = "background: $overlayText, $backgroundImageText";
    }else if($hasOverlay && empty($fields[4]->value)){
        $backgroundStyle = "background: $overlayText";
    }else{
        $backgroundStyle = "";
    }
    // dd($backgroundStyle);
@endphp
<section style="{!! $backgroundStyle !!}">
    <div class="container">
        <div class="row">
            <div class="col-md-{{ $fields[9]->value }}">
                <div class=" {{ !empty($fields[8]->value) ? "title-" . $fields[8]->value : '' }}">
                    @if(!empty($fields[5]->value))
                    <div style="{{ !empty($fields[6]->value) ? "padding-top: " . $fields[6]->value : '' }}"></div>
                    <div class="textbox-on-image wow fadeInUp" data-wow-delay=".1s">
                        {!! $fields[5]->value !!}
                    </div>
                    <div style="{{ !empty($fields[7]->value) ? "padding-top:" . $fields[7]->value : '' }}"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
