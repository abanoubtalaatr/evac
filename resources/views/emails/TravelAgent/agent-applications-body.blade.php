<div>
    @php
    $settings = \App\Models\Setting::query()->first();
 @endphp
    Dear Business Partner,
    <br>
    Greeting from EVAC.
    <br>
    Kindly find attached your applications report till date.
    <br>
    Best regards,
    <br>
    Johnny Salem
    <br>
    Managing Director
    <br>
    Emirates Visa Application Center
    <br>
    {{$settings->address}}
    <br>
    {{$settings->mobile}}
    <br>
</div>
