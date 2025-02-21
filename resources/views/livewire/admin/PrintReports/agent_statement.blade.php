<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .table-responsive {
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

        .border-0 {
            border: none;
        }

        .card-body {
            padding: 15px;
        }

        .table {
    width: 100%;
    table-layout: fixed; /* Ensures equal spacing */
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    word-wrap: break-word; /* Ensures text wraps */
    overflow-wrap: break-word;
    text-overflow: ellipsis;
    white-space: nowrap; /* Optional: Prevents wrapping */
}

.table th:nth-child(1),
.table td:nth-child(1) { width: 15%; } /* Adjust width percentages */
.table th:nth-child(2),
.table td:nth-child(2) { width: 20%; }
.table th:nth-child(3),
.table td:nth-child(3) { width: 20%; }
.table th:nth-child(4),
.table td:nth-child(4) { width: 15%; }
.table th:nth-child(5),
.table td:nth-child(5) { width: 15%; }


    </style>
</head>

<body>

    <main>
        @include('livewire.admin.shared.reports.header', ['showReportsAgentStatement' => true])
        <!--dashboard-->
        <section class="dashboard">
            <div class="row">
                <span class="span-block">Date : {{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}</span>

                @php
                    $agent = \App\Models\Agent::query()->find(request()->agent);

                @endphp
                @if ($agent)
                    <span class="span-block">Agent : {{ $agent->name }}</span>
                    <span class="span-block mb-3">Financial No: {{ $agent->finance_no }}</span>
                @endif

                @if (request()->fromDate && request()->toDate)
                    {{-- <h4>{{request()->fromDate}} -  {{request()->toDate}}</h4> --}}
                @endif

                @php
                    $totalDrCount = 0;
                    $totalCrCount = 0;

                    if (\App\Helpers\isOwner()) {
                        if (isset($agent)) {
                            $invoiceQuery = \App\Models\AgentInvoice::query()->where('agent_id', $agent->id);
                            $paymentQuery = \App\Models\PaymentTransaction::query()->where('agent_id', $agent->id);

                            // Add date range filters if provided
                            if (request()->fromDate && request()->toDate) {
                                $invoiceQuery
                                    ->whereDate('from', '>=', request()->fromDate)
                                    ->whereDate('to', '<=', request()->toDate);
                                $paymentQuery
                                    ->whereDate('created_at', '>=', request()->fromDate)
                                    ->whereDate('created_at', '<=', request()->toDate);
                            }

                            $data['invoices'] = $invoiceQuery->get();
                            $data['payment_received'] = $paymentQuery->get();
                        }
                    } else {
                        if (isset($agent)) {
                            $invoiceQuery = \App\Models\AgentInvoice::query()
                                ->whereHas('agent', function ($agent) {
                                    $agent->where('is_visible', 1);
                                })
                                ->where('agent_id', $agent->id);
                            $paymentQuery = \App\Models\PaymentTransaction::query()
                                ->whereHas('agent', function ($agent) {
                                    $agent->where('is_visible', 1);
                                })
                                ->where('agent_id', $agent->id);

                            // Add date range filters if provided
                            if (request()->fromDate && request()->toDate) {
                                $invoiceQuery
                                    ->whereDate('from', '>=', request()->fromDate)
                                    ->whereDate('to', '<=', request()->toDate);
                                $paymentQuery
                                    ->whereDate('created_at', '>=', request()->fromDate)
                                    ->whereDate('created_at', '<=', request()->toDate);
                            }

                            $data['invoices'] = $invoiceQuery->get();
                            $data['payment_received'] = $paymentQuery->get();
                        }
                    }

                    // Combine and order the results
                    $combinedResults = collect($data['invoices'])
                        ->merge($data['payment_received'])
                        ->sortBy(function ($item) {
                            // Assuming 'from' for invoices and 'created_at' for payment_received
                            return $item instanceof \App\Models\AgentInvoice ? $item->from : $item->created_at;
                        })
                        ->values()
                        ->all();

                    // Calculate the total sum of total_amount from invoices
                    $totalDrCount = collect($data['invoices'])->sum('total_amount');

                    // Calculate the total sum of amount from payment_received
                    $totalCrCount = collect($data['payment_received'])->sum('amount');

                    $records['totalDrCount'] = $totalDrCount;
                    $records['totalCrCount'] = $totalCrCount;
                    $outStanding = $records['totalDrCount'] - $records['totalCrCount'];

                    $records['data'] = $combinedResults;

                @endphp

                @if (isset($records) && count($records) > 0)
                    <table class="table-page table mt-3">
                        <thead>
                            <tr>
                                <th class="">Inv No</th>
                                <th class="">From</th>
                                <th class="">To</th>
                                <th class="text-center">Db.</th>
                                <th class="text-center">Cr.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records['data'] as $record)
                                @if ($record instanceof \App\Models\AgentInvoice)
                                    <tr>
                                        <td>{{ $record['invoice_title'] }}</td>
                                        <td class="text-center">{{ $record['from'] }}</td>
                                        <td class="text-center">{{ $record['to'] }}</td>
                                        @php
                                            $totalDrCount += $record->total_amount;
                                        @endphp
                                        <td class="text-center">{{ \App\Helpers\formatCurrency($record->total_amount) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr>

                                        <td class="text-center">
                                            {{ \Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}
                                        </td>
                                        <td class='text-center'>Payment received @if ($record->note)
                                                <span>- Note : {{ $record->note }} </span>
                                            @endif
                                        </td>

                                        <td></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{ \App\Helpers\formatCurrency($record->amount) }}</td>
                                        @php
                                            $totalCrCount += $record->amount;
                                        @endphp
                                    </tr>
                                @endif
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="text-center">Totals</td>
                                <td></td>
                                <td class="text-center">{{ \App\Helpers\formatCurrency($records['totalDrCount']) }}
                                </td>
                                <td class="text-center">{{ \App\Helpers\formatCurrency($records['totalCrCount']) }}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center"><strong>Outstanding bal</strong></td>
                                <td></td>
                                <td class="text-center">
                                    {{ \App\Helpers\formatCurrency($records['totalDrCount'] - $records['totalCrCount']) }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center">
                        Outstanding Balance is {{ \App\Helpers\convertNumberToWorldsInUsd($outStanding ?? 0) }}
                    </p>
                @endif

            </div>
        </section>
        @include('livewire.admin.shared.reports.footer')
        <style>
            .span-block {
                display: block;
                margin-bottom: 3px;
                font-size: 12px;
            }
        </style>
    </main>

</body>

</html>
