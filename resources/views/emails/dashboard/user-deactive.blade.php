@component('mail::message')

@php
    $rl = app()->getLocale() =="ar" ?  'right' :  'left' ;
@endphp

<div style="text-align: center; font-size:2em;">
    <h3>{{trans('messages.Welcome_in_Awn_Application') }}</h3>
    <p>{{$message}}</p>
    <p>{{__('site.because')}}: {{$reason}}</p>

</div>
<br>

<p style="text-align: {{$rl}}; "> {{ trans('messages.your_phone') }}    : {{$phone}} </p>

<p style="text-align: {{$rl}}; ">{{ trans('messages.thanks') }},</p>
<p style="text-align: {{$rl}}; ">{{ config('app.name') }}</p>
@endcomponent
