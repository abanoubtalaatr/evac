
<div wire:ignore.self class="modal fade" id="serviceTransactionModal" tabindex="-1" role="dialog" aria-labelledby="serviceTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="serviceTransactionModalLabel">Add Service Transaction</h5>
                <button type="button" onclick="$('#serviceTransactionModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2 ">
                    <label class="" for="service_id">Service : </label>
                    <select wire:model="form.service_id" class="form-control" id="service_id" >
                        <option value="">Select Service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('form.service_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group my-2 ">
                    <label class="" for="agent_id">Travel agent : </label>
                    <select wire:model="form.agent_id" class="form-control my-select-2" id="agent_id">
                        <option value="">Select Travel agent</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('form.agent_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>


                <div class="form-group my-2">
                    <label for="passport_no">Passport No : </label>
                    <input type="text" wire:model="form.passport_no" class="form-control" id="passport_no">
                    @error('form.passport_no')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="name">Name : </label>
                    <input type="text" wire:model="form.name" class="form-control" id="name">
                    @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="surname">Surname :</label>
                    <input type="text" wire:model="form.surname" class="form-control" id="surname">
                    @error('form.surname')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="notes">Notes : </label>
                    <textarea wire:model="form.notes" class="form-control" id="notes"></textarea>
                    @error('form.notes')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="amount">Amount $ : </label>
                    <input type="text" wire:model="form.amount" class="form-control" id="amount">
                    @error('form.amount')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="vat">Vat $: </label>
                    <input type="text" readonly wire:model="form.vat" class="form-control" id="vat">

                </div>
                <div class="form-group my-2 d-flex gap-5">
                    <label>Payment method:</label>

                    <div class="">
                        <input class="form-check-input" type="radio" id="invoice" wire:model="form.payment_method" checked value="invoice">
                        <label class="form-check-label" for="invoice">Invoice</label>
                    </div>

                    <div class="">
                        <input class="form-check-input" type="radio" id="cash" wire:model="form.payment_method" value="cash">
                        <label class="form-check-label" for="cash">Cash</label>
                    </div>

                    @error('form.payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#serviceTransactionModal').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</div>

