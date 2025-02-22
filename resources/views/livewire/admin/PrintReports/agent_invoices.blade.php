<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        main {
            width: 100%;
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
            font-weight: bold;
            color: black;
        }

        .card-body {
            padding: 15px;
        }

        /* Simplified Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        /* Footer Styling */
        tfoot td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        .span-block {
            display: block;
            margin-bottom: 5px;
        }

        .text-center {
            text-align: center;
        }

        /* Custom Row and Column Styling */
        .row {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .col-half {
            width: 50%;
            box-sizing: border-box;
            padding: 10px;
        }

        .col-75 {
            width: 75%;
            box-sizing: border-box;
            padding: 10px;
        }

        .col-25 {
            width: 25%;
            box-sizing: border-box;
            padding: 10px;
        }
    </style>
</head>

<body>
    <main>
        @php
            $settings = \App\Models\Setting::query()->first();
            $logoPath = $settings->logo ? public_path('uploads/pics/' . $settings->logo) : null;
        @endphp
        @if ($logoPath && file_exists($logoPath))
            <div style="text-align: center">
                <img width="220" height="200"
                    src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}">
            </div>
        @endif
        <h4 class="text-center">INVOICE</h4>

        <div class="row">

            <div class="col-75 " style="width: 75%;box-sizing: border-box;padding: 10px;">
                @include('livewire.admin.shared.reports.header', ['showInvoiceTitle' => true])
            </div>
            <div class="col-25" style="width: 25%;box-sizing: border-box;padding: 10px;">
                @php
                    $agent = \App\Models\Agent::query()->find(request()->agent);
                    $invoice = \App\Models\AgentInvoice::query()->find(request()->invoice);
                @endphp
                @if ($agent)
                    <strong class="span-block">Agent: {{ $agent->name }}</strong>
                    <strong class="span-block">Agent address: {{ $agent->address }}</strong>
                    <strong class="span-block">Tel: {{ $agent->telephone }}</strong>
                    <strong class="span-block">Account No: {{ $agent->account_number }}</strong>
                @endif
                <strong class="span-block">INV No: {{ $invoice ? $invoice->invoice_title : '' }}</strong>
            </div>
        </div>

        <!-- Dashboard -->
        <section class="dashboard">
            <div class="row">
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
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Item #</th>
                                <th style="width: 40%;">@lang('admin.description')</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 20%;">Unit Price</th>
                                <th style="width: 20%;">Amount</th>
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
                                        <td>{{ \App\Helpers\formatCurrency($service->qty * ($service->service_fee + $service->dubai_fee)) }}
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
                                $oldBalance =
                                    $totalForInvoice +
                                    $totalAmountFromDayOneUntilEndOfInvoice['totalVat'] -
                                    $allAmountFromDayOneUntilEndOfInvoice;
                            @endphp

                            <tr>
                                <td colspan="5"></td>
                            </tr>
                        </tbody>
                        <tfoot>
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
                                <td><strong>${{ \App\Helpers\formatCurrency($subTotal + $data['agents'][0]['totalVat']) }}</strong>
                                </td>
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
                                <td><strong>${{ \App\Helpers\formatCurrency($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <p class="text-center">Amount due is
                        {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}
                    </p>
                @else
                    <div class="row" style="margin-top: 10px">
                        <div class="alert alert-warning" style="width: 100%; padding: 10px;">@lang('site.no_data_to_display')</div>
                    </div>
                @endif
            </div>
        </section>
        @include('livewire.admin.shared.reports.footer')
    </main>
</body>

</html>
