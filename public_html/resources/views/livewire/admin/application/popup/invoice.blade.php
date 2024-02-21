
<div wire:ignore.self class="modal fade" id="showApplicationInvoiceModal{{$application->id}}" tabindex="-1" role="dialog" aria-labelledby="applicationInvoiceModalLabel{{$application->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="applicationInvoiceModal{{$application->id}}Label">Edit Invoice application</h5>
                <button type="button" onclick="$('#showApplicationInvoiceModal{{$application->id}}').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2">
                    <label>Payment method:</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="cash" wire:model="formInvoice.payment_method" value="cash">
                        <label class="form-check-label" for="cash">Cash</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="invoice" wire:model="formInvoice.payment_method" value="invoice">
                        <label class="form-check-label" for="invoice">Invoice</label>
                    </div>

                    @error('formInvoice.payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="amount">Amount : </label>
                    <input type="text" wire:model="formInvoice.amount" class="form-control" id="amount" >
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#showApplicationInvoiceModal{{$application->id}}').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="updateInvoice">Save</button>
            </div>
        </div>
    </div>
</div>

