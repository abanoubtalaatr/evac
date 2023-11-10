@extends('layouts.auth')
@section('content')
    <section class="login">
        <div class="row">
            <div class="col-md-5">
                <div class="login-back">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <x-langselect/>
                            <div class="login-form">

                                <form method="POST" action="{{route('user.verify_registration_code')}}"

                                      style="text-align: {{app()->getLocale()=='ar'? 'right' : 'left' }} ">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <ul>
                                                <li>{!! \Session::get('success') !!}</li>
                                            </ul>
                                        </div>
                                    @endif
                                    @csrf
                                    @method('POST')
                                    <div class="login-logo">
                                        <img
                                            src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/logo.svg"
                                            alt=""></div>
                                    <p><span> @lang('site.enter_verification_code')</span>
                                    </p>

                                    <div class="input-group login-group floating-label-group">
                                        <div class="input-group-prepend"><img
                                                src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/user.svg"
                                                alt=""></div>

                                        <input value="{{session()->has('username')??''}}" name="username"
                                               class="form-control" type="text">
                                        <label class="floating-label">@lang('validation.attributes.username')</label>

                                    </div>
                                        @error('username') <span class="invalid-info">{{$message}}</span> @enderror
                                    <div class="input-group login-group floating-label-group">
                                        <div class="input-group-prepend"><img
                                                src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/lock.svg"
                                                alt=""></div>

                                        <input name="verification_code" class="form-control" type="text"
                                               autocomplete="flase" autofocus value="{{ old('verification_code') }}">
                                        <label class="floating-label">@lang('site.enter_verification_code')</label>

                                    </div>
                                        @error('verification_code') <span class="invalid-info">{{$message}}</span> @enderror

                                    <div class="login-btns">
                                        <button type='submit'
                                                class="button btn-red full">@lang('site.send')</button>
                                        {{--                                        <button onclick='window.location.href="{{route('user.register_form')}}"'--}}
                                        {{--                                                style='text-align:center'--}}
                                        {{--                                                class="button btn-border full">@lang('messages.I_dont_have_an_account')</button>--}}
                                    </div>

                                    {{--                                    <a class="red"--}}
                                    {{--                                     >@lang('site.resend_verification_code')</a>--}}
                                </form>
                                <form action="{{route('user.resend_verification_code')}}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input hidden name="username" value="{{session()->get('username')}}">
                                    <button type='submit'
                                            class="button btn-green ">@lang('site.resend_verification_code')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="login-img"><img
                        src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/login-img@2x.png" alt="">
                </div>
            </div>
        </div>
    </section>

@endsection
