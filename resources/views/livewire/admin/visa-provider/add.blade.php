
<div wire:ignore.self class="modal fade" id="visaProviderModal" tabindex="-1" role="dialog" aria-labelledby="visaProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="visProviderModalLabel">Add Visa provider</h5>
                <button type="button" onclick="$('#visaProviderModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2">
                    <label for="companyName">Name:</label>
                    <input type="text" wire:model="form.name" class="form-control" id="name">
                    @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#visaProviderModal').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</div>

