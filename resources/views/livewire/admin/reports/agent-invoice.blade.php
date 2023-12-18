
<main class="main-content">

    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2 form_wrapper">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3  p-2 rounded align form_wrapper">
                <div class="col-3 form-group form_wrapper">
                    <label for="status-select">@lang('admin.travel_agent')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>

                <div class="my-2 form_wrapper">
                    @include('livewire.admin.travel-agent.popup.send-email',[''])
                    <div class="form-group col-3">
                        <button class="btn btn-info form-control contact-input" wire:click="sendForAdmins" @if($disableSendForAdminsButton) disabled @endif>
                            Send all invoices to admins
                        </button>
                    </div>
{{--                @include('livewire.admin.shared.reports.actions')--}}
                </div>
                <div class="form-group col-2 form_wrapper">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            <hr class="form_wrapper">

            @php
            $rowsCount= 1;
            $totalAmount = 0;
            $totalGrand = 0;
            $totalPayment = 0;
            @endphp
            @if(isset($records['agents']) && count($records['agents']) > 0)
                <table class="table-page table">
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
                    @endphp

                    @foreach($records['agents'] as $agent)
                        @if(!is_null($agent['agent']))
                        <tr>
                            <td class="text-uppercase"><strong>{{$agent['agent']['name']}}</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">
                                <button class="btn btn-primary" wire:click="printData('{{$agent['agent']['id']}},{{request()->from}},{{request()->to}}')">Print</button>
                                <button class="btn btn-secondary" wire:click="exportReport('{{$agent['agent']['id']}},{{request()->from}},{{request()->to}}')">CSV</button>
                                <button class="btn btn-info" wire:click="toggleShowModal('{{$agent['agent']['id']}}')">Email</button>

                            </td>
                        </tr>
                        {{-- Display Visa information --}}
                        @if(isset($agent['visas']) && count($agent['visas']) > 0)

                            @foreach($agent['visas'] as $visa)

                                <tr>
                                    @php
                                        $totalAmount += $visa->total * $visa->qty;
                                    @endphp
                                    <td class="text-center">#{{ $rowsCount++ }}</td>
                                    <td class="text-center">{{ $visa->name }}</td>
                                    <td class="text-center">{{ $visa->qty }}</td>
                                    <td class="text-center">{{ $visa->total }}</td>
                                    <td class="text-center">{{ $visa->total * $visa->qty }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- Display Service information --}}
                        @if(isset($agent['services']) && count($agent['services']) > 0)
                            @foreach($agent['services'] as $service)

                                <tr>
                                    @php
                                        $totalAmount += $service->amount * $service->qty;
                                    @endphp
                                    <td class="text-center">#{{ $rowsCount++ }}</td>
                                    <td class="text-center">{{ $service->name }}</td>
                                    <td class="text-center">{{ $service->qty }}</td>
                                    <td class="text-center">{{ $service->amount }}</td>
                                    <td class="text-center">{{ $service->amount * $service->qty }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @php
                            $totalPayment += \App\Models\PaymentTransaction::query()->where('agent_id', $agent['agent']['id'])->sum('amount');
                        @endphp
                        @endif

                    @endforeach

                    {{-- Display total --}}
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tfooter>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><strong>Total USD</strong></td>
                            <td class="text-center"><strong>$ {{ $totalAmount }}</strong></td>
                            <td></td>
                        </tr>
                        <tr>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><strong>Old balance</strong></td>
                            <td class="text-center"><strong>$ {{ ($totalAmount + $totalPayment) - $totalAmount }}</strong></td>
                            <td></td>
                        </tr>
                        <tr>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><strong>Grand total </strong></td>
                            <td class="text-center"><strong>$ {{ $totalAmount - $totalPayment }}</strong></td>
                            <td></td>
                        </tr>
                    </tfooter>
                    </tbody>
                </table>
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

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationInvoiceModal', function (application) {
            $('#showApplicationInvoiceModal' + application).modal('show');
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationModal', function (applicationId) {
            $('#applicationModal' + applicationId).modal('show');
        });
    });
</script>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('printTable', function (url) {
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

    document.addEventListener('livewire:load', function () {
    Livewire.on('show-message', function () {
        var errorMessage = document.getElementById('error-message');

        // Show the message
        errorMessage.style.display = 'block';

        // Hide the message after 1000 milliseconds (1 second)
        setTimeout(function () {
            errorMessage.style.display = 'none';
        }, 1000);
    });
    });
</script>
@endpush
