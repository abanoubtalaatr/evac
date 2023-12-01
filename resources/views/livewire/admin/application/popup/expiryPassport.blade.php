
<!-- Modal -->
<div class="modal fade" id="expiryPassportModal" tabindex="-1" role="dialog" aria-labelledby="expiryPassportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expiryPassportModalLabel">Alert</h5>
                <button type="button" onclick="$('#expiryPassportModal').modal('hide')" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <p class="text-dark">Passport expires is less than 6 month choose action below.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="resetApplication">Reset application</button>
                <button type="button" class="btn btn-light" wire:click="save">Accept & continue</button>
            </div>
        </div>
    </div>
</div>

