<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.about_us')</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="terms_wrapper">
        <div class="container">
            <div class="row flex-reverse">
                <div class="col-lg-6">
                    <h3>@lang('site.our_mission')</h3>
                    <p>{{$settings->mission}}</p>
                </div>
                <div class="col-lg-6">
                    <div class="ab_img">
                        <img src="{{url('uploads/pics/'. $settings->mission_image)}}" alt="">
                    </div>
                </div>
            </div>
            <div class="row">


                <div class="col-lg-6">
                    <div class="ab_img">
                        <img src="{{url('uploads/pics/'. $settings->objective_image)}}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3>@lang('site.our_objective')</h3>
                    <p>{{$settings->objective}}</p>
                </div>

            </div>
            <div class="row flex-reverse">
                <div class="col-lg-6">
                    <h3>@lang('site.our_vision')</h3>
                    <p>{{$settings->vision}}</p>
                </div>
                <div class="col-lg-6">
                    <div class="ab_img">
                        <img src="{{url('uploads/pics/' . $settings->vision_image)}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
