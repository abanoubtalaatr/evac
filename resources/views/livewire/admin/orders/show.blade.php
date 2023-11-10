<main class="main-content">
    <x-admin.head/>
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">
            <hr>
            <h4 class="">@lang('site.important_info')</h4>
            <div class="col-lg-12 d-flex align-items-center">
                <div class="col-2">
                  <span> @lang('site.order_number') : </span> <span class="mx-2">{{$order->id}}</span>
                </div>
                <div class="col-2">
                    <span> @lang('site.status') : </span> <span class="mx-2">@lang('site.'.$order->status)</span>
                </div>
                <div class="col-2">
                    <span> @lang('site.created_at') : </span> <span class="mx-2">{{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}</span>
                </div>
                <div class="col-3">
                    <span> @lang('site.service') : </span> <span class="mx-2">{{app()->getLocale() =='ar' ? $order->service->name_ar: $order->service->name_en}}</span>
                </div>
                @if($order->subServices)
                    <div class="col-3">
                        @foreach($order->subServices as $subService)
                            <div>
                            <span> @lang('site.sub_service') : </span> <span class="mx-2">{{app()->getLocale() =='ar' ? $subService->name_ar: $subService->name_en}}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-12 d-flex align-items-center mt-3">
{{--                <div class="col-4">--}}
{{--                    <span> @lang('site.address') : </span> <span class="mx-2">{{ $order->address}}</span>--}}
{{--                </div>--}}
                <div class="col-2">
                    <span> @lang('site.payment_type') : </span> <span class="mx-2">{{ $order->payment_type}}</span>
                </div>
                <div class="col-2">
                    <span> @lang('site.appointment') : </span> <span class="mx-2">{{ \Carbon\Carbon::parse($order->appointment)->format('Y-m-d')}}</span>
                </div>

                <div class="col-2">
                    <span> @lang('site.total') : </span> <span class="mx-2">{{$order->total}}</span>
                </div>
            </div>
            <hr class="my-3">
            <h4 class="">@lang('site.order_owner_info')</h4>
            <div class="col-12 d-flex align-items-center my-2">
                <div class="col-4">
                    <span> @lang('site.name') : </span> <span class="mx-2">{{$order->user->username}}</span>
                </div>
                <div class="col-4">
                    <span> @lang('site.mobile') : </span> <span class="mx-2">{{$order->user->mobile}}</span>
                </div>
                <div class="col-4">
                    <span> @lang('site.email') : </span> <span class="mx-2">{{$order->user->email}}</span>
                </div>
            </div>
            <hr>
            <h4 class="">@lang('site.car_info')</h4>
            <div class="col-12 d-flex align-items-center my-2">
                <div class="col-4">
                    <span> @lang('site.car_brand') : </span> <span class="mx-2">{{app()->getLocale() =='ar'?$order->car->carBrand->name_ar:$order->car->carBrand->name_en}}</span>
                </div>
                <div class="col-3">
                    <span> @lang('site.car_module') : </span> <span class="mx-2">{{app()->getLocale() =='ar'?$order->car->carModule->name_ar:$order->car->carModule->name_en}}</span>
                </div>
                <div class="col-3">
                    <span> @lang('site.manufacturing_year') : </span> <span class="mx-2">{{$order->car->manufacturing_year}}</span>
                </div>
                <div class="col-2">
                    <span> @lang('site.car_color') : </span> <span class="mx-2"><input type="color" value="{{$order->car->color}}"></span>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center my-2">

                <div class="col-5">
                    <span> @lang('site.car_plat_number') : </span> <span class="mx-2">{{$order->car->plat_number}}</span>
                    <span> @lang('site.car_plat_char') : </span> <span class="mx-2">{{$order->car->plat_char}}</span>

                </div>
                <div class="col-4">
                    <span> @lang('site.number_of_engine_valves') : </span> <span class="mx-2">{{$order->car->number_of_engine_valves}}</span>
                </div>
                <div class="col-3">
                    <span> @lang('site.fuel_type') : </span> <span class="mx-2">{{$order->car->fuel_type}}</span>
                </div>
            </div>
            </div>
        <hr>
        <h4 class="">@lang('site.oil_info')</h4>
        <div class="col-12 d-flex align-items-center">
            <div class="col-4">
                <span> @lang('site.oils') : </span> <span class="mx-2">{{app()->getLocale() =='ar' ? $order->oil->name_ar : $order->oil->name_en}}</span>
            </div>

            <div class="col-4">
                <span> @lang('site.oil_brand') : </span> <span class="mx-2">{{app()->getLocale() =='ar' ? $order->oilBrand->name_ar : $order->oilBrand->name_en}}</span>
            </div>
            <div class="col-4">
                <span> @lang('site.number_of_kilo') : </span> <span class="mx-2">{{$order->number_of_kilo}}</span>
            </div>
        </div>
        <hr>
        <h4>@lang("site.location") </h4>
        <h5 class="btn btn-info"><a target="_blank" class="text-white" href="https://www.google.com/maps/search/?api=1&query={{$order->latitude}},{{$order->longitude}}">@lang('site.location_on_map')</a>
        </h5>
        <hr>
        <div id="map" style="width: 100%; height: 500px;"></div>

    </div>
</main>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&amp;callback=initMap"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: {{$order['latitude']}}, lng: {{$order['longitude']}}} ,// Use the Blade syntax to access the values
            title: "Hello World!",
        });
    }
    initMap();
</script>
