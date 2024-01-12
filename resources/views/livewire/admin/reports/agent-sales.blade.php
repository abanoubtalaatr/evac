
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
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>

                <div class="my-2 form_wrapper">
                    @include('livewire.admin.shared.reports.actions')

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
            $totalVisas =0;
            $totalGrand = 0;
            $totalPayment = 0;
            $totalServices = 0;
            $defaultVisa = \App\Models\VisaType::query()->where('is_default', 1)->first();
            $totalDefaultVisaCount = 0;
            $totalNewSales = 0;
            $totalPreviousBal = 0;
            $totalServiceTransactionsAmount =0;
            $totalApplicationsAmount= 0;
            $totalApplicationsAmountForAgent = 0;
            $totalServiceTransactionsAmountForAgent = 0;
            $totalPreviousBalForAllAgents = 0;
            $totalNewSalesForAllAgents =0;
            $totalForAllAgent =0;

            @endphp
            @if(isset($records) && count($records) > 0)
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Default visa</th>
                        <th>Previous Bal</th>
                        <th>New Sales</th>
                        <th>Total Amount</th>
                        @foreach(\App\Models\VisaType::query()->get() as $visa)
                            <th>{{$visa->name}}</th>
                        @endforeach
                        <th>Total Visas</th>

                    @foreach(\App\Models\Service::query()->get() as $service)
                            <th>{{$service->name}}</th>
                        @endforeach
                        <th>Total Services</th>

                    </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
@php
    $totalAmountForAgent =  0;
    $defaultVisaCount = \App\Models\Application::query()->where('visa_type_id', $defaultVisa->id)->where('travel_agent_id', $record->id)->count();
    $totalDefaultVisaCount += $defaultVisaCount;
    if(isset($from) & isset($to)){
        $totalAmountForAgent = \App\Helpers\totalAmount($record->id, $from, $to);

        $totalPreviousBalForAllAgents += \App\Helpers\oldBalance($record->id, $totalAmountForAgent);
        $totalNewSalesForAllAgents += $totalAmountForAgent;
        $totalForAllAgent += \App\Helpers\oldBalance($record->id, $totalAmountForAgent)+ $totalAmountForAgent;
    }

@endphp
                            <tr>
                                <td>{{$record->name}}</td>
                                <td>{{$defaultVisaCount}}</td>
                                <td>{{\App\Helpers\oldBalance($record->id, $totalAmountForAgent)}}</td>
                                <td>{{  $totalAmountForAgent}}</td>
                                <td>{{\App\Helpers\oldBalance($record->id, $totalAmountForAgent)+ $totalAmountForAgent}}</td>

                                @foreach(\App\Models\VisaType::query()->get() as $visa)
                                    @php
                                       $visaCountForAgent =  \App\Models\Application::query()->where('visa_type_id', $visa->id)->where('travel_agent_id', $record->id)->count();
                                    @endphp
                                    <td>{{$visaCountForAgent}}</td>
                                @endforeach

                                <td>{{\App\Models\Application::query()->where('travel_agent_id', $record->id)->count()}}</td>


                                @foreach(\App\Models\Service::query()->get() as $service)
                                    @php
                                        $countServiceForAgent = \App\Models\ServiceTransaction::query()->where('service_id', $service->id)->where('agent_id', $record->id)->count();
                                        $totalServices += $countServiceForAgent;
                                      @endphp

                                    <td>{{$countServiceForAgent}}</td>
                                @endforeach
                                <td>{{\App\Models\ServiceTransaction::query()->where('agent_id', $record->id)->count()}}</td>

                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Total </td>
                        <td>{{$totalDefaultVisaCount}}</td>
                        <td>{{$totalPreviousBalForAllAgents}}</td>
                        <td>{{$totalNewSalesForAllAgents}}</td>
                        <td>{{$totalForAllAgent}}</td>

                        @foreach(\App\Models\VisaType::query()->get() as $visa)
                            @php
                                $totalVisaCountForAllAgents = \App\Models\Application::query()
                                    ->where('visa_type_id', $visa->id)
                                    ->whereIn('travel_agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalVisaCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$records->total_applications_count}}</td>

                        @foreach(\App\Models\Service::query()->get() as $service)
                            @php
                                $totalServiceCountForAllAgents = \App\Models\ServiceTransaction::query()
                                    ->where('service_id', $service->id)
                                    ->whereIn('agent_id', $records->pluck('id'))
                                    ->count();
                            @endphp
                            <td>{{$totalServiceCountForAllAgents}}</td>
                        @endforeach

                        <td>{{$records->total_service_transactions_count}}</td>
                    </tr>
                    </tfoot>

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
