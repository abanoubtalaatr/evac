<section class="about testmonial">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="heading_box text-center">
                    <h2>@lang('site.testmoinal')</h2>
                    <p>@lang("site.testmonials_description")</p>

                </div>
            </div>
            <div class="col-lg-12">
                <div class="test_slider">
                    @foreach($testmoinals as $testmoinal)
                    <div class="test_box" style="height: 300px">
                        @if($testmoinal->image)
                            <img src="{{url('uploads/pics/'. $testmoinal->image)}}" alt="">
                        @else
                            <img src="{{asset('website/assets/images/public/prof.png')}}" alt="">
                        @endif
                        <p>{{$testmoinal->opinion}}</p>
                        <h3>{{$testmoinal->name}}</h3>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
