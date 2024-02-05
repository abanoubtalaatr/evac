
<div wire:ignore.self class="modal fade" id="showPaymentHistoryModal{{$agent->id}}" tabindex="-1" role="dialog" aria-labelledby="PaymentHistoryModalLabel{{$agent->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentHistoryModal{{$agent->id}}Label">Payment history for {{$record->name}}</h5>
                <button type="button" onclick="$('#showPaymentHistoryModal{{$agent->id}}').modal('hide');" wire:click="emptyForm" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2 ">
                    @if($agent->paymentTransactions->count() > 0)
                        @foreach($agent->paymentTransactions()->latest('created_at')->get() as $payment)
                    <div class="d-flex gap-2">
                        <div>Amount : {{$payment->amount}} $ =></div>
                        <div>Date : {{\Carbon\Carbon::parse($payment->created_at)->format('d-m-Y h:m')}}</div>
                    </div>
                        <hr>

                    @endforeach
                    @else
                        <div>
                            <p>No data yet</p>
                        </div>
                    @endif
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#showPaymentHistoryModal{{$agent->id}}').modal('hide');" wire:click="emptyForm" data-dismiss="modal">Close</button>
{{--                <button type="submit" class="btn btn-primary" wire:click="update">Save</button>--}}
            </div>
        </div>
    </div>
</div>

