
<!-- Modal -->
<div class="modal fade" id="blackListModal" tabindex="-1" role="dialog" aria-labelledby="blackListModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blackListModalLabel">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-dark">Passport blacklisted, application will be reset.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="resetApplication">Reset application</button>
            </div>
        </div>
    </div>
</div>

