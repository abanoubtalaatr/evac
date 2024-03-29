
@if($confirmingPayInvoice)
    <div class="modal" id="pay-invoice-application" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay application</h5>
                    <button wire:click="toggleConfirmPayInvoice" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @php
                    $record = \App\Models\Application::query()->find($payInvoiceRecordId);


                @endphp

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group my-2 ">
                                <label class="my-2">Application Ref : </label>
                                <input type="text" readonly class="form-control" value="{{$record->application_ref}}">
                            </div>
                            <div class="form-group my-2 ">
                                <label class="my-2">Pasport No : </label>
                                <input type="text" readonly class="form-control" value="{{$record->passport_no}}">
                            </div>
                            <div class="form-group my-2 ">
                                <label class="my-2">Amount : </label>
                                <input type="text" readonly class="form-control" value="{{$record->amount}}">
                            </div>
                            <div class="form-group my-2 ">
                                <label class="my-2">Applicant : </label>
                                <input type="text" readonly class="form-control" value="{{$record->first_name . ' ' . $record->last_name}}">
                            </div>
                            <div class="form-group my-2 ">
                                <label class="my-2">Create at : </label>
                                <input type="date" wire:model="form.createdDate" class="form-control" >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button wire:click="toggleConfirmPayInvoice" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="printPage('{{route('admin.applications.print', ['application' => $record->id])}}')">Print</button>

                    <button wire:click="payInvoice({{$record->id}})" type="button" class="btn btn-warning">Pay & Print</button>
                </div>
            </div>
        </div>
    </div>
@endif
@push('scripts')
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
@endpush

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('payInvoiceAndPrint', function (url) {
            // Handle the event, e.g., call the printPage function
            printPage(url);
        });
    });
</script>
