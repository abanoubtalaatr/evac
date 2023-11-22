
<!-- Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Alert</h5>
                <button type="button" class="close border-0 p-0 bg-none" data-dismiss="modal" aria-label="Close" onclick="$('#sendEmailModal').modal('hide');">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Write an email</label>
                <input class="form-control" required type="email" placeholder="Write the email you want to send to him">
                @error('email')<p style="color: red;">{{ $message }}</p>@enderror

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="send">Send</button>
                <button type="button" class="btn btn-secondary" onclick="$('#sendEmailModal').modal('hide');" >Close</button>
            </div>
        </div>
    </div>
</div>

