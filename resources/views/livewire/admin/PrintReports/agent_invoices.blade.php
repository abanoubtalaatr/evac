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

        /* Table Styling */
        .table {
            width: 100%;
            margin-bottom: 0;
            background-color: #fff;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center; /* Centralized text for consistency */
            border-bottom: 1px solid #ddd;
            vertical-align: middle; /* Align content vertically */
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
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

        /* Define min/max widths for table columns */
        .table th:nth-child(1),
        .table td:nth-child(1) { /* Item # */
            min-width: 50px;
            max-width: 50px;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) { /* Description */
            min-width: 200px;
            max-width: 250px;
            word-wrap: break-word; /* Ensure long text wraps */
        }

        .table th:nth-child(3),
        .table td:nth-child(3) { /* Qty */
            min-width: 60px;
            max-width: 60px;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) { /* Unit Price */
            min-width: 100px;
            max-width: 120px;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) { /* Amount */
            min-width: 100px;
            max-width: 120px;
        }

        /* Bordered Table */
        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        /* Alternating Row Colors */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        /* Footer Styling */
        tfooter tr td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }

        .span-block {
            display: block;
            margin-bottom: 3px;
        }
    </style>
</head>

<body>
    <main>
        @include('livewire.admin.shared.reports.header', ['showInvoiceTitle' => true])

        <!-- Dashboard -->
        <section class="dashboard">
            <div class="row">
                @php
                    $agent = \App\Models\Agent::query()->find(request()->agent);
                    $invoice = \App\Models\AgentInvoice::query()->find(request()->invoice);
                @endphp
                @if ($agent)
                    <div class="mt-4" style="margin-bottom: 10px; margin-top: 10px;">
                        <strong class="span-block">Agent: {{ $agent->name }}</strong>
                        <strong class="span-block">Agent address: {{ $agent->address }}</strong>
                        <strong class="span-block">Tel: {{ $agent->telephone }}</strong>
                        <strong class="span-block">Account No: {{ $agent->account_number }}</strong>
                    </div>
                @endif
                <strong class="span-block">INV No: {{ $invoice ? $invoice->invoice_title : '' }}</strong>

                @if (request()->fromDate && request()->toDate)
                    <h4>Period of sales from: {{ request()->fromDate }} - To: {{ request()->toDate }}</h4>
                @endif
                <br>

                @php
                    $data = (new \App\Services\AgentInvoiceService())->getRecords(
                        request()->agent,
                        request()->fromDate,
                        request()->toDate,
                    );
                @endphp

                @if (isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item #</th>
                                <th>@lang('admin.description')</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rowsCount = 1;
                                $totalAmount = 0;
                                $totalVat = 0;
                                $subTotal = 0;
                            @endphp

                            {{-- Display Visa information --}}
                            @if (isset($data['agents'][0]['visas']) && count($data['agents'][0]['visas']) > 0)
                                @foreach ($data['agents'][0]['visas'] as $visa)
                                    <tr>
                                        @php
                                            $totalAmount += $visa->totalAmount;
                                            $totalVat += $visa->totalVat;
                                            $subTotal += $visa->totalAmount;
                                        @endphp
                                        <td>#{{ $rowsCount++ }}</td>
                                        <td>{{ $visa->name }}</td>
                                        <td>{{ $visa->qty }}</td>
                                        <td>{{ \App\Helpers\formatCurrency($visa->total) }}</td>
                                        <td>{{ \App\Helpers\formatCurrency($visa->totalAmount) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Display Service information --}}
                            @if (isset($data['agents'][0]['services']) && count($data['agents'][0]['services']) > 0)
                                @foreach ($data['agents'][0]['services'] as $service)
                                    <tr>
                                        @php
                                            $totalAmount += $service->totalAmount;
                                            $subTotal += $service->qty * ($service->service_fee + $service->dubai_fee);
                                        @endphp
                                        <td>#{{ $rowsCount++ }}</td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ $service->qty }}</td>
                                        <td>{{ $service->service_fee + $service->dubai_fee }}</td>
                                        <td>{{ \App\Helpers\formatCurrency($service->qty * ($service->service_fee + $service->dubai_fee)) }}</td>
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
                                $oldBalance = ($totalForInvoice + $totalAmountFromDayOneUntilEndOfInvoice['totalVat']) - $allAmountFromDayOneUntilEndOfInvoice;
                            @endphp

                            <tr>
                                <td colspan="5"></td>
                            </tr>
                        </tbody>
                        <tfooter>
                            <tr>
                                <td></td>
                                <td>Subtotal:</td>
                                <td></td>
                                <td></td>
                                <td>{{ $subTotal }}</td>
                            </tr>
                            @if (\App\Helpers\isExistVat())
                                <tr>
                                    <td></td>
                                    <td>Vat {{ \App\Helpers\valueOfVat() }} %</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $data['agents'][0]['totalVat'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td><strong>Total USD</strong></td>
                                <td></td>
                                <td></td>
                                <td><strong>${{ \App\Helpers\formatCurrency($subTotal + $data['agents'][0]['totalVat']) }}</strong></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><strong>Old balance</strong></td>
                                <td></td>
                                <td></td>
                                <td><strong>${{ \App\Helpers\formatCurrency($oldBalance) }}</strong></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><strong>Grand total</strong></td>
                                <td></td>
                                <td></td>
                                <td><strong>${{ \App\Helpers\formatCurrency($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}</strong></td>
                            </tr>
                        </tfooter>
                    </table>
                    <p class="text-center">Amount due is {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $totalAmount) }}</p>
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