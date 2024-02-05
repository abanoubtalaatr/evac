
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
                @include('livewire.admin.shared.reports.actions')

                <div class="my-2 form_wrapper">

                </div>
                <div class="form-group col-2 form_wrapper">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            <hr class="form_wrapper">

            @php
            $totalDrCount= 0;
            $totalCrCount = 0;

            @endphp
            @if(isset($records) && count($records) > 0)
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">@lang('admin.description')</th>
                        <th class="text-center">Dr.</th>
                        <th class="text-center">Cr.</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($records['invoices'] as $record)
                        <tr>
                            <td class="text-center">{{$record['from'] . ' - ' . $record['to']}}</td>
                            <td class="text-center">{{$record->invoice_title}}</td>
                            @php
                                $totalDrCount += $record->total_amount;
                            @endphp
                            <td class="text-center">{{$record->total_amount}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    @foreach($records['payment_received'] as $record)
                        <tr>
                            <td class="text-center">{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</td>
                            <td class='text-center'>Payment received</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{$record->amount}}</td>
                            @php
                            $totalCrCount += $record->amount;
                            @endphp
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-center"><strong>Totals</strong></td>
                        <td  class="text-center">{{$totalDrCount}}</td>
                        <td class="text-center">{{$totalCrCount}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center"><strong>Outstanding bal</strong></td>
                        <td  class="text-center">{{$totalDrCount -  $totalCrCount}}</td>
                        <td class="text-center"></td>
                        <td></td>
                    </tr>

                    </tfoot>

                    </tbody>
                </table>
            @else
                <div class="row" style="margin-top: 10px">
                    <div class="alert alert-warning">Search by agent</div>
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
