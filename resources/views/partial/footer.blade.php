<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="foot">
                    <img src="{{asset('website/assets/images/logo-foot.svg')}}" alt="">

                </div>
            </div>
            <div class="col-lg-4">
                <div class="foot">
                    <p class="mt-3">{{$settings->brief}}</p>
                    <ul class="social_top_right d-flex mt-3">
                        <li><a target="_blank" href="{{$settings->twitter}}"><img src="{{asset('website/assets/images/x.svg')}}" alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->instagram}}"><img src="{{asset('website/assets/images/insta.svg')}}" alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->youtube}}"><img src="{{asset('website/assets/images/youtube.svg')}}" alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->facebook}}"><img src="{{asset('website/assets/images/facebook.svg')}}" alt=""></a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>@lang("site.services")</h5>
                    <ul>
                        <li><a href="#">@lang('site.preventive_maintenance')</a></li>
                        <li><a href="#">@lang('site.services_engine')</a></li>
                        <li><a href="#">@lang('site.tires_and_wheels')</a></li>
                        <li><a href="#">@lang('site.repairs')</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>@lang("site.support")</h5>
                    <ul>
                        <li><a href="#">@lang('site.center_of_help')</a></li>
                        <li><a href="{{route('page', ['page' => 'privacy'])}}">@lang('site.privacy_policy')</a></li>
                        <li><a href="{{route('contact')}}">@lang('site.contact_us')</a></li>
                        <li><a href="#">@lang('site.questions_and_answers')</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>@lang("site.company")</h5>
                    <ul>
                        <li><a href="{{route('about')}}">@lang('site.about_us')</a></li>
                        <li><a href="#"> @lang('site.news_and_articles')</a></li>
                        <li><a href="{{route('page', ['page' => 'terms'])}}">@lang('site.terms_and_conditions')</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="copy_right text-center">
                <p>حقوق النشر © 2023 <a href="#">بوجى</a> جميع الحقوق محفوظ       -     برمجة وتصميم <a href="#">فيودكس</a></p>
            </div>
        </div>
    </div>
</footer>
<!--video Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video autoplay loop  controls>
                    <source src="{{asset(url('uploads/pics/' . $settings->video_url))}}" type="video/mp4">
                </video>
            </div>

        </div>
    </div>
</div>
