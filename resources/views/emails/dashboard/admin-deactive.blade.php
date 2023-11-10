@component('mail::message')

@php
    $rl = app()->getLocale() =="ar" ?  'right' :  'left' ;
@endphp

<div style="text-align: center; font-size:2em;"> {{trans('messages.Welcome_in_Awn_Application') }}
    {{trans('messages.Your_Account_as_an_admin_is_deactivated_by_Super_Admin') }}
</div>
<br>

<p style="text-align: {{$rl}}; "> {{ trans('messages.your_email') }}    : {{$email}} </p>

<p style="text-align: {{$rl}}; ">{{ trans('messages.thanks') }},</p>
<p style="text-align: {{$rl}}; ">{{ config('app.name') }}</p>
@endcomponent
