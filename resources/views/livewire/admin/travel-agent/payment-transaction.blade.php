<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded alig input-container">

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.search')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>

                <div class="form-group col-2 mt-4">
                    <button wire:click="showAll()"
                            class="btn btn-primary form-control contact-input">Show all</button>
                </div>

            </div>
{{--@dd($records)--}}
            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" >@lang('admin.agent')</th>
                        <th class="text-center" >@lang('admin.total')</th>
                        <th class="text-center" >@lang('admin.amount_paid')</th>
                        <th class="text-center" >@lang('admin.amount_should_paid')</th>
                        <th>@lang('site.actions')</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        @php

                            $totalAmount = $record->amount + $record->amount_service;
                        @endphp
{{--                        @if($totalAmount > 0 && $record->is_active ==1)--}}
                        <tr>
                            <td>#{{$record->id}}</td>
                            <td class='text-center'>{{$record->name}}</td>

                            <td class="text-center" >{{$totalAmount? \App\Helpers\formatCurrency($totalAmount):0}}</td>
                            <td class="text-center" >{{$record->amount_paid? \App\Helpers\formatCurrency($record->amount_paid):0}}</td>
                            <td class="text-center" >
                                {{ \App\Helpers\formatCurrency($totalAmount - $record->amount_paid) }}</td>
                            <td>
                                <div class="actions">
                                    @if(($totalAmount - $record->amount_paid) > 0)
                                    <button class="btn btn-primary mt-2" wire:click="showAddPaymentHistory({{$record->id}})">Pay Amount</button>

                                    @endif
                                    @include('livewire.admin.travel-agent.popup.payment-history',['agent' => $record])

                                        <button class="btn btn-secondary mt-2" wire:click="showPaymentHistory({{$record->id}})">Payment history</button>

                                    @if($record->amount_paid > 0)
                                            <button class="btn btn-info mt-2" onclick="printPage('{{route('admin.travel_agent_payment_transactions_print_last_receipt',['agent' => $record->id])}}')">Print last receipt</button>

                                        @endif
                                </div>
                            </td>
                        </tr>
{{--                        @endif--}}
                    @endforeach
                    </tbody>
                </table>

                {{$records->links()}}
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
            @include('livewire.admin.travel-agent.popup.add-payment-history')
        </div>
    </div>
</main>
<script>


    document.addEventListener('livewire:load', function () {
        Livewire.on('showPaymentHistoryModal', function (agent) {
            $('#showPaymentHistoryModal' + agent).modal('show');
        });
        Livewire.on('showAddPaymentHistoryModal', function () {
            $('#showAddPaymentHistoryModal').modal('show');
        });
        Livewire.on('hideAddPaymentHistoryModal', function () {
            $('#showAddPaymentHistoryModal').modal('hide');
        });
        Livewire.on('makeAgentNull', function () {
            $('#agent_search').val('');  // Clear the input field

        });

    });

</script>

<script>
    // Function to load content into an iframe and trigger printing
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

        // Wait for the iframe to load
        iframe.onload = function() {
            // Print the content of the iframe
            iframe.contentWindow.print();
        };
    }

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
@include('livewire.admin.shared.agent_search_script')

<script>
    // Function to load content into an iframe and trigger printing
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

        // Wait for the iframe to load
        iframe.onload = function() {
            // Print the content of the iframe
            iframe.contentWindow.print();
        };
    }
</script>
