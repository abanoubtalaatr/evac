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
           $agents = \App\Models\Agent::query()->isActive();
       } else {
           $agents = \App\Models\Agent::owner()->isActive();
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

            $applications = \App\Models\Application::query()->whereNull('travel_agent_id')->groupBy('first_name', 'last_name')->get();
            $totalDirectSales = 0;
            $totalUnPaidBalDirect =0;
            $unpaid = 0;
            $totalAmount = 0;

        foreach ($applications as $row) {
            $applicationsForPersons = \App\Models\Application::query()
                ->where('first_name', $row->first_name)
                ->where('last_name', $row->last_name)
                ->get();

            foreach ($applicationsForPersons as $applicationsForPerson) {
                $totalAmount = $applicationsForPerson->dubai_fee + $applicationsForPerson->service_fee + $applicationsForPerson->vat;
                $totalDirectSales += $totalAmount;
                $unpaid = 0;

                if ($applicationsForPerson->payment_method == 'invoice') {
                    $unpaid = $totalAmount;
                    $totalUnPaidBalDirect += $totalAmount;
                }

                $key = $row->first_name . $row->last_name;

                if (isset($data['directs'][$key])) {
                    // Key exists, add total amount to existing total
                    $data['directs'][$key]['total'] += $totalAmount;
                    $data['directs'][$key]['un_paid'] += $unpaid;
                } else {
                    // Key doesn't exist, create a new entry
                    $data['directs'][$key] = [
                        'name' => $row->first_name . ' ' . $row->last_name,
                        'total' => $totalAmount,
                        'un_paid' => $unpaid,
                    ];
                }
            }
        }

        $serviceTransactions = \App\Models\ServiceTransaction::query()->where('status', '!=', 'deleted')->groupBy('name', 'surname')->whereNull('agent_id')->get();

        foreach ($serviceTransactions as $row) {
            $serviceTransactionsForPersons = \App\Models\ServiceTransaction::query()->where('status', '!=', 'deleted')
                ->where('name', $row->name)
                ->where('surname', $row->surname)
                ->get();

            foreach ($serviceTransactionsForPersons as $serviceTransactionsForPerson) {
                $totalAmount = $serviceTransactionsForPerson->dubai_fee + $serviceTransactionsForPerson->service_fee + $serviceTransactionsForPerson->vat;
                $totalDirectSales += $totalAmount;
                $unpaid = 0;

                if ($serviceTransactionsForPerson->payment_method == 'invoice') {
                    $unpaid = $totalAmount;
                    $totalUnPaidBalDirect += $totalAmount;
                }

                $key = $row->name . $row->surname;

                if (isset($data['directs'][$key])) {
                    // Key exists, add total amount to existing total
                    $data['directs'][$key]['total'] += $totalAmount;
                    $data['directs'][$key]['un_paid'] += $unpaid;
                } else {
                    // Key doesn't exist, create a new entry
                    $data['directs'][$key] = [
                        'name' => $row->name . ' ' . $row->surname,
                        'total' => $totalAmount,
                        'un_paid' => $unpaid,
                    ];
                }
            }
        }
        $data['agents'] = $totalSalesByAgent;
        $data['total_sales_for_all_agents'] = $totalSalesForAllAgent;
        $data['total_un_paid_bal_for_agents'] = $totalUnPaidBal;
        $data['total_sales_for_direct'] = $totalDirectSales;
        $data['total_un_paid_bal_for_direct'] = $totalUnPaidBalDirect;

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
