@if($confirmingDelete)
<div wire:ignore.self class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConfirmationModalLabel">Confirm Cancellation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reasonForDeletion">Reason for Deletion</label>
                    <input type="text" wire:model="reasonForDeletion" class="form-control" id="reasonForDeletion" placeholder="Enter reason for deletion">
                    @error('reasonForDeletion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button wire:click="validateAndDelete({{$record->id}})" type="button" class="btn btn-warning">Confirm Cancellation</button>
            </div>
        </div>
    </div>
</div>
@endif
@if($confirmingDelete)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button wire:click="toggleConfirmDelete" type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group my-3">
                        <label for="reasonForDeletion">Reason for Deletion</label>
                        <input type="text" wire:model="reasonForDeletion" class="form-control" id="reasonForDeletion" placeholder="Enter reason for deletion">
                        @error('reasonForDeletion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="validateAndDelete({{$record->id}})" type="button" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>
@endif

