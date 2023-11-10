 <!-- start services section -->
        <section class="services">
            <div class="container">
                <div class="row">
                    <div class="col-12 ">
                        <div class="text-center ser-head mb-5">
                            <h2>@lang('site.our_services')</h2>
                            <p>@lang('site.we_provide_best_quality')</p>

                        </div>

                    </div>

                        @foreach($pages as $page)
                        <div class="col-lg-4">
                            <div class="ser text-center">
                                <img src="{{$page->picture_url}}" alt="">
                                <h3>{{$page->{"title_".app()->getLocale()} }}</h3>
                                <p>{{$page->{"desc_".app()->getLocale()} }}</p>
                                <a href="{{route('show_page',$page->id)}}" class="btn btn-2">@lang('site.read_more') <i class="fa-solid fa-chevron-right"></i></a>
                            </div>
                        </div>
                        @endforeach


                </div>
            </div>
        </section>
