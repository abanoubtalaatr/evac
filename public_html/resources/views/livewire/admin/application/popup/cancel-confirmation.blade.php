@if($confirmingCancel)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Cancellation</h5>
                    <button wire:click="toggleConfirmCanceled" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the application?
                </div>
                <div class="modal-footer">
                    <button wire:click="toggleConfirmCanceled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button wire:click="canceled({{$record->id}})" type="button" class="btn btn-warning">Confirm Cancellation</button>
                </div>
            </div>
        </div>
    </div>
@endif
