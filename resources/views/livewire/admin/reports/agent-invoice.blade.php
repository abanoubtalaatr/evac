<main class="main-content">

    <!--head-->
    <x-admin.head />
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2 form_wrapper">
            <h4>{{ $page_title }}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3  p-2 rounded align form_wrapper">
                <div class="col-3 form-group form_wrapper">
                    <label for="status-select">@lang('admin.travel_agent')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>
                <div class="form-group col-2 mt-4 form_wrapper">
                    <button class="btn btn-warning" wire:click="previousWeek"> Previous week</button>
                </div>
                <div class="form-group col-2 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border contact-input" type="date" wire:model="from" readonly>
                </div>

                <div class="form-group col-2 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border contact-input" type="date" wire:model="to" readonly>
                </div>
                <div class="form-group col-2 mt-4 form_wrapper">
                    <button class="btn btn-success" wire:click="nextWeek"> Next week</button>
                </div>
                <hr>
                <div class="my-2 form_wrapper d-flex">
                    @include('livewire.admin.travel-agent.popup.send-email')
                    {{--                    <div class="form-group col-3 mx-2"> --}}
                    {{--                        <button class="btn btn-info form-control contact-input" wire:click="sendForAdmins" @if ($disableSendForAdminsButton) disabled @endif> --}}
                    {{--                            Send all invoices to admins --}}
                    {{--                        </button> --}}
                    {{--                    </div> --}}
                    <div class="form-group col-3">
                        <button class="btn btn-success form-control contact-input" wire:click="saveInvoices">
                            Save the invoices
                        </button>
                        @if (isset($showSaveInvoiceMessage) && $showSaveInvoiceMessage)
                            <p class="text-danger my-2 p-2">Invoices saved successfully <button class="border-0 mx-3"
                                    wire:click="hideSaveInvoiceMessage">Ok</button></p>
                        @endif
                    </div>
                    <div class="col-2 mx-2">
                        <button class="btn btn-danger form-control contact-input" wire:click="endYear">End of
                            Year</button>
                    </div>
                    <div class="col-2 mx-2">
                        <button @if (\App\Models\Setting::query()->first()->is_new_year == 1) disabled @endif
                            class="btn btn-info form-control contact-input" wire:click="startYear">Start new
                            Year</button>
                    </div>

                    {{--                @include('livewire.admin.shared.reports.actions') --}}
                    <hr>

                </div>
                <div class="form-group col-2 form_wrapper">
                    <button wire:click="resetData()"
                        class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            {{-- <hr class="form_wrapper"> --}}

            @php
                $rowsCount = 1;
                $totalAmount = 0;
                $totalGrand = 0;
                $totalPayment = 0;
                $totalVat = 0;
                $totalVatSerivce = 0;
            @endphp
            @if (isset($records['agents']) && count($records['agents']) > 0)
                <table class="table-page table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Item #</th>
                            <th class="text-center">@lang('admin.description')</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Actions</th>

                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $rowsCount = 1;
                            $totalAmount = 0;
                            $totalServiceTransactionsAmount = 0;
                            $totalApplicationAmount = 0;
                            $totalForInvoice = 0;
                            $allAmountFromDayOneUntilEndOfInvoice = 0;
                            $oldBalance = 0;
                            $totalVatService = 0;
                            $subTotal =0;

                        @endphp

                        @foreach ($records['agents'] as $index => $agent)
                            @if (!is_null($agent['agent']))
                                {{-- @if ($index != 0)
                                    <tr style="eight: 30px !important;display: block;border: 1px solid transparent;">
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td class="text-center">&nbsp;</td>

                                    </tr>
                                @endif --}}
                                <tr>
                                    <td class="text-uppercase text-center"><strong>{{ $agent['agent']['name'] }}</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        @if (\App\Helpers\totalAmountBetweenTwoDate($agent['agent']['id'], $from, $to) > 0)
                                            <button class="btn btn-primary"
                                                wire:click="printData('{{ $agent['agent']['id'] }}')">Print</button>
                                            <button class="btn btn-secondary"
                                                wire:click="exportReport('{{ $agent['agent']['id'] }}')">CSV</button>
                                            <button class="btn btn-info"
                                                wire:click="toggleShowModal('{{ $agent['agent']['id'] }}')">Email</button>
                                        @endif
                                    </td>
                                </tr>
                                {{-- Display Visa information --}}
                                @if (isset($agent['visas']) && count($agent['visas']) > 0)
                                    @foreach ($agent['visas'] as $key => $visa)
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
                                            <td class="text-center">&nbsp;</td>
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- Display Service information --}}
                                @if (isset($agent['services']) && count($agent['services']) > 0)
                                    @foreach ($agent['services'] as $key => $service)
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
                                            <td class="text-center">&nbsp;</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @php
                                    $totalForInvoice = 0;
                                    $carbonFrom = \Illuminate\Support\Carbon::parse($from);
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
                                        ->whereDate('created_at', '<=', $to)
                                        ->sum('amount');

                                    foreach ($totalAmountFromDayOneUntilEndOfInvoice['visas'] as $visa) {
                                        $totalForInvoice += $visa->totalAmount;
                                    }
                                    foreach ($totalAmountFromDayOneUntilEndOfInvoice['services'] as $service) {
                                        $totalForInvoice += $service->totalAmount;
                                    }
                                @endphp
                            @endif
                        @endforeach

                        @php

                            $oldBalance = $totalForInvoice - $allAmountFromDayOneUntilEndOfInvoice;

                        @endphp
                        
                        
                    </tbody>
                </table>
                {{-- <p class="text-center">Amount due is
                    {{ \App\Helpers\convertNumberToWorldsInUsd($oldBalance + $totalAmount) }}</p> --}}
            @else
                <div class="row" style="margin-top: 10px">
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif

        </div>
    </div>
</main>
@push('scripts')
    @include('livewire.admin.shared.agent_search_script')

    <style>
        td,
        tfoot,
        th,
        thead,
        tr {
            white-space: nowrap;

        }

        tr {
            vertical-align: middle;

        }
    </style>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('showApplicationInvoiceModal', function(application) {
                $('#showApplicationInvoiceModal' + application).modal('show');
            });
        });

        document.addEventListener('livewire:load', function() {
            Livewire.on('showApplicationModal', function(applicationId) {
                $('#applicationModal' + applicationId).modal('show');
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('printTable', function(url) {
                printPage(url);
            });
        });

        function printPage(url) {
            // Create an iframe element
            var iframe = document.createElement('iframe');
            // Set the source URL of the iframe
            iframe.src = url;

            // Set styles to hide the iframe
            iframe.style.position = 'absolute';
            iframe.style.top = '-9999px';
            iframe.style.left = '-9999px';
            iframe.style.width = '0';
            iframe.style.height = '0';

            // Append the iframe to the document body
            document.body.appendChild(iframe);

            iframe.onload = function() {
                try {
                    iframe.contentWindow.print();
                } catch (error) {
                    // Handle errors
                    console.error('Error printing:', error);
                } finally {
                    // Remove the iframe after printing or in case of an error
                    console.log('Removing iframe');
                    // document.body.removeChild(iframe);
                }
            };
        }
    </script>


    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('show-message', function() {
                var errorMessage = document.getElementById('error-message');

                // Show the message
                errorMessage.style.display = 'block';

                // Hide the message after 1000 milliseconds (1 second)
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 1000);
            });
        });
    </script>
@endpush
