@component('mail::message')
<p> {{$replay}} </p>
@lang('site.thanks'),<br>
{{ config('app.name') }}
@endcomponent
