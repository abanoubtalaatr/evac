<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.my_orders')</h2>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="myCar_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table_wrapper table-responsive border rounded">
                        <table class="table table-borderless mb-0">
                            <!-- start table head -->

                            <tbody><tr>
                                <th scope="col">@lang('site.order_id')</th>
                                <th scope="col">@lang('site.order_status')</th>
                                <th scope="col">@lang('site.order_created_at') </th>
                                <th scope="col">@lang('site.service_name') </th>
                                <th scope="col">@lang('site.appointment')</th>
                                <th scope="col">@lang('site.payment_type')</th>
                                <th scope="col">&nbsp;</th>


                            </tr>
                            <!-- start table body -->
                            @foreach($records as $record)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{trans("site.$record->status")}}</td>
                                    <td>{{\Illuminate\Support\Carbon::parse($record->created_at)->diffForHumans()}}</td>
                                    <td>{{$record->service? $record->service->name :''}}</td>
                                    <td>{{\Illuminate\Support\Carbon::parse($record->appointment)->format('Y/m/d')}}</td>
                                    <td>{{trans("site.$record->payment_type")}}</td>

                                    <td>
                                        <ul class="d-flex justify-content-evenly">
                                            <li><a href="{{route('user.orders.show', ['order'  =>$record->id])}}" class="btn btn-2">@lang('site.service_details')</a></li>
                                            @if($record->is_paid == 0 && $record->payment_type == 'online')
                                              <li><a href="" class="btn btn-2">@lang('site.complete_payment')</a></li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody></table>
                    </div>
                    <div class="col-12 mt-4 d-flex justify-content-center">
                        {{$records->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
