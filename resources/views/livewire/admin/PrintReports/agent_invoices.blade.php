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
                <h4>Agent : {{$agent->name}}</h4>
                <h4>Agent address: {{$agent->address}}</h4>

                <h4>Financial No: {{$agent->finance_no}}</h4>
                <h4>Tel : {{$agent->telephone}}</h4>
                <h4>Agent invoices</h4>
            @endif
            <h4>Date : {{\Illuminate\Support\Carbon::today()->format('Y-m-d')}}</h4>

        @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} - To : {{request()->toDate}}</h4>
            @endif

            @php
                if (request()->agent && !is_null(request()->agent) && request()->agent !='no_result') {
                    $data = ['visas' => [], 'services' => []];

                    $visas = \App\Models\VisaType::query()->get();

                    foreach ($visas as $visa) {
                        $applications = \App\Models\Application::query()
                            ->where('visa_type_id', $visa->id)
                            ->where('travel_agent_id', request()->agent);

                        if (request()->fromDate && request()->toDate) {
                            $applications->whereBetween('created_at', [request()->fromDate, request()->toDate . ' 23:59:59']);
                        }

                        $applications = $applications->get(); // Assign the results to the variable

                        $visa->qty = $applications->count();
                        $data['visas'][] = $visa;
                    }

                    $services = \App\Models\Service::query()->get();

                    foreach ($services as $service) {
                        $serviceTransactions = \App\Models\ServiceTransaction::query()
                            ->where('agent_id', request()->agent)
                            ->where('service_id', $service->id);

                        if (request()->fromDate && request()->toDate) {
                            $serviceTransactions->whereBetween('created_at', [request()->fromDate, request()->toDate . ' 23:59:59']);
                        }

                        $serviceTransactions = $serviceTransactions->get(); // Assign the results to the variable

                        $service->qty = $serviceTransactions->count();
                        $data['services'][] = $service;
                    }

                }
                @endphp

            @php
                $rowsCount= 1;
                $totalAmount = 0;
                $totalGrand = 0;
                $totalPayment = 0;
            @endphp
            @if(isset($data['visas']) && count($data['visas']) > 0)
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
                    @endphp


                        @if(!is_null($agent))

                            {{-- Display Visa information --}}
                            @if(isset($data['visas']) && count($data['visas']) > 0)

                                @foreach($data['visas'] as $visa)

                                    <tr>
                                        @php
                                            $totalAmount += $visa->total * $visa->qty;
                                        @endphp
                                        <td class="text-center">#{{ $rowsCount++ }}</td>
                                        <td class="text-center">{{ $visa->name }}</td>
                                        <td class="text-center">{{ $visa->qty }}</td>
                                        <td class="text-center">{{ $visa->total }}</td>
                                        <td class="text-center">{{ $visa->total * $visa->qty }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Display Service information --}}
                            @if(isset($data['services']) && count($data['services']) > 0)
                                @foreach($data['services'] as $service)

                                    <tr>
                                        @php
                                            $totalAmount += $service->amount * $service->qty;
                                        @endphp
                                        <td class="text-center">#{{ $rowsCount++ }}</td>
                                        <td class="text-center">{{ $service->name }}</td>
                                        <td class="text-center">{{ $service->qty }}</td>
                                        <td class="text-center">{{ $service->amount }}</td>
                                        <td class="text-center">{{ $service->amount * $service->qty }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @php
                                $totalPayment += \App\Models\PaymentTransaction::query()->where('agent_id', $agent->id)->sum('amount');
                            @endphp
                        @endif


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
                        <td class="text-center"><strong>$ {{ ($totalAmount + $totalPayment) - $totalAmount }}</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><strong>Grand total </strong></td>
                        <td class="text-center"><strong>$ {{ $totalAmount - $totalPayment }}</strong></td>
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
