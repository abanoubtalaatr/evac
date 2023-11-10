
@component('mail::message')

@php
    $rl = app()->getLocale() =="ar" ?  'right' :  'left' ;
@endphp
<div style="text-align: center; font-size:2em;"> {{trans('messages.Welcome_in_Awn_Application_as_new_admin') }}</div>
<br>
<p style="text-align: {{$rl}}; "> {{ trans('messages.your_email') }}    : {{$email}} </p>
<p style="text-align: {{$rl}}; " > {{ trans('messages.your_password') }} : {{$password}} </p>

<p style="text-align: {{$rl}}; ">{{ trans('messages.thanks') }},</p>
<p style="text-align: {{$rl}}; ">{{ config('app.name') }}</p>
@endcomponent
