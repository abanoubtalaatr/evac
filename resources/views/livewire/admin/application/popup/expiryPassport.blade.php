<!-- Modal -->
<div class="modal fade" id="expiryPassportModal" tabindex="-1" role="dialog" aria-labelledby="expiryPassportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expiryPassportModalLabel">Alert</h5>
                <button type="button" onclick="$('#expiryPassportModal').modal('hide')" class="close"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            @php

                $settings = \App\Models\Setting::query()->first();
                $numberOfExpireDays = 180;

                if ($settings && $settings->passport_expiry_days) {
                    $numberOfExpireDays = $settings->passport_expiry_days;
                }
            @endphp
            <div class="modal-body">
                @if ($isExpiryInPast)
                    <p> You can not add application because this passport exipred already</p>
                @else
                    <p class="text-dark">Passport expires is less than {{ $numberOfExpireDays }} days, choose action
                        below.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="resetApplication">Reset application</button>

                @if (!$isExpiryInPast)
                    <button type="button" class="btn btn-light" wire:click="save">Accept & continue</button>
                @endif
            </div>
        </div>
    </div>
</div>
