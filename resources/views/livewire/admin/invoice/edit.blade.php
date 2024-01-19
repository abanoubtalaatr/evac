
<div wire:ignore.self class="modal fade" id="showInvoiceModal{{$invoice->id}}" tabindex="-1" role="dialog" aria-labelledby="visaInvoiceLabel{{$invoice->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel{{$invoice->id}}">Edit Invoice</h5>
                <button type="button" onclick="$('#showInvoiceModal{{$invoice->id}}').modal('hide');" class="close btn" wire:click="emptyForm" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2 ">
                    <label class="" for="agent">Travel agent : </label>
                    <select wire:model="form.agent_id" class="form-control my-select-2" id="agent_id" readonly>
                        <option value="">Select Travel agent</option>
{{--                        <input type="text" readonly wire:model="form.agent_id" class="form-control" id="title_invoice">--}}

                                                @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('form.agent_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="title_invoice">Title invoice:</label>
                    <input type="text" readonly wire:model="form.invoice_title" class="form-control" id="title_invoice">
                    @error('form.invoice_title')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="date">From :</label>
                    <input type="date" readonly wire:model="form.from" class="form-control" id="date">
                    @error('form.from')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="date">To :</label>
                    <input type="date" readonly wire:model="form.to" class="form-control" id="date">
                    @error('form.from')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="companyName">Total amount :</label>
                    <input type="text" readonly wire:model="form.total_amount" class="form-control" id="total_amount">
                    @error('form.total_amount')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="companyName">Old Balance :</label>
                    <input type="text" readonly wire:model="form.old_balance" class="form-control" id="old_balance">
                    @error('form.old_balance')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="companyName">Grand total :</label>
                    <input type="text" readonly wire:model="form.grand_total" class="form-control" id="grand_total">
                    @error('form.grand_total')<p style='color:red'> {{$message}} </p>@enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  onclick="$('#showInvoiceModal{{$invoice->id}}').modal('hide');" wire:click="emptyForm" data-dismiss="modal">Close</button>
{{--                <button type="submit" class="btn btn-primary" wire:click="update">Save</button>--}}
            </div>

        </div>
    </div>
</div>

