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
    .heading{
        font-weight: bolder;
        font-size: 18px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .mx-10{
        margin-right: 10px;
        margin-left: 10px;
    }
    .invoice-heading{
        font-size: 20px;
    }
</style>
</head>
<body>
<div class=" mt-5 ">
    @php
        $settings= \App\Models\Setting::query()->first();
    @endphp

    <div style="">
        <div class="my-2 text-center mb-10" >
            <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
        </div>

        <div style="display: flex;text-align: center;align-items: center;justify-content:center;margin-top: 30px" >

            <div class="heading mx-10"> {{ $settings->address??""}} - </div>
            @if(isset($settings->registration_no) && !empty($settings->registration_no))
                <div class="heading mx-10">Reg No : {{$settings->registration_no??''}} - </div>
            @endif
            <div class="heading mx-10">Tel : {{$settings->mobile??''}}</div>
{{--            --}}
{{--            @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--                <div>Vat registration : {{ $settings->vat_no}}</div>--}}
{{--            @endif--}}

        </div>
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-3 invoice-heading" >
            <p class="text-capitalize text-center heading">INVOICE RECEIPT</p>
        </div>
        <div style="text-align: start">
            <div class="py-1"><strong>Ref No: {{$application->application_ref}}</strong> </div>
            <div class="py-1"><strong>Date : {{\Carbon\Carbon::parse($application->created_at)->format('d/m/Y')}} </strong></div>
            <div class="py-1"><strong class="text-uppercase">Visa type : {{$application->visaType? $application->visaType->name:''}}</strong></div>
        </div>

       <div class="mt-4" style="text-align: center">
        @if($application->travelAgent)
        <div class="border-dotted border-top-0 border-right-0 border-left-0 mt-4 py-1">
            <strong>TRAVEL AGENT: {{$application->travelAgent->name}} / ACC NBR : {{$application->travelAgent->account_number}} / {{$application->travelAgent->address}}</strong>
        </div>
               <div><strong class="text-uppercase py-2">APPLICANT NAME: {{$application->first_name . ' '. $application->last_name}}</strong></div>
        @else
               <a href="#" style="" class="btn text-uppercase"><strong>Direct</strong></a>
               <div><strong class="text-uppercase py-2">APPLICANT NAME: {{$application->first_name . ' '. $application->last_name}}</strong></div>

           @endif
           <div><strong class="text-uppercase py-2">Passport: {{$application->passport_no}} </strong></div>
       </div>
        <div class="text-start">
            <div>
                <span>Visa fees  : {{\App\Helpers\formatCurrency($application->dubai_fee)}} USD</span>
            </div>

            <div>
                <div>
                <span>Service fees : {{\App\Helpers\formatCurrency($application->service_fee)}} USD </span>
                </div>
                @if($application->vat > 0 )
                    <div>
                    <span>VAT  {{$settings->vat_rate}}  : {{$application->vat}}  </span>
                    </div>
                @endif
            </div>
        </div>



        @php
              $total = ($application->dubai_fee + $application->service_fee + $application->vat);
        @endphp

        <div class="font-weight-bolder py-3 fa-4x text-center"  style="font-weight: bolder;font-size: 19px">Total Fees : {{$total}} USD  ({{\App\Helpers\convertNumberToWorldsInUsd($total)}} )  {!! $application->payment_method =='invoice'? "<strong class='text-danger'>Unpaid</strong>" :"<strong>Paid</strong>"  !!} </div>

        <div class="text-start">
            <strong> {{$settings->invoice_footer}}</strong>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
