@php
$firstFieldTitle = $fields[0];
$secondFieldBackgroundColor = $fields[1];
$thirdFieldTextColor = $fields[2];
$fields->forget(0);
$fields->forget(1);
$fields->forget(2);
$fields = $fields->values();

$neededValues = 4;
$persons = []; 
for($i = 0; $i < count($fields); $i += $neededValues){
    $person = [];
    $person['name'] = $fields[$i]->value;
    $person['title'] = $fields[$i+1]->value;
    $person['description'] = $fields[$i+2]->value;
    $person['image'] = $fields[$i+3]->value;
    $persons[] = $person;
}

$colNumber = 3;
if(count($persons) === 1){
    $colNumber = 12;
}else if(count($persons) === 2){
    $colNumber = 6;
}else if(count($persons) === 3){
    $colNumber = 4;
}

@endphp

<style>
#team{
    background-color: {{ $secondFieldBackgroundColor->value }};
}
#team h2, #team p{
    color:{{ $thirdFieldTextColor->value }};
}
</style>

<section id="team" >
    <div class="container">
        <div class="row">
            <div class="title">
                {!! $firstFieldTitle->value !!}
            </div>
        </div>
        <div class="row" >

            @foreach($persons as $person)
                <div class="col-md-{{ $colNumber }} col-sm-{{ $colNumber }} col-xs-6">
                    <div class="block wow fadeInUp" data-wow-delay=".2s">
                        <img src="{{ $person['image'] }}" alt="">
                        <div class="team-overlay">
                            <h3>{{ $person['name'] }} <span>{{ $person['title'] }}</span></h3>
                            <span class="icon"><i class="ion-quote"></i></span>
                            <p>{!! $person['description'] !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>