
    <section class="login">
        <div class="row">
            <div class="col-md-5">
            <div class="login-back">
                <div class="row justify-content-center">
                <div class="col-md-8">
{{--                    <x-langselect/>--}}

                    <div class="login-form ">
                        @if($error_message)
                        <div class="alert alert-danger">
                            {{$error_message}}
                        </div>
                        @endif

                    <form wire:submit.prevent="attempt" method='post'>
{{--                        <div class="login-logo"><img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/home/logo.svg" alt=""></div>--}}
                   <div class="login-logo bg-white mb-3 rounded" style="height: 100px">
                       <x-logo />
                   </div>

                        @if(session()->has('in_active_message'))
                            <div class="alert alert-danger">
                                {{session()->get('in_active_message')}}
                            </div>
                        @endif
                        <p class="text-white">@lang('site.enter_login_data')<span> @lang('site.to_continue')</span></p>
                        <div class="input-group login-group floating-label-group">
                            <div class="input-group-prepend text-white"><img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/user.svg" alt=""></div>
                            <input wire:model.defer='email' class="form-control text-white" type="text" autocomplete="flase" autofocus>
                            <label class="floating-label text-white">@lang('validation.attributes.email')</label>
                            @error('email') {{$message}} @enderror
                        </div>
                        <div class="input-group login-group floating-label-group">
                            <div class="input-group-prepend"><img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/lock.svg" alt=""></div>
                            <input wire:model.defer='password' class="form-control text-white" type="password" id="password" autocomplete="chrome-off">
                            <label class="floating-label text-white">@lang('validation.attributes.password')</label>
{{--                            <div class="input-group-prepend check text-white"><i class="fas fa-eye-slash text-white"></i></div>--}}
                            @error('password') {{$message}} @enderror
                        </div>
{{--                        <div class="flex-div-2"><a class="grey" href="#">@lang('site.remember_me')</a>--}}
{{--                        <label class="switch">--}}
{{--                            <input type="checkbox "><span class="slider"></span>--}}
{{--                        </label>--}}
                        </div>
{{--                        <a class="red" href="{{route('admin.forgot_password')}}">@lang('site.i_forgot_my_password')</a>--}}
                        <div class="login-btns">
                            <button type='button' wire:click='attempt' class="button btn-red full">@lang('messages.Login')</button>
                        </div>

                    </form>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="col-md-7 d-none">
            <div class="login-img"><img src="{{asset('frontAssets')}}/assets_{{app()->getLocale()}}/imgs/login/login-img@2x.png" alt=""></div>
            </div>
        </div>
    </section>
