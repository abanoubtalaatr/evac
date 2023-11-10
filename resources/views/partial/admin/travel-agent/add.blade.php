<div wire:ignore.self class="modal fade" id="agentModal" tabindex="-1" role="dialog" aria-labelledby="agentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="agentModalLabel">Add Agent</h5>
                <button type="button" onclick="$('#agentModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group my-2">
                    <label for="companyName">Name:</label>
                    <input type="text" wire:model="form.name" class="form-control" id="name">
                    @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="companyName">Company Name:</label>
                    <input type="text" wire:model="form.company_name" class="form-control" id="companyName">
                    @error('form.company_name')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <div class="form-group my-2">
                    <label for="address">Address : </label>
                    <input type="text" wire:model="form.address" class="form-control" id="address">
                    @error('form.address')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="mobile">Mobile : </label>
                    <input type="text" wire:model="form.mobile" class="form-control" id="mobile">
                    @error('form.mobile')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="email">Email : </label>
                    <input type="text" wire:model="form.email" class="form-control" id="email">
                    @error('form.email')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="owner_name">Owner name : </label>
                    <input type="text" wire:model="form.owner_name" class="form-control" id="owner_name">
                    @error('form.owner_name')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="contact_name">Contact name : </label>
                    <input type="text" wire:model="form.contact_name" class="form-control" id="contact_name">
                    @error('form.contact_name')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="iata_no">IATA No : </label>
                    <input type="text" wire:model="form.iata_no" class="form-control" id="iata_no">
                    @error('form.iata_no')<p style='color:red'> {{$message}} </p>@enderror
                </div>

                <div class="form-group my-2">
                    <label for="finance_no">Finance No : </label>
                    <input type="text" wire:model="form.finance_no" class="form-control" id="finance_no">
                    @error('form.finance_no')<p style='color:red'> {{$message}} </p>@enderror
                </div>


                <div class="form-group my-2">
                    <label for="telephone">Telephone:</label>
                    <input type="tel" wire:model="form.telephone" class="form-control" id="telephone">
                    @error('form.telephone')<p style='color:red'> {{$message}} </p>@enderror
                </div>
                <!-- Add more form fields as needed -->
            </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#agentModal').modal('hide');" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:click="store">Save</button>

                </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('addAgentButton').addEventListener('click', function() {
        $('#agentModal').modal('show');
    });

</script>
