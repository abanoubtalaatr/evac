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
            <div class="logo-wrap bg-white mb-5 " style="height: 100px">
                <x-logo  />
            </div>

                @if(auth('admin')->user()->hasRole('super admin'))
                    <li>
                        <a href="{{route('admin.dashboard')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/dashboard.svg"
                                 alt="">
                            @lang('site.dashboard')
                        </a>
                    </li>
                @else
                    @can('Manage dashboard')
                        <li>
                            <a href="{{route('admin.dashboard')}}" class="text-white">
                                <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/dashboard.svg"
                                     alt="">
                                @lang('site.dashboard')
                            </a>
                        </li>
                    @endcan
               @endif
            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.admins.index')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.admins')
                    </a>
                </li>
            @else
                @can('Manage admins')
                    <li>
                        <a href="{{route('admin.admins.index')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('site.admins')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.role')}}"  class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('site.roles')
                    </a>
                </li>
            @else
                @can('Manage roles')
                    <li>
                        <a href="{{route('admin.role')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('site.roles')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.day_office')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.day_office')
                    </a>
                </li>

            @else
                @can('Manage day office')
                    <li>
                        <a href="{{route('admin.day_office')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.day_office')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.travel_agents')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.agents')
                    </a>
                </li>

            @else
                @can('Manage travel agents')
                    <li>
                        <a href="{{route('admin.travel_agents')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.agents')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.visa_types')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.visa_types')
                    </a>
                </li>

            @else
                @can('Manage visa types')
                    <li>
                        <a href="{{route('admin.visa_types')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.visa_types')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.applicants')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.applicants')
                    </a>
                </li>

            @else
                @can('Manage applicants')
                    <li>
                        <a href="{{route('admin.applicants')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.applicants')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.services')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.services')
                    </a>
                </li>

            @else
                @can('Manage services')
                    <li>
                        <a href="{{route('admin.services')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.services')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.service_transactions')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.service_transactions')
                    </a>
                </li>

            @else
                @can('Manage service transactions')
                    <li>
                        <a href="{{route('admin.service_transactions')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.service_transactions')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.visa_providers')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.visa_providers')
                    </a>
                </li>

            @else
                @can('Manage visa providers')
                    <li>
                        <a href="{{route('admin.visa_providers')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.visa_providers')
                        </a>
                    </li>
                @endcan
            @endif


            @if(auth('admin')->user()->hasRole('super admin'))

                    <li>
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('admin.applications')
                    </li>
                <li style="line-height: 20px;margin-left: 55px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">
                        <a href="{{route('admin.applications.store')}}" class="text-white">
                            @lang('admin.new_applications')
                        </a>
                    </li>
                <li style="line-height: 20px;margin-left: 55px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">
                        <a href="{{route('admin.applications.appraisal')}}" class="text-white">
                            @lang('admin.appraisal')
                        </a>
                    </li>
                <li style="line-height: 20px;margin-left: 55px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">
                    <a href="{{ route('admin.applications.appraised') }}" class="text-white">
                        @lang('admin.appraised')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 55px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">
                    <a href="{{route('admin.applications.revise')}}" class="text-white">
                        @lang('admin.revise')
                    </a>
                </li>
            @else
                @can('Manage visa applications')
                    <li>
                        <a href="{{route('admin.applications')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('admin.applications')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->hasRole('super admin'))
                <li>
                    <a href="{{route('admin.settings')}}" class="text-white">
                        <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                        @lang('messages.settings')
                    </a>
                </li>
            @else
                @can('Manage settings')
                    <li>
                        <a href="{{route('admin.settings')}}" class="text-white">
                            <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">
                            @lang('messages.settings')
                        </a>
                    </li>
                @endcan
            @endif


            <li>
                <a href="{{route('admin.profile')}}" class="text-white">
                    <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/tasks.svg" alt="">@lang('site.profile')
                </a>
            </li>
            <li>
                <a href="{{route('admin.logout')}}" class="text-white">
                    <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/logout.svg" alt="">@lang('messages.logout')
                </a>
            </li>
        </div>
    </div>
    <div id="page-content-wrapper">
        <!-- Main Content-->
    {{ isset($slot)? $slot : ''}}
    @yield('content')
    <!-- End Main Content-->
        <!-- Main footer-->
        <footer class="main-footer">
            <p>All rights reserved {{date('Y')}} - Evac system</p>
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
