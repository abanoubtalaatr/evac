
    <main class="main-content">
      <section class="reset">
        <div class="container"><a class="back-login" href="login.html"><img src="{{asset('assets_'.app()->getLocale())}}/imgs/login/back-arrow.svg" alt=""><span>Go-back</span></a>
          <div class="row center-row">
            <div class="col-md-5">
              <div class="reset-d">
                <div class="reset-logo"><img src="{{asset('assets_'.app()->getLocale())}}/imgs/contact/contact-user@2x.png" alt=""></div>
                <h3 class="red">@lang('messages.reset_your_password')</h3>
                <p class="grey">@lang('general.enter_code')</p>

                @if($error_message)
                    <div class="alert alert-warning">
                        <p>{{$error_message}}</p>
                    </div>
                @endif

                 @if($show_new_password_form ==0 )
                    <input wire:model.defer='code' class="form-control reset-input" type="text" />
                    <button class="button btn-red full" wire:click='verifyCode'>
                        @lang('site.send_otp')
                    </button>
                @else
                    <div class="form-group">
                        <label for="">@lang('validation.attributes.new_password')</label>
                        <input wire:model.defer='new_password' class="form-control reset-input" type="text" />
                        @error('new_password') <p class="text-danger">{{$message}}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="">@lang('validation.attributes.new_password_confirmation')</label>
                        <input wire:model.defer='new_password_confirmation' class="form-control reset-input" type="text" />
                        @error('new_password_confirmation') <p class="text-danger">{{$message}}</p> @enderror
                    </div>

                    <button class="button btn-red full" wire:click='store'>
                        @lang('site.send')
                    </button>
                @endif
                <a class="grey" href="{{route('user.login_form')}}">
                    @lang('site.already_have_an_account')
                    <span class="red">@lang('general.login')</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
