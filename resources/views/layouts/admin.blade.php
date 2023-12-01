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

@php
    $settings  = \App\Models\Setting::query()->first();
    $background = "darkblue";
    if(isset($settings->background)){
        $background = $settings->background;
    }
@endphp
<div id="wrapper" style="background: {{$background}}">
    <!--Sidebar-->

    <div id="sidebar-wrapper" style="background: {{$background}}">
        <div class="sidebar-nav">
            <div class="logo-wrap bg-white mb-5 " style="height: 100px;padding-right: 7px">
                <x-logo  />
            </div>


            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{route('admin.dashboard')}}" class="text-white">
                    <img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/dashboard.svg"
                         alt="">

                    @lang('site.dashboard')

                </a>
            </li>
            <li class="{{ request()->routeIs('admin.day_office') ? 'active' : '' }}">
                <a href="{{route('admin.day_office')}}" class="text-white">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.day_office')</span>
                </a>
            </li>


            @if(auth('admin')->user()->id ==1)
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.applications')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.store') ? 'active' : 'abanoub' }} ">
                    <a href="{{route('admin.applications.store')}}" class="text-white  {{\App\Helpers\checkDayStart(1)? '':'disabled'}}">
                        @lang('admin.new_applications')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.appraisal') ? 'active' : '' }}">
                    <a href="{{route('admin.applications.appraisal')}}" class="text-white {{\App\Helpers\checkDayStart(1)? '':'disabled'}}">
                        @lang('admin.appraisal')
                    </a>
                </li>
{{--                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">--}}
{{--                    <a href="{{ route('admin.applications.appraised') }}" class="text-white ">--}}
{{--                        @lang('admin.appraised')--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.revise') ? 'active' : '' }}">
                    <a href="{{route('admin.applications.revise')}}" class="text-white ">
                        @lang('admin.revise')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.deleted') ? 'active' : '' }}">
                    <a href="{{route('admin.applications.deleted')}}" class="text-white ">
                        @lang('admin.deleted_applications')
                    </a>
                </li>
            @else
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.applications')</span>
                </li>
                @can("Manage new application")
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.store') ? 'active' : '' }}">
                        <a href="{{route('admin.applications.store')}}" class="text-white {{\App\Helpers\checkDayStart(1)? '':'disabled' }} ">
                            @lang('admin.new_applications')
                        </a>
                    </li>
                @endcan

                @can("Manage new application")
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.appraisal') ? 'active' : '' }}">
                        <a href="{{route('admin.applications.appraisal')}}" class="text-white {{\App\Helpers\checkDayStart(1)? '':'disabled'}}">
                            @lang('admin.appraisal')
                        </a>
                    </li>
                @endcan
{{--                @can("Manage appraised")--}}
{{--                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2">--}}
{{--                        <a href="{{ route('admin.applications.appraised') }}" class="text-white ">--}}
{{--                            @lang('admin.appraised')--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}
                @can("Manage revises")
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.revise') ? 'active' : '' }}">
                        <a href="{{route('admin.applications.revise')}}" class="text-white ">
                            @lang('admin.revise')
                        </a>
                    </li>
                @endcan
                @can("Manage Deleted applications")
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.deleted') ? 'active' : '' }}">
                        <a href="{{route('admin.applications.deleted')}}" class="text-white ">
                            @lang('admin.deleted_applications')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->id ==1)
                <li class="{{ request()->routeIs('admin.service_transactions') ? 'active' : '' }}">
                    <a href="{{route('admin.service_transactions')}}" class="text-white  ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.service_transactions')</span>
                    </a>
                </li>
            @else
                @can('Manage service transactions')
                    <li class="{{ request()->routeIs('admin.service_transactions') ? 'active' : '' }}">
                        <a href="{{route('admin.service_transactions')}}" class="text-white ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.service_transactions')</span>
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->id ==1)
                <li>
                    <a  class="text-white  ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.reports')</span>
                    </a>
                </li>
            @else
                @can('Manage reports')
                    <li >
                        <a  class="text-white ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.reports')</span>
                        </a>
                    </li>
                @endcan
            @endif


            @if(auth('admin')->user()->id == 1)
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.travel_agents')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.travel_agents') ? 'active' : '' }}">
                    <a href="{{route('admin.travel_agents')}}" class="text-white ">
                        @lang('admin.travel_agents')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.travel_agents_applications') ? 'active' : '' }}">
                    <a href="{{route('admin.travel_agent_applications')}}" class="text-white ">
                        @lang('admin.agent_applications')
                    </a>
                </li>

            @else
                @can('Manage travel agents')

                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.travel_agents')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.travel_agents') ? 'active' : '' }}">
                    <a href="{{route('admin.travel_agents')}}" class="text-white ">
                        @lang('admin.travel_agents')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.travel_agent_applications') ? 'active' : '' }}">
                    <a href="{{route('admin.travel_agent_applications')}}" class="text-white ">
                        @lang('admin.agent_applications')
                    </a>
                </li>
                @endcan
            @endif

            @if(auth('admin')->user()->id == 1)
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.direct_client_applications')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.un_paid') ? 'active' : '' }}">
                    <a href="{{route('admin.applications.un_paid')}}" class="text-white ">
                        @lang('admin.payment_applications')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.service_transactions.invoices') ? 'active' : '' }}">
                    <a href="{{route('admin.service_transactions.invoices')}}" class="text-white ">
                        @lang('admin.payment_services')
                    </a>
                </li>

            @else
                @can('Manage travel agents')
                    <li class=" ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.direct_client_applications')</span>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.applications.un_paid') ? 'active' : '' }}">
                        <a href="{{route('admin.applications.un_paid')}}" class="text-white ">
                            @lang('admin.payment_applications')
                        </a>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.service_transactions.invoices') ? 'active' : '' }}">
                        <a href="{{route('admin.service_transactions.invoices')}}" class="text-white ">
                            @lang('admin.payment_services')
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->id == 1)
                <li class="{{ request()->routeIs('admin.visa_types') ? 'active' : '' }}">
                    <a href="{{route('admin.visa_types')}}" class="text-white  ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.visa_types')</span>
                    </a>
                </li>

            @else
                @can('Manage visa types')
                    <li class="{{ request()->routeIs('admin.visa_types') ? 'active' : '' }}">
                        <a href="{{route('admin.visa_types')}}" class="text-white  ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.visa_types')</span>
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->id == 1)
                <li class="{{ request()->routeIs('admin.visa_providers') ? 'active' : '' }}">
                    <a href="{{route('admin.visa_providers')}}" class="text-white ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.visa_providers')</span>
                    </a>
                </li>

            @else
                @can('Manage visa providers')
                    <li class="{{ request()->routeIs('admin.visa_providers') ? 'active' : '' }}">
                        <a href="{{route('admin.visa_providers')}}" class="text-white ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.visa_providers')</span>
                        </a>
                    </li>
                @endcan
            @endif
            @if(auth('admin')->user()->id == 1)
                <li class="{{ request()->routeIs('admin.services') ? 'active' : '' }}">
                    <a href="{{route('admin.services')}}" class="text-white  ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.services')</span>
                    </a>
                </li>

            @else
                @can('Manage services')
                    <li class="{{ request()->routeIs('admin.services') ? 'active' : '' }}">
                        <a href="{{route('admin.services')}}" class="text-white  ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.services')</span>
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->id == 1)
                <li class="{{ request()->routeIs('admin.applicants') ? 'active' : '' }}">
                    <a href="{{route('admin.applicants')}}" class="text-white  ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.applicants')</span>
                    </a>
                </li>

            @else
                @can('Manage applicants')
                    <li class="{{ request()->routeIs('admin.applicants') ? 'active' : '' }}">
                        <a href="{{route('admin.applicants')}}" class="text-white  ">
                            <i class="fa fa-suitcase mr-5"></i>
                            <span class="ml-2">@lang('admin.applicants')</span>
                        </a>
                    </li>
                @endcan
            @endif


        @if(auth('admin')->user()->id == 1)
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.users')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                    <a href="{{route('admin.admins.index')}}" class="text-white ">
                        @lang('admin.users')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.role') ? 'active' : '' }}">
                    <a href="{{route('admin.role')}}" class="text-white ">
                        @lang('admin.roles')
                    </a>
                </li>
            @else
                @can('Manage admins')
                    <li class=" ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.users')</span>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                        <a href="{{route('admin.admins.index')}}" class="text-white ">
                            @lang('admin.users')
                        </a>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.role') ? 'active' : '' }}">
                        <a href="{{route('admin.role')}}" class="text-white ">
                            @lang('admin.roles')
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth('admin')->user()->id ==1 )
                <li class=" ">
                    <i class="fa fa-suitcase mr-5"></i>
                    <span class="ml-2">@lang('admin.settings')</span>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <a href="{{route('admin.settings')}}" class="text-white ">
                        @lang('admin.general_settings')
                    </a>
                </li>
                <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <a href="{{route('admin.profile')}}" class="text-white ">
                        @lang('admin.profile')
                    </a>
                </li>
            @else
                @can('Manage settings')
                    <li class=" ">
                        <i class="fa fa-suitcase mr-5"></i>
                        <span class="ml-2">@lang('admin.settings')</span>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <a href="{{route('admin.settings')}}" class="text-white ">
                            @lang('admin.general_settings')
                        </a>
                    </li>
                    <li style="line-height: 20px;margin-left: 18px;padding-left: 0px;text-indent: 0" class="border-bottom pb-2 ">
                        <a href="{{route('admin.profile')}}" class="text-white ">
                            @lang('admin.profile')
                        </a>
                    </li>
                @endcan
            @endif


            <li>
                <a href="{{route('admin.logout')}}" class="text-white">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="ml-2">@lang('messages.logout')</span>
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
    .active{
        padding-top: 6px;
        background: #d01a79;
        text-indent: 9px !important;
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
    a.disabled {
        pointer-events: none;
        cursor: default;
        color: grey !important;
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
