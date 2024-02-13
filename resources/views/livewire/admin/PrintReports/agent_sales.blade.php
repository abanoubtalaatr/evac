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
            <h4>Date : {{\Illuminate\Support\Carbon::today()->format('Y-m-d')}}</h4>

            @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} - To : {{request()->toDate}}</h4>
            @endif
            @php
                $fromDate = request()->fromDate;
            $toDate = request()->toDate;
   $today = now()->format('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'
        $query = \App\Models\Agent::query();
        // Check if the user is the owner
        if (!\App\Helpers\isOwner()) {
            $query->owner();
        }

        if($fromDate && $toDate){
            $query->where(function ($query) use ($fromDate, $toDate, $today) {
                if ($fromDate && $toDate) {
                    $query->whereHas('applications', function ($appQuery) use ($fromDate, $toDate) {
                        $appQuery->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate);
                    })->orWhereHas('serviceTransactions', function ($transQuery) use ($fromDate, $toDate) {
                        $transQuery->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate);
                    });
                } else {
                    // If no fromDate and toDate, filter records for the last one week
                    $query->whereHas('applications', function ($appQuery) use ($today) {
                        $appQuery->whereBetween('created_at', [now()->subWeek(), $today]);
                    })->orWhereHas('serviceTransactions', function ($transQuery) use ($today) {
                        $transQuery->whereBetween('created_at', [now()->subWeek(), $today]);
                    });
                }
            })
                ->withSum('applications', 'vat')
                ->withSum('applications', 'dubai_fee')
                ->withSum('applications', 'service_fee')
                ->withSum('serviceTransactions', 'vat')
                ->withSum('serviceTransactions', 'dubai_fee')
                ->withSum('serviceTransactions', 'service_fee')
                ->withCount('applications')
                ->withCount('serviceTransactions');

            // Add a new column for previous balance
            $query->addSelect([
                'previous_bal' => \App\Models\PaymentTransaction::query()
                    ->whereColumn('agent_id', 'agents.id')
                    ->selectRaw('COALESCE(SUM(amount), 0)'),


            ]);


            $records = $query->orderBy('name')->get();

            // Retrieve counts from the result
            $totalApplicationsCount = $records->pluck('applications_count')->sum();
            $totalServiceTransactionsCount = $records->pluck('service_transactions_count')->sum();
            $totalPreviousBalSum = $records->pluck('previous_bal')->sum();
            $totalTotalAmountSum = $records->pluck('total_amount')->sum();

            // Add total counts to the result
            $records->total_applications_count = $totalApplicationsCount;
            $records->total_previous_bal_sum = $totalPreviousBalSum;
            $records->total_service_transactions_count = $totalServiceTransactionsCount;
}else{
            $records =[];
}
                            $rowsCount= 1;
            $totalAmount = 0;
            $totalVisas =0;
            $totalGrand = 0;
            $totalPayment = 0;
            $totalServices = 0;
            $defaultVisa = \App\Models\VisaType::query()->where('is_default', 1)->first();
            $totalDefaultVisaCount = 0;
            $totalNewSales = 0;
            $totalPreviousBal = 0;
            $totalServiceTransactionsAmount =0;
            $totalApplicationsAmount= 0;
            $totalApplicationsAmountForAgent = 0;
            $totalServiceTransactionsAmountForAgent = 0;
            $totalPreviousBalForAllAgents = 0;
            $totalNewSalesForAllAgents =0;
            $totalForAllAgent =0;
            $totalApplicationCount =0;
            $totalApplicationVisaCount =0;
            $totalServiceCount = 0;

            @endphp
            @if(isset($records) && count($records) > 0)
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th>Agent</th>
                        <th>{{$defaultVisa->name}}</th>
                        <th>Previous Bal</th>
                        <th>New Sales</th>
                        <th>Total Amount</th>
                        @foreach(\App\Models\VisaType::query()->where('id', '!=', $defaultVisa->id)->get() as $visa)
                            <th>{{$visa->name}}</th>
                        @endforeach
                        <th>Total Visas</th>

                        @foreach(\App\Models\Service::query()->get() as $service)
                            <th>{{$service->name}}</th>
                        @endforeach
                        <th>Total Services</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        @php
                            $totalAmountForAgent =  0;
                            $defaultVisaCount = \App\Models\Application::query()->when(request()->fromDate && request()->toDate, function ($query) {
                                                                    $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                                            })->where('visa_type_id', $defaultVisa->id)->where('travel_agent_id', $record->id)->count();
                            $totalDefaultVisaCount += $defaultVisaCount;
                            if(isset(request()->fromDate) & isset(request()->toDate)){
                                $totalAmountForAgent = \App\Helpers\totalAmount($record->id, request()->fromDate, request()->toDate);

                                $totalPreviousBalForAllAgents += \App\Helpers\oldBalance($record->id, $totalAmountForAgent, request()->fromDate, request()->toDate);
                                $totalNewSalesForAllAgents += \App\Helpers\totalAmountBetweenTwoDate($record->id, request()->fromDate, request()->toDate);
                                $totalForAllAgent += \App\Helpers\oldBalance($record->id, $totalAmountForAgent,request()->fromDate, request()->toDate)+ $totalAmountForAgent;
                            }

                        @endphp
                        <tr>
                            <td>{{$record->name}}</td>
                            <td>{{$defaultVisaCount}}</td>
                            <td>{{\App\Helpers\formatCurrency(\App\Helpers\oldBalance($record->id, $totalAmountForAgent,request()->fromDate, request()->toDate))}}</td>
                            <td>{{\App\Helpers\formatCurrency( \App\Helpers\totalAmountBetweenTwoDate($record->id, $fromDate, $toDate))}}</td>
                            <td>{{\App\Helpers\formatCurrency(\App\Helpers\oldBalance($record->id, $totalAmountForAgent,request()->fromDate, request()->toDate) + \App\Helpers\totalAmountBetweenTwoDate($record->id, $fromDate, $toDate))}}</td>

                            @foreach(\App\Models\VisaType::query()->where('id', '!=', $defaultVisa->id)->get() as $visa)
                                @php
                                    $visaCountForAgent =  \App\Models\Application::query()->when(request()->fromDate && request()->toDate, function ($query) {
                                         $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                     })->where('visa_type_id', $visa->id)->where('travel_agent_id', $record->id)->count();

                                @endphp
                                <td>{{$visaCountForAgent}}</td>
                            @endforeach

                            @php
                                $countVisa =  \App\Models\Application::query()->when(request()->fromDate && request()->toDate, function ($query){
                                         $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                     })->where('travel_agent_id', $record->id)->count();
                                $totalApplicationVisaCount +=$countVisa;
                            @endphp
                            <td>
                                {{$countVisa}}
                            </td>


                            @foreach(\App\Models\Service::query()->get() as $service)
                                @php
                                    $countServiceForAgent = \App\Models\ServiceTransaction::query()->when(request()->fromDate && request()->toDate, function ($query) {
                                        $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                    })->where('service_id', $service->id)->where('agent_id', $record->id)->count();
                                    $totalServices += $countServiceForAgent;
                                @endphp

                                <td>{{$countServiceForAgent}}</td>
                            @endforeach
                            @php
                                $serviceCount = \App\Models\ServiceTransaction::query()->when(request()->fromDate && request()->toDate, function ($query){
                                         $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                     })->where('agent_id', $record->id)->count();
                                $totalServiceCount +=$serviceCount;
                            @endphp
                            <td>
                                {{$serviceCount}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Total </td>
                        <td>{{\App\Helpers\formatCurrency($totalDefaultVisaCount)}}</td>
                        <td>{{\App\Helpers\formatCurrency($totalPreviousBalForAllAgents)}}</td>
                        <td>{{\App\Helpers\formatCurrency($totalNewSalesForAllAgents)}}</td>
                        <td>{{\App\Helpers\formatCurrency($totalNewSalesForAllAgents + $totalPreviousBalForAllAgents )}}</td>


                        @foreach(\App\Models\VisaType::query()->where('id', '!=', $defaultVisa->id)->get() as $visa)
                            @php
                                $totalVisaCountForAllAgents = \App\Models\Application::query()
                                    ->where('visa_type_id', $visa->id)
                                    ->when(request()->fromDate && request()->toDate, function ($query) {
                                            $query->whereDate('created_at', '>=', request()->toDate)->whereDate('created_at', '<=', request()->toDate);
                                    })
                                    ->whereIn('travel_agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalVisaCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$totalApplicationVisaCount}}</td>

                    @foreach(\App\Models\Service::query()->get() as $service)
                            @php
                                $totalServiceCountForAllAgents = \App\Models\ServiceTransaction::query()
                                    ->where('service_id', $service->id)
                                    ->when(request()->$fromDate && request()->$toDate, function ($query) {
                                            $query->whereDate('created_at', '>=', request()->fromDate)->whereDate('created_at', '<=', request()->toDate);
                                        })
                                    ->whereIn('agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalServiceCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$totalServiceCount}}</td>
                    </tr>
                    </tfoot>

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
