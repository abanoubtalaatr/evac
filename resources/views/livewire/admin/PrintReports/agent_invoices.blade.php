<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title ?? 'Report'}}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .table-responsive{
            overflow: hidden;
        }
        main {
            padding: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }

        .card-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: bolder;
            color: black;
        }
        .border-0{
            border: none    ;
        }

        .card-body {
            padding: 15px;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            background-color: #fff;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .total-row {
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
        .btn-primary{
            background: #0b5ed7;
        }
        /* Set a fixed width for each column */
        .table th:nth-child(1),
        .table td:nth-child(1),
        .table th:nth-child(2),
        .table td:nth-child(2),
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 33.33%; /* Equal width for each column */
        }
        table {
            width: 100%;
        }

        th, td {
            width: auto; /* or specify widths in pixels */
        }
        .span-block{
            display: block;
            margin-bottom: 3px;
        }

    </style>
</head>
<body>

<main>
    @include('livewire.admin.shared.reports.header')

    <!--dashboard-->
    <section class="dashboard">
        <div class="row">

           @php
           $agent = \App\Models\Agent::query()->find(request()->agent);

           @endphp
        @if($agent)
            <div class="mt-4" style="margin-bottom: 10px;margin-top: 10px;">
                <strong class="span-block">Agent : {{$agent->name}}</strong>
                <strong class="span-block">Agent address: {{$agent->address}}</strong>

                <strong class="span-block">Tel : {{$agent->telephone}}</strong>
                <strong class="span-block">Account No: {{$agent->account_number}}</strong>
            </div>
            @endif
            <strong class="span-block">Date : {{\Illuminate\Support\Carbon::today()->format('Y-m-d')}}</strong>
            <strong class="span-block">INV No: {{\Illuminate\Support\Carbon::parse(now())->format('Y/m/d')}}</strong>

            @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} - To : {{request()->toDate}}</h4>
            @endif
            <br>
            @php
                $data = (new \App\Services\AgentInvoiceService())->getRecords(request()->agent, request()->fromDate, request()->toDate);
            @endphp

            @php
                $rowsCount= 1;
                $totalAmount = 0;
                $totalGrand = 0;
                $totalPayment = 0;

            @endphp
            @if(isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)
                <table  width="100%" cellpadding="0" cellspacing="0" align="center" >
                    <thead>
                    <tr>
                        <th class="text-center">Item #</th>
                        <th class="text-center">@lang('admin.description')</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Amount</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php
                        $rowsCount = 1;
                        $totalAmount = 0;
                        $totalApplicationAmount =0;
                        $totalServiceTransactionsAmount =0;

                    $totalApplicationAmount += \App\Models\Application::query()->sum('dubai_fee');
                    $totalApplicationAmount += \App\Models\Application::query()->sum('service_fee');
                    $totalApplicationAmount += \App\Models\Application::query()->sum('vat');
                    $totalServiceTransactionsAmount += \App\Models\ServiceTransaction::query()->sum('dubai_fee');
                    $totalServiceTransactionsAmount += \App\Models\ServiceTransaction::query()->sum('service_fee');
                    $totalServiceTransactionsAmount += \App\Models\ServiceTransaction::query()->sum('vat');
                    @endphp



                            {{-- Display Visa information --}}
                            @if(isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)

                                @foreach($data['agents'][0]['visas'] as $visa)

                                    <tr>
                                        @php
                                            $totalAmount += $visa->totalAmount;
                                        @endphp
                                        <td class="text-center">#{{ $rowsCount++ }}</td>
                                        <td class="text-center">{{ $visa->name }}</td>
                                        <td class="text-center">{{ $visa->qty }}</td>
                                        <td class="text-center">{{ $visa->total }}</td>
                                        <td class="text-center">{{ $visa->totalAmount }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Display Service information --}}
                            @if(isset($data['agents'][0]['services']) && count($data['agents'][0]['services']) > 0)
                                @foreach($data['agents'][0]['services'] as $service)

                                    <tr>
                                        @php
                                            $totalAmount += $service->totalAmount;
                                        @endphp
                                        <td class="text-center">#{{ $rowsCount++ }}</td>
                                        <td class="text-center">{{ $service->name }}</td>
                                        <td class="text-center">{{ $service->qty }}</td>
                                        <td class="text-center">{{ $service->amount }}</td>
                                        <td class="text-center">{{ $service->totalAmount }}</td>
                                    </tr>
                                @endforeach
                            @endif

                    @php
                        foreach ($data['agents'] as $agent) {
                if (!is_null($agent['agent'])){
                    $totalPayment += \App\Models\PaymentTransaction::query()->where('agent_id', $agent['agent']['id'])->sum('amount');
                    $oldBalance = ($totalApplicationAmount + $totalServiceTransactionsAmount) - $totalPayment - $totalAmount;
                }
            }
                    @endphp


                    {{-- Display total --}}
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><strong>Total USD</strong></td>
                        <td class="text-center"><strong>$ {{ $totalAmount }}</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><strong>Old balance</strong></td>
                        <td class="text-center"><strong>$ {{ $oldBalance }}</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><strong>Grand total </strong></td>
                        <td class="text-center"><strong>$ {{ $oldBalance + $totalAmount }}</strong></td>
                        <td></td>
                    </tr>
                    </tfoot>

                    </tbody>
                </table>
            @else
                <div class="row" style="margin-top: 10px">
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
      </div>
    </section>
    @include('livewire.admin.shared.reports.footer')

</main>

</body>
</html>
