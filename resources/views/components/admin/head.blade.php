<!--head-->
<div class="head-notifi">
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
        <li class="notifi-li">
            <a href="{{ LaravelLocalization::getLocalizedURL(
    app()->getLocale() == 'en' ? 'ar' : 'en',
    route('admin.dashboard'),
    []
) }}">
                <img
                    src="{{ asset('frontAssets')}}/assets_{{ app()->getLocale() }}/imgs/home/{{ app()->getLocale() == 'en' ? 'sa.svg' : 'us.png' }}"
                    alt="">
            </a>
        </li>
{{--        --}}{{-- <a href="{{route('admin.edit_profile')}}">--}}
{{--                <img src="{{auth('admin')->user()->avatar_url}}" alt="">--}}
{{--        </a> --}}
    </ul>
</div>
