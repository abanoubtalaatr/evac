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
            margin-bottom: 0;
            background-color: #fff;
            border-collapse: collapse;
        }

        .table th,
        .table td {
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

        .text-center {
            text-align: center;
        }

        .btn-primary {
            background: #0b5ed7;
        }

        /* Set a fixed width for each column */


        table {
            width: 100%;
        }

        th,
        td {
            width: auto;
            /* or specify widths in pixels */
        }

        .span-block {
            display: block;
            margin-bottom: 3px;
        }

        /* Basic Table Styling */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
            /* Ensures borders between cells are collapsed */
        }

        /* Border for Table */
        .table-bordered {
            border: 1px solid #dee2e6;
            /* Light gray border */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            /* Apply border to cells */
            padding: 0.75rem;
            /* Padding inside each cell */
            text-align: left;
            /* Align text to the left */
        }

        /* Table header styling */
        .table th {
            background-color: #f8f9fa;
            /* Light background for header */
            color: #495057;
            /* Dark text color */
            font-weight: bold;
        }

        /* Table row alternating color */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* Light gray for odd rows */
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
            /* White for even rows */
        }

        /* Optional: Pagination Style */
        .table-page {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .table-page a {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            color: #007bff;
            text-decoration: none;
            border-radius: 0.25rem;
        }

        .table-page a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .table-page a.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
</head>

<body>

    <main>
        @include('livewire.admin.shared.reports.header', ['showInvoiceTitle' => true])

        <!--dashboard-->
        <section class="dashboard">

            <div class="row">

                @php
                    $agent = \App\Models\Agent::query()->find(request()->agent);
                    $invoice = \App\Models\AgentInvoice::query()->find(request()->invoice);
                @endphp
                @if ($agent)
                    <div class="mt-4" style="margin-bottom: 10px;margin-top: 10px;">
                        <strong class="span-block">Agent : {{ $agent->name }}</strong>
                        <strong class="span-block">Agent address: {{ $agent->address }}</strong>

                        <strong class="span-block">Tel : {{ $agent->telephone }}</strong>
                        <strong class="span-block">Account No: {{ $agent->account_number }}</strong>
                    </div>
                @endif
                {{--            <strong class="span-block">Date : {{\Illuminate\Support\Carbon::today()->format('Y-m-d')}}</strong> --}}
                <strong class="span-block">INV No: {{ $invoice ? $invoice->invoice_title : '' }}</strong>

                @if (request()->fromDate && request()->toDate)
                    <h4>Period of sales from : {{ request()->fromDate }} - To : {{ request()->toDate }}</h4>
                @endif
                <br>
                @php
                    $data = (new \App\Services\AgentInvoiceService())->getRecords(
                        request()->agent,
                        request()->fromDate,
                        request()->toDate,
                    );

                @endphp

                @php
                    $rowsCount = 1;
                    $totalAmount = 0;
                    $totalGrand = 0;
                    $totalPayment = 0;
                    $totalVat = 0;
                    $subTotal = 0;

                @endphp
                @if (isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)
                    <table width="100%" cellpadding="0" cellspacing="0" align="center" class=" table table-bordered">
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
                                $totalApplicationAmount = 0;
                                $totalServiceTransactionsAmount = 0;
                                $oldBalance = 0;

                            @endphp



                            {{-- Display Visa information --}}
                            @if (isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)
                                @php
                                    $grandTotal = 0;
                                @endphp
                                @foreach ($data['agents'][0]['visas'] as $key => $visa)
                                    <tr>
                                        @php
                                            $totalAmount += $visa->totalAmount;
                                            $totalVat += $visa->totalVat;
                                            $subTotal += $visa->totalAmount;
                                        @endphp
                                        <td class="text-center">#{{ $rowsCount++ }}</td>
                                        <td class="text-center">{{ $visa->name }}</td>
                                        <td class="text-center">{{ $visa->qty }}</td>
                                        <td class="text-center">{{ \App\Helpers\formatCurrency($visa->total) }}</td>
                                        <td class="text-center">
                                            {{ \App\Helpers\formatCurrency($visa->totalAmount) }}</td>

                                    </tr>
                                @endforeach
                            @endif

                            {{-- Display Service information --}}
                            @if (isset($data['agents'][0]['services']) && count($data['agents'][0]['services']) > 0)
                                @foreach ($data['agents'][0]['services'] as $key => $service)
                                <tr>
                                    @php
                                        $totalAmount += $service->totalAmount;
                                        $subTotal += $service->qty *($service->service_fee + $service->dubai_fee);
                                    @endphp
                                    <td class="text-center">#{{ $rowsCount++ }}</td>
                                    <td class="text-center">{{ $service->name }}</td>
                                    <td class="text-center">{{ $service->qty }}</td>
                                    <td class="text-center">{{ $service->service_fee + $service->dubai_fee }}
                                    </td>
                                    <td class="text-center">
                                        {{ \App\Helpers\formatCurrency($service->qty *($service->service_fee + $service->dubai_fee)) }}
                                    </td>
                                    
                                </tr>
                                
                                @endforeach
                            @endif

                            @php
                                $allAmountFromDayOneUntilEndOfInvoice = 0;
                                $totalForInvoice = 0;
                                foreach ($data['agents'] as $agent) {
                                    if (!is_null($agent['agent'])) {
                                        $carbonFrom = \Illuminate\Support\Carbon::parse(request()->fromDate);
                                        $carbonFrom->subDay();
                                        $fromDate = '1970-01-01';
                                        $totalAmountFromDayOneUntilEndOfInvoice = (new \App\Services\AgentInvoiceService())->getAgentData(
                                            $agent['agent']['id'],
                                            $fromDate,
                                            $carbonFrom->format('Y-m-d'),
                                        );

                                        $allAmountFromDayOneUntilEndOfInvoice = \App\Models\PaymentTransaction::query()
                                            ->where('agent_id', $agent['agent']['id'])
                                            ->whereDate('created_at', '>=', $fromDate)
                                            ->whereDate('created_at', '<=', request()->toDate)
                                            ->sum('amount');

                                        foreach ($totalAmountFromDayOneUntilEndOfInvoice['visas'] as $visa) {
                                            $totalForInvoice += $visa->totalAmount;
                                        }

                                        foreach ($totalAmountFromDayOneUntilEndOfInvoice['services'] as $service) {
                                            $totalForInvoice += $service->totalAmount;
                                        }
                                    }
                                }
                            @endphp
                            @php
                                $oldBalance = $totalForInvoice - $allAmountFromDayOneUntilEndOfInvoice;
                            @endphp

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tfooter>
                                <tr>

                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">Subtotal : </td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">{{ $subTotal }}</td>
                                </tr>
                                @if (\App\Helpers\isExistVat())
                                    <tr>

                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">Vat {{ \App\Helpers\valueOfVat() }} % </td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">{{ $data['agents'][0]['totalVat'] }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td></td>
                                    <td class="text-center"><strong>Total USD</strong></td>
                                    <td></td>

                                    <td></td>
                                    <td class="text-center"><strong>$
                                            {{ \App\Helpers\formatCurrency($subTotal + $data['agents'][0]['totalVat']) }}</strong>
                                    </td>

                                </tr>

                                <tr>

                                    <td></td>
                                    <td class="text-center"><strong>Old balance</strong></td>
                                    <td></td>
                                    <td></td>

                                    <td class="text-center"><strong>$
                                            {{ \App\Helpers\formatCurrency($oldBalance) }}</strong></td>

                                </tr>
                                <tr>

                                    <td></td>
                                    <td class="text-center"><strong>Grand total </strong></td>
                                    <td></td>
                                    <td></td>

                                    <td class="text-center"><strong>$
                                            {{ \App\Helpers\formatCurrency($oldBalance + $totalAmount) }}</strong></td>

                                </tr>
                            </tfooter>

                        </tbody>
                    </table>
                    <p class="text-center">Amount due is
                        {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $totalAmount) }}</p>
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
