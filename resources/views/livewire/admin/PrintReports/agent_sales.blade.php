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

            @php
                $fromDate = request()->fromDate;
            $toDate = request()->toDate;

            $today = now()->format('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'

            $query = \App\Models\Agent::query()
                ->where(function ($query) use ($fromDate, $toDate, $today) {
                    if ($fromDate && $toDate) {
                        $query->whereHas('applications', function ($appQuery) use ($fromDate, $toDate) {
                            $appQuery->whereBetween('created_at', [$fromDate, $toDate]);
                        })->orWhereHas('serviceTransactions', function ($transQuery) use ($fromDate, $toDate) {
                            $transQuery->whereBetween('created_at', [$fromDate, $toDate]);
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

            // Check if the user is the owner
            if (\App\Helpers\isOwner()) {
                $query->owner();
            }

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
                $visasAndCount = [];
            @endphp
            @if(isset($records) && count($records) > 0)
                <table class="table-page table">
                    <thead>
                    <tr>

                        <th>Agent</th>
                        <th>Default visa</th>
                        <th>Previous Bal</th>
                        <th>New Sales</th>
                        <th>Total Amount</th>
                        @foreach(\App\Models\VisaType::query()->get() as $visa)
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
                            $totalAmountForAgent = $record->applications_sum_vat+ $record->applications_sum_dubai_fee+ $record->applications_sum_service_fee+ $record->service_transactions_sum_vat+ $record->service_transactions_sum_dubai_fee+ $record->service_transactions_sum_service_fee+ $record->applications_count+ $record->service_transactions_count;
                            $defaultVisaCount = \App\Models\Application::query()->where('visa_type_id', $defaultVisa->id)->where('travel_agent_id', $record->id)->count();
                            $totalDefaultVisaCount += $defaultVisaCount;
                            $totalAmount += $totalAmountForAgent;

                        @endphp
                        <tr>
                            <td>{{$record->name}}</td>
                            <td>{{$defaultVisaCount}}</td>
                            <td>{{$record->previous_bal}}</td>
                            <td>{{$totalAmountForAgent - $record->previous_bal }}</td>
                            <td>{{$totalAmountForAgent}}</td>

                            @foreach(\App\Models\VisaType::query()->get() as $visa)
                                @php
                                    $visaCountForAgent =  \App\Models\Application::query()->where('visa_type_id', $visa->id)->where('travel_agent_id', $record->id)->count();
                                @endphp
                                <td>{{$visaCountForAgent}}</td>
                            @endforeach

                            <td>{{\App\Models\Application::query()->where('travel_agent_id', $record->id)->count()}}</td>


                            @foreach(\App\Models\Service::query()->get() as $service)
                                @php
                                    $countServiceForAgent = \App\Models\ServiceTransaction::query()->where('service_id', $service->id)->where('agent_id', $record->id)->count();
                                    $totalServices += $countServiceForAgent;
                                @endphp

                                <td>{{$countServiceForAgent}}</td>
                            @endforeach
                            <td>{{\App\Models\ServiceTransaction::query()->where('agent_id', $record->id)->count()}}</td>

                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Total </td>
                        <td>{{$totalDefaultVisaCount}}</td>
                        <td>{{$records->total_previous_bal_sum}}</td>
                        <td>{{$totalAmount - $records->total_previous_bal_sum}}</td>
                        <td>{{$totalAmount}}</td>

                        @foreach(\App\Models\VisaType::query()->get() as $visa)
                            @php
                                $totalVisaCountForAllAgents = \App\Models\Application::query()
                                    ->where('visa_type_id', $visa->id)
                                    ->whereIn('travel_agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalVisaCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$records->total_applications_count}}</td>

                        @foreach(\App\Models\Service::query()->get() as $service)
                            @php
                                $totalServiceCountForAllAgents = \App\Models\ServiceTransaction::query()
                                    ->where('service_id', $service->id)
                                    ->whereIn('agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalServiceCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$records->total_service_transactions_count}}</td>
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
