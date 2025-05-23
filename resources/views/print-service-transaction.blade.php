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
        <div class="my-2 text-center">
            <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
        </div>

        <div>Office address: {{ $settings->address??""}}</div>
        <div>Tel : {{$settings->mobile??""}}</div>
        @if(!empty($settings->registration_no))
        <div>Registration No : {{$settings->registration_no??''}}</div>
        @endif
{{--        @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--            <div>Vat registration : {{$settings->vat_no}}</div>--}}
{{--        @endif--}}

        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3"><strong class="">Invoice / Receipt</strong></div>
        <div class="fa-3x">------------------</div>
        <div >Ref No : {{$serviceTransaction->service_ref}} </div>
        <div>Date : {{\Carbon\Carbon::parse($serviceTransaction->created_at)->format('d-m-Y')}} </div>
        <div>Service : {{$serviceTransaction->service? $serviceTransaction->service->name:''}}</div>
        @if($serviceTransaction->agent)
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3 text-center"><strong >Travel agent: <span style="font-size: 27px">{{$serviceTransaction->agent->name}}</span></strong></div>
            <div >Account number: {{$serviceTransaction->agent->account_number}}</div>

            <div class="fa-3x">---------------</div>
        <div>Name : <span style="font-size: 27px;">{{$serviceTransaction->name ." ". $serviceTransaction->surname}}</span></div>
        @else
            <a href="#" style="font-weight: bolder;padding-top:5px;background: lightblue;color:black;font-size: 20px">Direct</a>
            <div>Name : <span style="font-size: 27px">{{$serviceTransaction->name ." ". $serviceTransaction->surname}}</span></div>

        @endif
        @php
            $total = ($serviceTransaction->service_fee) + ($serviceTransaction->dubai_fee) + $serviceTransaction->vat;
        @endphp
        <div> Amount : $ {{\App\Helpers\formatCurrency($total - $serviceTransaction->vat)}} USD ({{$serviceTransaction->payment_method =='invoice' ? "Unpaid" : "Paid"}})</div>
        @if($serviceTransaction->vat >0)
            <span>VAT  :  {{$settings->vat_rate}}  : $ {{\App\Helpers\formatCurrency($serviceTransaction->vat)}}  </span>
        @endif

        <div> Total Amount : $ {{\App\Helpers\formatCurrency($total)}} USD </div>

        <div class="font-weight-bolder py-3 fa-1x text-center" ><strong>Total Fees : {{\App\Helpers\formatCurrency($total)}} USD  ({{\App\Helpers\convertNumberToWorldsInUsd($total)}} )  {!! $serviceTransaction->payment_method =='invoice'? "<strong class='text-danger'>Unpaid</strong>" :"<strong>Paid</strong>"  !!}</strong> </div>

        <div class="text-start">
            <strong>{{$settings->invoice_footer}}</strong>
        </div>

        @if(\App\Helpers\isExistVat())
        <div class="text-start">
            <strong> Vat Reg {{\App\Helpers\registrationNumber()}}</strong>
        </div>
        @endif

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
