<section class="services">
    <div class="layer"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="heading_box">
                    <h3>{{$service->title}} </h3>
                    <h2>{{$service->description}}</h2>
                    <p>{!! $service->content !!}</p>
                    <div class="d-lg-flex justify-content-between">
                        @foreach(array_chunk($services->toArray(), 3) as $chunk)
                            <ul class="serv_list">
                                @foreach($chunk as $service)
                                    <li><a href="#"><i class="fa-solid fa-check"></i> {!! app()->getLocale() =='ar' ? $service['name_ar'] : $service['name_en'] !!}</a></li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                    <button  onclick="window.location='{{route('services.index')}}'" class="btn btn-1 px-lg-5  mt-3">@lang('site.show_services')</button>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="serv_img">
                    <img src="{{asset('website/assets/images/man.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
