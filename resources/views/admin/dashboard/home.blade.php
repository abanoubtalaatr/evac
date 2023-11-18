@extends('layouts.admin')
@section('content')
<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--wallet-->
    <div class="border-div">
        <h2>@lang('site.dashboard')</h2>
        <div class="row">
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$new_applications}}</h4>
                    <p class="grey">@lang('admin.new_applications')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$appraised_applications}}</h4>
                    <p class="grey">@lang('admin.appraised_application')</p>
                </div>
            </div>

            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$agent_count}}</h4>
                    <p class="grey">@lang('admin.agents')</p>
                </div>
            </div>


            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$services}}</h4>
                    <p class="grey">@lang('admin.services')</p>
                </div>
            </div>

            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$serviceTransactions}}</h4>
                    <p class="grey">@lang('admin.service_transactions')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$visaProviders}}</h4>
                    <p class="grey">@lang('admin.visa_providers')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$visaTypes}}</h4>
                    <p class="grey">@lang('admin.visa_types')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$applicants}}</h4>
                    <p class="grey">@lang('admin.applicants')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.users.index')}}'>
                    <h4>{{$deletedApplications}}</h4>
                    <p class="grey">@lang('admin.deleted_applications')</p>
                </div>
            </div>
        </div>


    </div>
</main>
@endsection
