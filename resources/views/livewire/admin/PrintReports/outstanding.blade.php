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
    </style>
</head>
<body>

<main>
    @include('livewire.admin.shared.reports.header')
    <!--dashboard-->
    <section class="dashboard">
        <div class="row">

            @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} : to: {{request()->toDate}}</h4>
            @endif

            @php
                $fromDate = request()->fromDate;
       $toDate = request()->toDate;

       if (\App\Helpers\isOwner()) {
            $agents = \App\Models\Agent::query()->isActive()->orderBy('name');
        } else {
            $agents = \App\Models\Agent::owner()->isActive()->orderBy('name');
        }

        $totalSalesByAgent = [];
        $totalSalesForAllAgent = 0;
        $totalUnPaidBal = 0;

        foreach ($agents->get() as $agent) {
                $paidBal = $agent->paymentTransactions->sum('amount');

                $applicationSum = $agent->applications()
                        ->sum('vat') +
                    $agent->applications()
                        ->sum('dubai_fee') +
                    $agent->applications()
                        ->sum('service_fee');

                // Sum for serviceTransactions
                $serviceTransactionSum = $agent->serviceTransactions()->where('status', '!=', 'deleted')
                        ->sum('vat') +
                    $agent->serviceTransactions()->where('status', '!=', 'deleted')
                        ->sum('dubai_fee') +
                    $agent->serviceTransactions()->where('status', '!=', 'deleted')
                        ->sum('service_fee');

                    $totalSales = $applicationSum + $serviceTransactionSum;

                $totalSalesForAllAgent += $totalSales;
                $totalUnPaidForEveryAgent = $totalSales - $paidBal;

                $totalUnPaidBal += $totalUnPaidForEveryAgent;

                $totalSalesByAgent[] = [
                    'agent_id' => $agent->id,
                    'agent_name' => $agent->name,
                    'total_sales' => $totalSales,
                    'un_paid_bail' => $totalSales - $paidBal,
                ];

            }

        $dataDirect = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw('(SELECT name, surname, vat, service_fee, dubai_fee FROM service_transactions WHERE status != "deleted" AND agent_id IS NULL
                    UNION ALL
                    SELECT first_name AS name, last_name AS surname, vat, service_fee, dubai_fee FROM applications WHERE travel_agent_id IS NULL) AS combined_data'))
            ->select('name', 'surname', \Illuminate\Support\Facades\DB::raw('SUM(vat + service_fee + dubai_fee) AS total_combined_fee'))
            ->groupBy('name', 'surname')
            ->orderBy('name')
            ->get();

        $totalDirectSales =0;
        $totalUnPaidBalDirect =0;
        foreach ($dataDirect as $item) {

            $unpaidAmount = \App\Models\Application::where('first_name', $item->name)
                ->where('last_name', $item->surname)
                ->where('payment_method', 'invoice')
                ->whereNull('travel_agent_id')
                ->sum(\Illuminate\Support\Facades\DB::raw('service_fee + vat + dubai_fee'));

            $unpaidAmount += \App\Models\ServiceTransaction::where('name', $item->name)
                ->where('surname', $item->surname)
                ->where('status', '!=', 'deleted')
                ->whereNull('agent_id')
                ->where('payment_method', 'invoice')
                ->sum(\Illuminate\Support\Facades\DB::raw('service_fee + vat + dubai_fee'));

            $data['directs'][] = [
                'name' => $item->name . ' ' . $item->surname,
                'total' => $item->total_combined_fee,
                'un_paid' => $unpaidAmount,
            ];

            $totalDirectSales += $item->total_combined_fee;
            $totalUnPaidBalDirect += $unpaidAmount;
        }

        $data['total_sales_for_direct'] = $totalDirectSales;
        $data['total_un_paid_bal_for_direct'] = $totalUnPaidBalDirect;
        $data['agents'] = $totalSalesByAgent;
        $data['total_sales_for_all_agents'] = $totalSalesForAllAgent;
        $data['total_un_paid_bal_for_agents'] = $totalUnPaidBal;

            @endphp
                @if(isset($data['agents']) && count($data['agents']) )
                    <table class="table-page table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center" >@lang('admin.agent')</th>
                            <th class="text-center" >@lang('admin.total_sales')</th>
                            <th class="text-center" >@lang('admin.un_paid_bail')</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['agents'] as $record)
                            <tr>
                                <td>#{{$loop->index + 1}}</td>
                                <td class='text-center'>{{$record['agent_name'] }}</td>
                                <td class='text-center'>$ {{\App\Helpers\formatCurrency($record['total_sales']) }}</td>
                                <td class='text-center'>$ {{\App\Helpers\formatCurrency($record['un_paid_bail'] )}}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            {{--                        <td></td>--}}
                            <td colspan="2">Total </td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_sales_for_all_agents'])}}</td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_un_paid_bal_for_agents'])}}</td>
                        </tr>
                        </tfoot>
                        </tbody>
                    </table>

                @else
                    <div class="row" style='margin-top:10px'>
                        <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                    </div>
                @endif
                @if(isset($data['directs']) && count($data['directs']) )
                    <table class="table-page table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center" >@lang('admin.direct')</th>
                            <th class="text-center" >@lang('admin.total_sales')</th>
                            <th class="text-center" >@lang('admin.un_paid_bail')</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['directs'] as $record)
                            <tr>
                                <td>#{{$loop->index + 1}}</td>
                                <td class='text-center'>{{$record['name'] }}</td>
                                <td class='text-center'>$ {{\App\Helpers\formatCurrency($record['total']) }}</td>
                                <td class='text-center'>$ {{\App\Helpers\formatCurrency($record['un_paid']) }}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total </td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_sales_for_direct'])}}</td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_un_paid_bal_for_direct'])}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Sales</td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_sales_for_direct'] + $data['total_sales_for_all_agents'])}}</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total unpaid</td>
                            <td class="text-center">{{\App\Helpers\formatCurrency($data['total_un_paid_bal_for_agents'] + $data['total_un_paid_bal_for_direct'])}}</td>
                            <td class="text-center"></td>
                        </tr>
                        </tfoot>
                        </tbody>
                    </table>

                @endif
        </div>
    </section>
    @include('livewire.admin.shared.reports.footer')

</main>

</body>
</html>
