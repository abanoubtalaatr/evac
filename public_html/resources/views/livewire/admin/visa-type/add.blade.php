
<div wire:ignore.self class="modal fade" id="visaTypeModal" tabindex="-1" role="dialog" aria-labelledby="visaTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="visaTypeModalLabel">Add Visa type</h5>
                <button type="button" onclick="$('#visaTypeModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
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
                    <label for="dubai_fee">Dubai Fee:</label>
                    <input type="text" wire:model="form.dubai_fee" class="form-control" id="dubai_fee">
                    @error('form.dubai_fee')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="service_fee">Service fee : </label>
                    <input type="text" wire:model="form.service_fee" class="form-control" id="service_fee">
                    @error('form.service_fee')<p style='color:red'> {{$message}} </p>@enderror
                </div>

{{--                <div class="form-group my-2">--}}
{{--                    <label for="mobile">Total : </label>--}}
{{--                    <input type="text" wire:model="form.total" class="form-control" id="total">--}}
{{--                    @error('form.total')<p style='color:red'> {{$message}} </p>@enderror--}}
{{--                </div>--}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#visaTypeModal').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>

            </div>

        </div>
    </div>
</div>

