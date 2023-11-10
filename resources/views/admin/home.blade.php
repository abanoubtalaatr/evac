@extends('admin.layouts.master')

@section('content')
<br>
<!-- Dashboard Analytics Start -->
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="row widget-statistic">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <p class="w-value">{{$total_users_count}}</p>
                    <h5 class="">{{ trans('messages.users') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <p class="w-value">{{$inactive_users_count}}</p>
                    <h5 class="">{{ trans('messages.Black_list') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <p class="w-value">{{$active_users_count}}</p>
                    <h5 class="">{{ trans('messages.users') }} {{ trans('messages.activations') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-engagement">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>                    </div>
                    <p class="w-value">0</p>
                    <h5 class="">{{ trans('messages.visits') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="row widget-statistic">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <p class="w-value">0</p>
                    <h5 class="">{{ trans('messages.new_chats') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-referral">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <p class="w-value">{{$new_tickets_count}}</p>
                    <h5 class="">{{ trans('messages.new_tickets') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-engagement">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                    </div>
                    <p class="w-value">{{$month_loans_count}}</p>
                    <h5 class="">{{ trans('messages.loans_per_month') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers3"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                    </div>
                    <p class="w-value">{{$year_loans_count}}</p>
                    <h5 class="">{{ trans('messages.loans_per_year') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="row widget-statistic">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                    </div>
                    <p class="w-value">0</p>
                    <h5 class="">{{ trans('messages.revenue_without_tax') }}</h5>
                </div>
                <div class="widget-content">
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Dashboard Analytics end -->


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <table class='table'>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">@lang('site.created_at')</th>
                <th class="text-center">@lang('messages.title')</th>
                <th class="text-center">@lang('messages.type')</th>
                <th class="text-center">@lang('site.details')</th>
            </tr>
            @foreach ($notifications as $notification)
                <tr>
                    <td class='text-center'>{{$notification->id}}</td>
                    <td class='text-center'>{{$notification->created_at}}</td>
                    <td class='text-center'>{{$notification->{"title_".app()->getLocale()} }}</td>
                    <td class='text-center'>@lang('site.'.$notification->type)</td>
                    <td class='text-center'>
                        <a href="{{$notification->redirect_url}}" class="btn btn-primary">@lang('site.details')</a>
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>


@endsection
