<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang("site.notifications")</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="notif_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @foreach($records as $record)
                    <div class="notif_box d-md-flex align-items-end justify-content-between">
                        <div class="">
                            <h3>@lang("site.order_id") {{$record->subject_id}}</h3>
                            <p>
                                {{$record->content}}
                                <a href="{{route('user.orders.show', ['order' => $record->subject_id])}}"> @lang("site.order_details") </a>

                                @if(config('appMessages.notifications.order.finish.title_en') == $record->title_en )
                                    @if(\App\Helpers\userRateServiceBefore(auth()->id(), $record->subject_id))
                                     <span class="btn btn-warning">@lang('site.you_rate_the_service_before')</span>
                                    @else
                                        <a href="{{route('user.opinion',['order' => $record->subject_id])}}" class="btn btn-info text-white">@lang('site.rate_service')</a>
                                    @endif
                                @endif
                            </p>
                        </div>
                        <span>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y/m/d H:m:s')}}</span>
                    </div>
                    @endforeach
                        <div class="col-12 mt-4 d-flex justify-content-center">
                            {{$records->links()}}
                        </div>
                </div>
            </div>
        </div>
    </section>
</div>
