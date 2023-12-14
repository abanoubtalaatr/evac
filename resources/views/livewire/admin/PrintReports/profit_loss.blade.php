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
{{--    @include('livewire.admin.shared.reports.header')--}}
    <!--dashboard-->
    <section class="dashboard">
        <h4>Period From : {{request()->fromDate}} till : {{request()->toDate}}</h4>
        <div class="row">
            @php
                $fromDate = request()->fromDate;
                $toDate = request()->toDate;

        $applicationServiceFees = \App\Models\Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('service_fee');
        $applicationDubaiFees = \App\Models\Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('dubai_fee');
        $applicationVats = \App\Models\Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('vat');
        $serviceTransactionServiceFees = \App\Models\ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('service_fee');
        $serviceTransactionDubaiFees = \App\Models\ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('dubai_fee');
        $serviceTransactionVats = \App\Models\ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('vat');
        $data['total_sales'] = $applicationServiceFees + $applicationDubaiFees + $applicationDubaiFees + $serviceTransactionServiceFees + $applicationVats + $serviceTransactionDubaiFees + $serviceTransactionVats;
        $data['profit_loss'] = ($applicationServiceFees + $serviceTransactionServiceFees) - ($applicationVats + $serviceTransactionVats);
        $data['vat'] = $applicationVats + $serviceTransactionVats;
        $data['dubai_fee'] = $applicationDubaiFees + $serviceTransactionDubaiFees;
        $data['payments_received'] = \App\Models\PaymentTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->sum('amount');

            @endphp

            <table class="table-page table my-3">
                <thead>
                <tr>
                    <th class="" >@lang('admin.description')</th>
                    <th class="">@lang('admin.amount')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Total sales  : </td>
                    <td>{{$data['total_sales']}} $</td>
                </tr>
                <tr>
                    <td>Less dubai fee :</td>
                    <td> {{$data['dubai_fee']}} $</td>
                </tr>
                <tr>
                    <td>VAT : </td>
                    <td>{{$data['vat']}} $</td>
                </tr>
                <tr>
                    <td>P&L : </td>
                    <td>{{$data['profit_loss']}} $</td>
                </tr>
                <tr>
                    <td>Payment received :  </td>
                    <td>{{$data['payments_received']}} $</td>
                </tr>
                </tbody>
            </table>

        </div>
    </section>
{{--    @include('livewire.admin.shared.reports.footer')--}}

</main>

</body>
</html>
