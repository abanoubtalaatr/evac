<div>
    @php
    $settings = \App\Models\Setting::query()->first();
 @endphp
    Dear Business Partner,
    <br>
    <span style="margin-bottom: 10px"> Greeting from EVAC.</span>
    <br>
    <span style="margin-bottom: 10px">    Kindly find attached your applications report till date.</span>
    <br>
<span style="margin-bottom: 10px">    Best regards,</span>
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
