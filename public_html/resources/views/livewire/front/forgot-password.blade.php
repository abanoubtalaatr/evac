
    <main class="main-content">
      <section class="reset">
        <div class="container">
            <a class="back-login" href="{{route('user.login_form')}}">
                <img src="{{asset('assets_'.app()->getLocale())}}/imgs/login/back-arrow.svg" alt="">
                <span>@lang('general.login')</span>
            </a>
          <div class="row center-row">
            <div class="col-md-5">
              <div class="reset-d">
                <div class="reset-logo"><img src="{{asset('assets_'.app()->getLocale())}}/imgs/contact/contact-user@2x.png" alt=""></div>
                <h3 class="red">@lang('messages.reset_your_password')</h3>
                <p class="grey">@lang('site.please_enter_your_email_username_or_mobile')</p>

                @if($error_message)
                    <div class="alert alert-warning">
                        <p>{{$error_message}}</p>
                    </div>
                @endif

                <input wire:model.defer='auth_field' class="form-control reset-input" type="text" />
                <button class="button btn-red full" wire:click='sendCode'>
                    @lang('site.send_otp')
                </button>
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
