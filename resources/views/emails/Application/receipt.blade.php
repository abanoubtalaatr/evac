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
<div class="container mt-5 ">
    @php
        $settings= \App\Models\Setting::query()->first();
    @endphp

    <div >
        <div class="my-2 text-center">
            <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
        </div>

        <div>Office address: {{ $settings->address??""}}</div>
        <div>Tel : {{$settings->mobile??""}}</div>
        @if(isset($settings->registration_no) && !empty($settings->registration_no))
            <div>Registration No : {{$settings->registration_no??''}}</div>
        @endif
        @if(isset($settings->vat_no) && !empty($settings->vat_no))
            <div>Vat registration : {{$settings->vat_no}}</div>
        @endif

        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong class="">Invoice Receipt</strong></div>
        <div class="fa-3x">------------------</div>
        <div >Ref N: {{$application->application_ref}} </div>
        <div>Date : {{\Carbon\Carbon::parse($application->created_at)->format('d-m-Y')}} </div>
        <div>Visa type : {{$application->visaType? $application->visaType->name:''}}</div>
        <div class="">
            @if($application->travelAgent)

                <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-4">
                    <strong >Travel agent: {{$application->travelAgent->name}}</strong>
                </div>
                <div class="fa-3x">---------------</div>
                <div >Account number: {{$application->travelAgent->account_number}}</div>

                <div>Name: {{$application->first_name . ' '. $application->last_name}}</div>
            @else
                Direct
            @endif
            <div>Passport No: {{$application->passport_no}} </div>
        </div>
        <div>Visa fees  : {{\App\Helpers\formatCurrency($application->visaType->dubai_fee)}}</div>
        <div>Service fees  : {{\App\Helpers\formatCurrency($application->visaType->service_fee)}}</div>
        @if($application->vat > 0)
            <div>VAT : {{\App\Helpers\formatCurrency($application->vat)}} </div>
        @endif
        @php
            $total = $application->dubai_fee + $application->service_fee + $application->vat;
        @endphp
        <div class="font-weight-bolder" style="font-weight: bolder">Total Fees : {{$total}} USD ({{$application->payment_method}})</div>
        <div class="mt-2">Service fees and sales tax included</div>
        <div>Fees in words : {{\App\Helpers\convertNumberToWorldsInUsd($total)}} </div>
        <div class="mt-2">
            <p>{{$settings->invoice_footer}}</p>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
