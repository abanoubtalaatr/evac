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
                <h4>From : {{request()->fromDate}} - To : {{request()->toDate}}</h4>
            @endif
@php
//dd(request()->all());
    $fromDate = request()->fromDate;
            $toDate = request()->toDate;
            $data['applications']['invoices'] = \App\Models\Application::query()
                ->where('payment_method', 'invoice')
                ->where('travel_agent_id', null)
->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            })                ->get();
            $data['applications']['cashes'] = \App\Models\Application::query()
                ->where('payment_method', 'cash')
                ->where('travel_agent_id', null)
->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            })                ->get();

            $data['serviceTransactions']['invoices'] = \App\Models\ServiceTransaction::query()
                ->where('agent_id', null)
                ->where('payment_method', 'invoice')
->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            })                ->get();

            $data['serviceTransactions']['cashes'] =  \App\Models\ServiceTransaction::query()
                ->where('agent_id', null)
                ->where('payment_method', 'cash')
->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            })                ->get();

 @endphp
            @if(count($data['applications']['invoices']) || count($data['serviceTransactions']['cashes']) || count($data['serviceTransactions']['invoices']))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" >@lang('admin.date')</th>
                        <th class="text-center">@lang('admin.application_name')</th>
                        <th class="text-center">@lang('admin.amount')</th>
                        <th class="text-center">@lang('admin.payment')</th>
                        <th class="text-center">@lang('admin.type')</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['applications']['invoices'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}</td>
                            <td class='text-center'>{{ $record->first_name . ' '. $record->last_name.  ' - ' . $record->passport_no}}</td>
                            <td class='text-center'>{{$record->amount}}</td>
                            <td class='text-center'>{{$record->payment_method}}</td>
                            <td class='text-center'>{{$record->visaType->name}}</td>
                        </tr>
                    @endforeach
                    @foreach($data['serviceTransactions']['invoices'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}</td>
                            <td class='text-center'>{{ $record->name . ' '. $record->surname.  ' - ' . $record->passport_no}}</td>
                            <td class='text-center'>{{$record->amount}}</td>
                            <td class='text-center'>{{$record->payment_method}}</td>
                            <td class='text-center'>{{$record->service->name}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach($data['applications']['cashes'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}</td>
                            <td class='text-center'>{{ $record->first_name . ' '. $record->last_name.  ' - ' . $record->passport_no}}</td>
                            <td class='text-center'>{{$record->amount}}</td>
                            <td class='text-center'>{{$record->payment_method}}</td>
                            <td class='text-center'>{{$record->visaType->name}}</td>
                        </tr>
                    @endforeach
                    @foreach($data['serviceTransactions']['cashes'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}</td>
                            <td class='text-center'>{{ $record->name . ' '. $record->surname.  ' - ' . $record->passport_no}}</td>
                            <td class='text-center'>{{$record->amount}}</td>
                            <td class='text-center'>{{$record->payment_method}}</td>
                            <td class='text-center'>{{$record->service->name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>
    </section>
    @include('livewire.admin.shared.reports.footer')

</main>

</body>
</html>
