<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report' }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <main style="width: 100%;">
        @php
            $settings = \App\Models\Setting::query()->first();
            $logoPath = $settings->logo ? public_path('uploads/pics/' . $settings->logo) : null;
        @endphp
        @if ($logoPath && file_exists($logoPath))
            <div style="text-align: center;">
                <img width="220" height="200" src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" style="max-width: 220px; height: auto;">
            </div>
        @endif
        <h4 style="text-align: center;">INVOICE</h4>

        <div style="display: flex; width: 100%; flex-wrap: wrap;">
            <div style="width: 75%; box-sizing: border-box; padding: 10px;">
                @include('livewire.admin.shared.reports.header', ['showInvoiceTitle' => true])
            </div>
            <div style="width: 25%; box-sizing: border-box; padding: 10px;">
                @php
                    $agent = \App\Models\Agent::query()->find(request()->agent);
                    $invoice = \App\Models\AgentInvoice::query()->find(request()->invoice);
                @endphp
                @if ($agent)
                    <strong style="display: block; margin-bottom: 5px;">Agent: {{ $agent->name }}</strong>
                    <strong style="display: block; margin-bottom: 5px;">Agent address: {{ $agent->address }}</strong>
                    <strong style="display: block; margin-bottom: 5px;">Tel: {{ $agent->telephone }}</strong>
                    <strong style="display: block; margin-bottom: 5px;">Account No: {{ $agent->account_number }}</strong>
                @endif
                <strong style="display: block; margin-bottom: 5px;">INV No: {{ $invoice ? $invoice->invoice_title : '' }}</strong>
            </div>
        </div>

        <!-- Dashboard -->
        <section style="width: 100%;">
            <div style="display: flex; width: 100%; flex-wrap: wrap;">
                @if (request()->fromDate && request()->toDate)
                    <h4 style="margin: 0;">Period of sales from: {{ request()->fromDate }} - To: {{ request()->toDate }}</h4>
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
                    <table style="width: 100%; border-collapse: collapse; background-color: #fff;">
                        <thead>
                            <tr>
                                <th style="width: 10%; border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle; background-color: #f8f9fa; font-weight: bold;">Item #</th>
                                <th style="width: 40%; border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle; background-color: #f8f9fa; font-weight: bold;">@lang('admin.description')</th>
                                <th style="width: 10%; border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle; background-color: #f8f9fa; font-weight: bold;">Qty</th>
                                <th style="width: 20%; border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle; background-color: #f8f9fa; font-weight: bold;">Unit Price</th>
                                <th style="width: 20%; border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle; background-color: #f8f9fa; font-weight: bold;">Amount</th>
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
                                    <tr style="@if ($rowsCount % 2 == 0) background-color: #ffffff; @else background-color: #f2f2f2; @endif">
                                        @php
                                            $totalAmount += $visa->totalAmount;
                                            $totalVat += $visa->totalVat;
                                            $subTotal += $visa->totalAmount;
                                        @endphp
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">#{{ $rowsCount++ }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ $visa->name }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ $visa->qty }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ \App\Helpers\formatCurrency($visa->total) }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ \App\Helpers\formatCurrency($visa->totalAmount) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (isset($data['agents'][0]['services']) && count($data['agents'][0]['services']) > 0)
                                @foreach ($data['agents'][0]['services'] as $service)
                                    <tr style="@if ($rowsCount % 2 == 0) background-color: #ffffff; @else background-color: #f2f2f2; @endif">
                                        @php
                                            $totalAmount += $service->totalAmount;
                                            $subTotal += $service->qty * ($service->service_fee + $service->dubai_fee);
                                        @endphp
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">#{{ $rowsCount++ }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ $service->name }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ $service->qty }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ $service->service_fee + $service->dubai_fee }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center; vertical-align: middle;">{{ \App\Helpers\formatCurrency($service->qty * ($service->service_fee + $service->dubai_fee)) }}</td>
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
                                <td colspan="5" style="border: 1px solid #dee2e6; padding: 10px;"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;">Subtotal:</td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;">{{ $subTotal }}</td>
                            </tr>
                            @if (\App\Helpers\isExistVat())
                                <tr>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;">Vat {{ \App\Helpers\valueOfVat() }} %</td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;">{{ $data['agents'][0]['totalVat'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>Total USD</strong></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>${{ \App\Helpers\formatCurrency($subTotal + $data['agents'][0]['totalVat']) }}</strong></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>Old balance</strong></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>${{ \App\Helpers\formatCurrency($oldBalance) }}</strong></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>Grand total</strong></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><strong>${{ \App\Helpers\formatCurrency($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <p style="text-align: center;">Amount due is {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $subTotal + $data['agents'][0]['totalVat']) }}</p>
                @else
                    <div style="display: flex; width: 100%; flex-wrap: wrap; margin-top: 10px;">
                        <div style="width: 100%; padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404;">@lang('site.no_data_to_display')</div>
                    </div>
                @endif
            </div>
        </section>
        @include('livewire.admin.shared.reports.footer')
    </main>
</body>
</html>