
<div wire:ignore.self class="modal fade" id="showServiceModal{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel{{$service->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Edit Service</h5>
                <button type="button" onclick="$('#showServiceModal{{$service->id}}').modal('hide');" wire:click="emptyForm" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2">
                    <label for="companyName">Name:</label>
                    <input type="text" wire:model="form.name" class="form-control" id="name">
                    @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="dubai_fee">Dubai fee:</label>
                    <input type="text" wire:model="form.dubai_fee" class="form-control" id="dubai_fee">
                    @error('form.dubai_fee')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="service_fee">Service fee:</label>
                    <input type="text" wire:model="form.service_fee" class="form-control" id="service_fee">
                    @error('form.service_fee')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="amount">Amount:</label>
                    <input type="text" wire:model="form.amount" class="form-control" id="amount">
                    @error('form.amount')<p style='color:red'> {{$message}} </p>@enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#showServiceModal{{$service->id}}').modal('hide');" wire:click="emptyForm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="update">Save</button>
            </div>
        </div>
    </div>
</div>

