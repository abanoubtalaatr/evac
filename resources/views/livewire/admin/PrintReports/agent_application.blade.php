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
    </style>
</head>
<body>

<main>
    @include('livewire.admin.shared.reports.header')
    <!--dashboard-->
    <section class="dashboard" style="margin-bottom: 20px">
        <div class="row">
            @php
                $agent = \App\Models\Agent::query()->find(request()->agent);
             @endphp
            @if(request()->fromDate && request()->toDate)
                <h4>From : {{request()->fromDate}} : to: {{request()->toDate}}</h4>
            @endif
            @if($agent)
                <h4>Agent : {{$agent->name}}</h4>
                <h4>Financial No: {{$agent->finance_no}}</h4>
                <h4>Address: {{$agent->address}}</h4>
                <h4>Tel: {{$agent->telephone}}</h4>

            @else
                <button class="" style="padding: 4px;border-radius: 4px;">Direct</button>
            @endif

            @php
                if (request()->isDirect) {
                         $data['applications'] = \App\Models\Application::query()
                             ->whereNull('travel_agent_id')
                             ->when(!empty(request()->fromDate) && !empty(request()->toDate), function ($query) {
                    $query->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->fromDate);
                })
                             ->latest()
                             ->get();

                         $data['serviceTransactions'] = \App\Models\ServiceTransaction::query()
                             ->whereNull('agent_id')
                             ->when(!empty(request()->fromDate) && !empty(request()->toDate), function ($query) {
                    $query->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);
                })
                             ->latest()
                             ->get();
                     } else {
                         if (request()->agent === null) {
                             return ['applications' => [], 'serviceTransactions' => []];
                         }

                         $data['applications'] = \App\Models\Application::query()
                             ->when(request()->agent !== 'no_result', function ($query) {
                                 $query->where('travel_agent_id', request()->agent);
                             })
                             ->when(request()->agent === 'no_result', function ($query) {
                                 $query->where('travel_agent_id', '>', 0);
                             })
                                        ->when(!empty(request()->fromDate) && !empty(request()->toDate), function ($query) {
                    $query->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);
                })
                             ->latest()
                             ->get();

                         $data['serviceTransactions'] = \App\Models\ServiceTransaction::query()
                             ->when(request()->agent !== 'no_result', function ($query) {
                                 $query->where('agent_id', request()->agent);
                             })
                             ->when(request()->agent === 'no_result', function ($query) {
                                 $query->where('agent_id', '>', 0);
                             })
                                        ->when(!empty(request()->fromDate) && !empty(request()->toDate), function ($query) {
                    $query->whereDate('created_at', '>=', request()->fromDate)
                        ->whereDate('created_at', '<=', request()->toDate);
                })
                             ->latest()
                             ->get();
                     }
 @endphp
            @if(count($data['applications']) || count($data['serviceTransactions']))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th class="text-center" width="40%">@lang('admin.description')</th>
                        <th class="text-center" width="30%">@lang('admin.type')</th>
                        <th class="text-center" width="25%">@lang('admin.date')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['applications'] as $record)
                        <tr>
                            <td width="5%">#{{$loop->index + 1}}</td>
                            <td class='text-center' width="40%">{{$record->application_ref . ' - '. $record->first_name . ' ' . $record->last_name }}(application)</td>
                            <td class='text-center' width="30%">{{ $record->visaType->name}}</td>
                            <td width="25%" class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</td>
                        </tr>
                    @endforeach
                    @foreach($data['serviceTransactions'] as $record)
                        <tr>
                            <td width="5%">#{{$loop->index + 1}}</td>
                            <td class='text-center' width="40%">{{$record->service_ref . ' - '. $record->name . ' - '. $record->surname }} (service)</td>
                            <td class='text-center' width="30%">{{ $record->service->name}}</td>
                            <td width="25%" class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</td>
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
