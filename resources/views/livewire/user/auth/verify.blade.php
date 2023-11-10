
<section class="auth">
    <div class="layer">
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
<div class="col-lg-5">
    <div class="sign_box text-center">
        <img src="{{asset('website/assets/images/auth/logo.svg')}}" alt="">
        <form class="mt-lg-5 mt-3">
            <div class=" mb-3">
                <label>
                    {{trans('site.code_sent_to')}}<b> {{$mobile}} +966</b> {{trans('site.please_enter_code_below')}}
                </label>
                <div class="row justify-content-center mb-3 otb_card text-center" dir="ltr">
                    <input wire:model.defer="number1" type="text" class="otp-input form-control col-3" maxlength="1" oninput="focusNext(1)">
                    <input wire:model.defer="number2" type="text" class="otp-input form-control col-3" maxlength="1" oninput="focusNext(2)">
                    <input wire:model.defer="number3" type="text" class="otp-input form-control col-3" maxlength="1" oninput="focusNext(3)">
                    <input wire:model.defer="number4" type="text" class="otp-input form-control col-3" maxlength="1" oninput="focusNext(4)">
                </div>
                <p id="timer"></p>
                @isset($message) <p class="text-danger">{{$message}}</p>@endisset
                <p wire:ignore wire:click="resendCode" onclick="location.reload()" id="resendCode" class="btn btn-0 hidden"> @lang('site.resend_code')<p/>
                <button class="btn btn-1 px-5 mt-3" type="button" wire:click="verifyCode">@lang('site.active_code')</button>

            </div>
        </form>
    </div>
</div>
    <div class="col-lg-1">

    </div>
    <div class="col-lg-6">
        <div class="sign_bg">
            <img src="{{asset('website/assets/images/auth/man.jpg')}}" alt="">
        </div>
    </div>
        </div>
    </div>
</section>

<script src="{{asset('website/assets/js/jquery.js')}}"></script>

<script>
    $(document).ready(function() {
        startCountdown();
    });
    const totalSeconds = {{env('EXPIRE_TIME_FOR_SEND_CODE')??30}};
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    const hours = Math.floor(totalSeconds / 3600);
    const remainingMinutes = Math.floor((totalSeconds % 3600) / 60);

    $('#timer').html(hours + ':' + minutes + ":" + seconds)
    // otp
    var countdownTimer;
    var countdownDuration = {{env('EXPIRE_TIME_FOR_SEND_CODE')??30}}; // Countdown duration in seconds

    function focusNext(currentField) {
        var nextField = currentField + 1;
        var nextFieldElement = document.querySelector('.otp-input:nth-child(' + nextField + ')');

        if (nextFieldElement) {
            nextFieldElement.focus();
        }
    }

    function startCountdown() {
        var timerElement = document.getElementById('timer');
        updateTimer(timerElement);

        countdownTimer = setInterval(function () {
            countdownDuration--;

            if (countdownDuration < 0) {
                clearInterval(countdownTimer);
                countdownTimer = null;
                $('#timer').html('');
                $('#resendCode').removeClass('hidden');
            } else {
                updateTimer(timerElement);
            }
        }, 1000);
    }

    function updateTimer(timerElement) {
        var minutes = Math.floor(countdownDuration / 60);
        var seconds = countdownDuration % 60;
        timerElement.textContent = minutes + ' : ' + seconds + ' ';
    }
</script>
