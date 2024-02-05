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
            /*width: 33.33%; !* Equal width for each column *!*/
        }
        table {
            width: 100%;
        }

        th, td {
            width: auto; /* or specify widths in pixels */
        }

    </style>
</head>
<body>

<main>
    @include('livewire.admin.shared.reports.header')
    <!--dashboard-->
    <section class="dashboard">
        <div class="row">
            <h4>Date : {{\Illuminate\Support\Carbon::today()->format('Y-m-d')}}</h4>

           @php
           $agent = \App\Models\Agent::query()->find(request()->agent);

           @endphp
        @if($agent)
                <h4>Agent : {{$agent->name}}</h4>
                <h4>Financial No: {{$agent->finance_no}}</h4>

            @endif

            @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} - To : {{request()->toDate}}</h4>
            @endif

            @php
                $totalDrCount= 0;
                $totalCrCount = 0;
 if(\App\Helpers\isOwner()){
            if (isset(request()->agent)) {
                $invoiceQuery = \App\Models\AgentInvoice::query()->where('agent_id', request()->agent);
                $paymentQuery = \App\Models\PaymentTransaction::query()->where('agent_id', request()->agent);

                // Add date range filters if provided
                if (request()->from && request()->to) {
                    $invoiceQuery->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);;
                    $paymentQuery->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);;
                }

                $records['invoices'] = $invoiceQuery->orderBy('from')->get();
                $records['payment_received'] = $paymentQuery->get();
            }
        }else{
            if (isset(request()->agent)) {
                $invoiceQuery = \App\Models\AgentInvoice::query()
                    ->whereHas('agent', function ($agent){
                        $agent->where('is_visible', 1);
                    })
                    ->where('agent_id', request()->agent);
                $paymentQuery = \App\Models\PaymentTransaction::query()
                    ->whereHas('agent', function ($agent){
                        $agent->where('is_visible', 1);
                    })
                    ->where('agent_id', request()->agent);

                // Add date range filters if provided
                if (request()->from && request()->to) {
                    $invoiceQuery->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);;
                    $paymentQuery->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);
                }

                $records['invoices'] = $invoiceQuery->orderBy('from')->get();
                $records['payment_received'] = $paymentQuery->get();
            }
        }

            @endphp
            @if(isset($records) && count($records) > 0)
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">@lang('admin.description')</th>
                        <th class="text-center">Dr.</th>
                        <th class="text-center">Cr.</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($records['invoices'] as $record)
                        <tr>
                            <td class="text-center">{{$record['from'] . ' - ' . $record['to']}}</td>
                            <td class =text-center>{{$record->invoice_title}}</td>
                            @php
                                $totalDrCount += $record->total_amount;
                            @endphp
                            <td class="text-center">{{$record->total_amount}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    @foreach($records['payment_received'] as $record)
                        <tr>
                            <td class="text-center">{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</td>
                            <td class='text-center'>Payment received</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{$record->amount}}</td>
                            @php
                                $totalCrCount += $record->amount;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-center">Totals</td>
                        <td  class="text-center">{{$totalDrCount}}</td>
                        <td class="text-center">{{$totalCrCount}}</td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>
            @endif
      </div>
    </section>
    @include('livewire.admin.shared.reports.footer')

</main>

</body>
</html>
