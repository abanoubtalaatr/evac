
<div wire:ignore.self class="modal fade" id="showAddPaymentHistoryModal" tabindex="-1" role="dialog" aria-labelledby="showAddPaymentHistoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="showAddPaymentHistoryModalLabel">Add payment</h5>
                <button type="button" onclick="$('#showAddPaymentHistoryModal').modal('hide');" wire:click="emptyForm" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2 ">
                    <label>Amount : </label>
                   <input wire:model="form.amount" type="number" class="form-control">
                    @error("form.amount")<p class="text-danger">{{$message}}</p> @enderror
                </div>

                <div class="form-group my-2 ">
                    <label>Created at : </label>
                    <input wire:model="form.created_at" type="date" class="form-control">
                    @error("form.created_at")<p class="text-danger">{{$message}}</p> @enderror
                </div>

                <div class="form-group my-2 ">
                    <label>Note : </label>
                    <textarea wire:model="form.note"  class="form-control"></textarea>
                    @error("form.note")<p class="text-danger">{{$message}}</p> @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#showAddPaymentHistoryModal').modal('hide');" wire:click="emptyForm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</div>

