<!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="{{route('admin.home')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                   </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="{{route('admin.home')}}" class="nav-link"> {{ config('app.name') }} </a>
                </li>
            </ul>



            <ul class="navbar-item flex-row ml-md-auto">
                <li class="nav-item dropdown language-dropdown">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @continue($localeCode == LaravelLocalization::getCurrentLocale())
                        <a class="nav-link" data-language="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            <i class="flag-icon flag-icon-{{ $localeCode == 'en' ? 'us' : 'sa' }}"></i>
                                {{ $properties['native'] }}
                        </a>
                        @endforeach
                    </li>


																<li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userChatDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#ffffff"><path d="M143.33333,21.5h-114.66667c-7.88333,0 -14.26167,6.45 -14.26167,14.33333l-0.0645,111.68533c0,6.3855 7.7185,9.589 12.2335,5.074l16.426,-16.426h100.33333c7.88333,0 14.33333,-6.45 14.33333,-14.33333v-86c0,-7.88333 -6.45,-14.33333 -14.33333,-14.33333zM64.5,78.83333c0,3.956 -3.21067,7.16667 -7.16667,7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667c3.956,0 7.16667,3.21067 7.16667,7.16667zM93.16667,78.83333c0,3.956 -3.21067,7.16667 -7.16667,7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667c3.956,0 7.16667,3.21067 7.16667,7.16667zM121.83333,78.83333c0,3.956 -3.21067,7.16667 -7.16667,7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667c3.956,0 7.16667,3.21067 7.16667,7.16667z"></path></g></g></svg>
																					@if(count($header_chats))<span class='btn btn-danger btn-sm'>*</span> @endif
																				</a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userChatDropdown">
                        <div class="">
																										@foreach($header_chats as $header_chat)
                            <div class="dropdown-item">
                                <a href="{{route('chat.user',$header_chat['id'])}}">
																																	{{$header_chat['id_number']}}
																																	<span class='btn btn-danger btn-sm'>{{$header_chat['unread_chats_count']}}</span>
																																</a>
                            </div>
																										@endforeach
                        </div>
                    </div>
                </li>



                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a href="{{route('profile.page')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> {{trans('messages.edit_profile')}}</a>
                            </div>

                            <div class="dropdown-item">
                                {!! Form::open(['route' => 'logout' , 'method' => 'POST' , 'id' => 'logout_form']) !!}
                                    <a  onclick="document.getElementById('logout_form').submit();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        {{trans('messages.logout')}}</a>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">{{trans('messages.control_panel')}}</li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@yield('navTitle')</span></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
            <ul class="navbar-nav flex-row ml-auto ">
                <li class="nav-item more-dropdown">
                    <div class="dropdown  custom-dropdown-icon">
                        <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>{{ trans('messages.settings') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                            <a class="dropdown-item" data-value="{{ trans('messages.settings') }}" href="{{route('settings.app.page')}}">{{ trans('messages.app') }}</a>
                            <a class="dropdown-item" data-value="{{ trans('messages.settings') }}" href="{{route('settings.contact.page')}}">{{ trans('messages.contact') }}</a>
                            <a class="dropdown-item" data-value="{{ trans('messages.settings') }}" href="{{route('settings.eligibility-conditions.page')}}">@lang('site.acceptance_settings')</a>
																												<a class="dropdown-item" data-value="{{ trans('messages.settings') }}" href="{{route('settings.homepage')}}">{{ trans('messages.homepage_setting') }}</a>
                        </div>


                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->
