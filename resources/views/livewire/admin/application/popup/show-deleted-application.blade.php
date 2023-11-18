<div wire:ignore.self class="modal fade " id="applicationModal{{$application->id}}" tabindex="-1" role="dialog" aria-labelledby="applicationModal{{$application->id}}Label" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModal{{$application->id}}Label">Show deleted Application</h5>
                <button type="button" onclick="$('#applicationModal{{$application->id}}').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group  ">
                            <label class="" for="visaType">Travel agent :</label>
                            <input class="border-0" readonly value="{{$application->agent? $application->agent->name:""}}" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group ">
                            <label for="visaProvider">Applicant name:</label>
                            <input class="border-0" readonly value="{{$application->applicant_name}}" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group ">
                            <div class="">
                                <label for="travelAgent">Application created at:</label>
                                <input class="border-0" readonly value="{{$application->application_create_date}}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="passport_no" class="">Passport no:</label>
                        <input type="text" readonly class="border-0" value="{{$application->passport_no}}">
                    </div>

                    <div class="col-12 ">
                        <div class="form-group  ">
                            <label for="title">Deletion date:</label>
                            <input type="text" readonly class="border-0" value="{{$application->deletion_date}}">
                        </div>
                    </div>

                    <div>
                        <div class="col-12 ">
                            <label for="first_name" class="">User that deleted application:</label>
                            <input type="text" readonly value="{{$application->user_name}}" class="border-0">
                        </div>
                        <div class="col-12 ">
                            <label for="last_name">Office name:</label>
                            <input type="text" readonly value="{{$application->office_name}}" class="border-0">
                        </div>
                    </div>

                    <div class="col-12 ">
                        <div class="form-group ">
                            <label for="expiry_date"> Deletion reason : </label>
                            <input class="border-0" readonly type="text" value="{{$application->delete_reason}}">
                        </div>
                    </div>
                </div>

            </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#applicationModal{{$application->id}}').modal('hide');" data-dismiss="modal">Close</button>
                </div>

        </div>
    </div>
</div>


