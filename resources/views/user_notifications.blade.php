@extends('layouts.user')
@section('content')
     <main class="main-content">
          <!--head-->
          <x-user.head/>
          <!--dashboard-->
          <section class="dashboard p-3">
              <div class="row">
                <div class="col-md-12">
                  <div class="border bg-white rounded p-lg-5 p-3 mb-3">
                    <h2 class="head-term">{{$page_title}}</h2>
                    @if(count($records))
                        @foreach($records as $record)
                        <a href="{{$record->redirect_url}}" class="notif-bg">
                            <div class="notif  bg-white p-3 d-flex">
                                <img src="{{$record->image_url}}" alt="">
                                <div class="notif-info">
                                    <h4>@lang('site.'.$record->type) <span class="float-start notif-time">{{$record->created_at}}</span></h4>
                                    <p>{{$record->{"content_".app()->getLocale()} }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                        {{$records->links()}}
                    @else
                        <div class="alert alert-warning">
                            @lang('site.no_data_to_display')
                        </div>
                    @endif

                  </div>
                </div>

              </div>

          </section>

        </main>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/notifications.css')}}">
@endpush
