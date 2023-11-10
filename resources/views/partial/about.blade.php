<section class="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="heading_box">
                    <h3>{{$about->title}}</h3>
                    <h2>{!! $about->descritption !!}</h2>
                <p>
                    {!! $about->content !!}
                </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ab_box2">
                    <img src="{{url('uploads/pics/' .$settings->mission_image)}}" alt="">
                </div>
                <div class="ab_info">
                    <div class="text-center">
                        <p> +{{$settings->number_of_experience}}</p>
                        <p>@lang('site.years_of_experience')</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ab_box">
                    <img src="{{url('uploads/pics/'. $settings->vision_image)}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
