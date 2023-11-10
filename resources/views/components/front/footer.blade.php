 <footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="foot">
                    <a href="#">
                        <img src="{{asset('assets_'.app()->getLocale())}}/images/logo.png" alt="">

                    </a>
                    <p>{{$settings->{"footer_word_".app()->getLocale()} }}</p>
                    <ul class="social-foot d-flex">
                        <li>
                            <a href="{{$settings->instagram}}">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{$settings->twitter}}"> <i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="{{$settings->youtube}}"> <i class="fa-brands fa-youtube"></i></i></a>
                        </li>
                        <li>
                            <a href="{{$settings->facebook}}"> <i class="fa-brands fa-facebook-f"></i></a>
                        </li>

                    </ul>
                    <ul class="contact-list">
                        <h5>@lang('site.contact_us')</h5>

                        <li><span>@lang('site.email')</span></li>
                        <li><a href="#">{{$settings->email}}</a></li>
                        <li><span>@lang('site.call_us')</span></li>
                        <li><a href="#">{{$settings->mobile}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="foot">
                    <h5>@lang('site.about_us')</h5>
                
                    <ul class="contact-list">
                            <li><a href="{{route('show_page',2)}}">{{ trans('site.terms_of_service') }}</a></li>
                            <li><a href="{{route('show_page',3)}}">{{ trans('site.privacy_policy') }}</a></li>
                    </ul> 

                </div>
            </div>

        </div>
    </div>
    <a class="floating-btn" href="#">
        <i class="fa-solid fa-chevron-up"></i>
    </a>
</footer>
