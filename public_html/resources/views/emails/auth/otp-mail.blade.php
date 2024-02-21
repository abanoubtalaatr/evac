@component('mail::message')
Welcome  in Awn Application
<p> Your Verification Code is : {{$code}} </p>
Thanks,
<br>
{{ config('app.name') }}
@endcomponent
