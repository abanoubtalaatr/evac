<!DOCTYPE html>
<html class="no-js">

<head>
    <title>@lang('site.site_title') @isset($page_title) {{ ' - '.$page_title}}  @endisset</title>
    <!-- Google Tag Manager -->

    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="description">
    <meta name="Sard" content="sard">
    <meta name="robots" content="index">
    <link rel="icon" href="{{asset('favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/css/style.css">
    <link rel="stylesheet"
          href="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/css/{{app()->getLocale()}}Style.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link defer rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="{{asset('frontAssets/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('frontAssets/plugins/toastr/toastr.min.css')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @livewireStyles()
    @stack('styles')

</head>


<body class="home-page" x-data x-on:saved="toastr.success($event.detail.message);">

<div id="wrapper">
    <!--Sidebar-->
    <div id="sidebar-wrapper">
        <div class="sidebar-nav">
            <div class="logo-wrap bg-white mb-5">
                <x-logo />
            </div>
            @can('Manage dashboard')
                <li>
                    <a href="{{route('admin.dashboard')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/dashboard.svg"
                             alt="">
                        @lang('site.dashboard')
                    </a>
                </li>
            @endcan

            @can('Manage orders')
                <li>
                    <a href="{{route('admin.orders')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.orders')
                    </a>
                </li>
            @endcan
            @can('Manage admins')
                <li>
                    <a href="{{route('admin.admins.index')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.admins')
                    </a>
                </li>
            @endcan
            @can('Manage roles')
                <li>
                    <a href="{{route('admin.role')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.roles')
                    </a>
                </li>
            @endcan
            @can('Manage users')
                <li>
                    <a href="{{route('admin.users.index')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.users')
                    </a>
                </li>
            @endcan

            @can('Manage car brands')
                <li>
                    <a href="{{route('admin.car_brands')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.car_brands')
                    </a>
                </li>
            @endcan

            @can('Manage car modules')
                <li>
                    <a href="{{route('admin.car_modules')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.car_modules')
                    </a>
                </li>
            @endcan

{{--            @can('Manage car cylinders')--}}
{{--                <li>--}}
{{--                    <a href="{{route('admin.car_cylinders')}}">--}}
{{--                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">--}}
{{--                        @lang('site.car_cylinders')--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}

            @can('Manage oil brands')
                <li>
                    <a href="{{route('admin.oil_brands')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.oil_brands')
                    </a>
                </li>
            @endcan

            @can('Manage oils')
                <li>
                    <a href="{{route('admin.oils')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.oils')
                    </a>
                </li>
            @endcan
            @can('Manage services')
                <li>
                    <a href="{{route('admin.services')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.services')
                    </a>
                </li>
            @endcan

            @can('Manage sub services')
                <li>
                    <a href="{{route('admin.sub_services')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.sub_services')
                    </a>
                </li>
            @endcan

        @can('Manage contact_us')
                <li>
                    <a href="{{route('admin.contacts')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/contact.svg" alt="">
                        @lang('site.contact_us')
                    </a>
                </li>
            @endcan

            @can('Manage pages')
                <li>
                    <a href="{{route('admin.pages.index')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('messages.pages')
                    </a>
                </li>
            @endcan

            @can('Manage sliders')
                <li>
                    <a href="{{route('admin.slider')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('general.slider')
                    </a>
                </li>
            @endcan


{{--            @can('Manage partners')--}}
{{--                <li>--}}
{{--                    <a href="{{route('admin.partner')}}">--}}
{{--                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">--}}
{{--                        @lang('site.partners')--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}


            @can('Manage settings')
                <li>
                    <a href="{{route('admin.settings')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('messages.settings')
                    </a>
                </li>
            @endcan
            @can('Manage opinions')
                <li>
                    <a href="{{route('admin.opinions')}}">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.opinions')
                    </a>
                </li>
            @endcan
            <li><a href="{{route('admin.profile')}}"><img
                        src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg"
                        alt="">@lang('site.profile')</a></li>
            <li><a href="{{route('admin.logout')}}"><img
                        src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/logout.svg"
                        alt="">@lang('messages.logout')</a></li>
        </div>
    </div>
    <div id="page-content-wrapper">
        <!-- Main Content-->
    {{ isset($slot)? $slot : ''}}
    @yield('content')
    <!-- End Main Content-->
        <!-- Main footer-->
        <footer class="main-footer">
            <p>All rights reserved {{date('Y')}} - Bogi</p>
        </footer>
        <!-- End Main footer-->
    </div>
</div>
<!-- End Main Content-->

<script src="{{asset('js/jquery.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/js/functions.js"></script>
<script src="{{asset('frontAssets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<script src="{{asset('js/jquery.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


    @if(\Illuminate\Support\Facades\Session::has('success'))
        Toast.fire({
            icon: 'success',
            title: '{!! \Illuminate\Support\Facades\Session::get('success') !!}'
    });
    @elseif(\Illuminate\Support\Facades\Session::has('error'))
        Toast.fire({
            icon: 'error',
            title: '{!! \Illuminate\Support\Facades\Session::get('error') !!}'
    });
    @endif

</script>
<style>
    .slick-list {
        height: 100% !important;
    }

    .slick-slide img {
        position: absolute;
        top: -20%;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        max-height: 80%;
        max-width: 100%;
        object-fit: contain;
    }

    .slick-slide {
        height: 230px;
        position: relative;
        text-align: center;
    }

    .select2-container--default {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple {
        padding: 10px;
    }

</style>


@livewireScripts()
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('showToast', function (data) {
            toastr[data.type](data.message);
        });
    });
</script>
@stack('scripts')
</body>

</html>
