<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.order_details')</h2>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="myCar_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="order_req">
                        <h3>@lang('site.initial_information')</h3>
                        <div class="d-lg-flex">
                            <ul>
                                <li class="d-flex"><h4>@lang('site.order_id')</h4> <h5>{{$order->id}}</h5></li>
                                <li class="d-flex"><h4>@lang('site.order_status') </h4> <h5>{{trans("site.$order->status")}}</h5></li>
                                <li class="d-flex"><h4>@lang('site.order_created_at')</h4> <h5>{{\Illuminate\Support\Carbon::parse($order->created_at)->format('Y/m/d')}}</h5></li>
                                <li class="d-flex"><h4>@lang("site.payment_type") </h4> <h5>{{trans("site.$order->payment_type")}}</h5></li>
                                <li class="d-flex"><h4>@lang('site.address')</h4> <h5>{{$order->address}}</h5></li>

                            </ul>
                            <ul>
                                <li class="d-flex"><h4>@lang('site.service_name')</h4> <h5>{{$order->service->name}}</h5></li>
                                <li class="d-flex">
                                    <h4>@lang("site.sub_services")</h4>

                                        @if(isset($order->subServices))
                                        @foreach($order->subServices as $subService)
                                            <h5 class="bg-info p-2 rounded">
                                                {{$subService->name}}
                                            </h5>
                                        @endforeach
                                        @endif

                                </li>
                                <li class="d-flex"><h4>@lang("site.appointment")</h4> <h5>{{\Illuminate\Support\Carbon::parse($order->appointment)->format("Y/m/d")}}</h5></li>
                                <li class="d-flex"><h4>@lang("site.location") </h4> <h5 class="orange"><a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$order->latitude}},{{$order->longitude}}">google map</a></h5></li>

                            </ul>
                        </div>
                    </div>
                    <div class="order_req">
                        <h3>@lang('site.order_owner_info')</h3>
                        <div class="d-lg-flex">
                            <ul>
                                <li class="d-flex"><h4>@lang('site.full_name') </h4> <h5>{{$order->user->username}}</h5></li>
                                <li class="d-flex"><h4>@lang('site.mobile') </h4> <h5>{{$order->user->mobile}}</h5></li>
                                <li class="d-flex"><h4>@lang('site.email')</h4> <h5>{{$order->user->email}}</h5></li>


                            </ul>

                        </div>
                    </div>
                    <div class="order_req">
                        <h3>@lang("site.car_info")</h3>
                        <div class="d-lg-flex">
                            <ul>
                                <li class="d-flex"><h4> @lang("site.car_brand")</h4> <h5>{{$order->car->carBrand->name}}</h5></li>
                                <li class="d-flex"><h4> @lang("site.car_module")</h4> <h5>{{$order->car->carModule->name}}</h5></li>
                                <li class="d-flex"><h4> @lang("site.manufacturing_year")</h4> <h5>{{$order->car->manufacturing_year}}</h5></li>


                            </ul>
                            <ul>
                                <li class="d-flex"><h4>@lang("site.car_color") </h4> <h5>{{$order->car->color}}</h5></li>
                                <li class="d-flex"><h4>@lang("site.plate") </h4> <h5>{{$order->car->plat_number . ' - ' . $order->car->plat_char}}</h5></li>
                                <li class="d-flex"><h4>@lang("site.number_of_engine_valves")</h4> <h5>{{$order->car->number_of_engine_valves}}</h5></li>


                            </ul>
                            <ul>
                                <li class="d-flex"><h4>@lang("site.fuel_type")</h4> <h5>{{$order->car->fuel_type}}</h5></li>
                            </ul>
                        </div>
                    </div>
                    <div class="order_req">
                        <h3>@lang("site.oil_info")</h3>
                        <div class="d-lg-flex">
                            <ul>
                                <li class="d-flex"><h4> @lang("site.oil_brand")</h4> <h5>{{$order->oilBrand ? $order->oilBrand->name:""}}</h5></li>
                                <li class="d-flex"><h4>@lang("site.oils")</h4> <h5>{{$order->oil ? $order->oil->name :''}}</h5></li>
{{--                                <li class="d-flex"><h4> العدد للعبوات</h4> <h5>587605289</h5></li>--}}
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
