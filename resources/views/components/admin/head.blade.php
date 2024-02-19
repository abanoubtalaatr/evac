<!--head-->
<div class="head-notifi p-3 form_wrapper">
    <div id="menu-toggle"><i class="fas fa-bars"></i></div>
    <h3></h3>
    <ul class="notifi-head">


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
            $carbonDateTime = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', isset(auth('admin')->user()->last_login_at)?auth('admin')->user()->last_login_at:now());
             $formattedDateTime = $carbonDateTime->format('D d M Y H:i:s a');
         @endphp
        <div class="col-6">
            <span class="d-block">Last login on  <strong>{{$formattedDateTime}}</strong></span>

            @php
                $data = \App\Helpers\displayTextInNavbarForOfficeTime(1);
            @endphp

{{--            @if(!empty($data['prefix']))--}}
{{--            <span class="d-block text-success fa-3 my-3">{{$data['prefix']}} <strong> {{$data['user']}}</strong> on {{$data['day'] .' '. $data['time']}} </span>--}}
{{--            @else--}}
{{--                <span class="d-block text-success fa-3 my-3">Please start your day</span>--}}
{{--            @endif--}}

            @foreach($data as $item)
             <span  class="d-block text-success fa-3 my-3 @if($item['prefix'] == 'Day closed by') text-danger @endif">
                  <span class=""> {{$item['prefix']}}</span>
                 <strong> {{$item['user']}}</strong>
                 on {{$item['day'] .' '. $item['time']}}
             </span>
            @endforeach
        </div>
    </div>

</div>
