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
                    <h4>{{$client_count}}</h4>
                    <p class="grey">@lang('site.users')</p>
                </div>
            </div>

            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.oil_brands')}}'>
                    <h4>{{$oil_brand}}</h4>
                    <p class="grey">@lang('site.oil_brands')</p>
                </div>
            </div>

            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.services')}}'>
                    <h4>{{$services_count}}</h4>
                    <p class="grey">@lang('site.services')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" onclick='window.location.href="{{route('admin.orders')}}'>
                    <h4>{{$orders_count}}</h4>
                    <p class="grey">@lang('site.orders')</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="dash box-shad" >
                    <h4>{{$total_money}}</h4>
                    <p class="grey">@lang('site.return_orders')</p>
                </div>
            </div>


        </div>


    </div>
</main>
@endsection
