
<div wire:ignore.self class="modal fade" id="showServiceTransactionInvoiceModal{{$transaction->id}}" tabindex="-1" role="dialog" aria-labelledby="serviceTransactionInvoiceModalLabel{{$transaction->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="showServiceTransactionInvoiceModal{{$transaction->id}}Label">Edit Invoice Service Transaction</h5>
                <button type="button" onclick="$('#showServiceTransactionInvoiceModal{{$transaction->id}}').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group my-2">
                    <label for="payment_method">Payment method : </label>
                   <select class="form-control" wire:model="formInvoice.payment_method">
                       <option value="cash">Cash</option>
                       <option value="invoice">Invoice</option>
                   </select>
                    @error('formInvoice.payment_method')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="amount">Amount : </label>
                    <input type="text" wire:model="formInvoice.amount" class="form-control" id="amount">
                    @error('formInvoice.amount')<p style='color:red'> {{$message}} </p>@enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#showServiceTransactionInvoiceModal{{$transaction->id}}').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="updateInvoice">Save</button>
            </div>
        </div>
    </div>
</div>

