<header>
    <div class="sub_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    @php $settings = \App\Models\Setting::query()->first(); @endphp
                    <ul class="social_top_right d-flex">
                        <li><a target="_blank" href="{{$settings->twitter}}"><img src="{{asset('website/assets/images/x.svg')}}" alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->instagram}}"><img  src="{{asset('website/assets/images/insta.svg')}}"alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->youtube}}"><img  src="{{asset('website/assets/images/youtube.svg')}}" alt=""></a></li>
                        <li><a target="_blank" href="{{$settings->facebook}}"><img  src="{{asset('website/assets/images/facebook.svg')}}" alt=""></a></li>

                    </ul>
                </div>

                <div class="col-md-6 d-md-flex justify-content-end">
                    <ul class="social_top_left d-flex">
                        <li><a href="#">{{$settings ? $settings->email:""}}<img src="{{asset('website/assets/images/Mail.svg')}}" alt=""></a></li>
                        <li><a href="#">{{$settings ? $settings->mobile:""}} <img  src="{{asset('website/assets/images/phone.svg')}}" alt=""></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('website/assets/images/logo.svg')}}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{route('home')}}">@lang("site.home_page")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('services.index')}}">@lang("site.services")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('about')}}">@lang("site.about_us")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('contact')}}">@lang('site.contact_with_us')</a>
                    </li>
                    <!-- in case login un hash this -->
                    @if(auth('users')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.profile')}}"><svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6328 7.31363C16.6328 10.509 14.6425 13.2514 11.9922 13.2514C9.33979 13.2514 7.35154 10.509 7.35154 7.31263C7.35154 4.11823 9.06342 2.25 11.9922 2.25C14.9209 2.25 16.6328 4.11723 16.6328 7.31363ZM3.84736 20.3943C4.24439 20.8523 5.9542 22.2525 11.9922 22.2525C18.0301 22.2525 19.7389 20.8523 20.137 20.3953C20.1739 20.3513 20.2012 20.3006 20.2171 20.246C20.2331 20.1915 20.2374 20.1344 20.2298 20.0782C20.139 19.1961 19.3202 15.2516 11.9922 15.2516C4.66411 15.2516 3.84529 19.1961 3.75351 20.0782C3.74604 20.1345 3.75052 20.1916 3.76665 20.2462C3.78279 20.3007 3.81026 20.3504 3.84736 20.3943Z"></path>
                                </svg>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.cars')}}">
                                <img height="24" width="25" src="{{asset('website/assets/images/steps/car.svg')}}">
                            </a>
                        </li>

                        {{--                        orders--}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.orders')}}">
                                <span class="notif">

                                </span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_2_5658)">
                                        <path d="M2 20C2 21.1 2.9 22 4 22H20C21.1 22 22 21.1 22 20V7H17.91C17.4315 4.167 14.967 2 12 2C9.033 2 6.5685 4.167 6.0905 7H2V20ZM12 4C13.8595 4 15.4225 5.2775 15.8685 7H8.1315C8.5775 5.2775 10.1405 4 12 4Z"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2_5658">
                                            <rect width="24" height="24" fill="white"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </li>
                        <!-- in case login un hash this -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{route("user.notifications")}}">
                                <span class="notif">
                                  {{auth('users')->user()->notifications()->where('is_admin', null)->where('when_read', null)->count()}}
                                </span>
                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.0294 12.74L14.0294 11.08C13.8194 10.71 13.6294 10.01 13.6294 9.6V7.07C13.6294 4.72 12.2494 2.69 10.2594 1.74C9.73936 0.82 8.77936 0.25 7.67936 0.25C6.58936 0.25 5.6094 0.84 5.0894 1.77C3.1394 2.74 1.7894 4.75 1.7894 7.07V9.6C1.7894 10.01 1.5994 10.71 1.3894 11.07L0.379396 12.74C-0.0206042 13.41 -0.110604 14.15 0.139396 14.83C0.379396 15.5 0.949396 16.02 1.6894 16.27C3.6294 16.93 5.6694 17.25 7.70936 17.25C9.74936 17.25 11.7894 16.93 13.7294 16.28C14.4294 16.05 14.9694 15.52 15.2294 14.83C15.4894 14.14 15.4194 13.38 15.0294 12.74Z"></path>
                                </svg>
                            </a>
                        </li>

                    @endif
                    <li class="nav-item">
                        <div class="nav-link">
                                  <span class="language-icon" onclick="changeLanguage()">
<img style="    cursor: pointer;" width="25" height="24" src="https://img.icons8.com/ios-filled/50/globe--v2.png" alt="globe--v2"/>
                                 </span>
                        </div>

                    </li>
                    <li class="nav-item">
                        @if(auth('users')->check())
                            <a class="nav-link btn_login" href="{{route('user.logout')}}"> @lang('site.logout')</a>

                        @else
                            <a class="nav-link btn_login" href="{{route('user.login')}}"> @lang('site.login')</a>

                        @endif
                    </li>


                    <!-- <li class="nav-item">
                        <a class="nav-link  btn_login2 " href="#">حساب جديد</a>
                    </li> -->
                </ul>

            </div>
        </div>
    </nav>
</header>
