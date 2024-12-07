<style>
    .iphone-15-pro h2{
        color: rgb(134, 134, 139);
        font-size: 56px;
        margin-bottom: 40px;
    }
    .iphone-15-pro p{
        color: rgb(245, 245, 247); 
        font-size: 21px;
    }
    .iphone-15-pro-image-container{
        vertical-align: middle; 
        overflow: hidden; 
        margin-bottom: 50px;
    }
    .iphone-15-pro-container{
        padding-top:70px; 
    }
    .iphone-15-pro-zoomable-image{
        height: 700px;
    }
    @media (max-width: 767px) {
        .iphone-15-pro h2{
            font-size: 32px;
            margin-bottom: 25px;
        }
        .iphone-15-pro-image-container{
            vertical-align: middle; 
            overflow: hidden; 
            margin-bottom: 40px;
        }
        .iphone-15-pro p{
            font-size: 18px;
        }
        .iphone-15-pro-container{
            padding-top:30px; 
        }
        .iphone-15-pro-zoomable-image{
            height: 400px;
        }
    }
    .iphone-15-pro-zoomable-image {
        animation: zoomAnimation 30s infinite alternate;
    }
    .iphone-15-pro-container-paragraph{
        line-height: 1.8;
        margin-bottom: 50px;
    }
    @keyframes zoomAnimation {
        from {
            transform: scale(1);
        }
        to {
            transform: scale(1.1); /* Adjust the scale factor as needed */
        }
    }
</style>
<section class="iphone-15-pro" style="background-color:{{ $fields[1]->value }};">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="iphone-15-pro-container" style="text-align: {{ $fields[2]->value }}">
                    {!! !empty($fields[3]->value) ? '<h2 class="wow fadeInUp">' . $fields[3]->value . '</h2>' : '' !!}
                    {!! !empty($fields[4]->value) ? '<div class="iphone-15-pro-container-paragraph wow fadeInUp">' . $fields[4]->value . '</div>' : '' !!}
                    <div class="iphone-15-pro-image-container">
                        <div class="iphone-15-pro-zoomable-image" style="background: linear-gradient(0deg, rgba(0, 0, 0, {{ $fields[5]->value }}), rgba(0, 0, 0, {{ $fields[5]->value }})), url('{{ $fields[0]->value }}') no-repeat; background-size: cover;background-position: center;"></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
