<!DOCTYPE html>
<html>
<head>
    <title>Receipt service transaction</title>
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
        <div >Ref No : {{$serviceTransaction->service_ref}} </div>
        <div>Date : {{\Carbon\Carbon::parse($serviceTransaction->created_at)->format('Y-m-d')}} </div>
        <div>Service : {{$serviceTransaction->service? $serviceTransaction->service->name:''}}</div>
        @if($serviceTransaction->agent)
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong >Travel agent</strong></div>
        <div class="fa-3x">---------------</div>
        <div>Name : {{$serviceTransaction->agent->name}}</div>
        @else
            Direct
        @endif
        <div> Amount : {{$serviceTransaction->amount}} USD ({{$serviceTransaction->payment_method}})</div>
        @if($serviceTransaction->vat >0)
            <div>VAT : {{\App\Helpers\formatCurrency($serviceTransaction->vat)}} </div>
        @endif


        <div>Service fee and sales tax included</div>
        <div>Fees in words : {{\App\Helpers\convertNumberToWorldsInUsd($serviceTransaction->amount)}} </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
