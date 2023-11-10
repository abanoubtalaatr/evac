<!--head-->
<div class="head-notifi p-3">
    <div id="menu-toggle"><i class="fas fa-bars"></i></div>
    <h3></h3>
    <ul class="notifi-head">
        @can('Manage notifications')
        <li class="notifi-li">
            <a href="#">

                <div class="n-wrap">

                    <a href="{{route('admin.notifications')}}">
                        @if(\App\Models\Notification::query()->where('is_admin', 1)->where('when_read', null)->count() > 0)

                            <div class="notifi-dot"></div>
                        @endif
                    <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/bell.png" alt="">
                    </a>
                </div>

            </a>
        </li>
        @endcan

{{--        <li class="notifi-li">--}}
{{--            <a href="{{ LaravelLocalization::getLocalizedURL(--}}
{{--    app()->getLocale() == 'en' ? 'ar' : 'en',--}}
{{--    route('admin.dashboard'),--}}
{{--    []--}}
{{--) }}">--}}
{{--                <img--}}
{{--                    src="{{ asset('frontAssets')}}/assets_{{ app()->getLocale() }}/imgs/home/{{ app()->getLocale() == 'en' ? 'sa.svg' : 'us.png' }}"--}}
{{--                    alt="">--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        --}}{{-- <a href="{{route('admin.edit_profile')}}">--}}
{{--                <img src="{{auth('admin')->user()->avatar_url}}" alt="">--}}
{{--        </a> --}}
    </ul>
    <div class="row">
        <div class="col-6">
            <span class="d-block">Welcome <strong>{{auth('admin')->user()->name}} |</strong> Logged from {{request()->ip()}}</span>
            <span class="d-block text-info fa-3 my-3">Lebanon Emirates Visa Application Center - EVAC</span>
        </div>
        @php
            $carbonDateTime = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', auth('admin')->user()->last_login_at);
             $formattedDateTime = $carbonDateTime->format('D d M Y H:i:s a');
         @endphp
        <div class="col-6">
            <span class="d-block">Last login on  <strong>{{$formattedDateTime}}</strong></span>

            @php
                $data = \App\Helpers\displayTextInNavbarForOfficeTime(1);

            @endphp
            @if(!empty($data['prefix']))
            <span class="d-block text-success fa-3 my-3">{{$data['prefix']}} <strong> {{auth('admin')->user()->name}}</strong> on {{$data['day'] .' '. $data['time']}} </span>
            @else
                <span class="d-block text-success fa-3 my-3">Please start your day</span>
            @endif
        </div>
    </div>

</div>
