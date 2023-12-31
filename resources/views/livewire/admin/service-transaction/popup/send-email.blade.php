@if($showSendEmail)
    <div class="modal" id="sendEmailModal" style="display: block">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendEmailModalLabel">Alert</h5>
                    <button type="button" class="close border-0 p-0 bg-none" wire:click="toggleShowModal" data-dismiss="modal" aria-label="Close" onclick="$('#sendEmailModal').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Write an email</label>
                    <input class="form-control" wire:model="email" required type="email" placeholder="Write the email you want to send to him">
                    @error('email')<p style="color: red;">{{ $message }}</p>@enderror

                </div>
                <div class="modal-footer">
                    <button  class="btn btn-light" wire:click="send">Send</button>
                    <button type="button" class="btn btn-secondary" wire:click="toggleShowModal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
