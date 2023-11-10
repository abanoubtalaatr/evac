@section('content')
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head text-center">
                        <h3>@lang("site.services")</h3>
                        <h2>@lang("site.explore_car_services")</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="services_warpp">
        <div class="container">
            <div class="row">
                @foreach($records as $record)
                <div class="col-lg-4 col-md-6">
                    <div class="serv_card">
                        <div class="card">
                            <img src="{{$record->image}}" class="card-img-top" style="height: 245px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{app()->getLocale() == 'ar' ? $record->name_ar : $record->name_en}}</h5>
                                <p class="card-text">{{app()->getLocale() == 'ar' ?  \Illuminate\Support\Str::limit($record->description_ar, 100)  : \Illuminate\Support\Str::limit($record->description_en, 100)}} </p>
                                <a href="{{route('services.show', $record->id)}}" class="btn btn-2 w-100 mt-3">{{trans('site.service_details')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                    <!-- start pagination bar -->
                <div class="col-12 mt-4 d-flex justify-content-center">
                    {{$records->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection
