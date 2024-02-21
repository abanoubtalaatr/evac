
<div wire:ignore.self class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="visProviderModalLabel">Add invoice</h5>
                <button type="button" onclick="$('#invoiceModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2 ">
                    <label class="" for="agent">Travel agent : </label>
                    <select wire:model="form.agent_id" class="form-control my-select-2" id="agent_id">
                        <option value="">Select Travel agent</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('form.agent_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="title_invoice">Title invoice:</label>
                    <input type="text" wire:model="form.invoice_title" class="form-control" id="title_invoice">
                    @error('form.invoice_title')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="date">Date :</label>
                    <input type="date" wire:model="form.created_at" class="form-control" id="date">
                    @error('form.created_at')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="companyName">Total amount :</label>
                    <input type="text" wire:model="form.total_amount" class="form-control" id="total_amount">
                    @error('form.created_at')<p style='color:red'> {{$message}} </p>@enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#invoiceModal').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</div>

