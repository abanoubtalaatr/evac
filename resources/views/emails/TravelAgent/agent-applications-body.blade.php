<div>
    @php
    $settings = \App\Models\Setting::query()->first();
 @endphp
    Dear Business Partner,
    <br>
    <span style="margin-bottom: 10px"> Greeting from EVAC.</span>
    <br>
    <br>
    <span style="margin-bottom: 10px">
        @if(isset($agentStatement))
        Kindly find attached your statement of account till date.

        @elseif(isset($agentInvoice))
        Kindly find attached invoice.

        @else
        Kindly find attached your applications report till date.

        @endif
    </span>
        <br>
    <br>
<span style="margin-bottom: 10px">    Best regards,</span>
    <br>
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
