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
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        .table th, .table td {
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
        .row {
            width: 100%;
            overflow: hidden;
        }
        .col-75 {
            width: 75%;
            float: left;
            box-sizing: border-box;
            padding: 10px;
        }
        .col-25 {
            width: 25%;
            float: left;
            box-sizing: border-box;
            padding: 10px;
        }
    </style>
</head>
<body>
    <main>
        @php
            // Logo logic
            $settings = \App\Models\Setting::query()->first();
            $logoPath = $settings->logo ? public_path('uploads/pics/' . $settings->logo) : null;
            $logoBase64 = ($logoPath && file_exists($logoPath)) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

            // Use passed variables or fallback to request
            $agentId = isset($agentId) ? $agentId : request()->input('agent');
            $invoiceId = isset($invoiceId) ? $invoiceId : request()->input('invoice');
            $fromDate = isset($fromDate) ? $fromDate : request()->input('fromDate');
            $toDate = isset($toDate) ? $toDate : request()->input('toDate');

            // Ensure agent and invoice are resolved
            $agent = isset($agent) ? $agent : \App\Models\Agent::query()->find($agentId);
            $invoice = isset($invoice) ? $invoice : \App\Models\AgentInvoice::query()->find($invoiceId);
        @endphp
        @if ($logoBase64)
            <div style="text-align: center;">
                <img width="220" height="200" src="{{ $logoBase64 }}" style="max-width: 220px; height: auto;">
            </div>
        @endif
        <h4 class="text-center">INVOICE</h4>

        <div class="row">
            <div class="col-75">
                @if (isset($headerData))
                    <!-- Static header data for email context -->
                    <div style="text-align: left;">
                        <strong class="span-block">{{ $headerData['company_name'] ?? 'Company Name' }}</strong>
                        <strong class="span-block">{{ $headerData['address'] ?? 'Company Address' }}</strong>
                        <strong class="span-block">Tel: {{ $headerData['telephone'] ?? 'Company Telephone' }}</strong>
                        @if (isset($showInvoiceTitle) && $showInvoiceTitle)
                            <strong class="span-block">Invoice</strong>
                        @endif
                    </div>
                @else
                    <!-- For print context, fallback to include -->
                    @include('livewire.admin.shared.reports.header', ['showInvoiceTitle' => true])
                @endif
            </div>
            <div class="col-25">
                @if ($agent)
                    <strong class="span-block">Agent: {{ $agent->name }}</strong>
                    <strong class="span-block">Agent address: {{ $agent->address }}</strong>
                    <strong class="span-block">Tel: {{ $agent->telephone }}</strong>
                    <strong class="span-block">Account No: {{ $agent->account_number }}</strong>
                @endif
                <strong class="span-block">INV No: {{ $invoice ? $invoice->invoice_title : '' }}</strong>
            </div>
        </div>

        <section class="dashboard">
            <div class="row">
                @if ($fromDate && $toDate)
                    <h4>Period of sales from: {{ $fromDate }} - To: {{ $toDate }}</h4>
                @endif
                <br>

                @php
                    $data = (new \App\Services\AgentInvoiceService())->getRecords(
                        $agentId,
                        $fromDate,
                        $toDate,
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
                                foreach ($data['agents'] as $agentData) {
                                    if (!is_null($agentData['agent'])) {
                                        $carbonFrom = \Illuminate\Support\Carbon::parse($fromDate);
                                        $carbonFrom->subDay();
                                        $fromDateStart = '1970-01-01';
                                        $totalAmountFromDayOneUntilEndOfInvoice = (new \App\Services\AgentInvoiceService())->getAgentData(
                                            $agentData['agent']['id'],
                                            $fromDateStart,
                                            $carbonFrom->format('Y-m-d'),
                                        );

                                        $allAmountFromDayOneUntilEndOfInvoice = \App\Models\PaymentTransaction::query()
                                            ->where('agent_id', $agentData['agent']['id'])
                                            ->whereDate('created_at', '>=', $fromDateStart)
                                            ->whereDate('created_at', '<=', $toDate)
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
                        </tfoot>
                    </table>
                    <p class="text-center">Amount due is {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}</p>
                @else
                    <div class="row" style="margin-top: 10px;">
                        <div class="alert alert-warning" style="width: 100%; padding: 10px;">@lang('site.no_data_to_display')</div>
                    </div>
                @endif
            </div>
        </section>
        @include('livewire.admin.shared.reports.footer')
    </main>
</body>
</html>