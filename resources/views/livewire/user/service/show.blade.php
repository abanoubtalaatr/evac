<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>{{app()->getLocale() == 'ar' ? $service->name_ar : $service->name_en}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="services_details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="serv_detail_info">
                        <p>{{app()->getLocale() =='ar' ? $service->description_ar : $service->description_en}}</p>

                        <div class="mt-3">
                            @if($service->subServices()->count() > 0)
                            <div class="my-3 row">
                                <h3 class="mb-3">{{trans('site.sub_services')}}</h3>
                                    @foreach($service->subServices as $subService)
                                        <div wire:ignore class="form-group col-md-6">
{{--                                            <input disabled wire:click="updatePrice({{$subService->id}})" type="checkbox" id="{{$subService->id}}">--}}
                                            <h5 for="{{$subService->id}}">
                                               - {{ app()->getLocale() == 'ar' ? $subService->name_ar : $subService->name_en }}
                                                <span>{{$subService->price}} - @lang('site.ryal')</span>
                                            </h5>
                                        </div>
                                    @endforeach
                            </div>
                            @endif
                            <div class="total_price mb-3">
                                <h4>{{trans('site.total_service_cost')}}</h4>
                                <h2>{{$price}} @lang('site.ryal')</h2>
                            </div>
                            <button class="btn btn-1 px-5" onclick="window.location='{{route('user.services.create')}}'">
                                @lang("site.request_service")
                            </button>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="serv_detail_img">
                        <img src="{{$service->image}}" alt="">
                    </div>
                </div>
                @if($service->video_url)
                    <div class="col-lg-6">
                        <div class="serv_detail_img">
                            <video height="100%" width="100%" controls autoplay loop >
                                <source src="{{url('uploads/pics/' . $service->video_url)}}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
