<section class="hero">
    <div class="layer"></div>
    <div class="container">
        <div class="hero_slider">
            @foreach($sliders as $slider)
            <div class="hero_info">
                <h2>@lang('site.welcome_to_bogi') </h2>
                <h1>{{$slider->title}}</h1>
                <p>{{$slider->description}}</p>
                <a href="{{route('services.index')}}" class="btn btn-1 " style="padding-top: 10px;" >@lang('site.more')</a>
            </div>
            @endforeach
        </div>
    </div>
</section>
