<div wire:ignore.self class="modal fade " id="applicationModal{{$application->id}}" tabindex="-1" role="dialog" aria-labelledby="applicationModal{{$application->id}}Label" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModal{{$application->id}}Label">Show Application</h5>
                <button type="button" onclick="$('#applicationModal{{$application->id}}').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group  ">
                            <label class="" for="visaType">Visa Type:</label>
                            <input class="border-0" value="{{$application->visaType? $application->visaType->name:""}}" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group ">
                            <label for="visaProvider">Visa Provider:</label>
                            <input class="border-0" value="{{$application->visaProvider? $application->visaProvider->name:""}}" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group ">
                            <div class="">
                                <label for="travelAgent">Travel Agent:</label>
                                <input class="border-0" value="{{$application->travelAgent?$application->travelAgent->name:""}}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="passport_no" class="">Passport no:</label>
                        <input type="text" class="border-0" value="{{$application->passport_no}}">
                    </div>

                    {{-- <div class="col-12 ">
                        <div class="form-group  ">
                            <label for="title">Title:</label>
                            <input type="text" class="border-0" value="{{$application->title}}">
                        </div>
                    </div> --}}

                    <div>
                        <div class="col-12 ">
                            <label for="first_name" class="">First name:</label>
                            <input type="text" value="{{$application->first_name}}" class="border-0">
                        </div>
                        <div class="col-12 ">
                            <label for="last_name">Last name:</label>
                            <input type="text" value="{{$application->last_name}}" class="border-0">
                        </div>
                    </div>

                    <div class="col-12 ">
                        <div class="form-group ">
                            <label for="expiry_date"> Expiry date:</label>
                            <input class="border-0" type="text" value="{{\Carbon\Carbon::parse($application->expiry_date)->format('Y-m-d')}}">
                        </div>
                    </div>

                    <div class="col-12 ">
                        <label for="notes" class="">Note:</label>
                        <p class="d-inline-block">{{$application->notes}}</p>
                    </div>

                    <div class="col-12 ">
                        <label for="amount" class="">Amount:</label>
                        <input class="border-0" value="{{$application->amount}}">
                    </div>

                </div>

            </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#applicationModal{{$application->id}}').modal('hide');" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>


