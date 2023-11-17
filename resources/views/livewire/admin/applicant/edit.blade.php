
<div wire:ignore.self class="modal fade" id="showApplicantModal{{$applicant->id}}" tabindex="-1" role="dialog" aria-labelledby="applicantModalLabel{{$applicant->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="showApplicantModalLabel{{$applicant->id}}">Edit Applicant</h5>
                <button type="button" onclick="$('#applicantModal{{$applicant->id}}').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group my-2 ">
                    <label class="" for="agent_id">Travel agent:</label>
                    <select  wire:model="form.agent_id" class="form-control" id="agent_id">
                        <option value="">Select Travel agent</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('form.agent_id')<p style="color: red;">{{ $message }}</p>@enderror

                </div>

                <div class="form-group my-2">
                    <label for="name">Name:</label>
                    <input type="text" wire:model="form.name" class="form-control" id="name">
                    @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="surname">Surname:</label>
                    <input type="text" wire:model="form.surname" class="form-control" id="surname">
                    @error('form.surname')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="address">Address:</label>
                    <input type="text" wire:model="form.address" class="form-control" id="address">
                    @error('form.address')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="passport_no">Passport number : </label>
                    <input type="text" wire:model="form.passport_no" class="form-control" id="passport_no">
                    @error('form.passport_no')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="passport_expiry">Passport expiry date : </label>
                    <input type="date" wire:model="form.passport_expiry" class="form-control" id="passport_expiry">
                    @error('form.passport_expiry')<p style='color:red'> {{$message}} </p>@enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#applicantModal{{$applicant->id}}').modal('hide');" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="update">Save</button>

            </div>

        </div>
    </div>
</div>

