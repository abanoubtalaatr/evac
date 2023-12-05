<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
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
        <div class="my-2 text-center">
            <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
        </div>

        <div> {{ $settings->address??""}}</div>
        <div>Phone : {{$settings->mobile??""}}</div>
        <div>Fax : {{$settings->fax??""}}</div>

         @if(!empty($settings->registration_no))
           <div>Reg No : {{$settings->registration_no??''}}</div>
        @endif

        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong class="">Receipt</strong></div>
        <div class="fa-3x">------------------</div>
        <div>Date : {{\Carbon\Carbon::parse($paymentTransaction->created_at)->format('d-m-Y')}} </div>
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong >Travel agent: {{$paymentTransaction->agent->name}}</strong></div>

        <div>Received the amount of $ {{($paymentTransaction->amount)}}</div>
        <div>Fees in Words : {{\App\Helpers\convertNumberToWorldsInUsd($paymentTransaction->amount)}} </div>
        <div class="mt-2">
            <p>{{$settings->invoice_footer}}</p>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
