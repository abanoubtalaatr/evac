
<!-- Modal -->
<div class="modal fade" id="passportHasMoreThanOneModal" tabindex="-1" role="dialog" aria-labelledby="passportHasMoreThanOneModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="p-3 text-center bg-primary text-white">
                <h5 class="modal-title" id="passportHasMoreThanOneModalLabel">Alert</h5>

            </div>
            <div class="modal-body">
                @if($passportApplications && count($passportApplications) > 0)

                    @foreach($passportApplications as $key => $passportApplication)
                        @if($key ==0 )
                            <p class="text-dark">Passport no : {{$passportApplications[0]->passport_no}} has applied in the past {{$numberOfDaysToCheckVisa}} days on </p>
                        @endif
                            <div class="text-dark d-flex gap-5">
                                <p class="">{{\Carbon\Carbon::parse($passportApplication->created_at)->format('Y-m-d h:m')}}</p>
                                <p class="">{{$passportApplication->travel_agent_id?\App\Models\Agent::query()->find($passportApplication->travel_agent_id)->name:'Direct'}}</p>
                                <p class="">{{\App\Models\VisaType::query()->find($passportApplication->visa_type_id)->name}}</p>
                            </div>
                    @endforeach
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="resetApplication">Reset application</button>
                <button type="button" class="btn btn-light" wire:click="save">Proceed</button>
            </div>
        </div>
    </div>
</div>

