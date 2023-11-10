<div class="col-lg-5">
    <div class="sign_box text-center">
        <img src="{{asset('website/assets/images/auth/logo.svg')}}" alt="">
        <form class="mt-lg-5 mt-3" wire:submit.prevent="attempt" method='post'>
            <div class=" mb-3 text-end">
                <label> {{trans('site.please_enter_phone')}}</label>
                <label class="form-label" for="user">{{trans('site.phone_number')}}</label>

                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0">
                                        <img src="{{asset('website/assets/images/auth/mobile.svg')}}" alt=""></span>
                    <input wire:model.defer="mobile" type="text" class="form-control bg-transparent border-0" id="user">

                    <span class="input-group-text bg-transparent border-0">+966</span>

                </div>
                @error('mobile')<p style='color:red'> {{$message}} </p>@enderror


            </div>
            <button type="button" wire:click='attempt'  class="btn btn-1 px-5 mt-3">تفعيل</button>
        </form>
    </div>
</div>
