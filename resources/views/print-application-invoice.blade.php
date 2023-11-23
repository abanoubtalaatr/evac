<!DOCTYPE html>
<html>
<head>
    <title>Receipt Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    p{
        padding-bottom: .1rem;
    }
</style>
</head>
<body>
<div class="container mt-5">
    @php
        $settings= \App\Models\Setting::query()->first();
    @endphp

    <div >
        <div class="my-2">
            <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
        </div>

        <div>Office address: {{ $settings->address??""}}</div>
        <div>Tel : {{$settings->mobile??""}}</div>
        <div>Registration No : {{$settings->registration_no??''}}</div>
        @if(isset($settings->vat_no))
            <div>Vat registration : {{$settings->vat_no}}</div>
        @endif

        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong class="">Invoice Receipt</strong></div>
        <div class="fa-3x">------------------</div>
        <div >Ref No : {{$application->application_ref}} </div>
        <div>Date : {{\Carbon\Carbon::parse($application->created_at)->format('Y-m-d')}} </div>
        <div>Visa type : {{$application->visaType? $application->visaType->name:''}}</div>
        @if($application->travelAgent)
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong >Travel agent</strong></div>
        <div class="fa-3x">---------------</div>
        <div>Name : {{$application->travelAgent->name}}</div>
        @else
            Direct
        @endif
        <div>Passport No : {{$application->passport_no}} </div>
        <div>Visa fees  : {{\App\Helpers\formatCurrency($application->visaType->dubai_fee)}}</div>
        <div>Service fees  : {{\App\Helpers\formatCurrency($application->visaType->service_fee)}}</div>
        @if($application->vat > 0)
            <div>VAT : {{\App\Helpers\formatCurrency($application->vat)}} </div>
        @endif

        <div>Total Fees : {{$application->amount}} USD ({{$application->payment_method}})</div>
        <div>Service fees and sales tax included</div>
        <div>Fees in words : {{\App\Helpers\convertNumberToWorldsInUsd($application->amount)}} </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
